<?php

require_once __DIR__ . '/../model/UsersRepository.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $role = $_POST['role'] ?? 'user';
    $statut = $_POST['statut'] ?? 'actif';

    // Validation des données
    if (empty($id) || empty($nom) || empty($prenom) || empty($email)) {
        header('Location: ../users.php?error=missing_fields');
        exit;
    }

    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: ../users.php?error=invalid_email');
        exit;
    }

    try {
        $usersRepo = new UsersRepository();
        
        if ($usersRepo->updateUser($id, $nom, $prenom, $email, $role, $statut)) {
            // Enregistrer l'action dans l'audit
            require_once 'AuditHelper.php';
            $auditHelper = new AuditHelper();
            $auditHelper->logUserEdit(1, $id, $email); // 1 = admin par défaut
            
            header('Location: ../users.php?success=user_updated');
        } else {
            header('Location: ../users.php?error=update_failed');
        }
    } catch (Exception $e) {
        error_log("Erreur lors de la mise à jour de l'utilisateur: " . $e->getMessage());
        header('Location: ../users.php?error=database_error');
    }
} else {
    header('Location: ../users.php');
}
?>
