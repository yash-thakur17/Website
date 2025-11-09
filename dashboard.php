<!-- <!DOCTYPE html> -->
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="base_styling.css">
    <link href="https://fonts.googleapis.com/css?family=Karla" rel="stylesheet">
    <title>Dashboard</title>
        
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .stat-title {
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .stat-value {
            font-size: 1.875rem;
            font-weight: 600;
            color: #111827;
        }

        .stat-trend {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            margin-top: 8px;
        }

        .trend-up {
            color: #059669;
        }

        .trend-down {
            color: #dc2626;
        }

        .invoice-sections {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }

        .section-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
            overflow: hidden;
        }

        .section-header {
            padding: 16px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .section-content {
            padding: 24px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-table th,
        .invoice-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .invoice-table th {
            font-weight: 500;
            color: #6b7280;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-paid {
            background: #d1fae5;
            color: #059669;
        }

        .status-unpaid {
            background: #fee2e2;
            color: #dc2626;
        }

        .status-partially {
            background: #fef3c7;
            color: #d97706;
        }


        .chart-container {
            height: 300px;
            margin-top: 16px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">
        <div class="dashboard-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Total Outstanding</span>
                </div>
                <div class="stat-value" id="totalOutstanding">₹0.00</div>
                <div class="stat-trend">
                    <span>Till Now</span>
                </div>
            </div> 

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Total Received</span>
                </div>
                <div class="stat-value" id="totalReceived">₹0.00</div>
                <div class="stat-trend">
                    <span>Till Now</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Unpaid Invoices</span>
                </div>
                <div class="stat-value" id="unpaidCount">0</div>
                <div class="stat-trend">
                    <span>Till Now</span>
                </div>
            </div>
        </div>

        <div class="invoice-sections">
            <div class="section-card">
                <div class="section-header">
                    <h3>Recent Invoices</h3>
                </div>
                <div class="section-content">
                    <table class="invoice-table" id="recentInvoicesTable">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Client</th>
                                <th>Due Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="section-card">
                <div class="section-header">
                    <h3>Payment Status</h3>
                </div>
                <div class="section-content">
                    <canvas id="paymentStatusChart"></canvas>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Fetch dashboard data
        async function loadDashboardData() {
            try {
                const response = await fetch('api/dashboard/get_dashboard_data.php');
                const data = await response.json();

                if (data.success) {
                    updateDashboardStats(data.stats);
                    updateRecentInvoices(data.recent_invoices);
                    updatePaymentChart(data.payment_status);
                }
            } catch (error) {
                console.error('Error loading dashboard data:', error);
            }
        }

        // Update dashboard statistics
        function updateDashboardStats(stats) {
            document.getElementById('totalOutstanding').textContent =
                '₹' + parseFloat(stats.total_outstanding).toLocaleString('en-IN');
            document.getElementById('totalReceived').textContent =
                '₹' + parseFloat(stats.total_received).toLocaleString('en-IN');
            document.getElementById('unpaidCount').textContent = stats.unpaid_count;
        }

        // Update recent invoices table
        function updateRecentInvoices(invoices) {
            const tbody = document.getElementById('recentInvoicesTable').getElementsByTagName('tbody')[0];
            tbody.innerHTML = invoices.map(invoice => `
                <tr>
                    <td>${invoice.invoice_number}</td>
                    <td>${invoice.client_name}</td>
                    <td>${new Date(invoice.due_date).toLocaleDateString()}</td>
                    <td>₹${parseFloat(invoice.amount).toLocaleString('en-IN')}</td>
                    <td><span class="status-badge status-${invoice.status.toLowerCase()}">${invoice.status}</span></td>
                </tr>
            `).join('');
        }

        // Create payment status chart
        function updatePaymentChart(data) {
            const ctx = document.getElementById('paymentStatusChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Paid', 'Unpaid', 'Partially Paid'],
                    datasets: [{
                        data: [data.paid, data.unpaid, data.partial],
                        backgroundColor: ['#059669', '#dc2626', '#d97706']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Load dashboard data when page loads
        document.addEventListener('DOMContentLoaded', loadDashboardData);
    </script>
</body>

</html>