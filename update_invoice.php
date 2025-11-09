<?php
// Turn off all error output to prevent HTML in JSON response
error_reporting(0);
ini_set('display_errors', 0);

// Clean output buffer
ob_clean();

// Set JSON headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Only POST allowed']);
    exit;
}

// Database connection using mysqli (consistent with your other files)
$conn = new mysqli("localhost", "root", "", "invoice_system");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}
 
try {
    // Get input
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    // Check if this is a delete request
    if (isset($data['delete']) && $data['delete'] === true) {
        if (empty($data['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invoice ID is required for deletion']);
            exit;
        }
        $invoice_id = (int)$data['id'];
        
        $conn->begin_transaction();
        try {
            // Delete from invoice_items first
            $delete_items = $conn->prepare("DELETE FROM invoice_items WHERE invoice_id = ?");
            $delete_items->bind_param("i", $invoice_id);
            $delete_items->execute();
            
            // Then delete from invoices
            $delete_invoice = $conn->prepare("DELETE FROM invoices WHERE id = ?");
            $delete_invoice->bind_param("i", $invoice_id);
            $delete_invoice->execute();
            
            $conn->commit();
            echo json_encode(['success' => true, 'message' => 'Invoice deleted successfully']);
        } catch (Exception $e) {
            $conn->rollback();
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to delete invoice: ' . $e->getMessage()]);
        }
        exit;
    }

    // Normal update validation
    if (
        !$data || empty($data["id"]) || empty($data["client_id"]) ||
        empty($data["invoice_date"]) ||
        !isset($data["items"]) || !is_array($data["items"])
    ) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required invoice data for update']);
        exit;
    }
    
    $conn->begin_transaction();
    try {
        $invoice_id = (int)$data["id"]; 
        
        // UPDATE invoices table
        // FIXED: Correct bind_param types - "isddddsddi"
        $invoice_stmt = $conn->prepare("UPDATE invoices SET client_id = ?, invoice_date = ?, subtotal = ?, total_tax = ?, discount = ?, grand_total = ?, status = ?, amount_received = ? WHERE id = ?");
        $invoice_stmt->bind_param(
            "isddddsdi",  // CORRECTED: i=int, s=string, d=double
            $data["client_id"],        // i - integer
            $data["invoice_date"],     // s - string (date)
            $data["subtotal"],         // d - double (decimal)
            $data["total_tax"],        // d - double (decimal)
            $data["discount"],         // d - double (decimal)
            $data["grand_total"],      // d - double (decimal) - FIXED!
            $data["status"],           // s - string - FIXED!
            $data["amount_received"],  // d - double (decimal) - FIXED!
            $invoice_id                // i - integer
        );
        $invoice_stmt->execute();

        // Delete existing invoice items to replace them
        $delete_stmt = $conn->prepare("DELETE FROM invoice_items WHERE invoice_id = ?");
        $delete_stmt->bind_param("i", $invoice_id);
        $delete_stmt->execute();

        // Insert new/updated invoice items
        $item_stmt = $conn->prepare("INSERT INTO invoice_items (invoice_id, description, quantity, unit, unit_price, tax_rate, line_total) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        foreach ($data["items"] as $item) {
            // Recalculate line total on the server for data integrity
            $line_total = (float)$item["quantity"] * (float)$item["unit_price"] * (1 + ((float)$item["tax_rate"] / 100));
            $unit = $item["unit"] ?? "";

            $item_stmt->bind_param(
                "isdsddd",
                $invoice_id,
                $item["description"],
                $item["quantity"],
                $unit,
                $item["unit_price"],
                $item["tax_rate"],
                $line_total
            );
            $item_stmt->execute();
        }
        
        $conn->commit();
        echo json_encode([
            "success" => true,
            "message" => "Invoice updated successfully"
        ]);
    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Transaction error: " . $e->getMessage()
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "A critical error occurred: " . $e->getMessage()
    ]);
}

$conn->close();
?>