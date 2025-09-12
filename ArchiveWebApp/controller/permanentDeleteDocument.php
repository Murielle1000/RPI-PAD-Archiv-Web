<?php
session_start();
require_once("../model/DocumentsRepository.php");
require_once("AuditHelper.php");

// Vérification du rôle administrateur
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php?error=access_denied');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $documentId = intval($_GET['id']);
    
    if ($documentId > 0) {
        $repo = new DocumentsRepository();
        
        // Récupérer les informations du document avant suppression définitive
        $deletedDocuments = $repo->getDeletedDocuments();
        $document = null;
        foreach ($deletedDocuments as $doc) {
            if ($doc['id'] == $documentId) {
                $document = $doc;
                break;
            }
        }
        
        if ($document) {
            // Supprimer le fichier physique si il existe
            if (file_exists($document['url_fichier'])) {
                unlink($document['url_fichier']);
            }
            
            $success = $repo->permanentDeleteDocument($documentId);
            
            if ($success) {
                // Enregistrer l'action dans l'audit
                $auditHelper = new AuditHelper();
                $auditHelper->logDocumentPermanentDelete($_SESSION['user_id'], $documentId, $document['titre'], $document['type']);
                
                header("Location: ../corbeille.php?success=permanently_deleted&title=" . urlencode($document['titre']));
            } else {
                header("Location: ../corbeille.php?error=delete_failed");
            }
        } else {
            header("Location: ../corbeille.php?error=document_not_found");
        }
    } else {
        header("Location: ../corbeille.php?error=invalid_id");
    }
} else {
    header("Location: ../corbeille.php");
}
exit;
?>
