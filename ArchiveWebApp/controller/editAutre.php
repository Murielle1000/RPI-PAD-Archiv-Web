<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("../model/DocumentsRepository.php");
session_start();

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $titre = $_POST['title'] ?? '';
    $type = $_POST['type'] ?? '';
    $categorie = $_POST['categorie'] ?? '';
    $description = $_POST['description'] ?? '';

    $repo = new DocumentsRepository();
    $success = $repo->updateDocument($id, $titre, $type, $categorie, $description);

    if ($success) {
        // Enregistrer l'action dans l'audit
        require_once 'AuditHelper.php';
        $auditHelper = new AuditHelper();
        $auditHelper->logDocumentEdit($_SESSION['user_id'] ?? 1, $id, $titre, $type);
        
        header("Location: ../autre.php?success=1");
        exit;
    } else {
        header("Location: ../autre.php?error=1");
        exit;
    }
}

// Si c'est une requête AJAX pour récupérer les données du document
if (isset($_GET['id']) && isset($_GET['ajax'])) {
    $id = intval($_GET['id']);
    $repo = new DocumentsRepository();
    $document = $repo->getById($id);
    
    if ($document) {
        header('Content-Type: application/json');
        echo json_encode($document);
        exit;
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Document non trouvé']);
        exit;
    }
}
?>
