<?php

require_once __DIR__ . '/../model/AuditRepository.php';

class AuditHelper
{
    private $auditRepo;

    public function __construct()
    {
        $this->auditRepo = new AuditRepository();
    }

    /**
     * Obtient l'adresse IP de l'utilisateur
     */
    private function getClientIP()
    {
        $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        foreach ($ipKeys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }

    /**
     * Obtient l'ID de l'utilisateur connecté depuis la session
     */
    private function getCurrentUserId()
    {
        // Ici, vous devrez adapter selon votre système de session
        // Pour l'instant, on retourne 1 (admin par défaut)
        return $_SESSION['user_id'] ?? 1;
    }

    /**
     * Enregistre une connexion
     */
    public function logLogin($userId, $email)
    {
        return $this->auditRepo->logAction(
            $userId,
            'CONNEXION',
            null,
            null,
            "Connexion de l'utilisateur: $email",
            $this->getClientIP()
        );
    }

    /**
     * Enregistre une déconnexion
     */
    public function logLogout($userId, $email)
    {
        return $this->auditRepo->logAction(
            $userId,
            'DÉCONNEXION',
            null,
            null,
            "Déconnexion de l'utilisateur: $email",
            $this->getClientIP()
        );
    }

    /**
     * Enregistre l'ajout d'un document
     */
    public function logDocumentAdd($userId, $documentId, $documentTitle, $documentType)
    {
        return $this->auditRepo->logAction(
            $userId,
            'AJOUT_DOCUMENT',
            $documentId,
            null,
            "Ajout du document '$documentTitle' (Type: $documentType)",
            $this->getClientIP()
        );
    }

    /**
     * Enregistre la modification d'un document
     */
    public function logDocumentEdit($userId, $documentId, $documentTitle, $documentType)
    {
        return $this->auditRepo->logAction(
            $userId,
            'MODIFICATION_DOCUMENT',
            $documentId,
            null,
            "Modification du document '$documentTitle' (Type: $documentType)",
            $this->getClientIP()
        );
    }

    /**
     * Enregistre la suppression d'un document
     */
    public function logDocumentDelete($userId, $documentId, $documentTitle, $documentType)
    {
        return $this->auditRepo->logAction(
            $userId,
            'SUPPRESSION_DOCUMENT',
            $documentId,
            null,
            "Suppression du document '$documentTitle' (Type: $documentType)",
            $this->getClientIP()
        );
    }

    /**
     * Enregistre l'ajout d'un utilisateur
     */
    public function logUserAdd($userId, $targetUserId, $targetUserEmail)
    {
        return $this->auditRepo->logAction(
            $userId,
            'AJOUT_UTILISATEUR',
            null,
            $targetUserId,
            "Ajout de l'utilisateur: $targetUserEmail",
            $this->getClientIP()
        );
    }

    /**
     * Enregistre la modification d'un utilisateur
     */
    public function logUserEdit($userId, $targetUserId, $targetUserEmail)
    {
        return $this->auditRepo->logAction(
            $userId,
            'MODIFICATION_UTILISATEUR',
            null,
            $targetUserId,
            "Modification de l'utilisateur: $targetUserEmail",
            $this->getClientIP()
        );
    }

    /**
     * Enregistre la suppression d'un utilisateur
     */
    public function logUserDelete($userId, $targetUserId, $targetUserEmail)
    {
        return $this->auditRepo->logAction(
            $userId,
            'SUPPRESSION_UTILISATEUR',
            null,
            $targetUserId,
            "Suppression de l'utilisateur: $targetUserEmail",
            $this->getClientIP()
        );
    }

    /**
     * Enregistre le blocage/déblocage d'un utilisateur
     */
    public function logUserStatusChange($userId, $targetUserId, $targetUserEmail, $newStatus)
    {
        return $this->auditRepo->logAction(
            $userId,
            'CHANGEMENT_STATUT_UTILISATEUR',
            null,
            $targetUserId,
            "Changement de statut de l'utilisateur: $targetUserEmail (Nouveau statut: $newStatus)",
            $this->getClientIP()
        );
    }

    /**
     * Enregistre l'ouverture d'un document
     */
    public function logDocumentView($userId, $documentId, $documentTitle, $documentType)
    {
        return $this->auditRepo->logAction(
            $userId,
            'CONSULTATION_DOCUMENT',
            $documentId,
            null,
            "Consultation du document '$documentTitle' (Type: $documentType)",
            $this->getClientIP()
        );
    }

    /**
     * Enregistre une action personnalisée
     */
    public function logCustomAction($userId, $action, $details = null, $documentId = null, $targetUserId = null)
    {
        return $this->auditRepo->logAction(
            $userId,
            $action,
            $documentId,
            $targetUserId,
            $details,
            $this->getClientIP()
        );
    }
}
?>
