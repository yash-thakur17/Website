<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Karla" rel="stylesheet">
    <link rel="stylesheet" href="base_styling.css">
    <title>Client Management</title>
    <style>
        /* Only keep unique or page-specific styles here */
        .client-card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table-container {
            overflow-x: auto;
        }

        .client-table {
            width: 100%;
            border-collapse: collapse;
        }

        .client-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .client-table th {
            padding: 16px 20px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .client-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
            transition: background-color 150ms ease;
        }

        .client-table tbody tr:hover {
            background-color: #f9fafb;
        }

        .client-table tbody tr:last-child {
            border-bottom: none;
        }

        .client-table td {
            padding: 16px 20px;
            color: #374151;
            font-weight: 500;
        }

        .client-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .client-details h4 {
            font-weight: 600;
            color: #111827;
            margin-bottom: 2px;
        }

        .client-details p {
            color: #6b7280;
            font-size: 12px;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn {
            padding: 6px 12px;
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
            max-width: 600px;
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

        .modal-form {
            display: grid;
            gap: 16px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-group input,
        .form-group textarea {
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: border-color 200ms ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 1px #3b82f6;
        }

        .form-group textarea {
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

        .client-detail-card {
            background-color: #f9fafb;
            padding: 16px;
            border-radius: 6px;
            margin-bottom: 16px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 500;
            color: #6b7280;
        }

        .detail-value {
            color: #111827;
            font-weight: 500;
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

        .header-actions {
            display: flex;
            gap: 12px;
        }

        .search-box {
            padding: 8px 16px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            width: 250px;
            outline: none;
        }

        .search-box:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 1px #3b82f6;
        }

        .add-client-btn {
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

        .add-client-btn:hover {
            background-color: #1d4ed8;
        }

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

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .filter-section {
            display: flex;
            align-items: center;
            gap: 12px;
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

        /* Responsive Styles */
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .client-table th,
            .client-table td {
                padding: 12px;
                font-size: 13px;
            }

            .modal-header h3 {
                font-size: 18px;
            }

            .modal-button {
                font-size: 13px;
            }

            .success-message,
            .error-message {
                font-size: 12px;
                padding: 10px 14px;
            }

            .search-box {
                width: 200px;
                padding: 6px 12px;
                font-size: 13px;
            }

            .add-client-btn {
                padding: 6px 12px;
                font-size: 13px;
            }
        }

        @media (max-width: 480px) {

            .client-table th,
            .client-table td {
                padding: 10px;
                font-size: 12px;
            }

            .modal-header h3 {
                font-size: 16px;
            }

            .modal-button {
                font-size: 12px;
            }

            .success-message,
            .error-message {
                font-size: 11px;
                padding: 8px 12px;
            }

            .search-box {
                width: 100%;
                padding: 8px 14px;
                font-size: 14px;
            }

            .add-client-btn {
                width: 100%;
                padding: 8px 14px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <div class="page-header">
            <div class="header-content">
                <h2>Client Management</h2>
                <div class="filter-section">
                    <input type="text" id="searchInput" placeholder="Search clients..." oninput="searchClients()">
                    <button class="add-client-btn" onclick="openAddClientModal()">+ Add New Client</button>
                </div>
            </div>
        </div>

        <div class="client-card">
            <div class="table-container">
                <table class="client-table">
                    <thead>
                        <tr>
                            <th>Company Name</th>
                            <!-- <th>Contact Person</th> -->
                            <th>Email</th>
                            <!-- <th>Phone</th> -->
                            <th>GST Number</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="clientTableBody">
                        <!-- Populated by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
 
        <script>
            function searchClients() {
                const searchTerm = document.getElementById('searchInput').value.toLowerCase();
                const rows = document.querySelectorAll('#clientTableBody tr');

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            }
        </script>
    </main>

    <!-- Add/Edit Client Modal -->
    <div class="modal-overlay" id="clientModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Add New Client</h3>
                <button class="close-button" onclick="closeModal()">&times;</button>
            </div>
            <form class="modal-form" id="clientForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="companyName">Company Name <span class="required">*</span></label>
                        <input type="text" id="companyName" name="companyName" required>
                    </div>
                    <div class="form-group">
                        <label for="contactPerson">Contact Person</label>
                        <input type="text" id="contactPerson" name="contactPerson">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="gstNumber">GST Number</label>
                        <input type="text" id="gstNumber" name="gstNumber" placeholder="e.g., 29ABCDE1234F1Z5" maxlength="15">
                    </div>
                    <div class="form-group">
                        <!-- Empty space for layout -->
                    </div>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" placeholder="Complete address..."></textarea>
                </div>
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" placeholder="Additional notes about this client..."></textarea>
                </div>
            </form>
            <div class="modal-buttons">
                <button type="button" class="modal-button cancel" onclick="closeModal()">Cancel</button>
                <button type="button" class="modal-button save" onclick="saveClient()">Save Client</button>
            </div>
        </div>
    </div>

    <!-- View Client Modal -->
    <div class="modal-overlay" id="viewClientModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Client Details</h3>
                <button class="close-button" onclick="closeViewModal()">&times;</button>
            </div>

            <div class="client-detail-card">
                <div class="detail-row">
                    <span class="detail-label">Company Name:</span>
                    <span class="detail-value" id="viewCompanyName"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Contact Person:</span>
                    <span class="detail-value" id="viewContactPerson"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value" id="viewEmail"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value" id="viewPhone"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">GST Number:</span>
                    <span class="detail-value" id="viewGstNumber"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Address:</span>
                    <span class="detail-value" id="viewAddress"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Notes:</span>
                    <span class="detail-value" id="viewNotes"></span>
                </div>
            </div>

            <div class="modal-buttons">
                <button type="button" class="modal-button save" onclick="editClientFromView()">Edit Client</button>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <div class="success-message" id="successMessage">
        ✓ Operation successful!
    </div>
    <div class="error-message" id="errorMessage">
        ✗ Something went wrong!
    </div>

    <script>
        let clientsData = {};
        let isEditMode = false;
        let currentEditId = null;

        // Load clients when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadClients();
        });

        // Load clients from database via existing get_clients.php
        function loadClients() {
            fetch('api/clients/get_clients.php')
                .then(response => response.json())
                .then(data => {
                    // Check if data has success property, or if it's directly an array of clients
                    let clients = [];
                    if (data.success && data.clients) {
                        // Format: {"success": true, "clients": [...]}
                        clients = data.clients;
                    } else if (Array.isArray(data)) {
                        // Format: [client1, client2, ...]  <- This is your format
                        clients = data;
                    } else if (data.success === false) {
                        showErrorMessage('Failed to load clients: ' + (data.error || 'Unknown error'));
                        return;
                    } else {
                        console.error('Unexpected data format:', data);
                        showErrorMessage('Unexpected data format from server');
                        return;
                    }

                    clientsData = {};
                    const tbody = document.getElementById('clientTableBody');
                    tbody.innerHTML = '';

                    if (clients.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #6b7280; padding: 40px;">No clients found</td></tr>';
                    } else {
                        clients.forEach((client, index) => {
                            clientsData[client.id] = client;
                            addClientRowToTable(client);
                        });
                    }
                })
                .catch(error => {
                    console.error('Network error:', error);
                    showErrorMessage('Failed to load clients: Network error');
                });
        }

        // Add client row to table
        function addClientRowToTable(client) {
            const tbody = document.getElementById('clientTableBody');
            const row = tbody.insertRow();

            row.innerHTML = `
                <td>
                    <div class="client-info">
                        <div class="client-details">
                            <h4>${client.company_name || client.companyName || ''}</h4>
                            <p>${client.contact_person || client.contactPerson || ''}</p>
                        </div>
                    </div>
                </td>
                <td>
                    <div>
                        <div>${client.email || ''}</div>
                        <div style="color: #6b7280; font-size: 12px;">${client.phone || ''}</div>
                    </div>
                </td>
                <td>${client.gst_number || client.gstNumber || ''}</td>
                <td>
                    <div class="action-buttons">
                        <button class="btn btn-view" onclick="viewClient(${client.id})">View</button>
                        <button class="btn btn-edit" onclick="editClient(${client.id})">Edit</button>
                        <button class="btn btn-delete" onclick="deleteClient(${client.id})">Delete</button>
                    </div>
                </td>
            `;
        }

        // Open Add Client Modal
        function openAddClientModal() {
            isEditMode = false;
            currentEditId = null;
            document.getElementById('modalTitle').textContent = 'Add New Client';
            document.getElementById('clientForm').reset();
            document.getElementById('clientModal').classList.add('active');
            document.getElementById('companyName').focus();
            document.body.style.overflow = 'hidden';
        }

        // Edit Client
        function editClient(clientId) {
            const client = clientsData[clientId];
            if (!client) return;

            isEditMode = true;
            currentEditId = clientId;
            document.getElementById('modalTitle').textContent = 'Edit Client';

            // Populate form with existing data (handle both snake_case and camelCase)
            document.getElementById('companyName').value = client.company_name || client.companyName || '';
            document.getElementById('contactPerson').value = client.contact_person || client.contactPerson || '';
            document.getElementById('email').value = client.email || '';
            document.getElementById('phone').value = client.phone || '';
            document.getElementById('gstNumber').value = client.gst_number || client.gstNumber || '';
            document.getElementById('address').value = client.address || '';
            document.getElementById('notes').value = client.notes || '';

            document.getElementById('clientModal').classList.add('active');
            document.getElementById('companyName').focus();
            document.body.style.overflow = 'hidden';
        }

        // View Client
        function viewClient(clientId) {
            const client = clientsData[clientId];
            if (!client) return;

            // Populate view modal (handle both snake_case and camelCase)
            document.getElementById('viewCompanyName').textContent = client.company_name || client.companyName || '';
            document.getElementById('viewContactPerson').textContent = client.contact_person || client.contactPerson || '';
            document.getElementById('viewEmail').textContent = client.email || '';
            document.getElementById('viewPhone').textContent = client.phone || '';
            document.getElementById('viewGstNumber').textContent = client.gst_number || client.gstNumber || '';
            document.getElementById('viewAddress').textContent = client.address || '';
            document.getElementById('viewNotes').textContent = client.notes || '';

            currentEditId = clientId;

            document.getElementById('viewClientModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Edit from view modal
        function editClientFromView() {
            closeViewModal();
            editClient(currentEditId);
        }

        function deleteClient(clientId) {
            const client = clientsData[clientId];
            if (!client) return;

            const companyName = client.company_name || client.companyName || 'this client';
            if (confirm(`Are you sure you want to delete ${companyName}?`)) {
                const formData = new FormData();
                formData.append('delete', '1');
                formData.append('id', clientId);

                fetch('api/clients/save_clients.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(text => {
                        console.log('Delete raw response:', text); // Debug log
                        try {
                            const data = JSON.parse(text);
                            if (data.success) {
                                // Remove from local data
                                delete clientsData[clientId];
                                // Remove from UI
                                const row = document.querySelector(`[onclick="viewClient(${clientId})"]`).closest('tr');
                                if (row) {
                                    row.remove();
                                }
                                showSuccessMessage('Client deleted successfully!');
                            } else {
                                showErrorMessage('Failed to delete client: ' + (data.message || 'Unknown error'));
                            }
                        } catch (parseError) {
                            console.error('JSON parse error:', parseError);
                            console.error('Raw response was:', text);
                            showErrorMessage('Invalid server response. Check console for details.');
                        }
                    })
                    .catch(error => {
                        console.error('Network error:', error);
                        showErrorMessage('Error deleting client: Network error');
                    });
            }
        }

        function saveClient() {
            // Basic validation
            if (!document.getElementById('companyName').value.trim()) {
                alert('Company Name is required.');
                document.getElementById('companyName').focus();
                return;
            }

            const email = document.getElementById('email').value.trim();
            if (email && !isValidEmail(email)) {
                alert('Please enter a valid email address.');
                document.getElementById('email').focus();
                return;
            }

            const gstNumber = document.getElementById('gstNumber').value.trim();
            if (gstNumber && !isValidGSTNumber(gstNumber)) {
                alert('Please enter a valid GST number (15 characters).');
                document.getElementById('gstNumber').focus();
                return;
            }

            const formData = new FormData();

            // Add form data using the field names your PHP expects
            formData.append('companyName', document.getElementById('companyName').value.trim());
            formData.append('contactPerson', document.getElementById('contactPerson').value.trim());
            formData.append('email', document.getElementById('email').value.trim());
            formData.append('phone', document.getElementById('phone').value.trim());
            formData.append('gstNumber', document.getElementById('gstNumber').value.trim());
            formData.append('address', document.getElementById('address').value.trim());
            formData.append('notes', document.getElementById('notes').value.trim());

            // Handle edit vs add mode
            if (isEditMode && currentEditId) {
                formData.append('edit', '1');
                formData.append('id', currentEditId);
            }

            // Show loading state
            document.querySelector('.modal-content').classList.add('loading');

            // Send to save_clients.php
            fetch('api/clients/save_clients.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(text => {
                    console.log('Save raw response:', text);
                    document.querySelector('.modal-content').classList.remove('loading');

                    try {
                        const data = JSON.parse(text);
                        if (data.success) {
                            closeModal();
                            showSuccessMessage(isEditMode ? 'Client updated successfully!' : 'Client added successfully!');
                            // Reload the clients table
                            loadClients();
                        } else {
                            showErrorMessage('Failed to save client: ' + (data.message || 'Unknown error'));
                        }
                    } catch (parseError) {
                        console.error('JSON parse error:', parseError);
                        console.error('Raw response was:', text);
                        showErrorMessage('Invalid server response. Check console for details.');
                    }
                })
                .catch(error => {
                    document.querySelector('.modal-content').classList.remove('loading');
                    console.error('Network error:', error);
                    showErrorMessage('Failed to save client: Network error');
                });
        }

        // Search Clients
        function searchClients() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#clientTableBody tr');

            rows.forEach(row => {
                const companyName = row.querySelector('h4')?.textContent.toLowerCase() || '';
                const contactPerson = row.querySelector('p')?.textContent.toLowerCase() || '';
                const email = row.querySelectorAll('td')[1]?.querySelector('div div')?.textContent.toLowerCase() || '';

                if (companyName.includes(searchTerm) || contactPerson.includes(searchTerm) || email.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Modal Functions
        function closeModal() {
            document.getElementById('clientModal').classList.remove('active');
            document.body.style.overflow = 'auto';
            document.getElementById('clientForm').reset();
            isEditMode = false;
            currentEditId = null;
        }

        function closeViewModal() {
            document.getElementById('viewClientModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Validation Functions
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function isValidGSTNumber(gstNumber) {
            const gstRegex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}[Z]{1}[0-9A-Z]{1}$/;
            return gstRegex.test(gstNumber);
        }

        // Message Functions
        function showSuccessMessage(message) {
            const messageEl = document.getElementById('successMessage');
            messageEl.textContent = message;
            messageEl.classList.add('show');

            setTimeout(() => {
                messageEl.classList.remove('show');
            }, 3000);
        }

        function showErrorMessage(message) {
            const messageEl = document.getElementById('errorMessage');
            messageEl.textContent = message;
            messageEl.classList.add('show');

            setTimeout(() => {
                messageEl.classList.remove('show');
            }, 3000);
        }

        // Event listeners
        document.addEventListener('click', function(event) {
            const modals = ['clientModal', 'viewClientModal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (event.target === modal) {
                    if (modalId === 'clientModal') {
                        closeModal();
                    } else {
                        closeViewModal();
                    }
                }
            });
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                if (document.getElementById('clientModal').classList.contains('active')) {
                    closeModal();
                }
                if (document.getElementById('viewClientModal').classList.contains('active')) {
                    closeViewModal();
                }
            }
        });
    </script>
</body>

</html>