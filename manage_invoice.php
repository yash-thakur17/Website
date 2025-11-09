<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Karla" rel="stylesheet">
    <title>Invoice Management</title>
    <link href="base_styling.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .page-header h2 {
            font-size: 30px;
            font-weight: 700;
            color: #111827;
        }

        .new-invoice-btn {
            background-color: #2563eb;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 200ms ease;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .new-invoice-btn:hover {
            background-color: #1d4ed8;
        }

        .invoice-card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table-container {
            overflow-x: auto;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .invoice-table th {
            padding: 16px 20px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .invoice-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
            transition: background-color 150ms ease;
        }

        .invoice-table tbody tr:hover {
            background-color: #f9fafb;
        }

        .invoice-table tbody tr:last-child {
            border-bottom: none;
        }

        .invoice-table td {
            padding: 16px 20px;
            color: #374151;
            font-weight: 500;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 150ms ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-view {
            background-color: #3b82f6;
            color: white;
        }

        .btn-view:hover {
            background-color: #2563eb;
        }

        .btn-edit {
            background-color: #10b981;
            color: white;
        }

        .btn-edit:hover {
            background-color: #059669;
        }

        .btn-delete {
            background-color: #ef4444;
            color: white;
        }

        .btn-delete:hover {
            background-color: #dc2626;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #6b7280;
        }

        .error-message {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .status-label {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-paid {
            background-color: #d1fae5;
            color: #059669;
        }

        .status-unpaid {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .status-partially-paid {
            background-color: #fef3c7;
            color: #d97706;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 8px;
            padding: 24px;
            width: 90%;
            max-width: 800px;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e5e7eb;
        }

        .modal-header h3 {
            font-size: 20px;
            font-weight: 600;
            color: #111827;
        }

        .close-button {
            background: none;
            border: none;
            font-size: 24px;
            color: #6b7280;
            cursor: pointer;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
        }

        .close-button:hover {
            background-color: #f3f4f6;
            color: #374151;
        }

        .invoice-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .detail-group {
            background-color: #f9fafb;
            padding: 16px;
            border-radius: 6px;
        }

        .detail-group label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 4px;
            display: block;
        }

        .detail-group .value {
            font-size: 16px;
            color: #111827;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: border-color 200ms ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 1px #3b82f6;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .items-table th {
            background-color: #f9fafb;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #f3f4f6;
        }

        .items-table tbody tr:last-child td {
            border-bottom: none;
        }

        .summary-section {
            background-color: #f9fafb;
            padding: 16px;
            border-radius: 6px;
            margin-top: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .summary-row.total {
            font-weight: 700;
            font-size: 18px;
            border-top: 1px solid #d1d5db;
            padding-top: 8px;
            margin-top: 8px;
        }

        .modal-buttons {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }

        .modal-button {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid;
            transition: all 200ms ease;
        }

        .modal-button.cancel {
            background: white;
            color: #374151;
            border-color: #d1d5db;
        }

        .modal-button.cancel:hover {
            background: #f9fafb;
        }

        .modal-button.save {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .modal-button.save:hover {
            background: #2563eb;
        }

        .modal-button.download {
            background: #059669;
            color: white;
            border-color: #059669;
        }

        .modal-button.download:hover {
            background: #047857;
        }

        .modal-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .filter-section {
            display: flex;
            gap: 16px;
            margin-bottom: 20px;
        }

        .filter-section input,
        .filter-section select {
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }

        .filter-section input {
            min-width: 250px;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <div class="page-header">
            <h2>Manage Invoices</h2>
        </div>

        <div id="errorContainer"></div>
        <div id="loadingIndicator" class="loading">Loading invoices...</div>

        <div class="header-content">
            <div class="filter-section">
                <input type="text" id="searchInput" placeholder="Search invoices..." oninput="filterInvoices()">
                <select id="statusFilter" onchange="filterInvoices()">
                    <option value="">All Statuses</option>
                    <option value="paid">Paid</option>
                    <option value="unpaid">Unpaid</option>
                    <option value="partially paid">Partially Paid</option>
                </select>
                <!-- <select id="dateFilter" onchange="filterInvoices()">
                            <option value="">All Dates</option>
                            <option value="today">Due Today</option>
                            <option value="week">Due This Week</option>
                            <option value="month">Due This Month</option>
                        </select> -->
            </div>
            <button class="new-invoice-btn" onclick="createNewInvoice()">+ New Invoice</button>
        </div>

        <div class="invoice-card" id="invoiceCard" style="display: none;">

            <div class="table-container">
                <table class="invoice-table">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="invoiceTableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- View Invoice Modal -->
    <div class="modal-overlay" id="viewModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Invoice Details</h3>
                <button class="close-button" onclick="closeModal('viewModal')">&times;</button>
            </div>

            <div class="invoice-details">
                <div class="detail-group">
                    <label>Invoice Number</label>
                    <div class="value" id="viewInvoiceNumber"></div>
                </div>
                <div class="detail-group">
                    <label>Client</label>
                    <div class="value" id="viewClient"></div>
                </div>
                <div class="detail-group">
                    <label>Invoice Date</label>
                    <div class="value" id="viewInvoiceDate"></div>
                </div>
            </div>

            <table class="items-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Tax %</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody id="viewItemsTable">
                    <!-- Dynamic content -->
                </tbody>
            </table>

            <div class="summary-section">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span id="viewSubtotal"></span>
                </div>
                <div class="summary-row">
                    <span>Tax:</span>
                    <span id="viewTax"></span>
                </div>
                <div class="summary-row total">
                    <span>Total:</span>
                    <span id="viewTotal"></span>
                </div>
            </div>

            <div class="modal-buttons">
                <button type="button" class="modal-button download" onclick="downloadInvoicePDF()">Download PDF</button>
            </div>
        </div>
    </div>

    <!-- Edit Invoice Modal -->
    <div class="modal-overlay" id="editModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Invoice</h3>
                <button class="close-button" onclick="closeModal('editModal')">&times;</button>
            </div>

            <form id="editInvoiceForm">
                <div class="invoice-details">
                    <div class="form-group">
                        <label for="editInvoiceNumber">Invoice Number</label>
                        <input type="text" id="editInvoiceNumber" readonly>
                        <input type="hidden" id="editInvoiceId">
                    </div>
                    <div class="form-group">
                        <label for="editClient">Client</label>
                        <select id="editClient">
                            <!-- Options will be loaded dynamically -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editInvoiceDate">Invoice Date</label>
                        <input type="date" id="editInvoiceDate">
                    </div>
                    <div class="form-group">
                        <label for="editStatus">Status</label>
                        <select id="editStatus" onchange="toggleAmountReceived()">
                            <option value="unpaid">Unpaid</option>
                            <option value="paid">Paid</option>
                            <option value="partially paid">Partially Paid</option>
                        </select>
                    </div>
                    <div class="form-group" id="amountReceivedGroup" style="display: none;">
                        <label for="editAmountReceived">Amount Received</label>
                        <input type="number" id="editAmountReceived" min="0" step="0.01" placeholder="0.00">
                    </div>
                </div>

                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Tax %</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="editItemsTable">
                        <!-- Dynamic content -->
                    </tbody>
                </table>

                <button type="button" class="btn btn-view" onclick="addEditItem()" style="margin-bottom: 20px;">Add Item</button>

                <div class="summary-section">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span id="editSubtotal">$0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Tax:</span>
                        <span id="editTax">$0.00</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total:</span>
                        <span id="editTotal">$0.00</span>
                    </div>
                </div>

                <div class="modal-buttons">
                    <button type="button" class="modal-button cancel" onclick="closeModal('editModal')">Cancel</button>
                    <button type="button" class="modal-button save" onclick="saveInvoiceChanges()" id="saveButton">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Global variables
        let invoicesData = {};
        let allClients = [];
        let currentInvoiceData = null;

        // Utility functions
        // Add these functions near your other utility functions
        function showSuccessMessage(message) {
            const errorContainer = document.getElementById('errorContainer');
            errorContainer.innerHTML = `<div class="success-message" style="background-color: #d1fae5; border: 1px solid #059669; color: #059669; padding: 16px; border-radius: 8px; margin-bottom: 20px;">${message}</div>`;
            setTimeout(() => {
                errorContainer.innerHTML = '';
            }, 3000);
        }

        function showErrorMessage(message) {
            const errorContainer = document.getElementById('errorContainer');
            errorContainer.innerHTML = `<div class="error-message">${message}</div>`;
            setTimeout(() => {
                errorContainer.innerHTML = '';
            }, 3000);
        }

        function showError(message) {
            document.getElementById('errorContainer').innerHTML = `<div class="error-message">${message}</div>`;
        }

        function hideError() {
            document.getElementById('errorContainer').innerHTML = '';
        }

        function formatCurrency(amount) {
            return `$${parseFloat(amount).toFixed(2)}`;
        }

        function formatDate(dateString) {
            const options = {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            };
            return new Date(dateString).toLocaleDateString('en-US', options);
        }

        function getStatusClass(status) {
            status = status.toLowerCase().trim();
            if (status === 'paid') return 'status-paid';
            if (status === 'partially paid') return 'status-partially-paid';
            return 'status-unpaid';
        }

        function toggleAmountReceived() {
            const status = document.getElementById('editStatus').value;
            const amountGroup = document.getElementById('amountReceivedGroup');
            const amountInput = document.getElementById('editAmountReceived');

            // Recalculate totals first to ensure we have the latest total
            calculateEditTotals();

            if (status === 'paid') {
                amountGroup.style.display = 'block';
                const total = parseFloat(document.getElementById('editTotal').textContent.replace('$', '')) || 0;
                amountInput.value = total.toFixed(2);
                amountInput.readOnly = true;
            } else if (status === 'partially paid') {
                amountGroup.style.display = 'block';
                amountInput.readOnly = false;
                if (!amountInput.value || amountInput.value === '0.00') {
                    amountInput.value = '';
                }
            } else {
                amountGroup.style.display = 'none';
                amountInput.value = '0.00'; // Changed from '' to '0.00'
                amountInput.readOnly = false;
            }
        }
        // API calls
        async function loadInvoices() {
            try {
                const response = await fetch('api/invoices/get_invoices.php', {
                    cache: 'no-store'
                });
                const data = await response.json();

                if (data.success && data.invoices) {
                    invoicesData = data.invoices.reduce((acc, inv) => {
                        acc[inv.invoice_number] = {
                            ...inv,
                            id: inv.id,
                            status: inv.status || 'unpaid' // Ensure status always has a value
                        };
                        return acc;
                    }, {});

                    const tbody = document.getElementById('invoiceTableBody');
                    if (tbody) {
                        tbody.innerHTML = data.invoices.map(inv => {
                            const status = inv.status || 'unpaid'; // Default to unpaid if missing
                            return `
                        <tr>
                            <td>${inv.invoice_number}</td>
                            <td>${inv.client?.company_name || ''}</td>
                            <td>${formatDate(inv.invoice_date)}</td>
                            <td><span class="status-label ${getStatusClass(status)}">${status.toUpperCase()}</span></td>
                            <td>${formatCurrency(inv.grand_total)}</td>
                            <td>
                                <div class="action-buttons">
                                    <button onclick="viewInvoice('${inv.invoice_number}')" class="btn btn-view">View</button>
                                    <button onclick="editInvoice('${inv.invoice_number}')" class="btn btn-edit">Edit</button>
                                    <button onclick="deleteInvoice('${inv.invoice_number}')" class="btn btn-delete">Delete</button>
                                </div>
                            </td>
                        </tr>
                    `;
                        }).join('');
                    }

                    if (document.getElementById('loadingIndicator')) {
                        document.getElementById('loadingIndicator').style.display = 'none';
                    }
                    if (document.getElementById('invoiceCard')) {
                        document.getElementById('invoiceCard').style.display = 'block';
                    }
                } else {
                    showError('Failed to load invoices');
                }
            } catch (error) {
                console.error('Error loading invoices:', error);
                showError('Failed to load invoices');
            }
        }
        async function loadClients() {
            try {
                const response = await fetch('api/clients/get_clients.php');
                const data = await response.json();
                if (Array.isArray(data)) {
                    allClients = data;
                    populateClientSelect('editClient'); // Fix: use correct ID
                } else {
                    showError('Failed to load clients');
                }
            } catch (error) {
                console.error('Error loading clients:', error);
                showError('Failed to load clients');
            }
        }

        function populateClientSelect(selectId = 'editClient') { // Fix: change default ID
            const select = document.getElementById(selectId);
            if (!select) return;

            select.innerHTML = '<option value="">Select Client...</option>' +
                allClients.map(client =>
                    `<option value="${client.id}">${client.company_name}</option>`
                ).join('');
        }
        // Modal functions
        function viewInvoice(invoiceNumber) {
            const invoice = invoicesData[invoiceNumber];
            if (!invoice) return;

            currentInvoiceData = invoice;

            document.getElementById('viewInvoiceNumber').textContent = invoice.invoice_number;
            document.getElementById('viewClient').textContent = invoice.client.company_name;
            document.getElementById('viewInvoiceDate').textContent = formatDate(invoice.invoice_date);
            // document.getElementById('viewDueDate').textContent = formatDate(invoice.due_date);
            document.getElementById('viewSubtotal').textContent = formatCurrency(invoice.subtotal);
            document.getElementById('viewTax').textContent = formatCurrency(invoice.total_tax);
            document.getElementById('viewTotal').textContent = formatCurrency(invoice.grand_total);

            const itemsTable = document.getElementById('viewItemsTable');
            itemsTable.innerHTML = '';
            invoice.items.forEach(item => {
                const row = itemsTable.insertRow();
                row.innerHTML = `
                        <td>${item.description}</td>
                        <td>${item.quantity}</td>
                        <td>${formatCurrency(item.unit_price)}</td>
                        <td>${item.tax_rate}%</td>
                        <td>${formatCurrency(item.line_total)}</td>
                    `;
            });

            document.getElementById('viewModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function editInvoice(invoiceNumber) {
            const invoice = invoicesData[invoiceNumber];
            if (!invoice) return;

            document.getElementById('editInvoiceId').value = invoice.id;
            document.getElementById('editInvoiceNumber').value = invoice.invoice_number;
            document.getElementById('editInvoiceDate').value = invoice.invoice_date;
            // document.getElementById('editDueDate').value = invoice.due_date;
            document.getElementById('editStatus').value = invoice.status || 'unpaid';
            document.getElementById('editAmountReceived').value = invoice.amount_received || 0;

            toggleAmountReceived();

            const client = allClients.find(c => c.company_name === invoice.client.company_name);
            if (client) {
                document.getElementById('editClient').value = client.id;
            }

            const itemsTable = document.getElementById('editItemsTable');
            itemsTable.innerHTML = '';
            invoice.items.forEach(item => {
                const row = itemsTable.insertRow();
                row.innerHTML = `
                        <td><input type="text" value="${item.description}" style="border: none; width: 100%;" oninput="calculateEditTotals()"></td>
                        <td><input type="number" value="${item.quantity}" min="0" step="0.01" style="border: none; width: 60px;" oninput="calculateEditTotals()"></td>
                        <td><input type="number" value="${item.unit_price}" min="0" step="0.01" style="border: none; width: 80px;" oninput="calculateEditTotals()"></td>
                        <td><input type="number" value="${item.tax_rate}" min="0" max="100" step="0.01" style="border: none; width: 60px;" oninput="calculateEditTotals()"></td>
                        <td class="calculated-amount">${formatCurrency(item.line_total)}</td>
                        <td><button type="button" class="btn btn-edit" onclick="removeEditItem(this)">Remove</button></td>
                    `;
            });

            calculateEditTotals();
            document.getElementById('editModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function addEditItem() {
            const table = document.getElementById('editItemsTable');
            const newRow = table.insertRow();
            newRow.innerHTML = `
                    <td><input type="text" placeholder="Description" style="border: none; width: 100%;" oninput="calculateEditTotals()"></td>
                    <td><input type="number" value="1" min="0" step="0.01" style="border: none; width: 60px;" oninput="calculateEditTotals()"></td>
                    <td><input type="number" value="0" min="0" step="0.01" style="border: none; width: 80px;" oninput="calculateEditTotals()"></td>
                    <td><input type="number" value="0" min="0" max="100" step="0.01" style="border: none; width: 60px;" oninput="calculateEditTotals()"></td>
                    <td class="calculated-amount">$0.00</td>
                    <td><button type="button" class="btn btn-edit" onclick="removeEditItem(this)">Remove</button></td>
                `;
            calculateEditTotals();
        }

        function removeEditItem(button) {
            const row = button.closest('tr');
            const table = document.getElementById('editItemsTable');
            if (table.rows.length > 1) {
                row.remove();
                calculateEditTotals();
            }
        }

        function calculateEditTotals() {
            const rows = document.querySelectorAll('#editItemsTable tr');
            let subtotal = 0;
            let totalTax = 0;

            rows.forEach(row => {
                const inputs = row.querySelectorAll('input');
                if (inputs.length >= 4) {
                    const quantity = parseFloat(inputs[1].value) || 0;
                    const unitPrice = parseFloat(inputs[2].value) || 0;
                    const taxRate = parseFloat(inputs[3].value) || 0;

                    const lineSubtotal = quantity * unitPrice;
                    const lineTax = lineSubtotal * (taxRate / 100);
                    const lineTotal = lineSubtotal + lineTax;

                    subtotal += lineSubtotal;
                    totalTax += lineTax;

                    const amountCell = row.querySelector('.calculated-amount');
                    if (amountCell) {
                        amountCell.textContent = formatCurrency(lineTotal);
                    }
                }
            });

            document.getElementById('editSubtotal').textContent = formatCurrency(subtotal);
            document.getElementById('editTax').textContent = formatCurrency(totalTax);
            document.getElementById('editTotal').textContent = formatCurrency(subtotal + totalTax);

            // Update amount received if status is paid
            const status = document.getElementById('editStatus').value;
            if (status === 'paid') {
                document.getElementById('editAmountReceived').value = (subtotal + totalTax).toFixed(2);
            }
        }

        async function saveInvoiceChanges() {
            hideError();
            const id = document.getElementById('editInvoiceId').value;
            const invoice_number = document.getElementById('editInvoiceNumber').value;
            const client_id = document.getElementById('editClient').value;
            const invoice_date = document.getElementById('editInvoiceDate').value;
            const status = document.getElementById('editStatus').value;

            const grand_total_text = document.getElementById('editTotal').textContent;
            const grand_total = parseFloat(grand_total_text.replace('$', ''));

            const amount_received_input = document.getElementById('editAmountReceived');
            let amount_received = parseFloat(amount_received_input.value) || 0;

            // --- Validation ---
            if (!client_id) {
                showError('Please select a client.');
                return;
            }
            if (!invoice_date) {
                showError('Please select an invoice date.');
                return;
            }

            // CRITICAL FIX: Ensure amount_received is set correctly for paid status
            if (status === 'paid') {
                amount_received = grand_total;
            } else if (status === 'unpaid') {
                amount_received = 0; // Explicitly set to 0 for unpaid
            }

            if (status === 'partially paid' && (amount_received <= 0 || amount_received >= grand_total)) {
                showError('For "Partially Paid" status, Amount Received must be greater than 0 and less than the total invoice amount.');
                return;
            }

            // Debug logging - remove after testing
            console.log('Saving invoice with status:', status);
            console.log('Amount received:', amount_received);
            console.log('Grand total:', grand_total);

            // Item validation and collection
            const items = [];
            const rows = document.querySelectorAll('#editItemsTable tr');
            let hasValidItem = false;

            rows.forEach(row => {
                const inputs = row.querySelectorAll('input');
                const description = inputs[0].value.trim();
                const quantity = parseFloat(inputs[1].value) || 0;
                const unit_price = parseFloat(inputs[2].value) || 0;
                const tax_rate = parseFloat(inputs[3].value) || 0;

                if (description && (quantity > 0 || unit_price > 0)) {
                    const line_total_no_tax = quantity * unit_price;
                    const line_tax = line_total_no_tax * (tax_rate / 100);
                    const line_total = line_total_no_tax + line_tax;

                    items.push({
                        description,
                        quantity: quantity.toFixed(2),
                        unit_price: unit_price.toFixed(2),
                        tax_rate: tax_rate.toFixed(2),
                        line_total: line_total.toFixed(2)
                    });
                    hasValidItem = true;
                }
            });

            if (!hasValidItem) {
                showError('Invoice must contain at least one item with a description and a non-zero quantity or unit price.');
                return;
            }

            const subtotal_text = document.getElementById('editSubtotal').textContent;
            const total_tax_text = document.getElementById('editTax').textContent;

            const invoiceData = {
                id,
                invoice_number,
                client_id,
                invoice_date,
                status,
                amount_received: amount_received.toFixed(2),
                grand_total: grand_total.toFixed(2),
                subtotal: parseFloat(subtotal_text.replace('$', '')).toFixed(2),
                total_tax: parseFloat(total_tax_text.replace('$', '')).toFixed(2),
                discount: "0.00", // ADD THIS LINE - your API needs it!
                items
            };

            // --- API Call ---
            try {
                document.getElementById('saveButton').disabled = true;
                const response = await fetch('api/invoices/update_invoice.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(invoiceData)
                });

                const data = await response.json();
                if (data.success) {
                    showSuccessMessage('Invoice updated successfully! 🎉');
                    closeModal('editModal');
                    await loadInvoices();
                } else {
                    showError(data.message || 'Failed to update invoice.');
                }
            } catch (error) {
                console.error('Error updating invoice:', error);
                showError('An unexpected error occurred while saving the invoice.');
            } finally {
                document.getElementById('saveButton').disabled = false;
            }
        }


        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function createNewInvoice() {
            window.location.href = 'invoice.php';
        }
        let companyInfo = null;

        // Load company info - FIXED VERSION
        async function loadCompanyInfo() {
            try {
                const response = await fetch('api/profile/get_profile.php');
                const data = await response.json();
                if (data.success && data.profile) {
                    companyInfo = data.profile; // Store it in the global variable!
                    return data.profile;
                } else {
                    console.error('Failed to load company info from server');
                    return null;
                }
            } catch (error) {
                console.error('Error loading company info:', error);
                return null;
            }
        }


        async function downloadInvoicePDF() {
            if (!currentInvoiceData) {
                alert('No invoice data available');
                return;
            }

            // Load company info if not already loaded
            if (!companyInfo) {
                await loadCompanyInfo();
            }

            const invoice = currentInvoiceData;

            // Use actual company data from database or fallback
            const company = companyInfo || {
                company_name: 'Your Company Name',
                address: '123 Business Street',
                city: 'City',
                state: 'State',
                zip_code: '12345',
                phone: '(123) 456-7890',
                email: 'info@yourcompany.com'
            };

            const client = invoice.client || {};

            // Create a beautiful invoice HTML
            const invoiceHTML = `
        <div style="font-family: 'Arial', sans-serif; max-width: 800px; margin: 0 auto; padding: 40px; color: #333;">
            <!-- Border wrapper -->
            <div style="border: 4px solid; border-image: linear-gradient(135deg, #667eea 0%, #764ba2 100%) 1; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
            
            <!-- Header with gradient -->
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; color: white;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h1 style="margin: 0; font-size: 36px; font-weight: 700;">INVOICE</h1>
                        <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">#${invoice.invoice_number}</p>
                    </div>
                </div>
            </div>

            <!-- Company & Client Info -->
            <div style="background: #f8f9fa; padding: 30px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                    <!-- From Section -->
                    <div>
                        <p style="margin: 0 0 8px 0; font-size: 11px; color: #6b7280; text-transform: uppercase; font-weight: 600; letter-spacing: 1px;">From</p>
                        <h3 style="margin: 0 0 8px 0; font-size: 18px; color: #111827; font-weight: 700;">${company.company_name || 'Your Company'}</h3>
                        <p style="margin: 0; font-size: 13px; color: #4b5563; line-height: 1.6;">
                            ${company.address || ''}<br>
                            ${company.city ? company.city + ', ' : ''}${company.state || ''} ${company.zip_code || ''}<br>
                            ${company.phone ? 'Phone: ' + company.phone : ''}<br>
                            ${company.email ? 'Email: ' + company.email : ''}
                        </p>
                    </div>

                    <!-- Bill To Section -->
                    <div style="text-align: right;">
                        <p style="margin: 0 0 8px 0; font-size: 11px; color: #6b7280; text-transform: uppercase; font-weight: 600; letter-spacing: 1px;">Bill To</p>
                        <h3 style="margin: 0 0 8px 0; font-size: 18px; color: #111827; font-weight: 700;">${client.company_name || 'N/A'}</h3>
                        <p style="margin: 0; font-size: 13px; color: #4b5563; line-height: 1.6;">
                            ${client.address || ''}<br>
                            ${client.city ? client.city + ', ' : ''}${client.state || ''} ${client.zip_code || ''}<br>
                            ${client.email || ''}<br>
                            ${client.phone || ''}
                        </p>
                    </div>
                </div>

                <!-- Invoice Details -->
                <div style="margin-top: 25px; padding-top: 25px; border-top: 2px solid #e5e7eb;">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                        <div>
                            <p style="margin: 0; font-size: 11px; color: #6b7280; text-transform: uppercase; font-weight: 600;">Invoice Date</p>
                            <p style="margin: 4px 0 0 0; font-size: 14px; color: #111827; font-weight: 600;">${formatDate(invoice.invoice_date)}</p>
                        </div>
                        <div style="text-align: right;">
                            <p style="margin: 0; font-size: 11px; color: #6b7280; text-transform: uppercase; font-weight: 600;">Amount Due</p>
                            <p style="margin: 4px 0 0 0; font-size: 20px; color: #667eea; font-weight: 700;">${formatCurrency(invoice.grand_total)}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div style="margin-top: 30px;">
                <table style="width: 100%; border-collapse: collapse; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <th style="padding: 15px; text-align: left; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Description</th>
                            <th style="padding: 15px; text-align: center; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; width: 80px;">Qty</th>
                            <th style="padding: 15px; text-align: right; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; width: 100px;">Unit Price</th>
                            <th style="padding: 15px; text-align: right; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; width: 80px;">Tax</th>
                            <th style="padding: 15px; text-align: right; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; width: 120px;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${invoice.items.map((item, index) => `
                            <tr style="border-bottom: 1px solid #e5e7eb; background: ${index % 2 === 0 ? '#ffffff' : '#f9fafb'};">
                                <td style="padding: 15px; font-size: 14px; color: #374151;">${item.description}</td>
                                <td style="padding: 15px; text-align: center; font-size: 14px; color: #374151; font-weight: 500;">${item.quantity}</td>
                                <td style="padding: 15px; text-align: right; font-size: 14px; color: #374151;">${formatCurrency(item.unit_price)}</td>
                                <td style="padding: 15px; text-align: right; font-size: 14px; color: #374151;">${item.tax_rate}%</td>
                                <td style="padding: 15px; text-align: right; font-size: 14px; color: #111827; font-weight: 600;">${formatCurrency(item.line_total)}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>

            <!-- Summary Section -->
            <div style="margin-top: 30px; display: flex; justify-content: flex-end;">
                <div style="width: 350px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 25px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #d1d5db;">
                        <span style="font-size: 14px; color: #6b7280; font-weight: 500;">Subtotal:</span>
                        <span style="font-size: 14px; color: #374151; font-weight: 600;">${formatCurrency(invoice.subtotal)}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #d1d5db;">
                        <span style="font-size: 14px; color: #6b7280; font-weight: 500;">Tax:</span>
                        <span style="font-size: 14px; color: #374151; font-weight: 600;">${formatCurrency(invoice.total_tax)}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding-top: 8px;">
                        <span style="font-size: 18px; color: #111827; font-weight: 700;">Total:</span>
                        <span style="font-size: 22px; color: #667eea; font-weight: 700;">${formatCurrency(invoice.grand_total)}</span>
                    </div>
                    ${invoice.status !== 'unpaid' && parseFloat(invoice.amount_received || 0) > 0 ? `
                        <div style="margin-top: 15px; padding-top: 15px; border-top: 2px solid #667eea;">
                            <div style="display: flex; justify-content: space-between;">
                                <span style="font-size: 14px; color: #059669; font-weight: 600;">Amount Paid:</span>
                                <span style="font-size: 14px; color: #059669; font-weight: 700;">${formatCurrency(invoice.amount_received)}</span>
                            </div>
                            ${parseFloat(invoice.grand_total) - parseFloat(invoice.amount_received) > 0 ? `
                                <div style="display: flex; justify-content: space-between; margin-top: 8px;">
                                    <span style="font-size: 14px; color: #dc2626; font-weight: 600;">Balance Due:</span>
                                    <span style="font-size: 14px; color: #dc2626; font-weight: 700;">${formatCurrency(parseFloat(invoice.grand_total) - parseFloat(invoice.amount_received))}</span>
                                </div>
                            ` : ''}
                        </div>
                    ` : ''}
                </div>
            </div>

            <!-- Footer -->
            <div style="margin-top: 40px; padding-top: 25px; border-top: 2px solid #e5e7eb; text-align: center;">
                <p style="margin: 0 0 8px 0; font-size: 13px; color: #6b7280; font-weight: 500;">Thank you for your business!</p>
                <p style="margin: 0; font-size: 11px; color: #9ca3af;">
                    Please make payment within 30 days. For any questions, contact us at ${company.email || 'info@yourcompany.com'}
                </p>
            </div>

            <!-- Decorative Bottom Bar -->
            <div style="margin-top: 25px; height: 8px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
            
            </div><!-- End border wrapper -->
        </div>
    `;

            // Create a temporary element
            const element = document.createElement('div');
            element.innerHTML = invoiceHTML;
            document.body.appendChild(element);

            // PDF options for professional output
            const opt = {
                margin: [10, 10, 10, 10],
                filename: `Invoice_${invoice.invoice_number}.pdf`,
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2,
                    useCORS: true,
                    letterRendering: true,
                    scrollY: 0,
                    scrollX: 0
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'portrait',
                    compress: true
                }
            };

            // Generate PDF
            html2pdf().set(opt).from(element).save().then(() => {
                document.body.removeChild(element);
            }).catch(error => {
                console.error('PDF generation error:', error);
                document.body.removeChild(element);
                alert('Failed to generate PDF. Please try again.');
            });
        }


        // Helper function for date formatting (if not already defined)
        function formatDate(dateString) {
            const options = {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            };
            return new Date(dateString).toLocaleDateString('en-US', options);
        }

        // Helper function for currency formatting (if not already defined)
        function formatCurrency(amount) {
            return `$${parseFloat(amount).toFixed(2)}`;
        }

        // Helper function to get status color for PDF
        function getStatusColor(status) {
            status = status.toLowerCase().trim();
            if (status === 'paid') return '#059669';
            if (status === 'partially paid') return '#d97706';
            return '#dc2626';
        }

        function deleteInvoice(invoiceNumber) {
            const invoice = invoicesData[invoiceNumber];
            if (!invoice || !invoice.id) {
                alert('Invoice ID not found');
                return;
            }

            if (confirm(`Are you sure you want to delete invoice #${invoiceNumber}?`)) {
                fetch('api/invoices/update_invoice.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            delete: true,
                            id: invoice.id
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            delete invoicesData[invoiceNumber];
                            loadInvoices();
                            alert('Invoice deleted successfully');
                        } else {
                            alert('Failed to delete invoice: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to delete invoice');
                    });
            }
        }

        // Filter and search functionality
        function filterInvoices() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
            const rows = document.querySelectorAll('#invoiceTableBody tr');

            rows.forEach(row => {
                // Get individual cell values for more accurate searching
                const invoiceNum = row.children[0].textContent.toLowerCase();
                const clientName = row.children[1].textContent.toLowerCase();
                const date = row.children[2].textContent.toLowerCase();
                // const dueDate = row.children[3].textContent.toLowerCase();
                const statusElement = row.querySelector('.status-label');
                const status = statusElement ? statusElement.textContent.trim().toLowerCase() : '';
                const amount = row.children[5].textContent.toLowerCase();

                // Combine searchable text, excluding the status which we'll handle separately
                const searchableText = `${invoiceNum} ${clientName} ${amount}`.toLowerCase();

                // Check if search term matches any field
                const matchesSearch = searchTerm === '' || searchableText.includes(searchTerm);

                // Check if status matches filter
                const matchesStatus = statusFilter === '' || status === statusFilter;

                // Show/hide row based on both conditions
                row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
            });
        }

        // Event listeners
        document.addEventListener('click', function(event) {
            const modals = ['viewModal', 'editModal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (event.target === modal) {
                    closeModal(modalId);
                }
            });
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const activeModals = document.querySelectorAll('.modal-overlay.active');
                activeModals.forEach(modal => {
                    closeModal(modal.id);
                });
            }
        });

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            loadInvoices();
            loadClients();
        });
    </script>
</body>

</html>