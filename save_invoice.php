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
 
// Test GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(['status' => 'working', 'file' => 'save_invoice.php']);
    exit;
}

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Only POST allowed']);
    exit;
}

try {
    // Database connection
    $conn = new mysqli("localhost", "root", "", "invoice_system");

    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed']);
        exit;
    }

    // Get input
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    // Validation
    if (
        !$data || empty($data["invoice_number"]) || empty($data["client_id"]) ||
        empty($data["invoice_date"]) ||
        empty($data["items"]) || !is_array($data["items"])
    ) {
        echo json_encode(['success' => false, 'message' => 'Missing required invoice data']);
        exit;
    }

    // Start transaction
    $conn->autocommit(FALSE);

    try {
        // Prepare invoice variables (PHP 8+ requirement)
        $invoice_number = $data["invoice_number"];
        $client_id = (int)$data["client_id"];
        $invoice_date = $data["invoice_date"];
        $subtotal = (float)$data["subtotal"];
        $total_tax = (float)$data["total_tax"];
        $discount = isset($data["discount"]) ? (float)$data["discount"] : 0.0;
        $grand_total = (float)$data["grand_total"];
        $status = 'Unpaid'; // Default status
        $amount_received = 0.0; // Default amount received

        // Insert invoice
        $invoice_stmt = $conn->prepare("INSERT INTO invoices (invoice_number, client_id, invoice_date, subtotal, total_tax, discount, grand_total, status, amount_received) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if (!$invoice_stmt) {
            throw new Exception("Invoice prepare failed");
        }

        $invoice_stmt->bind_param(
            "sisddddsd",
            $invoice_number,
            $client_id,
            $invoice_date,
            $subtotal,
            $total_tax,
            $discount,
            $grand_total,
            $status,
            $amount_received
        );

        if (!$invoice_stmt->execute()) {
            throw new Exception("Invoice insert failed");
        }

        $invoice_id = $conn->insert_id;

        // Insert invoice items
        $item_stmt = $conn->prepare("INSERT INTO invoice_items (invoice_id, description, quantity, unit, unit_price, tax_rate, line_total) VALUES (?, ?, ?, ?, ?, ?, ?)");

        if (!$item_stmt) {
            throw new Exception("Item prepare failed");
        }

        foreach ($data["items"] as $item) {
            if (empty($item["description"]) || !isset($item["quantity"]) || !isset($item["unit_price"])) {
                throw new Exception("Invalid item data");
            }

            // Prepare item variables (PHP 8+ requirement)
            $item_invoice_id = (int)$invoice_id;
            $item_description = $item["description"];
            $item_quantity = (float)$item["quantity"];
            $item_unit = isset($item["unit"]) ? $item["unit"] : "";
            $item_unit_price = (float)$item["unit_price"];
            $item_tax_rate = isset($item["tax_rate"]) ? (float)$item["tax_rate"] : 0.0;
            $item_line_total = $item_quantity * $item_unit_price * (1 + ($item_tax_rate / 100));

            $item_stmt->bind_param(
                "isdsddd",
                $item_invoice_id,
                $item_description,
                $item_quantity,
                $item_unit,
                $item_unit_price,
                $item_tax_rate,
                $item_line_total
            );

            if (!$item_stmt->execute()) {
                throw new Exception("Item insert failed");
            }
        }

        // Commit transaction
        $conn->commit();

        echo json_encode([
            "success" => true,
            "invoice_id" => $invoice_id,
            "message" => "Invoice created successfully"
        ]);

        $invoice_stmt->close();
        $item_stmt->close();
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        throw $e;
    }

    $conn->close();
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error occurred"
    ]);
}
