<?php

require_once __DIR__ . '/../model/UsersRepository.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'] ?? '';
    $statut = $_GET['statut'] ?? '';

    // Validation des données
    if (empty($id) || empty($statut)) {
        header('Location: ../users.php?error=missing_parameters');
        exit;
    }

    // Validation du statut
    if (!in_array($statut, ['actif', 'bloque'])) {
        header('Location: ../users.php?error=invalid_status');
        exit;
    }

    try {
        $usersRepo = new UsersRepository();
        
        if ($usersRepo->toggleUserStatus($id, $statut)) {
            // Enregistrer l'action dans l'audit
            require_once 'AuditHelper.php';
            $auditHelper = new AuditHelper();
            $auditHelper->logUserStatusChange(1, $id, 'Utilisateur', $statut); // 1 = admin par défaut
            
            $message = $statut === 'actif' ? 'user_unblocked' : 'user_blocked';
            header('Location: ../users.php?success=' . $message);
        } else {
            header('Location: ../users.php?error=status_update_failed');
        }
    } catch (Exception $e) {
        error_log("Erreur lors du changement de statut de l'utilisateur: " . $e->getMessage());
        header('Location: ../users.php?error=database_error');
    }
} else {
    header('Location: ../users.php');
}
?>
