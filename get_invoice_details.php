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
    // Check if invoice_number is provided
    if (!isset($_GET['invoice_number'])) {
        echo json_encode(['success' => false, 'message' => 'Invoice number is required']);
        exit;
    }

    $invoice_number = $_GET['invoice_number'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "invoice_system");

    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed']);
        exit;
    }

    // Get invoice with client information
    $sql = "SELECT 
                i.*,
                c.company_name,
                c.contact_person,
                c.email,
                c.phone,
                c.gst_number,
                c.address
            FROM invoices i
            JOIN clients c ON i.client_id = c.id
            WHERE i.invoice_number = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $invoice_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Invoice not found']);
        exit;
    }

    $invoice = $result->fetch_assoc();

    // Get invoice items
    $items_sql = "SELECT * FROM invoice_items WHERE invoice_id = ?";
    $items_stmt = $conn->prepare($items_sql);
    $items_stmt->bind_param("i", $invoice['id']);
    $items_stmt->execute();
    $items_result = $items_stmt->get_result();

    $items = [];
    while ($item = $items_result->fetch_assoc()) {
        $items[] = [
            'id' => $item['id'],
            'description' => $item['description'],
            'quantity' => (float)$item['quantity'],
            'unit' => $item['unit'],
            'unit_price' => (float)$item['unit_price'],
            'tax_rate' => (float)$item['tax_rate'],
            'line_total' => (float)$item['line_total']
        ];
    }

    $invoice_data = [
        'id' => $invoice['id'],
        'invoice_number' => $invoice['invoice_number'],
        'invoice_date' => $invoice['invoice_date'],
        'due_date' => $invoice['due_date'],
        'subtotal' => (float)$invoice['subtotal'],
        'total_tax' => (float)$invoice['total_tax'],
        'discount' => (float)$invoice['discount'],
        'grand_total' => (float)$invoice['grand_total'],
        'status' => $invoice['status'],
        'client' => [
            'company_name' => $invoice['company_name'],
            'contact_person' => $invoice['contact_person'],
            'email' => $invoice['email'],
            'phone' => $invoice['phone'],
            'gst_number' => $invoice['gst_number'],
            'address' => $invoice['address']
        ],
        'items' => $items
    ];

    echo json_encode(['success' => true, 'invoice' => $invoice_data]);

    $stmt->close();
    $items_stmt->close();
    $conn->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error occurred']);
}
