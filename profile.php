<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Karla" rel="stylesheet">
    <link rel="stylesheet" href="base_styling.css">
    <title>Company Profile Settings</title>
    <style>
        /* Only keep unique or page-specific styles here */
        .settings-container {
            display: grid;
            gap: 32px;
            max-width: 800px;
        }

        .page-header {
            margin-bottom: 32px;
        }

        .page-header h2 {
            font-size: 30px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }

        .page-header p {
            color: #6b7280;
            font-size: 16px;
        }

        .settings-card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 24px;
            border-bottom: 1px solid #e5e7eb;
        }

        .card-header h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .card-header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .card-content {
            padding: 24px;
        }

        .form-grid {
            display: grid;
            gap: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group textarea {
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: all 200ms ease;
            background-color: #ffffff;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .required {
            color: #dc2626;
        }

        .help-text {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }

        .save-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
        }

        .last-updated {
            font-size: 14px;
            color: #6b7280;
        }

        .save-button {
            background-color: #2563eb;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 200ms ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .save-button:hover {
            background-color: #1d4ed8;
            transform: translateY(-1px);
        }

        .save-button:disabled {
            background-color: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }

        .preview-section {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin-top: 24px;
        }

        .preview-header {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .preview-content {
            background-color: white;
            border-radius: 6px;
            padding: 20px;
            border-left: 4px solid #3b82f6;
        }

        .company-preview {
            font-weight: 600;
            font-size: 18px;
            color: #111827;
            margin-bottom: 8px;
        }

        .company-details {
            color: #6b7280;
            line-height: 1.5;
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

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .main-content {
                padding: 20px;
            }

            .save-section {
                flex-direction: column;
                gap: 16px;
                align-items: stretch;
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
            <h2>Company Profile Settings</h2>
            <p>Manage your company information that appears on invoices and documents</p>
        </div>

        <div class="settings-container">
            <!-- Company Information Card -->
            <div class="settings-card">
                <div class="card-header">
                    <h3>Company Information</h3>
                    <p>Basic details about your company</p>
                </div>
                <div class="card-content">
                    <form id="companyForm" class="form-grid">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="company_name">Company Name <span class="required">*</span></label>
                                <input type="text" id="company_name" name="company_name" required>
                                <div class="help-text">This appears on all invoices and documents</div>
                            </div>
                            <div class="form-group">
                                <label for="gst_number">GST Number</label>
                                <input type="text" id="gst_number" name="gst_number" placeholder="e.g., 29ABCDE1234F1Z5" maxlength="15">
                                <div class="help-text">Your Goods and Services Tax registration number</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email">
                                <div class="help-text">Primary business email address</div>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone">
                                <div class="help-text">Main contact number</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="website">Website URL</label>
                                <input type="url" id="website" name="website" placeholder="https://www.example.com">
                                <div class="help-text">Your company website (optional)</div>
                            </div>
                            <div class="form-group">
                                <label for="pan_number">PAN Number</label>
                                <input type="text" id="pan_number" name="pan_number" placeholder="e.g., ABCTY1234D" maxlength="10">
                                <div class="help-text">Permanent Account Number (optional)</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address">Business Address</label>
                            <textarea id="address" name="address" placeholder="Enter your complete business address..."></textarea>
                            <div class="help-text">Full address including city, state, and postal code</div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bank Details Card -->
            <div class="settings-card">
                <div class="card-header">
                    <h3>Banking Information</h3>
                    <p>Bank details for payment instructions on invoices</p>
                </div>
                <div class="card-content">
                    <div class="form-grid">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="bank_name">Bank Name</label>
                                <input type="text" id="bank_name" name="bank_name">
                                <div class="help-text">Name of your bank</div>
                            </div>
                            <div class="form-group">
                                <label for="account_number">Account Number</label>
                                <input type="text" id="account_number" name="account_number">
                                <div class="help-text">Your bank account number</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="ifsc_code">IFSC Code</label>
                                <input type="text" id="ifsc_code" name="ifsc_code" placeholder="e.g., SBIN0001234" maxlength="11">
                                <div class="help-text">Indian Financial System Code</div>
                            </div>
                            <div class="form-group">
                                <label for="account_holder_name">Account Holder Name</label>
                                <input type="text" id="account_holder_name" name="account_holder_name">
                                <div class="help-text">Name as per bank account</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="branch_name">Branch Name</label>
                                <input type="text" id="branch_name" name="branch_name">
                                <div class="help-text">Bank branch location (optional)</div>
                            </div>
                            <div class="form-group">
                                <label for="swift_code">SWIFT Code</label>
                                <input type="text" id="swift_code" name="swift_code" placeholder="e.g., SBININBB123">
                                <div class="help-text">For international payments (optional)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Preferences Card -->
            <div class="settings-card">
                <div class="card-header">
                    <h3>Invoice Preferences</h3>
                    <p>Default settings for your invoices</p>
                </div>
                <div class="card-content">
                    <div class="form-grid">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="invoice_prefix">Invoice Number Prefix</label>
                                <input type="text" id="invoice_prefix" name="invoice_prefix" placeholder="e.g., INV-" maxlength="10">
                                <div class="help-text">Prefix for invoice numbers (optional)</div>
                            </div>
                            <div class="form-group">
                                <label for="default_due_days">Default Due Days</label>
                                <input type="number" id="default_due_days" name="default_due_days" value="30" min="1" max="365">
                                <div class="help-text">Default number of days for payment</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="invoice_terms">Default Terms & Conditions</label>
                            <textarea id="invoice_terms" name="invoice_terms" placeholder="Enter default terms and conditions for invoices..."></textarea>
                            <div class="help-text">These will appear on all invoices by default</div>
                        </div>

                        <div class="form-group">
                            <label for="payment_instructions">Payment Instructions</label>
                            <textarea id="payment_instructions" name="payment_instructions" placeholder="Enter payment instructions for clients..."></textarea>
                            <div class="help-text">Instructions on how clients should make payments</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview Section -->
            <div class="settings-card">
                <div class="card-header">
                    <h3>Preview</h3>
                    <p>How your company information will appear on invoices</p>
                </div>
                <div class="card-content">
                    <div class="preview-content" id="companyPreview">
                        <div class="company-preview">Your Company Name</div>
                        <div class="company-details">
                            <div>Email: info@company.com</div>
                            <div>Phone: (555) 123-4567</div>
                            <div>GST: 29ABCDE1234F1Z5</div>
                            <div>123 Business Street, City, State 12345</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Section -->
            <div class="save-section">
                <div class="last-updated">
                    Last updated: <span id="lastUpdated">Never</span>
                </div>
                <button type="button" class="save-button" onclick="saveSettings()">
                    💾 Save Changes
                </button>
            </div>
        </div>
    </main>

    <!-- Success/Error Messages -->
    <div class="success-message" id="successMessage">
        ✓ Settings saved successfully!
    </div>
    <div class="error-message" id="errorMessage">
        ✗ Failed to save settings!
    </div>

    <script>
        // Load settings when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadSettings();
            setupPreview();
        });

        // Load settings from database/localStorage
        function loadSettings() {
            // Try to load from server first, fallback to localStorage for demo
            fetch('api/profile/get_profile.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        populateForm(data.profile);
                        updatePreview();
                    } else {
                        // Fallback to localStorage if server doesn't have data
                        loadFromLocalStorage();
                    }
                })
                .catch(error => {
                    console.error('Error loading profile:', error);
                    loadFromLocalStorage();
                });
        }

        // Load from localStorage (fallback/demo)
        function loadFromLocalStorage() {
            const savedSettings = localStorage.getItem('companyProfile');
            if (savedSettings) {
                const settings = JSON.parse(savedSettings);
                populateForm(settings);
                updatePreview();
                document.getElementById('lastUpdated').textContent = settings.lastUpdated || 'Unknown';
            }
        }

        // Populate form with data
        function populateForm(data) {
            const fields = [
                'company_name', 'gst_number', 'email', 'phone', 'website', 'pan_number', 'address',
                'bank_name', 'account_number', 'ifsc_code', 'account_holder_name', 'branch_name', 'swift_code',
                'invoice_prefix', 'default_due_days', 'invoice_terms', 'payment_instructions'
            ];

            fields.forEach(field => {
                const element = document.getElementById(field);
                if (element && data[field]) {
                    element.value = data[field];
                }
            });
        }

        // Setup live preview updates
        function setupPreview() {
            const fields = ['company_name', 'email', 'phone', 'gst_number', 'address'];
            fields.forEach(field => {
                document.getElementById(field).addEventListener('input', updatePreview);
            });
            updatePreview(); // Initial preview
        }

        // Update preview section
        function updatePreview() {
            const company_name = document.getElementById('company_name').value || 'Your Company Name';
            const email = document.getElementById('email').value || 'info@company.com';
            const phone = document.getElementById('phone').value || '(555) 123-4567';
            const gst_number = document.getElementById('gst_number').value || '29ABCDE1234F1Z5';
            const address = document.getElementById('address').value || '123 Business Street, City, State 12345';

            const preview = document.getElementById('companyPreview');
            preview.innerHTML = `
                <div class="company-preview">${company_name}</div>
                <div class="company-details">
                    <div>Email: ${email}</div>
                    <div>Phone: ${phone}</div>
                    <div>GST: ${gst_number}</div>
                    <div>${address}</div>
                </div>
            `;
        }

        // Save settings
        function saveSettings() {
            const formData = new FormData();

            // Collect all form data
            const fields = [
                'company_name', 'gst_number', 'email', 'phone', 'website', 'pan_number', 'address',
                'bank_name', 'account_number', 'ifsc_code', 'account_holder_name', 'branch_name', 'swift_code',
                'invoice_prefix', 'default_due_days', 'invoice_terms', 'payment_instructions'
            ];

            const settings = {};
            fields.forEach(field => {
                const value = document.getElementById(field).value.trim();
                settings[field] = value;
                formData.append(field, value);
            });

            // Validation
            if (!settings.company_name) {
                alert('Company Name is required.');
                document.getElementById('company_name').focus();
                return;
            }

            if (settings.email && !isValidEmail(settings.email)) {
                alert('Please enter a valid email address.');
                document.getElementById('email').focus();
                return;
            }

            if (settings.gst_number && !isValidGSTNumber(settings.gst_number)) {
                alert('Please enter a valid GST number.');
                document.getElementById('gst_number').focus();
                return;
            }

            // Show loading state
            const saveButton = document.querySelector('.save-button');
            saveButton.disabled = true;
            saveButton.textContent = 'Saving...';

            // Try to save to server
            fetch('api/profile/save_profile.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessMessage('Settings saved successfully!');
                        document.getElementById('lastUpdated').textContent = new Date().toLocaleDateString();
                    } else {
                        throw new Error(data.error || 'Failed to save');
                    }
                })
                .catch(error => {
                    console.error('Server save failed, using localStorage:', error);
                    // Fallback to localStorage
                    settings.lastUpdated = new Date().toLocaleDateString();
                    localStorage.setItem('companyProfile', JSON.stringify(settings));
                    showSuccessMessage('Settings saved locally!');
                    document.getElementById('lastUpdated').textContent = settings.lastUpdated;
                })
                .finally(() => {
                    saveButton.disabled = false;
                    saveButton.innerHTML = '💾 Save Changes';
                });
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

        // Message functions 
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
    </script>
</body>

</html>