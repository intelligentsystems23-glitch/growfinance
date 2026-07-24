<?php
class User
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAll($filters = [])
    {
        $sql = "SELECT u.*, 
                GROUP_CONCAT(DISTINCT r.display_name) as roles
                FROM users u
                LEFT JOIN user_roles ur ON u.id = ur.user_id
                LEFT JOIN roles r ON ur.role_id = r.id
                WHERE 1=1";
        $params = [];

        if (isset($filters['is_active'])) {
            $sql .= " AND u.is_active = :is_active";
            $params[':is_active'] = $filters['is_active'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (u.username LIKE :search OR u.email LIKE :search OR u.full_name LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $sql .= " GROUP BY u.id ORDER BY u.username";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $sql = "SELECT u.*, 
                GROUP_CONCAT(ur.role_id) as role_ids
                FROM users u
                LEFT JOIN user_roles ur ON u.id = ur.user_id
                WHERE u.id = :id
                GROUP BY u.id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        try {
            $this->db->beginTransaction();

            $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (
                        username, email, password, full_name, phone, role, is_active
                    ) VALUES (
                        :username, :email, :password, :full_name, :phone, :role, :is_active
                    )";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':username' => $data['username'],
                ':email' => $data['email'],
                ':password' => $hashed_password,
                ':full_name' => $data['full_name'],
                ':phone' => $data['phone'] ?? null,
                ':role' => $data['role'] ?? 'employee',
                ':is_active' => $data['is_active'] ?? 1
            ]);

            $user_id = $this->db->lastInsertId();

            // Assign roles
            if (!empty($data['roles'])) {
                $this->assignRoles($user_id, $data['roles']);
            }

            $this->db->commit();
            return $user_id;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function update($id, $data)
    {
        try {
            $this->db->beginTransaction();

            $sql = "UPDATE users SET
                        username = :username,
                        email = :email,
                        full_name = :full_name,
                        phone = :phone,
                        role = :role,
                        is_active = :is_active";

            if (!empty($data['password'])) {
                $sql .= ", password = :password";
            }

            $sql .= " WHERE id = :id";

            $params = [
                ':id' => $id,
                ':username' => $data['username'],
                ':email' => $data['email'],
                ':full_name' => $data['full_name'],
                ':phone' => $data['phone'] ?? null,
                ':role' => $data['role'] ?? 'employee',
                ':is_active' => $data['is_active'] ?? 1
            ];

            if (!empty($data['password'])) {
                $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            // Update roles
            $stmt = $this->db->prepare("DELETE FROM user_roles WHERE user_id = :user_id");
            $stmt->execute([':user_id' => $id]);

            if (!empty($data['roles'])) {
                $this->assignRoles($id, $data['roles']);
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    private function assignRoles($user_id, $roles)
    {
        $sql = "INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)";
        $stmt = $this->db->prepare($sql);

        foreach ($roles as $role_id) {
            $stmt->execute([':user_id' => $user_id, ':role_id' => $role_id]);
        }
    }

    public function delete($id)
    {
        $sql = "UPDATE users SET is_active = 0 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function authenticate($username_or_email, $password)
    {
        $sql = "SELECT u.*, 
                GROUP_CONCAT(p.name) as permissions
                FROM users u
                LEFT JOIN user_roles ur ON u.id = ur.user_id
                LEFT JOIN role_permissions rp ON ur.role_id = rp.role_id
                LEFT JOIN permissions p ON rp.permission_id = p.id
                WHERE (u.username = :identifier OR u.email = :identifier) AND u.is_active = 1
                GROUP BY u.id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':identifier' => $username_or_email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Update last login
            $sql = "UPDATE users SET last_login = NOW(), last_login_ip = :ip WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $user['id'], ':ip' => $_SERVER['REMOTE_ADDR'] ?? '']);

            unset($user['password']);
            return $user;
        }

        return false;
    }

    public function getAllRoles()
    {
        $sql = "SELECT * FROM roles ORDER BY display_name";
        return $this->db->query($sql)->fetchAll();
    }

    public function getAllPermissions()
    {
        $sql = "SELECT * FROM permissions ORDER BY module, display_name";
        return $this->db->query($sql)->fetchAll();
    }

    public function getRolePermissions($role_id)
    {
        $sql = "SELECT permission_id FROM role_permissions WHERE role_id = :role_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':role_id' => $role_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function updateRolePermissions($role_id, $permissions)
    {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("DELETE FROM role_permissions WHERE role_id = :role_id");
            $stmt->execute([':role_id' => $role_id]);

            if (!empty($permissions)) {
                $sql = "INSERT INTO role_permissions (role_id, permission_id) VALUES (:role_id, :permission_id)";
                $stmt = $this->db->prepare($sql);
                foreach ($permissions as $perm_id) {
                    $stmt->execute([':role_id' => $role_id, ':permission_id' => $perm_id]);
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function hasPermission($user_id, $permission)
    {
        $sql = "SELECT COUNT(*) as count 
                FROM user_roles ur
                JOIN role_permissions rp ON ur.role_id = rp.role_id
                JOIN permissions p ON rp.permission_id = p.id
                WHERE ur.user_id = :user_id AND p.name = :permission";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id, ':permission' => $permission]);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
}
