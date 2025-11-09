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

    $sql = "SELECT * FROM clients ORDER BY company_name";
    $result = $conn->query($sql);

    $clients = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $clients[] = $row;
        }
    }

    echo json_encode($clients);

    $conn->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
