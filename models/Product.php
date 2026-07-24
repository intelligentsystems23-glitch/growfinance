<?php
class Product {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getAll($filters = []) {
        $sql = "SELECT i.*, c.category_name,
                (SELECT SUM(quantity) FROM stock_movements WHERE item_id = i.id AND movement_type = 'sale') as total_sold,
                (SELECT SUM(quantity * unit_price) FROM stock_movements WHERE item_id = i.id AND movement_type = 'sale') as total_sales
                FROM items i
                LEFT JOIN categories c ON i.category_id = c.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['search'])) {
            $sql .= " AND (i.item_name LIKE :search OR i.item_code LIKE :search OR i.sku LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['category_id'])) {
            $sql .= " AND i.category_id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }
        
        if (!empty($filters['type'])) {
            $sql .= " AND i.type = :type";
            $params[':type'] = $filters['type'];
        }
        
        if (isset($filters['is_active'])) {
            $sql .= " AND i.is_active = :is_active";
            $params[':is_active'] = $filters['is_active'];
        }
        
        if (isset($filters['low_stock'])) {
            $sql .= " AND i.current_stock <= i.reorder_level";
        }
        
        $sql .= " ORDER BY i.item_name ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $sql = "SELECT i.*, c.category_name,
                (SELECT SUM(quantity) FROM stock_movements WHERE item_id = i.id AND movement_type = 'purchase') as total_purchased,
                (SELECT SUM(quantity) FROM stock_movements WHERE item_id = i.id AND movement_type = 'sale') as total_sold
                FROM items i
                LEFT JOIN categories c ON i.category_id = c.id
                WHERE i.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        try {
            $this->db->beginTransaction();
            
            // Generate item code if not provided
            if (empty($data['item_code'])) {
                $data['item_code'] = $this->generateItemCode();
            }
            
            $sql = "INSERT INTO items (
                        item_code, sku, barcode, item_name, type, category_id,
                        description, unit, sale_price, purchase_price,
                        cost_method, current_stock, opening_stock,
                        reorder_level, min_stock, max_stock, location,
                        image, is_active, created_at
                    ) VALUES (
                        :item_code, :sku, :barcode, :item_name, :type, :category_id,
                        :description, :unit, :sale_price, :purchase_price,
                        :cost_method, :current_stock, :opening_stock,
                        :reorder_level, :min_stock, :max_stock, :location,
                        :image, :is_active, NOW()
                    )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':item_code' => $data['item_code'],
                ':sku' => !empty($data['sku']) ? $data['sku'] : null,
                ':barcode' => !empty($data['barcode']) ? $data['barcode'] : null,
                ':item_name' => $data['item_name'],
                ':type' => $data['type'] ?? 'product',
                ':category_id' => !empty($data['category_id']) ? $data['category_id'] : null,
                ':description' => !empty($data['description']) ? $data['description'] : null,
                ':unit' => !empty($data['unit']) ? $data['unit'] : 'pcs',
                ':sale_price' => $data['sale_price'] ?? 0,
                ':purchase_price' => $data['purchase_price'] ?? 0,
                ':cost_method' => $data['cost_method'] ?? 'Average',
                ':current_stock' => $data['current_stock'] ?? 0,
                ':opening_stock' => $data['opening_stock'] ?? 0,
                ':reorder_level' => $data['reorder_level'] ?? 10,
                ':min_stock' => $data['min_stock'] ?? 0,
                ':max_stock' => $data['max_stock'] ?? 0,
                ':location' => $data['location'] ?? null,
                ':image' => $data['image'] ?? null,
                ':is_active' => $data['is_active'] ?? 1
            ]);
            
            $item_id = $this->db->lastInsertId();
            
            // Record opening stock movement if stock > 0
            if (($data['current_stock'] ?? 0) > 0) {
                $this->recordStockMovement($item_id, 'adjustment', $data['current_stock'], 
                    $data['purchase_price'] ?? 0, 'opening', $item_id, 'Opening stock');
            }
            
            $this->db->commit();
            return $item_id;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function update($id, $data) {
        $sql = "UPDATE items SET
                    sku = :sku,
                    barcode = :barcode,
                    item_name = :item_name,
                    type = :type,
                    category_id = :category_id,
                    description = :description,
                    unit = :unit,
                    sale_price = :sale_price,
                    purchase_price = :purchase_price,
                    cost_method = :cost_method,
                    reorder_level = :reorder_level,
                    min_stock = :min_stock,
                    max_stock = :max_stock,
                    location = :location,
                    is_active = :is_active";
        
        $params = [
            ':id' => $id,
            ':sku' => !empty($data['sku']) ? $data['sku'] : null,
            ':barcode' => !empty($data['barcode']) ? $data['barcode'] : null,
            ':item_name' => $data['item_name'],
            ':type' => $data['type'] ?? 'product',
            ':category_id' => !empty($data['category_id']) ? $data['category_id'] : null,
            ':description' => !empty($data['description']) ? $data['description'] : null,
            ':unit' => !empty($data['unit']) ? $data['unit'] : 'pcs',
            ':sale_price' => $data['sale_price'] ?? 0,
            ':purchase_price' => $data['purchase_price'] ?? 0,
            ':cost_method' => $data['cost_method'] ?? 'Average',
            ':reorder_level' => $data['reorder_level'] ?? 10,
            ':min_stock' => $data['min_stock'] ?? 0,
            ':max_stock' => $data['max_stock'] ?? 0,
            ':location' => $data['location'] ?? null,
            ':is_active' => $data['is_active'] ?? 1
        ];

        if (isset($data['image'])) {
            $sql .= ", image = :image";
            $params[':image'] = $data['image'];
        }

        $sql .= " WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    public function delete($id) {
        // Soft delete - just deactivate
        $sql = "UPDATE items SET is_active = 0 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function updateStock($id, $quantity, $type = 'add', $reference_type = null, $reference_id = null, $notes = null) {
        try {
            $this->db->beginTransaction();
            
            // Get current item
            $item = $this->getById($id);
            
            // Update stock
            if ($type == 'add') {
                $sql = "UPDATE items SET current_stock = current_stock + :quantity WHERE id = :id";
            } else {
                $sql = "UPDATE items SET current_stock = current_stock - :quantity WHERE id = :id";
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id, ':quantity' => $quantity]);
            
            // Record movement
            $movement_type = $type == 'add' ? 'purchase' : 'sale';
            if ($reference_type == 'adjustment') {
                $movement_type = 'adjustment';
            }
            
            $this->recordStockMovement($id, $movement_type, $quantity, 
                $item['purchase_price'], $reference_type, $reference_id, $notes);
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    private function recordStockMovement($item_id, $type, $quantity, $unit_price, $ref_type, $ref_id, $notes) {
        $sql = "INSERT INTO stock_movements (
                    item_id, movement_type, quantity, unit_price,
                    reference_type, reference_id, notes, created_by
                ) VALUES (
                    :item_id, :movement_type, :quantity, :unit_price,
                    :reference_type, :reference_id, :notes, :created_by
                )";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':item_id' => $item_id,
            ':movement_type' => $type,
            ':quantity' => $quantity,
            ':unit_price' => $unit_price,
            ':reference_type' => $ref_type,
            ':reference_id' => $ref_id,
            ':notes' => $notes,
            ':created_by' => $_SESSION['user_id'] ?? 1
        ]);
    }
    
    private function generateItemCode() {
        $sql = "SELECT COUNT(*) as count FROM items";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        
        return 'ITEM-' . str_pad($result['count'] + 1, 6, '0', STR_PAD_LEFT);
    }
    
    // FIXED: Simplified version that doesn't reference purchases table
    public function getStockMovements($item_id, $limit = 50) {
        $sql = "SELECT sm.*, u.username
                FROM stock_movements sm
                LEFT JOIN users u ON sm.created_by = u.id
                WHERE sm.item_id = :item_id
                ORDER BY sm.created_at DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':item_id', $item_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getCategories() {
        $sql = "SELECT * FROM categories WHERE is_active = 1 ORDER BY category_name";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getLowStockItems() {
        $sql = "SELECT * FROM items 
                WHERE current_stock <= reorder_level 
                AND is_active = 1 
                ORDER BY current_stock ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getStockValue() {
        $sql = "SELECT 
                    SUM(current_stock * purchase_price) as total_value,
                    COUNT(*) as total_items,
                    SUM(current_stock) as total_quantity
                FROM items 
                WHERE type = 'product' AND is_active = 1";
        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }
}
