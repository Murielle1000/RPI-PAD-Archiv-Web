<?php

require_once __DIR__ . '/DBRepository.php';

class AuditRepository
{
    private $db;

    public function __construct()
    {
        $dbRepo = new DBRepository();
        $this->db = $dbRepo->getConnection();
    }

    /**
     * Enregistre une action dans l'audit
     */
    public function logAction($userId, $action, $documentId = null, $targetUserId = null, $details = null, $ipAddress = null)
    {
        try {
            $sql = "INSERT INTO audit_logs (user_id, action, document_id, target_user_id, action_date, ip_address, details) 
                    VALUES (:user_id, :action, :document_id, :target_user_id, NOW(), :ip_address, :details)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':action', $action, PDO::PARAM_STR);
            $stmt->bindParam(':document_id', $documentId, PDO::PARAM_INT);
            $stmt->bindParam(':target_user_id', $targetUserId, PDO::PARAM_INT);
            $stmt->bindParam(':ip_address', $ipAddress, PDO::PARAM_STR);
            $stmt->bindParam(':details', $details, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'enregistrement de l'audit: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère tous les logs d'audit avec les informations des utilisateurs
     */
    public function getAllAuditLogs($limit = 100, $offset = 0)
    {
        try {
            $sql = "SELECT al.*, u.nom, u.prenom, u.email, u.role,
                           d.titre as document_titre, d.type as document_type,
                           tu.nom as target_nom, tu.prenom as target_prenom
                    FROM audit_logs al
                    LEFT JOIN users u ON al.user_id = u.id
                    LEFT JOIN documents d ON al.document_id = d.id
                    LEFT JOIN users tu ON al.target_user_id = tu.id
                    ORDER BY al.action_date DESC
                    LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des logs d'audit: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère les logs d'audit filtrés
     */
    public function getFilteredAuditLogs($filters = [], $limit = 100, $offset = 0)
    {
        try {
            $whereConditions = [];
            $params = [];

            // Filtre par utilisateur
            if (!empty($filters['user_id'])) {
                $whereConditions[] = "al.user_id = :user_id";
                $params[':user_id'] = $filters['user_id'];
            }

            // Filtre par action
            if (!empty($filters['action'])) {
                $whereConditions[] = "al.action = :action";
                $params[':action'] = $filters['action'];
            }

            // Filtre par recherche de détails
            if (!empty($filters['details_search'])) {
                $whereConditions[] = "al.details LIKE :details_search";
                $params[':details_search'] = '%' . $filters['details_search'] . '%';
            }

            // Filtre par date de début
            if (!empty($filters['date_from'])) {
                $whereConditions[] = "al.action_date >= :date_from";
                $params[':date_from'] = $filters['date_from'];
            }

            // Filtre par date de fin
            if (!empty($filters['date_to'])) {
                $whereConditions[] = "al.action_date <= :date_to";
                $params[':date_to'] = $filters['date_to'];
            }

            $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

            $sql = "SELECT al.*, u.nom, u.prenom, u.email, u.role,
                           d.titre as document_titre, d.type as document_type,
                           tu.nom as target_nom, tu.prenom as target_prenom
                    FROM audit_logs al
                    LEFT JOIN users u ON al.user_id = u.id
                    LEFT JOIN documents d ON al.document_id = d.id
                    LEFT JOIN users tu ON al.target_user_id = tu.id
                    $whereClause
                    ORDER BY al.action_date DESC
                    LIMIT :limit OFFSET :offset";

            $stmt = $this->db->prepare($sql);
            
            // Bind des paramètres de filtres
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des logs d'audit filtrés: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Compte le nombre total de logs
     */
    public function countAuditLogs($filters = [])
    {
        try {
            $whereConditions = [];
            $params = [];

            if (!empty($filters['user_id'])) {
                $whereConditions[] = "user_id = :user_id";
                $params[':user_id'] = $filters['user_id'];
            }

            if (!empty($filters['action'])) {
                $whereConditions[] = "action = :action";
                $params[':action'] = $filters['action'];
            }

            if (!empty($filters['details_search'])) {
                $whereConditions[] = "details LIKE :details_search";
                $params[':details_search'] = '%' . $filters['details_search'] . '%';
            }

            if (!empty($filters['date_from'])) {
                $whereConditions[] = "action_date >= :date_from";
                $params[':date_from'] = $filters['date_from'];
            }

            if (!empty($filters['date_to'])) {
                $whereConditions[] = "action_date <= :date_to";
                $params[':date_to'] = $filters['date_to'];
            }

            $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

            $sql = "SELECT COUNT(*) as total FROM audit_logs $whereClause";
            $stmt = $this->db->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Erreur lors du comptage des logs d'audit: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Récupère l'historique des actions pour un document spécifique
     */
    public function getDocumentAuditHistory($documentId, $limit = 50, $offset = 0)
    {
        try {
            $sql = "SELECT al.*, u.nom, u.prenom, u.email, u.role,
                           d.titre as document_titre, d.type as document_type
                    FROM audit_logs al
                    LEFT JOIN users u ON al.user_id = u.id
                    LEFT JOIN documents d ON al.document_id = d.id
                    WHERE al.document_id = :document_id
                    ORDER BY al.action_date DESC
                    LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':document_id', $documentId, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'historique du document: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Compte le nombre d'actions pour un document spécifique
     */
    public function countDocumentAuditLogs($documentId)
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM audit_logs WHERE document_id = :document_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':document_id', $documentId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Erreur lors du comptage de l'historique du document: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Récupère les statistiques d'audit
     */
    public function getAuditStats()
    {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_actions,
                        COUNT(DISTINCT user_id) as unique_users,
                        COUNT(CASE WHEN action_date >= DATE_SUB(NOW(), INTERVAL 24 HOUR) THEN 1 END) as actions_today,
                        COUNT(CASE WHEN action_date >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 1 END) as actions_week
                    FROM audit_logs";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des statistiques d'audit: " . $e->getMessage());
            return [];
        }
    }
}
?>
