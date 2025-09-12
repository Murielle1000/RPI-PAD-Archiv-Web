<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once("../model/DocumentsRepository.php");
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titre = $_POST['title'] ?? '';
        $type = $_POST['type'] ?? '';
        $categorie = $_POST['categorie'] ?? '';
        $description = $_POST['description'] ?? '';

        // Gestion du fichier
        $url_fichier = '';
        if (isset($_FILES['url_fichier']) && $_FILES['url_fichier']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = uniqid() . '_' . basename($_FILES['url_fichier']['name']); // nom unique
            $targetFile = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['url_fichier']['tmp_name'], $targetFile)) {
                $url_fichier = $targetFile;
            }
        }

        $repo = new DocumentsRepository();
        $success = $repo->addDocument($titre, $type, $categorie, $url_fichier, $description);

        if ($success) {
            // Enregistrer l'action dans l'audit
            require_once 'AuditHelper.php';
            $auditHelper = new AuditHelper();
            $documentId = $repo->getLastInsertId(); // Récupérer l'ID du document ajouté
            $auditHelper->logDocumentAdd($_SESSION['user_id'] ?? 1, $documentId, $titre, $type);
            
            header("Location: ../listeLois.php?success=1");
            exit;
        } else {
            header("Location: ../listeLois.php?error=1");
            exit;
        }
    }
?>