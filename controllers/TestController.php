<?php
require_once __DIR__ . '/../core/Controller.php';

class TestController extends Controller {
    
    public function index() {
        echo "<h2>Test Controller</h2>";
        echo "<form method='POST' action='test/create'>";
        echo "<input type='text' name='name' placeholder='Name' required>";
        echo "<button type='submit'>Submit (POST)</button>";
        echo "</form>";
        
        echo "<hr>";
        echo "<form id='ajaxForm'>";
        echo "<input type='text' name='name' id='ajaxName' placeholder='Name' required>";
        echo "<button type='button' onclick='submitAjax()'>Submit (AJAX)</button>";
        echo "</form>";
        
        echo "<div id='result'></div>";
        
        echo "<script>
        function submitAjax() {
            var name = $('#ajaxName').val();
            $.ajax({
                url: 'test/create',
                type: 'POST',
                data: {name: name},
                success: function(response) {
                    $('#result').html('<p style=\'color: green;\'>✓ Success: ' + JSON.stringify(response) + '</p>');
                },
                error: function(xhr) {
                    $('#result').html('<p style=\'color: red;\'>✗ Error: ' + xhr.responseText + '</p>');
                }
            });
        }
        </script>";
    }
    
    public function create() {
        if ($this->isPost()) {
            $name = $this->post('name');
            
            // Test database insert
            try {
                $sql = "INSERT INTO contacts (type, company_name, contact_person, email, created_at) 
                        VALUES ('customer', :name, :name, :email, NOW())";
                $stmt = $this->db->prepare($sql);
                $result = $stmt->execute([
                    ':name' => $name,
                    ':email' => $name . '@test.com'
                ]);
                
                if ($result) {
                    $id = $this->db->lastInsertId();
                    $this->json(['success' => true, 'id' => $id, 'message' => 'Record created']);
                } else {
                    $this->json(['success' => false, 'message' => 'Insert failed']);
                }
            } catch (Exception $e) {
                $this->json(['success' => false, 'message' => $e->getMessage()]);
            }
            return;
        }
        
        echo "This endpoint only accepts POST requests.";
    }
}
