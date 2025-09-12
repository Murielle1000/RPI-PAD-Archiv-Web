<?php
session_start();

// Enregistrer la déconnexion dans l'audit avant de détruire la session
if (isset($_SESSION['user_id']) && isset($_SESSION['email'])) {
    require_once 'controller/AuditHelper.php';
    $auditHelper = new AuditHelper();
    $auditHelper->logLogout($_SESSION['user_id'], $_SESSION['email']);
}

// Détruire la session
session_destroy();

// Rediriger vers la page de connexion
header('Location: login.php');
exit;
?>
