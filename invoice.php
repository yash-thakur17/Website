<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Karla" rel="stylesheet">
    <link rel="stylesheet" href="base_styling.css">
    <title>Invoice Application</title>
    <style>
        /* Only keep unique or page-specific styles here */
        .invoice-card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            padding: 32px;
            max-width: 960px;
            margin-left: auto;
            margin-right: auto;
        }

        .invoice-card h2 {
            font-size: 30px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 32px;
        }

        .invoice-details-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }

        @media (min-width: 768px) {
            .invoice-details-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .invoice-details-grid {
                grid-template-columns: repeat(3, 1fr);
            }
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
            margin-top: 4px;
            display: block;
            width: 100%;
            padding: 8px 12px;
            font-size: 16px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            outline: none;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 1px #3b82f6;
        }

        .table-container {
            overflow-x: auto;
            margin-bottom: 32px;
        }

        .invoice-table {
            min-width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            background-color: #ffffff;
        }

        .invoice-table thead {
            background-color: #f9fafb;
        }

        .invoice-table th {
            padding: 12px 16px;
            text-align: left;
            font-size: 12px;
            font-weight: 500;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #e5e7eb;
        }

        .invoice-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }

        .invoice-table td {
            padding: 16px;
            white-space: nowrap;
            color: #111827;
        }

        .invoice-table td input {
            width: 100%;
            border: none;
            outline: none;
            padding: 4px;
            background: transparent;
            font-size: 14px;
        }

        .invoice-table td input:focus {
            box-shadow: none;
            background-color: #f9fafb;
            border-radius: 4px;
        }

        .quantity-cell {
            width: 80px;
        }

        .unit-cell {
            width: 100px;
        }

        .amount-cell {
            font-weight: 600;
            color: #059669;
        }

        .remove-button {
            background-color: #dc2626;
            color: white;
            border: none;
            padding: 4px 8px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        .remove-button:hover {
            background-color: #b91c1c;
        }

        .add-item-button {
            background-color: #e5e7eb;
            color: #374151;
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: background-color 200ms ease-in-out;
            margin-bottom: 32px;
        }

        .add-item-button:hover {
            background-color: #d1d5db;
        }

        .summary-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 32px;
        }

        .summary-card {
            width: 100%;
            max-width: 320px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .summary-item span:first-child {
            color: #374151;
            font-weight: 500;
        }

        .summary-item span:last-child {
            color: #111827;
            font-weight: 600;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #d1d5db;
            padding-top: 16px;
            margin-top: 16px;
        }

        .summary-total span {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }

        .create-invoice-button-container {
            display: flex;
            justify-content: flex-end;
        }

        .create-invoice-button {
            background-color: #2563eb;
            color: #ffffff;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 18px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background-color 200ms ease-in-out;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .create-invoice-button:hover {
            background-color: #1d4ed8;
        }

        .create-invoice-button:disabled {
            background-color: #9ca3af;
            cursor: not-allowed;
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
            max-width: 500px;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
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

        .modal-form {
            display: grid;
            gap: 16px;
        }

        .modal-form .form-group label {
            font-weight: 500;
            color: #374151;
        }

        .modal-form .form-group input,
        .modal-form .form-group textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: border-color 200ms ease;
        }

        .modal-form .form-group input:focus,
        .modal-form .form-group textarea:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 1px #3b82f6;
        }

        .modal-form .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .required {
            color: #dc2626;
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

        .modal-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .success-message {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #10b981;
            color: white;
            padding: 12px 16px;
            border-radius: 6px;
            font-size: 14px;
            z-index: 1001;
            transform: translateX(100%);
            transition: transform 300ms ease;
        }

        .success-message.show {
            transform: translateX(0);
        }

        .error-message {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #dc2626;
            color: white;
            padding: 12px 16px;
            border-radius: 6px;
            font-size: 14px;
            z-index: 1001;
            transform: translateX(100%);
            transition: transform 300ms ease;
        }

        .error-message.show {
            transform: translateX(0);
        }

        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <div class="invoice-card">
            <h2>New Invoice</h2>

            <!-- Invoice Details Form -->
            <div class="invoice-details-grid">
                <div class="form-group">
                    <label for="client">Client</label>
                    <select id="client" onchange="handleClientSelection(this)">
                        <option value="">Loading clients...</option>
                    </select>
                </div> 
                <div class="form-group">
                    <label for="invoice-date">Invoice Date</label>
                    <input type="date" id="invoice-date">
                </div>
                <div class="form-group">
                    <label for="invoice-number">Invoice #</label>
                    <input type="text" id="invoice-number" readonly>
                </div>
            </div>

            <!-- Invoice Items Table -->
            <div class="table-container">
                <table class="invoice-table">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Unit Price</th>
                            <th>Tax (%)</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="invoice-items">
                        <!-- Rows will be added dynamically -->
                    </tbody>
                </table>
            </div>

            <button class="add-item-button" onclick="addNewRow()">Add Item</button>

            <!-- Summary Section -->
            <div class="summary-section">
                <div class="summary-card">
                    <div class="summary-item">
                        <span>Subtotal</span>
                        <span id="subtotal">$0.00</span>
                    </div>
                    <div class="summary-item">
                        <span>Total Tax</span>
                        <span id="total-tax">$0.00</span>
                    </div>
                    <div class="summary-item">
                        <span>Discount</span>
                        <span id="discount">$0.00</span>
                    </div>
                    <div class="summary-total">
                        <span>Total</span>
                        <span id="grand-total">$0.00</span>
                    </div>
                </div>
            </div>

            <div class="create-invoice-button-container">
                <button class="create-invoice-button" onclick="createInvoice()" id="createInvoiceBtn">Create Invoice</button>
            </div>
        </div>
    </main>

    <!-- Add Client Modal -->
    <div class="modal-overlay" id="clientModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Client</h3>
                <button class="close-button" onclick="closeClientModal()">&times;</button>
            </div>
            <form class="modal-form" id="clientForm">
                <div class="form-group">
                    <label for="company_name">Company Name <span class="required">*</span></label>
                    <input type="text" id="company_name" required>
                </div>
                <div class="form-group">
                    <label for="contact_person">Contact Person</label>
                    <input type="text" id="contact_person">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email">
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone">
                </div>
                <div class="form-group">
                    <label for="gst_number">GST Number</label>
                    <input type="text" id="gst_number" placeholder="e.g., 29ABCDE1234F1Z5" maxlength="15">
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" placeholder="Full address..."></textarea>
                </div>
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea id="notes" placeholder="Additional notes about this client..."></textarea>
                </div>
            </form>
            <div class="modal-buttons">
                <button type="button" class="modal-button cancel" onclick="closeClientModal()">Cancel</button>
                <button type="button" class="modal-button save" onclick="saveNewClient()" id="saveClientBtn">Save Client</button>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    <div class="success-message" id="successMessage">
        ✓ Success!
    </div>

    <!-- Error Message -->
    <div class="error-message" id="errorMessage">
        ✗ Error occurred!
    </div>

    <script>
        // API Configuration
        const API_BASE_URL = '.'; // Current directory (same folder as invoice.php)

        // Global variables
        let clients = [];

        // Utility functions
        function showMessage(message, isSuccess = true) {
            const messageEl = document.getElementById(isSuccess ? 'successMessage' : 'errorMessage');
            messageEl.textContent = (isSuccess ? '✓ ' : '✗ ') + message;
            messageEl.classList.add('show');

            setTimeout(() => {
                messageEl.classList.remove('show');
            }, 3000);
        }

        function setLoading(element, loading = true) {
            if (loading) {
                element.classList.add('loading');
                element.disabled = true;
            } else {
                element.classList.remove('loading');
                element.disabled = false;
            }
        }

        // API calls
        async function fetchClients() {
            const response = await fetch('api/clients/get_clients.php');
            const data = await response.json();

            if (response.ok) {
                clients = data;
                populateClientDropdown();
                // console.log('Clients loaded:', clients);
            } else {
                throw new Error(data.error || 'Failed to fetch clients');
            }
        }

        async function createClient(clientData) {
            const response = await fetch('api/clients/save_clients.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(clientData)
            });

            const data = await response.json();

            if (response.ok && data.success) {
                return data;
            } else {
                throw new Error(data.message || 'Failed to create client');
            }
        }

        async function fetchNextInvoiceNumber() {
            const response = await fetch('api/invoices/get_next_invoice.php');
            const data = await response.json();

            if (response.ok) {
                document.getElementById('invoice-number').value = data.invoice_number;
            } else {
                throw new Error(data.error || 'Failed to fetch invoice number');
            }
        }

        async function saveInvoice(invoiceData) {
            const response = await fetch('api/invoices/save_invoice.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(invoiceData)
            });

            const data = await response.json();

            if (response.ok && data.success) {
                return data;
            } else {
                throw new Error(data.message || 'Failed to create invoice');
            }
        }

        // Client dropdown functions
        function populateClientDropdown() {
            const clientSelect = document.getElementById('client');
            clientSelect.innerHTML = '<option value="add-new">➕ Add New Client...</option>';

            clients.forEach(client => {
                const option = document.createElement('option');
                option.value = client.id;
                option.textContent = client.company_name;
                clientSelect.appendChild(option);
            });

            // Select first real client if available
            if (clients.length > 0) {
                clientSelect.value = clients[0].id;
            }
        }

        function handleClientSelection(select) {
            if (select.value === 'add-new') {
                openClientModal();
                // Reset to previous value or first client
                if (clients.length > 0) {
                    select.value = clients[0].id;
                }
            }
        }

        // Modal functions
        function openClientModal() {
            document.getElementById('clientModal').classList.add('active');
            document.getElementById('company_name').focus();
            document.body.style.overflow = 'hidden';
        }

        function closeClientModal() {
            document.getElementById('clientModal').classList.remove('active');
            document.body.style.overflow = 'auto';
            document.getElementById('clientForm').reset();
        }

        async function saveNewClient() {
            const saveBtn = document.getElementById('saveClientBtn');
            setLoading(saveBtn);

            try {
                const clientData = {
                    company_name: document.getElementById('company_name').value.trim(),
                    contact_person: document.getElementById('contact_person').value.trim(),
                    email: document.getElementById('email').value.trim(),
                    phone: document.getElementById('phone').value.trim(),
                    gst_number: document.getElementById('gst_number').value.trim(),
                    address: document.getElementById('address').value.trim(),
                    notes: document.getElementById('notes').value.trim()
                };

                // Client-side validation
                if (!clientData.company_name) {
                    showMessage('Company Name is required', false);
                    document.getElementById('company_name').focus();
                    return;
                }

                if (clientData.email && !isValidEmail(clientData.email)) {
                    showMessage('Please enter a valid email address', false);
                    document.getElementById('email').focus();
                    return;
                }

                if (clientData.gst_number && !isValidGSTNumber(clientData.gst_number)) {
                    showMessage('Please enter a valid GST number', false);
                    document.getElementById('gst_number').focus();
                    return;
                }

                const result = await createClient(clientData);

                // Add new client to local array
                const newClient = {
                    id: result.id,
                    company_name: clientData.company_name,
                    ...clientData
                };
                clients.push(newClient);

                // Update dropdown and select new client
                populateClientDropdown();
                document.getElementById('client').value = result.id;

                closeClientModal();
                showMessage('Client added successfully!');

            } catch (error) {
                showMessage(error.message, false);
            } finally {
                setLoading(saveBtn, false);
            }
        }

        // Validation functions
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function isValidGSTNumber(gst_number) {
            const gstRegex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}[Z]{1}[0-9A-Z]{1}$/;
            return gstRegex.test(gst_number);
        }

        // Invoice calculation functions
        function calculateRow(input) {
            const row = input.closest('tr');
            const quantity = parseFloat(row.children[1].querySelector('input').value) || 0;
            const unitPrice = parseFloat(row.children[3].querySelector('input').value) || 0;
            const taxRate = parseFloat(row.children[4].querySelector('input').value) || 0;

            const subtotal = quantity * unitPrice;
            const taxAmount = subtotal * (taxRate / 100);
            const total = subtotal + taxAmount;

            row.children[5].textContent = `${total.toFixed(2)}`;
            updateSummary();
        }

        function updateSummary() {
            const rows = document.querySelectorAll('#invoice-items tr');
            let subtotal = 0;
            let totalTax = 0;

            rows.forEach(row => {
                const quantity = parseFloat(row.children[1].querySelector('input').value) || 0;
                const unitPrice = parseFloat(row.children[3].querySelector('input').value) || 0;
                const taxRate = parseFloat(row.children[4].querySelector('input').value) || 0;

                const rowSubtotal = quantity * unitPrice;
                const rowTax = rowSubtotal * (taxRate / 100);

                subtotal += rowSubtotal;
                totalTax += rowTax;
            });

            const discount = 0;
            const grandTotal = subtotal + totalTax - discount;

            document.getElementById('subtotal').textContent = `${subtotal.toFixed(2)}`;
            document.getElementById('total-tax').textContent = `${totalTax.toFixed(2)}`;
            document.getElementById('discount').textContent = `${discount.toFixed(2)}`;
            document.getElementById('grand-total').textContent = `${grandTotal.toFixed(2)}`;
        }

        function addNewRow() {
            const tbody = document.getElementById('invoice-items');
            const newRow = document.createElement('tr');

            newRow.innerHTML = `
                <td><input type="text" placeholder="Description" oninput="calculateRow(this)"></td>
                <td class="quantity-cell"><input type="number" value="1" min="0" step="0.01" oninput="calculateRow(this)"></td>
                <td class="unit-cell"><input type="text" placeholder="unit" oninput="calculateRow(this)"></td>
                <td><input type="number" value="0.00" min="0" step="0.01" oninput="calculateRow(this)"></td>
                <td><input type="number" value="0" min="0" max="100" step="0.01" oninput="calculateRow(this)"></td>
                <td class="amount-cell">$0.00</td>
                <td><button class="remove-button" onclick="removeRow(this)">Remove</button></td>
            `;

            tbody.appendChild(newRow);
            newRow.querySelector('input').focus();
            updateSummary();
        }

        function removeRow(button) {
            const row = button.closest('tr');
            const tbody = document.getElementById('invoice-items');

            if (tbody.children.length > 1) {
                row.remove();
                updateSummary();
            } else {
                showMessage('Cannot remove the last item. At least one item is required.', false);
            }
        }

        // Invoice creation
        async function createInvoice() {
            const createBtn = document.getElementById('createInvoiceBtn');
            setLoading(createBtn);

            try {
                const clientId = document.getElementById('client').value;
                const invoiceDate = document.getElementById('invoice-date').value;
                const invoiceNumber = document.getElementById('invoice-number').value;

                // Validation
                if (!clientId || clientId === 'add-new') {
                    showMessage('Please select a client', false);
                    return;
                }

                if (!invoiceDate || !invoiceNumber) {
                    showMessage('Please fill in all required fields', false);
                    return;
                }

                // Collect items
                const items = [];
                const rows = document.querySelectorAll('#invoice-items tr');

                rows.forEach(row => {
                    const description = row.children[0].querySelector('input').value.trim();
                    const quantity = parseFloat(row.children[1].querySelector('input').value) || 0;
                    const unit = row.children[2].querySelector('input').value.trim();
                    const unitPrice = parseFloat(row.children[3].querySelector('input').value) || 0;
                    const taxRate = parseFloat(row.children[4].querySelector('input').value) || 0;

                    if (description && quantity > 0 && unitPrice >= 0) {
                        items.push({
                            description,
                            quantity,
                            unit,
                            unit_price: unitPrice,
                            tax_rate: taxRate
                        });
                    }
                });

                if (items.length === 0) {
                    showMessage('Please add at least one valid item to the invoice', false);
                    return;
                }

                // Calculate totals
                let subtotal = 0;
                let totalTax = 0;

                items.forEach(item => {
                    const itemSubtotal = item.quantity * item.unit_price;
                    const itemTax = itemSubtotal * (item.tax_rate / 100);
                    subtotal += itemSubtotal;
                    totalTax += itemTax;
                });

                const invoiceData = {
                    invoice_number: invoiceNumber,
                    client_id: parseInt(clientId),
                    invoice_date: invoiceDate,
                    subtotal: subtotal,
                    total_tax: totalTax,
                    discount: 0,
                    grand_total: subtotal + totalTax,
                    items: items
                };

                const result = await saveInvoice(invoiceData);

                showMessage(`Invoice #${invoiceNumber} created successfully!`);

                // Reset form after successful creation
                setTimeout(() => {
                    resetForm();
                }, 2000);

            } catch (error) {
                showMessage(error.message, false);
            } finally {
                setLoading(createBtn, false);
            }
        }

        function resetForm() {
            // Clear items table
            document.getElementById('invoice-items').innerHTML = '';
            addNewRow();

            // Reset form fields
            document.getElementById('invoice-date').value = new Date().toISOString().split('T')[0];
            document.getElementById('due-date').value = new Date().toISOString().split('T')[0];

            // Get next invoice number
            fetchNextInvoiceNumber();

            // Update summary
            updateSummary();

            // Select first client if available
            if (clients.length > 0) {
                document.getElementById('client').value = clients[0].id;
            }
        }

        // Event listeners
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('clientModal');
            if (event.target === modal) {
                closeClientModal();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modal = document.getElementById('clientModal');
                if (modal.classList.contains('active')) {
                    closeClientModal();
                }
            }
        });

        // Initialize application
        document.addEventListener('DOMContentLoaded', function() {
            // Set today's date
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('invoice-date').value = today; 

            // Load data
            fetchClients();
            fetchNextInvoiceNumber();

            // Add first row
            addNewRow();
        });
    </script>
</body>

</html>