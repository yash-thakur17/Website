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
    echo json_encode(['status' => 'working', 'file' => 'save_clients.php']);
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

    // Support both JSON and form data
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (stripos($contentType, 'application/json') !== false) {
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
    } else {
        $data = $_POST;
    }

    // Map camelCase to snake_case for compatibility
    $field_map = [
        'companyName' => 'company_name',
        'contactPerson' => 'contact_person',
        'gstNumber' => 'gst_number',
    ];
    foreach ($field_map as $camel => $snake) {
        if (isset($data[$camel]) && !isset($data[$snake])) {
            $data[$snake] = $data[$camel];
        }
    }

    // Delete client if requested (delete takes priority)
    if (isset($data['delete']) && $data['delete'] == '1' && !empty($data['id'])) {
        $id = intval($data['id']);
        $stmt = $conn->prepare("DELETE FROM clients WHERE id=?");
        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Prepare failed']);
            exit;
        }
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Client deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Delete failed']);
        }
        $stmt->close();
        $conn->close();
        exit;
    }

    // Check for edit mode
    if (isset($data['edit']) && $data['edit'] == '1' && !empty($data['id'])) {
        // Update existing client
        $id = intval($data['id']);
        $company_name = $data["company_name"] ?? '';
        $contact_person = $data["contact_person"] ?? '';
        $email = $data["email"] ?? '';
        $phone = $data["phone"] ?? '';
        $gst_number = $data["gst_number"] ?? '';
        $address = $data["address"] ?? '';
        $notes = $data["notes"] ?? '';

        $stmt = $conn->prepare("UPDATE clients SET company_name=?, contact_person=?, email=?, phone=?, gst_number=?, address=?, notes=? WHERE id=?");
        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Prepare failed']);
            exit;
        }
        $stmt->bind_param(
            "sssssssi",
            $company_name,
            $contact_person,
            $email,
            $phone,
            $gst_number,
            $address,
            $notes,
            $id
        );
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Client updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Update failed']);
        }
        $stmt->close();
        $conn->close();
        exit;
    }

    // FIXED: Assign variables BEFORE using them in INSERT
    $company_name = $data["company_name"] ?? '';
    $contact_person = $data["contact_person"] ?? '';
    $email = $data["email"] ?? '';
    $phone = $data["phone"] ?? '';
    $gst_number = $data["gst_number"] ?? '';
    $address = $data["address"] ?? '';
    $notes = $data["notes"] ?? '';

    // Validation - at least company name is required
    if (empty($company_name)) {
        echo json_encode(['success' => false, 'message' => 'Company name is required']);
        exit;
    }

    // Insert client
    $stmt = $conn->prepare("INSERT INTO clients (company_name, contact_person, email, phone, gst_number, address, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Prepare failed']);
        exit;
    }

    // Now bind with properly defined variables
    $stmt->bind_param(
        "sssssss",
        $company_name,
        $contact_person,
        $email,
        $phone,
        $gst_number,
        $address,
        $notes
    );

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Client saved successfully', 'id' => $conn->insert_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Execute failed']);
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error occurred: ' . $e->getMessage()]);
}
