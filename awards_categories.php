<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * Uganda One Festival 2026 - Awards Categories Management
 */

require_once 'config.php';
checkAdminLogin();

$conn = getDBConnection();
$page_title = 'Awards Categories';
$breadcrumb = 'Home / Awards / Categories';
$message = '';

// Handle Add/Edit/Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
            case 'edit':
                $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
                $name = $conn->real_escape_string($_POST['category_name']);
                $slug = $conn->real_escape_string($_POST['category_slug'] ?: strtolower(str_replace(' ', '-', $name)));
                $desc = $conn->real_escape_string($_POST['description']);
                $type = $conn->real_escape_string($_POST['category_type']);
                $domain_id = (int)$_POST['domain_id'];
                $criteria = $conn->real_escape_string($_POST['eligibility_criteria']);
                $fee = (float)$_POST['nomination_fee'];
                $max_finalists = (int)$_POST['max_finalists'];
                $requires_artwork = isset($_POST['requires_artwork']) ? 1 : 0;
                $artwork_type = $conn->real_escape_string($_POST['artwork_type']);
                $is_active = isset($_POST['is_active']) ? 1 : 0;
                $display_order = (int)$_POST['display_order'];
                $icon = $conn->real_escape_string($_POST['icon']);

                if ($id > 0) {
                    $sql = "UPDATE award_categories SET 
                        category_name=?, category_slug=?, description=?, category_type=?,
                        domain_id=?, eligibility_criteria=?, nomination_fee=?, max_finalists=?,
                        requires_artwork=?, artwork_type=?, is_active=?, display_order=?, icon=?
                        WHERE id=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('ssssisdiisiisi', $name, $slug, $desc, $type, $domain_id, 
                        $criteria, $fee, $max_finalists, $requires_artwork, $artwork_type, 
                        $is_active, $display_order, $icon, $id);
                    $msg = 'Category updated successfully!';
                } else {
                    $sql = "INSERT INTO award_categories 
                        (category_name, category_slug, description, category_type, domain_id,
                         eligibility_criteria, nomination_fee, max_finalists, requires_artwork,
                         artwork_type, is_active, display_order, icon)
                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('ssssisdiisisi', $name, $slug, $desc, $type, $domain_id,
                        $criteria, $fee, $max_finalists, $requires_artwork, $artwork_type,
                        $is_active, $display_order, $icon);
                    $msg = 'Category added successfully!';
                }
                
                if ($stmt->execute()) {
                    $message = '<div class="alert alert-success">✓ ' . $msg . '</div>';
                    logActivity('award_category_' . ($id ? 'update' : 'create'), $name);
                } else {
                    $message = '<div class="alert alert-error">✗ Error: ' . $conn->error . '</div>';
                }
                $stmt->close();
                break;

            case 'delete':
                $id = (int)$_POST['id'];
                $stmt = $conn->prepare("DELETE FROM award_categories WHERE id=?");
                $stmt->bind_param('i', $id);
                if ($stmt->execute()) {
                    $message = '<div class="alert alert-success">✓ Category deleted.</div>';
                }
                $stmt->close();
                break;
        }
    }
}

// Get all categories with domain info
$categories = $conn->query("
    SELECT ac.*, d.name as domain_name, d.icon as domain_icon, d.color as domain_color
    FROM award_categories ac
    LEFT JOIN domains d ON ac.domain_id = d.id
    ORDER BY ac.display_order, ac.id
")->fetch_all(MYSQLI_ASSOC);

// Get domains for dropdown
$domains = $conn->query("SELECT * FROM domains ORDER BY display_order")->fetch_all(MYSQLI_ASSOC);

$conn->close();
require_once 'includes/header.php';
?>

<style>
.form-section { background:#fff; border-radius:12px; padding:25px; margin-bottom:20px; box-shadow:0 2px 10px rgba(0,0,0,0.08); }
.form-section h3 { margin-bottom:20px; color:#5C2E0F; }
.category-card { background:#fff; border-radius:10px; padding:20px; margin-bottom:15px; 
    border-left:4px solid #f39c12; box-shadow:0 2px 8px rgba(0,0,0,0.06); }
.category-card.inactive { opacity:0.6; border-left-color:#ccc; }
.badge { padding:3px 10px; border-radius:12px; font-size:11px; font-weight:600; }
.btn-sm { padding:6px 14px; font-size:12px; border-radius:6px; }
</style>

<h2>🏅 Awards Categories</h2>
<?= $message ?>

<div class="form-section">
    <h3>Add New Category</h3>
    <form method="POST">
        <input type="hidden" name="action" value="add">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Category Name *</label>
                <input type="text" name="category_name" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Slug</label>
                <input type="text" name="category_slug" class="form-control" placeholder="auto-generated">
            </div>
            <div class="col-md-4 mb-3">
                <label>Category Type</label>
                <select name="category_type" class="form-select">
                    <option value="performance">Performance</option>
                    <option value="exhibition">Exhibition</option>
                    <option value="innovation">Innovation</option>
                    <option value="people">People's Choice</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>Domain</label>
                <select name="domain_id" class="form-select">
                    <option value="">None</option>
                    <?php foreach($domains as $d): ?>
                    <option value="<?= $d['id'] ?>"><?= $d['icon'] ?> <?= $d['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <label>Max Finalists</label>
                <input type="number" name="max_finalists" class="form-control" value="5" min="1">
            </div>
            <div class="col-md-2 mb-3">
                <label>Fee (UGX)</label>
                <input type="number" name="nomination_fee" class="form-control" value="0" step="1000">
            </div>
            <div class="col-12 mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="2"></textarea>
            </div>
            <div class="col-12 mb-3">
                <label>Eligibility Criteria</label>
                <textarea name="eligibility_criteria" class="form-control" rows="2"></textarea>
            </div>
            <div class="col-md-3 mb-3">
                <label>Icon (emoji)</label>
                <input type="text" name="icon" class="form-control" placeholder="🏆">
            </div>
            <div class="col-md-3 mb-3">
                <label>Display Order</label>
                <input type="number" name="display_order" class="form-control" value="0">
            </div>
            <div class="col-md-3 mb-3">
                <label>Artwork Required</label>
                <select name="requires_artwork" class="form-select">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label>Artwork Type</label>
                <select name="artwork_type" class="form-select">
                    <option value="">Any</option>
                    <option value="image">Image</option>
                    <option value="video">Video</option>
                    <option value="audio">Audio</option>
                </select>
            </div>
            <div class="col-12">
                <label><input type="checkbox" name="is_active" checked> Active</label>
                <button type="submit" class="btn btn-primary" style="float:right;">Add Category</button>
            </div>
        </div>
    </form>
</div>

<h3>Existing Categories (<?= count($categories) ?>)</h3>
<?php foreach($categories as $cat): ?>
<div class="category-card <?= $cat['is_active'] ? '' : 'inactive' ?>">
    <div style="display:flex; justify-content:space-between; align-items:start;">
        <div>
            <strong style="font-size:18px;"><?= $cat['icon'] ?> <?= htmlspecialchars($cat['category_name']) ?></strong>
            <span class="badge" style="background:<?= $cat['domain_color'] ?? '#ccc' ?>20; color:<?= $cat['domain_color'] ?? '#666' ?>;">
                <?= $cat['domain_name'] ?: 'No Domain' ?>
            </span>
            <span class="badge" style="background:#f0f0f0;"><?= $cat['category_type'] ?></span>
            <?= $cat['is_active'] ? '<span class="badge" style="background:#d4edda;color:#155724;">Active</span>' : '<span class="badge" style="background:#f8d7da;color:#721c24;">Inactive</span>' ?>
        </div>
        <div>
            <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this category?')">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                <button class="btn btn-sm btn-danger">Delete</button>
            </form>
        </div>
    </div>
    <div style="margin-top:10px; font-size:13px; color:#666;">
        <strong>Slug:</strong> <?= $cat['category_slug'] ?> | 
        <strong>Fee:</strong> UGX <?= number_format($cat['nomination_fee']) ?> | 
        <strong>Max Finalists:</strong> <?= $cat['max_finalists'] ?>
    </div>
</div>
<?php endforeach; ?>

<?php require_once 'includes/footer.php'; ?>