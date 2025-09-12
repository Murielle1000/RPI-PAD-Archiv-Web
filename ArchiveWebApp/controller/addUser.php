<?php

require_once __DIR__ . '/../model/UsersRepository.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = password_hash() $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    // Validation des données
    if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
        header('Location: ../users.php?error=missing_fields');
        exit;
    }

    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: ../users.php?error=invalid_email');
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
            header('Location: ../users.php?error=email_exists');
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
            $auditHelper->logUserAdd(1, $newUserId, $email); // 1 = admin par défaut
            
            header('Location: ../users.php?success=user_added');
        } else {
            header('Location: ../users.php?error=add_failed');
        }
    } catch (PDOException $e) {
        error_log("Erreur lors de l'ajout de l'utilisateur: " . $e->getMessage());
        // Pour le debug, on peut temporairement afficher l'erreur
        header('Location: ../users.php?error=database_error&debug=' . urlencode($e->getMessage()));
    } catch (Exception $e) {
        error_log("Erreur générale lors de l'ajout de l'utilisateur: " . $e->getMessage());
        header('Location: ../users.php?error=database_error&debug=' . urlencode($e->getMessage()));
    }
} else {
    header('Location: ../users.php');
}
?>
