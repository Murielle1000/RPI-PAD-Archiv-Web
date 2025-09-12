<?php
require_once("../model/DocumentsRepository.php");
session_start();

// S'assurer que le script ne s'exécute pas indéfiniment
set_time_limit(0);

// Initialisation du référentiel de documents
$repo = new DocumentsRepository();

// Récupérer l'ID du document depuis l'URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Récupérer les informations du document
    $document = $repo->getById($id);

    // Vérifier si le document existe et s'il a une URL de fichier
    if ($document && !empty($document['url_fichier'])) {
        // Enregistrer la consultation du document dans l'audit
        if (isset($_SESSION['user_id'])) {
            require_once 'AuditHelper.php';
            $auditHelper = new AuditHelper();
            $auditHelper->logDocumentView($_SESSION['user_id'], $id, $document['titre'], $document['type']);
        }
        $filePath = realpath($document['url_fichier']);

        // Vérifier si le fichier existe réellement sur le disque
        if ($filePath && file_exists($filePath)) {
            // Déterminer le type MIME pour que le navigateur sache comment afficher le fichier
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $filePath);
            finfo_close($finfo);

            // Définir les en-têtes pour forcer l'ouverture du fichier dans le navigateur
            header('Content-Type: ' . $mimeType);
            header('Content-Length: ' . filesize($filePath));
            header('Content-Disposition: inline; filename="' . basename($filePath) . '"');

            // Lire le fichier et l'envoyer à la sortie
            readfile($filePath);
            exit;
        }
    }
}

// Si le fichier n'est pas trouvé, rediriger l'utilisateur avec une erreur
header("Location: ../listeNotes.php?error=1&message=Fichier introuvable");
exit;
?>
