<?php
// Définit l'en-tête de la réponse comme JSON.
// Cela indique au navigateur que le contenu est au format JSON et non HTML.
header('Content-Type: application/json');

// Reporte toutes les erreurs PHP pour le débogage. À désactiver sur un serveur de production.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Paramètres de connexion à la base de données.
$servername = "localhost";
$username = "root";
$password = ""; // 
$dbname = "archiveweb";

try {
    // Crée une nouvelle connexion PDO (PHP Data Objects).
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Configure PDO pour lancer une exception en cas d'erreur SQL.
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
    // Si la connexion échoue, renvoie une réponse d'erreur JSON et arrête le script.
    echo json_encode(['status' => 'error', 'message' => 'Erreur de connexion à la base de données: ' . $e->getMessage()]);
    exit();
}

// Vérifie si la méthode de la requête est POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère l'email et le mot de passe envoyés par le formulaire.
    // htmlspecialchars() protège contre les attaques XSS.
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // Utilise une requête préparée pour éviter les injections SQL.
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifie si un utilisateur a été trouvé avec cet email.
    if ($user) {
        // Vérifie d'abord si l'utilisateur n'est pas bloqué
        if ($user['statut'] === 'bloque') {
            echo json_encode(['status' => 'error', 'message' => 'Votre compte a été bloqué. Contactez l\'administrateur.']);
        } else {
        // Vérifie si le mot de passe fourni correspond à celui de la base de données.
        if(password_verify($password, $user['password'])) {
            // Enregistrer la connexion dans l'audit
            require_once 'controller/AuditHelper.php';
            $auditHelper = new AuditHelper();
            $auditHelper->logLogin($user['id'], $user['email']);
            // Stocker le rôle et l'id utilisateur en session
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            // Connexion réussie : prépare la réponse JSON.
            echo json_encode(['status' => 'success', 'message' => 'Connexion réussie', 'role' => $user['role'] ?? 'guest']);
        } else {
                // Mot de passe incorrect.
                echo json_encode(['status' => 'error', 'message' => 'Mot de passe incorrect.']);
            }
        }
    } else {
        // Utilisateur non trouvé.
        echo json_encode(['status' => 'error', 'message' => 'Adresse e-mail non trouvée.']);
    }

} else {
    // Si la requête n'est pas une méthode POST, renvoie une erreur.
    echo json_encode(['status' => 'error', 'message' => 'Méthode de requête non valide.']);
}

// Ferme la connexion à la base de données.
$conn = null;
?>
