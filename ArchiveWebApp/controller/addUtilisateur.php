<?php
session_start();
require_once __DIR__ . '/../model/UtilisateursRepository.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);
    $role = $_POST['role'] ?? 'user';

    // Validation des données
    if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
        header('Location: ../utilisateurs.php?error=missing_fields');
        exit;
    }

    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: ../utilisateurs.php?error=invalid_email');
        exit;
    }

    try {
        $dbRepo = new DBRepository();
        $db = $dbRepo->getConnection();

        // Vérifier si l'email existe déjà
        $sql = "SELECT id FROM users WHERE email = :email";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->fetch()) {
            header('Location: ../utilisateurs.php?error=email_exists');
            exit;
        }

        // Insérer le nouvel utilisateur
        $sql = "INSERT INTO users (nom, prenom, email, password, role, statut, add_date) 
                VALUES (:nom, :prenom, :email, :password, :role, 'actif', NOW())";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // Enregistrer l'action dans l'audit
            require_once 'AuditHelper.php';
            $auditHelper = new AuditHelper();
            $newUserId = $db->lastInsertId();
            $currentUserId = $_SESSION['user_id'] ?? 1;
            
            // Debug: vérifier que l'audit est bien enregistré
            $auditResult = $auditHelper->logUserAdd($currentUserId, $newUserId, $email);
            if (!$auditResult) {
                error_log("Erreur lors de l'enregistrement de l'audit pour l'ajout d'utilisateur: $email");
            }
            
            header('Location: ../utilisateurs.php?success=user_added');
        } else {
            header('Location: ../utilisateurs.php?error=add_failed');
        }
    } catch (PDOException $e) {
        error_log("Erreur lors de l'ajout de l'utilisateur: " . $e->getMessage());
        // Pour le debug, on peut temporairement afficher l'erreur
        header('Location: ../utilisateurs.php?error=database_error&debug=' . urlencode($e->getMessage()));
    } catch (Exception $e) {
        error_log("Erreur générale lors de l'ajout de l'utilisateur: " . $e->getMessage());
        header('Location: ../utilisateurs.php?error=database_error&debug=' . urlencode($e->getMessage()));
    }
} else {
    header('Location: ../utilisateurs.php');
}
?>
