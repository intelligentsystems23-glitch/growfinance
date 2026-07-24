<?php
if (!class_exists('Product')) {
    require_once __DIR__ . '/../models/Product.php';
}

class ProductController {
    private $productModel;
    private $db;
    
    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $this->db = getConnection();
        $this->productModel = new Product($this->db);
    }
    
    public function index() {
        $filters = [];
        
        if (isset($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }
        if (isset($_GET['category_id'])) {
            $filters['category_id'] = $_GET['category_id'];
        }
        if (isset($_GET['type'])) {
            $filters['type'] = $_GET['type'];
        }
        if (isset($_GET['low_stock'])) {
            $filters['low_stock'] = true;
        }
        
        $products = $this->productModel->getAll($filters);
        $categories = $this->productModel->getCategories();
        $stockValue = $this->productModel->getStockValue();
        $lowStockItems = $this->productModel->getLowStockItems();
        
        $title = 'Products & Services';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/products/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function view($id) {
        $product = $this->productModel->getById($id);
        
        if (!$product) {
            header('Location: ' . BASE_URL . '/products');
            exit;
        }
        
        $movements = $this->productModel->getStockMovements($id);
        
        $title = 'Product Details';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/products/view.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            try {
                // Handle image upload
                if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                    $image = $this->uploadImage($_FILES['image']);
                    $_POST['image'] = $image;
                }
                
                $product_id = $this->productModel->create($_POST);
                echo json_encode(['success' => true, 'id' => $product_id]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        $categories = $this->productModel->getCategories();
        
        $title = 'Add New Product';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/products/create.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            try {
                if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                    $image = $this->uploadImage($_FILES['image']);
                    $_POST['image'] = $image;
                }
                
                $this->productModel->update($id, $_POST);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        $product = $this->productModel->getById($id);
        $categories = $this->productModel->getCategories();
        
        if (!$product) {
            header('Location: ' . BASE_URL . '/products');
            exit;
        }
        
        $title = 'Edit Product';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/products/edit.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            try {
                $this->productModel->delete($id);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
    }
    
    public function adjustStock($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            try {
                $quantity = $_POST['quantity'];
                $type = $_POST['adjustment_type'];
                $notes = $_POST['notes'] ?? '';
                
                $this->productModel->updateStock($id, $quantity, $type, 'adjustment', null, $notes);
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
    }
    
    // ============================================
    // NEW METHODS - ADD THESE INSIDE THE CLASS
    // ============================================
    
    /**
     * Activate a product
     */
    public function activate($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            try {
                $sql = "UPDATE items SET is_active = 1 WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':id' => $id]);
                
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
    }
    
    /**
     * Duplicate a product
     */
    public function duplicate($id) {
        try {
            $product = $this->productModel->getById($id);
            
            if (!$product) {
                header('Location: ' . BASE_URL . '/products');
                exit;
            }
            
            // Remove id and modify name
            unset($product['id']);
            $product['item_name'] = $product['item_name'] . ' (Copy)';
            $product['item_code'] = ''; // Will auto-generate
            $product['sku'] = $product['sku'] ? $product['sku'] . '-COPY' : '';
            $product['current_stock'] = 0;
            $product['opening_stock'] = 0;
            
            $new_id = $this->productModel->create($product);
            
            header('Location: ' . BASE_URL . '/products/edit/' . $new_id);
        } catch (Exception $e) {
            header('Location: ' . BASE_URL . '/products?error=' . urlencode($e->getMessage()));
        }
        exit;
    }
    
    /**
     * Generate and display barcode
     */
    public function barcode($id) {
        $product = $this->productModel->getById($id);
        
        if (!$product) {
            echo 'Product not found';
            return;
        }
        
        // Simple barcode display page
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Barcode - <?= htmlspecialchars($product['item_name']) ?></title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
            <style>
                body { padding: 20px; }
                .barcode-container { text-align: center; }
                @media print {
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <div class="barcode-container">
                <h4><?= htmlspecialchars($product['item_name']) ?></h4>
                <p><strong><?= htmlspecialchars($product['item_code']) ?></strong></p>
                
                <?php if ($product['barcode']): ?>
                <img src="https://barcode.tec-it.com/barcode.ashx?data=<?= urlencode($product['barcode']) ?>&code=Code128&dpi=96" alt="Barcode">
                <p class="mt-2"><strong><?= htmlspecialchars($product['barcode']) ?></strong></p>
                <?php else: ?>
                <p>No barcode assigned</p>
                <?php endif; ?>
                
                <p class="mt-3">Price: $<?= number_format($product['sale_price'], 2) ?></p>
                
                <button class="btn btn-primary no-print" onclick="window.print()">Print</button>
                <button class="btn btn-secondary no-print" onclick="window.close()">Close</button>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
    
    // ============================================
    // END OF NEW METHODS
    // ============================================
    
    private function uploadImage($file) {
        $uploadDir = __DIR__ . '/../assets/uploads/products/';
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $extension;
        $targetPath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return 'assets/uploads/products/' . $filename;
        }
        
        throw new Exception('Failed to upload image');
    }
}
