<?php
$tcpdf_path = __DIR__ . '/libraries/tcpdf/tcpdf.php';

if (file_exists($tcpdf_path)) {
    require_once $tcpdf_path;
    
    try {
        // Create new PDF document
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator('ERP System');
        $pdf->SetAuthor('ERP System');
        $pdf->SetTitle('TCPDF Test');
        $pdf->SetSubject('TCPDF Test');
        
        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Add a page
        $pdf->AddPage();
        
        // Set font
        $pdf->SetFont('helvetica', '', 16);
        
        // Add content
        $pdf->Cell(0, 10, 'TCPDF Installation Successful!', 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, 'Your ERP system can now generate PDF reports.', 0, 1, 'C');
        $pdf->Ln(20);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Available PDF Reports:', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 8, '• Sales Report', 0, 1, 'L');
        $pdf->Cell(0, 8, '• Expense Report', 0, 1, 'L');
        $pdf->Cell(0, 8, '• Profit & Loss Statement', 0, 1, 'L');
        $pdf->Cell(0, 8, '• Inventory Report', 0, 1, 'L');
        
        // Output PDF
        $pdf->Output('tcpdf_test.pdf', 'I');
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    }
    
} else {
    echo "<p style='color: red;'>✗ TCPDF not found at: $tcpdf_path</p>";
    echo "<p>Please make sure TCPDF is installed in the libraries/tcpdf/ folder.</p>";
}
?>