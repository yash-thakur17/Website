<?php
// Turn off all error output to prevent HTML in JSON response
error_reporting(0);
ini_set('display_errors', 0);

// Clean output buffer
ob_clean();

// Set JSON headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
 
try {
    // Database connection
    $conn = new mysqli("localhost", "root", "", "invoice_system");
    
    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed']);
        exit;
    }

    // Get all invoices with client information
    $sql = "SELECT 
                i.id, 
                i.invoice_number, 
                i.client_id, 
                i.subtotal, 
                i.total_tax, 
                i.discount, 
                i.grand_total, 
                i.status, 
                i.invoice_date, 
                i.amount_received,
                c.company_name
            FROM invoices i 
            LEFT JOIN clients c ON i.client_id = c.id 
            ORDER BY i.invoice_date DESC";
    
    $result = $conn->query($sql);
    
    $invoices = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Get invoice items for this invoice
            $items_sql = "SELECT * FROM invoice_items WHERE invoice_id = " . $row['id'];
            $items_result = $conn->query($items_sql);
            
            $items = [];
            if ($items_result && $items_result->num_rows > 0) {
                while ($item_row = $items_result->fetch_assoc()) {
                    $items[] = [
                        'id' => $item_row['id'],
                        'description' => $item_row['description'],
                        'quantity' => (float)$item_row['quantity'],
                        'unit' => $item_row['unit'],
                        'unit_price' => (float)$item_row['unit_price'],
                        'tax_rate' => (float)$item_row['tax_rate'],
                        'line_total' => (float)$item_row['line_total']
                    ];
                }
            }
            
            $invoices[] = [
                'id' => $row['id'],
                'invoice_number' => $row['invoice_number'],
                'invoice_date' => $row['invoice_date'],
                'due_date' => $row['due_date'],
                'subtotal' => (float)$row['subtotal'],
                'total_tax' => (float)$row['total_tax'],
                'discount' => (float)$row['discount'],
                'grand_total' => (float)$row['grand_total'],
                'status' => $row['status'],
                'amount_received' => isset($row['amount_received']) ? (float)$row['amount_received'] : 0.0,
                'client' => [
                    'company_name' => $row['company_name'],
                    'contact_person' => $row['contact_person'],
                    'email' => $row['email'],
                    'phone' => $row['phone'],
                    'gst_number' => $row['gst_number'],
                    'address' => $row['address']
                ],
                'items' => $items
            ];
        }
    }
    
    echo json_encode(['success' => true, 'invoices' => $invoices]);
    
    $conn->close();

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error occurred']);
}
?>