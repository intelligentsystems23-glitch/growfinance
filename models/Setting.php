<?php
class Setting {
    private $db;
    private $cache = [];
    
    public function __construct($db) {
        $this->db = $db;
        $this->loadSettings();
    }
    
    private function loadSettings() {
        try {
            $sql = "SELECT setting_key, setting_value FROM settings";
            $stmt = $this->db->query($sql);
            if ($stmt) {
                $settings = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($settings as $setting) {
                    $this->cache[$setting['setting_key']] = $setting['setting_value'];
                }
            }
        } catch (Exception $e) {
            // Settings table may not exist yet or connection issue
        }
    }
    
    public function get($key, $default = null) {
        return $this->cache[$key] ?? $default;
    }
    
    public function set($key, $value) {
        try {
            $sql = "UPDATE settings SET setting_value = :value WHERE setting_key = :key";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([':key' => $key, ':value' => $value]);
            
            if ($result) {
                $this->cache[$key] = $value;
            }
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function getAll($group = null) {
        try {
            // Check if sort_order column exists in table
            $hasSortOrder = false;
            try {
                $colCheck = $this->db->query("SHOW COLUMNS FROM settings LIKE 'sort_order'");
                $hasSortOrder = ($colCheck && $colCheck->rowCount() > 0);
            } catch (Exception $e) {}

            $sql = "SELECT * FROM settings";
            if ($group) {
                $sql .= " WHERE group_name = :group";
                if ($hasSortOrder) {
                    $sql .= " ORDER BY sort_order ASC, id ASC";
                } else {
                    $sql .= " ORDER BY label ASC";
                }
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':group' => $group]);
            } else {
                if ($hasSortOrder) {
                    $sql .= " ORDER BY group_name ASC, sort_order ASC, id ASC";
                } else {
                    $sql .= " ORDER BY group_name ASC, label ASC";
                }
                $stmt = $this->db->query($sql);
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function getAllGrouped() {
        $settings = $this->getAll();
        $grouped = [];
        foreach ($settings as $setting) {
            $group = $setting['group_name'] ?: 'general';
            $grouped[$group][] = $setting;
        }
        return $grouped;
    }
    
    public function updateBatch($settings) {
        try {
            $this->db->beginTransaction();
            
            foreach ($settings as $key => $value) {
                $this->set($key, $value);
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return false;
        }
    }

    public function handleFileUploads($files) {
        if (empty($files['name']['settings'])) {
            return;
        }

        $uploadDir = __DIR__ . '/../assets/uploads/settings/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        foreach ($files['name']['settings'] as $key => $name) {
            if (!empty($name) && $files['error']['settings'][$key] === UPLOAD_ERR_OK) {
                $tmpName = $files['tmp_name']['settings'][$key];
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
                
                if (in_array($ext, $allowed)) {
                    $filename = $key . '_' . time() . '.' . $ext;
                    $targetPath = $uploadDir . $filename;
                    
                    if (move_uploaded_file($tmpName, $targetPath)) {
                        $relativePath = 'assets/uploads/settings/' . $filename;
                        $this->set($key, $relativePath);
                    }
                }
            }
        }
    }
}
