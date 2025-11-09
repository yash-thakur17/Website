<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

include '../../includes/config.php';

function executeQuery($conn, $sql) {
    $result = $conn->query($sql);

    if ($result === false) {
        throw new Exception("Database query failed: " . $conn->error);
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $result->free_result();
    return $data;
}

try {
    if (!isset($conn) || !$conn instanceof mysqli) {
        throw new Exception("Database connection object is not available or invalid.");
    }
    
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    $type = $_GET['type'] ?? 'invoices';
    $sql = '';

    switch ($type) {
        case 'dashboard_stats':
            $sql = "SELECT 
                        SUM(grand_total) as total_invoiced,
                        SUM(COALESCE(amount_received, 0)) as total_received,
                        SUM(CASE WHEN status IN ('unpaid', 'partially_paid', 'Partially Paid') THEN (grand_total - COALESCE(amount_received, 0)) ELSE 0 END) as total_outstanding,
                        (SELECT COUNT(DISTINCT client_id) FROM invoices) as active_clients,
                        COUNT(id) as total_invoices,
                        ROUND(AVG(DATEDIFF(updated_at, invoice_date))) as avg_days_outstanding
                    FROM invoices
                    WHERE updated_at IS NOT NULL";
            break;

        case 'monthly_revenue':
            $sql = "SELECT 
                        DATE_FORMAT(updated_at, '%Y-%m') as month,
                        SUM(COALESCE(amount_received, 0)) as revenue,
                        COUNT(id) as invoice_count
                    FROM invoices 
                    WHERE updated_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) AND status IN ('paid', 'Paid')
                    GROUP BY DATE_FORMAT(updated_at, '%Y-%m')
                    ORDER BY month DESC";
            break;

        case 'top_paying_clients':
            $sql = "SELECT 
                        c.company_name,
                        COUNT(i.id) as total_invoices,
                        SUM(i.grand_total) as total_amount,
                        SUM(COALESCE(i.amount_received, 0)) as amount_paid
                    FROM clients c
                    LEFT JOIN invoices i ON c.id = i.client_id
                    GROUP BY c.id, c.company_name
                    HAVING total_amount > 0
                    ORDER BY total_amount DESC
                    LIMIT 10";
            break;

        case 'client_outstanding':
            $sql = "SELECT 
                        c.id as client_id, 
                        c.company_name, 
                        c.contact_person, 
                        c.email,
                        SUM(
                            CASE 
                                WHEN i.status IN ('unpaid', 'partially_paid', 'Partially Paid') 
                                THEN (i.grand_total - COALESCE(i.amount_received, 0))
                                ELSE 0
                            END
                        ) as outstanding_amount,
                        COUNT(CASE WHEN i.status IN ('unpaid', 'partially_paid', 'Partially Paid') THEN 1 END) as outstanding_invoices
                    FROM clients c
                    LEFT JOIN invoices i ON c.id = i.client_id
                    GROUP BY c.id, c.company_name, c.contact_person, c.email
                    HAVING outstanding_amount > 0
                    ORDER BY outstanding_amount DESC";
            break;

        case 'payment_performance':
            $sql = "SELECT 
                        c.company_name,
                        ROUND(AVG(DATEDIFF(i.updated_at, i.invoice_date))) as avg_payment_days,
                        COUNT(i.id) as total_invoices,
                        SUM(i.grand_total) as total_amount,
                        COUNT(CASE WHEN DATEDIFF(i.updated_at, i.invoice_date) <= 30 THEN 1 END) as on_time_payments
                    FROM invoices i
                    LEFT JOIN clients c ON i.client_id = c.id
                    WHERE i.status IN ('paid', 'Paid')
                        AND i.updated_at IS NOT NULL 
                        AND i.invoice_date IS NOT NULL
                    GROUP BY c.id, c.company_name
                    HAVING total_invoices >= 1
                    ORDER BY avg_payment_days ASC";
            break;

        case 'aging_report':
            $sql = "SELECT 
                        i.id, 
                        i.invoice_number, 
                        i.client_id, 
                        c.company_name,
                        (i.grand_total - COALESCE(i.amount_received, 0)) as outstanding_amount,
                        DATEDIFF(CURDATE(), i.invoice_date) as days_overdue,
                        CASE 
                            WHEN DATEDIFF(CURDATE(), i.invoice_date) <= 30 THEN 'current'
                            WHEN DATEDIFF(CURDATE(), i.invoice_date) BETWEEN 31 AND 60 THEN '31-60'
                            WHEN DATEDIFF(CURDATE(), i.invoice_date) BETWEEN 61 AND 90 THEN '61-90'
                            ELSE '90+'
                        END as aging_bucket
                    FROM invoices i
                    LEFT JOIN clients c ON i.client_id = c.id
                    WHERE i.status IN ('unpaid', 'partially_paid', 'Partially Paid')
                        AND (i.grand_total - COALESCE(i.amount_received, 0)) > 0.01
                    ORDER BY days_overdue DESC";
            break;

        case 'invoices':
            $sql = "SELECT 
                        i.id, 
                        i.invoice_number, 
                        i.client_id, 
                        i.subtotal,
                        i.total_tax, 
                        i.discount, 
                        i.grand_total as amount, 
                        i.status,
                        i.invoice_date, 
                        i.amount_received as paid_amount,
                        i.created_at, 
                        i.updated_at, 
                        c.company_name as client_name
                    FROM invoices i 
                    LEFT JOIN clients c ON i.client_id = c.id 
                    ORDER BY i.invoice_date DESC";
            break;

        default:
            throw new Exception("Invalid report type specified.");
    }

    $data = executeQuery($conn, $sql);
    echo json_encode($data);

} catch (Exception $e) {
    error_log("Exception in get_reports_data.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);

} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}