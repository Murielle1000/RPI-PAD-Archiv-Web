<?php

require_once __DIR__ . '/../model/UsersRepository.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'] ?? '';

    // Validation des données
    if (empty($id)) {
        header('Location: ../users.php?error=missing_id');
        exit;
    }

    try {
        $usersRepo = new UsersRepository();
        
        if ($usersRepo->deleteUser($id)) {
            // Enregistrer l'action dans l'audit
            require_once 'AuditHelper.php';
            $auditHelper = new AuditHelper();
            $auditHelper->logUserDelete(1, $id, 'Utilisateur supprimé'); // 1 = admin par défaut
            
            header('Location: ../users.php?success=user_deleted');
        } else {
            header('Location: ../users.php?error=delete_failed');
        }
    } catch (Exception $e) {
        error_log("Erreur lors de la suppression de l'utilisateur: " . $e->getMessage());
        header('Location: ../users.php?error=database_error');
    }
} else {
    header('Location: ../users.php');
}
?>
