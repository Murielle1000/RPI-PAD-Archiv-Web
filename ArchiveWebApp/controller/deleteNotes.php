
<?php
    require_once("../model/DocumentsRepository.php");
    session_start();

    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $repo = new DocumentsRepository();
        
        // Récupérer les informations du document avant suppression pour l'audit
        $document = $repo->getById($id);
        
        $success = $repo->deleteDocument($id);

        if ($success) {
            // Enregistrer l'action dans l'audit
            if ($document) {
                require_once 'AuditHelper.php';
                $auditHelper = new AuditHelper();
                $auditHelper->logDocumentDelete($_SESSION['user_id'] ?? 1, $id, $document['titre'], $document['type']);
            }
            
            header("Location: ../listeNotes.php?success=1");
            exit;
        } else {
            header("Location: ../listeNotes.php?error=1");
            exit;
        }
    } else {
        header("Location: ../listeNotes.php?error=1");
        exit;
    }
?>