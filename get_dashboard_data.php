<?php
header('Content-Type: application/json');
include '../../includes/config.php';

try {
    $stats = [
        'total_outstanding' => 0,
        'total_received' => 0,
        'unpaid_count' => 0
    ];

    // Get total outstanding and received amounts
    $sql = "SELECT 
        SUM(CASE WHEN status != 'paid' THEN grand_total - COALESCE(amount_received, 0) ELSE 0 END) as total_outstanding,
        SUM(COALESCE(amount_received, 0)) as total_received,
        COUNT(CASE WHEN status != 'paid' THEN 1 END) as unpaid_count
        FROM invoices";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $stats['total_outstanding'] = $row['total_outstanding'] ?? 0;
    $stats['total_received'] = $row['total_received'] ?? 0;
    $stats['unpaid_count'] = $row['unpaid_count'] ?? 0;

    // Get recent invoices
    $sql = "SELECT i.invoice_number, c.company_name as client_name, 
            i.grand_total as amount, i.status
            FROM invoices i
            JOIN clients c ON i.client_id = c.id
            ORDER BY i.created_at DESC LIMIT 5";
    $result = $conn->query($sql);
    $recent_invoices = [];
    while($row = $result->fetch_assoc()) {
        $recent_invoices[] = $row;
    }

    // Get payment status distribution
    $sql = "SELECT 
        COUNT(CASE WHEN status = 'paid' THEN 1 END) as paid,
        COUNT(CASE WHEN status = 'unpaid' THEN 1 END) as unpaid,
        COUNT(CASE WHEN status = 'partially paid' THEN 1 END) as partial
        FROM invoices";
    $result = $conn->query($sql);
    $payment_status = $result->fetch_assoc();

    echo json_encode([
        'success' => true,
        'stats' => $stats,
        'recent_invoices' => $recent_invoices,
        'payment_status' => $payment_status
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}