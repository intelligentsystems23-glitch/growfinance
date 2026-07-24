<?php
require_once 'config/database.php';

echo "<h2>Database Connection Test</h2>";

try {
    $pdo = getConnection();
    echo "<p style='color: green;'>✓ Database connected successfully!</p>";
    
    // Test inserting into a table
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $count = $stmt->fetchColumn();
    echo "<p>Users in database: $count</p>";
    
    // Handle insert test before displaying form
    if (isset($_POST['test_insert'])) {
        $sql = "INSERT INTO contacts (type, company_name, contact_person, email, created_at) 
                VALUES ('customer', :company, :contact, :email, NOW())";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':company' => $_POST['test_name'],
            ':contact' => $_POST['test_name'],
            ':email' => 'test@test.com'
        ]);
        
        if ($result) {
            echo "<p style='color: green;'>✓ Record inserted successfully! ID: " . $pdo->lastInsertId() . "</p>";
        } else {
            echo "<p style='color: red;'>✗ Insert failed</p>";
        }
    }
    
    // Test if we can insert
    echo "<h3>Test Insert:</h3>";
    echo "<form method='POST'>";
    echo "<input type='text' name='test_name' placeholder='Test Name' required>";
    echo "<button type='submit' name='test_insert'>Test Insert</button>";
    echo "</form>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Database error: " . $e->getMessage() . "</p>";
}
?>