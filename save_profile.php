<?php
header('Content-Type: application/json');
include '../../includes/config.php';

$data = json_decode(file_get_contents('php://input'), true);

$stmt = $conn->prepare("INSERT INTO company_profile (company_name, address, city, country, postal_code, email, phone, website, tax_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssss", 
    $data['company_name'],
    $data['address'],
    $data['city'],
    $data['country'],
    $data['postal_code'],
    $data['email'],
    $data['phone'],
    $data['website'],
    $data['tax_number']
);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Profile saved successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save profile']);
}