
<?php
session_start();
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin.php');
    } else {
        header('Location: profil.php');
    }
    exit;
} else {
    header('Location: login.php');
    exit;
}
?> 