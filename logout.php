<?php
session_start();
session_destroy();
unset($_SESSION);

// Clear session storage via JavaScript
echo "<script>
    sessionStorage.clear();
    localStorage.removeItem('username');
    window.location.href = 'index.php';
</script>";
exit;
