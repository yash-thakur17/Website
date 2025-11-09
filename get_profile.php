<?php
header('Content-Type: application/json');
include '../../includes/config.php';

$sql = "SELECT * FROM company_profile ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
$profile = $result->fetch_assoc();

echo json_encode([
    'success' => true,
    'profile' => $profile
]);

// echo $result;