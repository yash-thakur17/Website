<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reports - Invoice Management</title>
  <link rel="stylesheet" href="base_styling.css">
  <link href="https://fonts.googleapis.com/css2?family=Karla:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    .header {
      margin-bottom: 30px;
    }

    .header h1 {
      font-size: 28px;
      color: #1f2937;
      margin-bottom: 5px;
      font-weight: 700;
    }

    .header p {
      color: #6b7280;
    }

    /* KPI Cards Grid */
    .kpi-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .kpi-card {
      background: white;
      padding: 24px;
      border-radius: 12px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      border: 1px solid #e5e7eb;
      border-left: 4px solid #3b82f6;
    }

    .kpi-card.danger {
      border-left: 4px solid #dc2626;
    }

    .kpi-card.warning {
      border-left: 4px solid #f59e0b;
    }

    .kpi-card.success {
      border-left: 4px solid #10b981;
    }

    .kpi-label {
      font-size: 13px;
      color: #6b7280;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 8px;
    }

    .kpi-value {
      font-size: 32px;
      font-weight: 700;
      margin-bottom: 8px;
      color: #1f2937;
    }

    .kpi-subtitle {
      font-size: 12px;
      color: #6b7280;
    }

    .kpi-subtitle.positive {
      color: #10b981;
    }

    .kpi-subtitle.negative {
      color: #dc2626;
    }

    /* Section Grid */
    .section-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 20px;
      margin-bottom: 30px;
    }

    .section {
      background: white;
      border-radius: 12px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      border: 1px solid #e5e7eb;
      overflow: hidden;
    }

    .section-header {
      padding: 20px 24px;
      border-bottom: 1px solid #e5e7eb;
      background: linear-gradient(135deg, #3b82f6, #1d4ed8);
      color: white;
    }

    .section-header h3 {
      font-size: 18px;
      font-weight: 600;
      margin: 0;
    }

    .section-content {
      padding: 24px;
    }

    /* Table Styles */
    .table-responsive {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
    }

    table thead {
      background: #f3f4f6;
    }

    table th {
      padding: 12px;
      text-align: left;
      font-weight: 600;
      color: #6b7280;
      border-bottom: 2px solid #e5e7eb;
    }

    table td {
      padding: 12px;
      border-bottom: 1px solid #f3f4f6;
    }

    table tbody tr:hover {
      background: #f9fafb;
    }

    .status-badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }

    .status-badge.paid {
      background: #d1fae5;
      color: #065f46;
    }

    .status-badge.unpaid {
      background: #fee2e2;
      color: #991b1b;
    }

    .status-badge.partially-paid {
      background: #fef3c7;
      color: #78350f;
    }

    .amount {
      font-weight: 600;
      color: #1f2937;
    }

    .amount.overdue {
      color: #dc2626;
    }

    .amount.current {
      color: #10b981;
    }

    /* Client List */
    .client-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 0;
      border-bottom: 1px solid #f3f4f6;
    }

    .client-item:last-child {
      border-bottom: none;
    }

    .client-info h4 {
      font-size: 14px;
      font-weight: 600;
      color: #1f2937;
      margin: 0 0 4px 0;
    }

    .client-info p {
      font-size: 12px;
      color: #6b7280;
      margin: 0;
    }

    /* Chart Styles */
    .chart-container {
      height: 250px;
      display: flex;
      align-items: flex-end;
      justify-content: space-around;
      padding: 20px 0;
      position: relative;
    }

    .chart-bar {
      width: 40px;
      background: linear-gradient(to top, #3b82f6, #60a5fa);
      border-radius: 4px 4px 0 0;
      position: relative;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .chart-bar:hover {
      background: linear-gradient(to top, #1d4ed8, #3b82f6);
      transform: translateY(-2px);
    }

    .chart-bar .label {
      position: absolute;
      bottom: -25px;
      left: 50%;
      transform: translateX(-50%);
      font-size: 11px;
      color: #6b7280;
      font-weight: 500;
    }

    .chart-bar .value {
      position: absolute;
      top: -25px;
      left: 50%;
      transform: translateX(-50%);
      font-size: 11px;
      color: #1f2937;
      font-weight: 600;
      white-space: nowrap;
    }

    /* Aging Report */
    .aging-report {
      grid-column: 1 / -1;
    }

    .aging-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
    }

    .aging-card {
      text-align: center;
      padding: 20px;
      border-radius: 8px;
      border: 2px solid #e5e7eb;
      transition: all 0.3s ease;
    }

    .aging-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .aging-card.current {
      border-color: #10b981;
      background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    }

    .aging-card.days-30 {
      border-color: #f59e0b;
      background: linear-gradient(135deg, #fffbeb, #fef3c7);
    }

    .aging-card.days-60 {
      border-color: #ef4444;
      background: linear-gradient(135deg, #fef2f2, #fecaca);
    }

    .aging-card.days-90 {
      border-color: #7c2d12;
      background: linear-gradient(135deg, #fef2f2, #fecaca);
    }

    .aging-card h4 {
      font-size: 12px;
      color: #6b7280;
      margin: 0 0 8px 0;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .aging-card .amount {
      font-size: 24px;
      font-weight: 700;
      margin: 0 0 4px 0;
    }

    .aging-card .count {
      font-size: 12px;
      color: #6b7280;
      margin: 0;
    }

    .loading {
      text-align: center;
      padding: 40px;
      color: #6b7280;
    }

    .error {
      color: #dc2626;
      padding: 20px;
      text-align: center;
      background: #fef2f2;
      border-radius: 8px;
      margin: 20px 0;
      border: 1px solid #fca5a5;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .section-grid {
        grid-template-columns: 1fr;
      }

      .kpi-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      }

      .aging-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      }
    }
  </style>
</head>

<body>
  <?php include 'includes/sidebar.php'; ?>
  <div class="main-content">
    <header class="header">
      <h1>Business Reports</h1>
      <p>Key metrics and insights into your invoice performance</p>
    </header>

    <!-- KPI Cards -->
    <div class="kpi-grid">
      <div class="kpi-card success">
        <div class="kpi-label">Total Revenue Invoiced</div>
        <div class="kpi-value" id="totalInvoiced">₹0</div>
        <div class="kpi-subtitle">All invoices</div>
      </div>

      <div class="kpi-card success">
        <div class="kpi-label">Total Amount Received</div>
        <div class="kpi-value" id="totalReceived">₹0</div>
        <div class="kpi-subtitle" id="collectionRate">0% collected</div>
      </div>

      <div class="kpi-card danger">
        <div class="kpi-label">Total Outstanding</div>
        <div class="kpi-value" id="totalOutstanding">₹0</div>
        <div class="kpi-subtitle">Amount still owed</div>
      </div>

      <div class="kpi-card warning">
        <div class="kpi-label">Days Sales Outstanding</div>
        <div class="kpi-value" id="daysOutstanding">0</div>
        <div class="kpi-subtitle">Days to collect payment</div>
      </div>

      <div class="kpi-card">
        <div class="kpi-label">Active Clients</div>
        <div class="kpi-value" id="activeClients">0</div>
        <div class="kpi-subtitle">With invoices</div>
      </div>

      <div class="kpi-card">
        <div class="kpi-label">Total Invoices</div>
        <div class="kpi-value" id="totalInvoices">0</div>
        <div class="kpi-subtitle">All time</div>
      </div>
    </div>

    <!-- Revenue Trend & Top Clients -->
    <div class="section-grid">
      <div class="section">
        <div class="section-header">
          <h3>Monthly Revenue Trend</h3>
        </div>
        <div class="section-content">
          <div class="chart-container" id="revenueChart">
            <div class="loading">Loading chart data...</div>
          </div>
        </div>
      </div>

      <div class="section">
        <div class="section-header">
          <h3>Top Paying Clients</h3>
        </div>
        <div class="section-content" id="topClients">
          <div class="loading">Loading client data...</div>
        </div>
      </div>
    </div>

    <!-- Payment Performance & Outstanding by Client -->
    <div class="section-grid">
      <div class="section">
        <div class="section-header">
          <h3>Client Payment Performance</h3>
        </div>
        <div class="section-content">
          <div class="table-responsive">
            <table id="paymentPerformanceTable">
              <thead>
                <tr>
                  <th>Client</th>
                  <th>Avg Days to Pay</th>
                  <th>On-Time Rate</th>
                  <th>Total Invoices</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="4" class="loading">Loading...</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="section">
        <div class="section-header">
          <h3>Outstanding by Client</h3>
        </div>
        <div class="section-content" id="outstandingByClient">
          <div class="loading">Loading...</div>
        </div>
      </div>
    </div>

    <!-- Aging Report -->
    <div class="section-grid">
      <div class="section aging-report">
        <div class="section-header">
          <h3>Aging Report - Outstanding Invoices</h3>
        </div>
        <div class="section-content">
          <div class="aging-grid" id="agingReport">
            <div class="aging-card current">
              <h4>Current (0-30 Days)</h4>
              <div class="amount">₹0</div>
              <div class="count">0 invoices</div>
            </div>
            <div class="aging-card days-30">
              <h4>31-60 Days</h4>
              <div class="amount">₹0</div>
              <div class="count">0 invoices</div>
            </div>
            <div class="aging-card days-60">
              <h4>61-90 Days</h4>
              <div class="amount">₹0</div>
              <div class="count">0 invoices</div>
            </div>
            <div class="aging-card days-90">
              <h4>90+ Days</h4>
              <div class="amount">₹0</div>
              <div class="count">0 invoices</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    class ReportsManager {
      constructor() {
        this.init();
      }

      init() {
        document.addEventListener('DOMContentLoaded', () => {
          this.loadAllReports();
        });
      }

      formatCurrency(amount) {
        return '₹' + parseFloat(amount || 0).toLocaleString('en-IN', {
          minimumFractionDigits: 0,
          maximumFractionDigits: 0
        });
      }

      showError(elementId, message = 'Failed to load data') {
        const element = document.getElementById(elementId);
        if (element) {
          element.innerHTML = `<div class="error">${message}</div>`;
        }
      }

      async fetchData(endpoint) {
        const response = await fetch('api/reports/' + endpoint);
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        if (data.error) {
          throw new Error(data.error);
        }
        return data;
      }

      async loadAllReports() {
        Promise.allSettled([
          this.loadDashboardStats(),
          this.loadRevenueChart(),
          this.loadTopClients(),
          this.loadPaymentPerformance(),
          this.loadOutstandingByClient(),
          this.loadAgingReport()
        ]);
      }

      // Dashboard Statistics
      async loadDashboardStats() {
        try {
          const stats = await this.fetchData('get_reports_data.php?type=dashboard_stats');
          this.updateDashboardStats(stats[0]);
        } catch (error) {
          console.error('Error loading dashboard stats:', error);
        }
      }

      updateDashboardStats(stats) {
        document.getElementById('totalInvoiced').textContent = this.formatCurrency(stats.total_invoiced);
        document.getElementById('totalReceived').textContent = this.formatCurrency(stats.total_received);
        document.getElementById('totalOutstanding').textContent = this.formatCurrency(stats.total_outstanding);
        document.getElementById('daysOutstanding').textContent = stats.avg_days_outstanding || 0;
        document.getElementById('activeClients').textContent = stats.active_clients || 0;
        document.getElementById('totalInvoices').textContent = stats.total_invoices || 0;
        
        const collectionRate = stats.total_invoiced > 0 ? Math.round((stats.total_received / stats.total_invoiced) * 100) : 0;
        document.getElementById('collectionRate').textContent = collectionRate + '% collected';
      }

      // Revenue Chart
      async loadRevenueChart() {
        try {
          const monthlyData = await this.fetchData('get_reports_data.php?type=monthly_revenue');
          this.renderRevenueChart(monthlyData);
        } catch (error) {
          console.error('Error loading revenue chart:', error);
          this.showError('revenueChart', 'Failed to load chart data');
        }
      }

      renderRevenueChart(monthlyData) {
        const chartContainer = document.getElementById('revenueChart');
        if (monthlyData.length === 0) {
          chartContainer.innerHTML = '<p style="text-align: center; color: #6b7280; padding: 40px;">No revenue data available</p>';
          return;
        }

        const revenues = monthlyData.map(d => parseFloat(d.revenue));
        const maxRevenue = Math.max(...revenues);
        const maxHeight = 180;
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        chartContainer.innerHTML = monthlyData.reverse().map(data => {
          const [year, month] = data.month.split('-');
          const monthName = months[parseInt(month) - 1];
          const revenue = parseFloat(data.revenue);
          const height = maxRevenue > 0 ? (revenue / maxRevenue) * maxHeight : 0;

          return `
            <div class="chart-bar" style="height: ${height}px;" title="${monthName}: ${this.formatCurrency(revenue)}">
              <div class="value">${this.formatCurrency(revenue)}</div>
              <div class="label">${monthName}</div>
            </div>
          `;
        }).join('');
      }

      // Top Paying Clients
      async loadTopClients() {
        try {
          const topClients = await this.fetchData('get_reports_data.php?type=top_paying_clients');
          this.renderTopClients(topClients);
        } catch (error) {
          console.error('Error loading top clients:', error);
          this.showError('topClients', 'Failed to load client data');
        }
      }

      renderTopClients(topClients) {
        const container = document.getElementById('topClients');
        if (topClients.length === 0) {
          container.innerHTML = '<p style="text-align: center; color: #6b7280; padding: 20px;">No client data found</p>';
          return;
        }

        container.innerHTML = topClients.slice(0, 5).map(client => `
          <div class="client-item">
            <div class="client-info">
              <h4>${client.company_name}</h4>
              <p>${client.total_invoices} invoices</p>
            </div>
            <div class="amount">${this.formatCurrency(client.total_amount)}</div>
          </div>
        `).join('');
      }

      // Payment Performance
      async loadPaymentPerformance() {
        try {
          const data = await this.fetchData('get_reports_data.php?type=payment_performance');
          this.renderPaymentPerformance(data);
        } catch (error) {
          console.error('Error loading payment performance:', error);
        }
      }

      renderPaymentPerformance(data) {
        const table = document.getElementById('paymentPerformanceTable').getElementsByTagName('tbody')[0];
        if (data.length === 0) {
          table.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #6b7280; padding: 20px;">No payment data available</td></tr>';
          return;
        }

        table.innerHTML = data.slice(0, 10).map(row => {
          const onTimeRate = row.total_invoices > 0 ? Math.round((row.on_time_payments / row.total_invoices) * 100) : 0;
          return `
            <tr>
              <td><strong>${row.company_name}</strong></td>
              <td>${row.avg_payment_days || 0} days</td>
              <td><span class="status-badge ${onTimeRate >= 80 ? 'paid' : onTimeRate >= 50 ? 'partially-paid' : 'unpaid'}">${onTimeRate}%</span></td>
              <td>${row.total_invoices}</td>
            </tr>
          `;
        }).join('');
      }

      // Outstanding by Client
      async loadOutstandingByClient() {
        try {
          const data = await this.fetchData('get_reports_data.php?type=client_outstanding');
          this.renderOutstandingByClient(data);
        } catch (error) {
          console.error('Error loading outstanding:', error);
          this.showError('outstandingByClient', 'Failed to load data');
        }
      }

      renderOutstandingByClient(data) {
        const container = document.getElementById('outstandingByClient');
        if (data.length === 0) {
          container.innerHTML = '<p style="text-align: center; color: #6b7280; padding: 20px;">No outstanding amounts</p>';
          return;
        }

        container.innerHTML = data.slice(0, 5).map(client => `
          <div class="client-item">
            <div class="client-info">
              <h4>${client.company_name}</h4>
              <p>${client.outstanding_invoices} unpaid</p>
            </div>
            <div class="amount overdue">${this.formatCurrency(client.outstanding_amount)}</div>
          </div>
        `).join('');
      }

      // Aging Report
      async loadAgingReport() {
        try {
          const invoices = await this.fetchData('get_reports_data.php?type=aging_report');
          this.updateAgingReport(invoices);
        } catch (error) {
          console.error('Error loading aging report:', error);
        }
      }

      updateAgingReport(invoices) {
        const aging = {
          current: { amount: 0, count: 0 },
          days30: { amount: 0, count: 0 },
          days60: { amount: 0, count: 0 },
          days90: { amount: 0, count: 0 }
        };

        invoices.forEach(invoice => {
          const amount = parseFloat(invoice.outstanding_amount);
          switch (invoice.aging_bucket) {
            case 'current':
            case '1-30':
              aging.current.amount += amount;
              aging.current.count++;
              break;
            case '31-60':
              aging.days30.amount += amount;
              aging.days30.count++;
              break;
            case '61-90':
              aging.days60.amount += amount;
              aging.days60.count++;
              break;
            case '90+':
              aging.days90.amount += amount;
              aging.days90.count++;
              break;
          }
        });

        const agingCards = document.querySelectorAll('.aging-card');
        const agingData = [aging.current, aging.days30, aging.days60, aging.days90];

        agingCards.forEach((card, index) => {
          const data = agingData[index];
          card.querySelector('.amount').textContent = this.formatCurrency(data.amount);
          card.querySelector('.count').textContent = `${data.count} invoice${data.count !== 1 ? 's' : ''}`;
        });
      }
    }

    const reportsManager = new ReportsManager();
  </script>
</body>

</html>