<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

try {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "invoice_system";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT MAX(CAST(invoice_number AS UNSIGNED)) as max_number FROM invoices WHERE invoice_number REGEXP '^[0-9]+$'";
    $result = $conn->query($sql);

    $max_number = 1000; // Default starting number
    if ($result && $row = $result->fetch_assoc()) {
        $max_number = ($row['max_number'] ?? 1000) + 1;
    }

    echo json_encode(['invoice_number' => $max_number]);

    $conn->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
