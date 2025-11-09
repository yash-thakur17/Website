<?php
$current = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <h1>Dashboard</h1>
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li><a href="dashboard.php" class="<?= $current === 'index.php' ? 'active' : '' ?>">Dashboard</a></li>
            <li><a href="invoice.php" class="<?= $current === 'invoice.php' ? 'active' : '' ?>">Invoices</a></li>
            <li><a href="manage_invoice.php" class="<?= $current === 'manage_invoice.php' ? 'active' : '' ?>">Manage Invoices</a></li>
            <li><a href="clients.php" class="<?= $current === 'clients.php' ? 'active' : '' ?>">Clients</a></li>
            <li><a href="profile.php" class="<?= $current === 'profile.php' ? 'active' : '' ?>">Profile</a></li>
            <li><a href="reports.php" class="<?= $current === 'reports.php' ? 'active' : '' ?>">Reports</a></li>
        </ul>
    </nav>
    <div class="user-info">
        <img src="assets/images/boy.jpg" alt="User Avatar" id="user-avatar">
        <span id="username">Admin User</span>
    </div>
    <div class="logout-button-container">
        <button class="logout-button" onclick="handleLogout()">Logout</button>
    </div>
</aside>

<script>
    function handleLogout() {
        if (confirm('Are you sure you want to logout?')) {
            window.location.href = 'index.php';
        }
    }
</script> 