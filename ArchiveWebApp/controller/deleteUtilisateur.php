<?php
session_start();
require_once __DIR__ . '/../model/UtilisateursRepository.php';
require_once 'AuditHelper.php';

// Vérification de la session et du rôle admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php?error=access_denied');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = intval($_GET['id'] ?? 0);

    // Validation des données
    if ($id <= 0) {
        header('Location: ../utilisateurs.php?error=missing_id');
        exit;
    }

    // Empêcher l'auto-suppression
    if ($id == $_SESSION['user_id']) {
        header('Location: ../utilisateurs.php?error=cannot_delete_self');
        exit;
    }

    try {
        $usersRepo = new UsersRepository();
        
        // Récupérer les informations de l'utilisateur avant suppression pour l'audit
        $userToDelete = $usersRepo->getUserById($id);
        if (!$userToDelete) {
            header('Location: ../utilisateurs.php?error=user_not_found');
            exit;
        }
        
        if ($usersRepo->deleteUser($id)) {
            // Enregistrer l'action dans l'audit
            $auditHelper = new AuditHelper();
            $auditHelper->logUserDelete($_SESSION['user_id'], $id, $userToDelete['email']);
            
            header('Location: ../utilisateurs.php?success=user_deleted');
        } else {
            header('Location: ../utilisateurs.php?error=delete_failed');
        }
    } catch (Exception $e) {
        error_log("Erreur lors de la suppression de l'utilisateur: " . $e->getMessage());
        header('Location: ../utilisateurs.php?error=database_error');
    }
} else {
    header('Location: ../utilisateurs.php');
}
?>
