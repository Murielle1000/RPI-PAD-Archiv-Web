<?php

// require_once __DIR__ . '/DBRepository.php';
require_once __DIR__ . '/../model/DBRepository.php';

class UsersRepository
{
    private $db;

    public function __construct()
    {
        $dbRepo = new DBRepository();
        $this->db = $dbRepo->getConnection();
    }

    /**
     * Récupère tous les utilisateurs actifs
     */
    public function getAllUsers()
    {
        try {
            $sql = "SELECT id, nom, prenom, email, role, statut, add_date FROM users WHERE statut = 'actif' ORDER BY add_date DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des utilisateurs: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère un utilisateur par son ID
     */
    public function getUserById($id)
    {
        try {
            $sql = "SELECT * FROM users WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Met à jour un utilisateur
     */
    public function updateUser($id, $nom, $prenom, $email, $role, $statut = 'actif')
    {
        try {
            $sql = "UPDATE users SET nom = :nom, prenom = :prenom, email = :email, role = :role, statut = :statut WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);
            $stmt->bindParam(':statut', $statut, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Bloque ou débloque un utilisateur
     */
    public function toggleUserStatus($id, $statut)
    {
        try {
            $sql = "UPDATE users SET statut = :statut WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':statut', $statut, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors du changement de statut de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime un utilisateur (soft delete - marque comme supprimé)
     */
    public function deleteUser($id)
    {
        try {
            $sql = "UPDATE users SET statut = 'supprimé', delete_at = NOW() WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Suppression définitive d'un utilisateur (pour la corbeille)
     */
    public function permanentDeleteUser($id)
    {
        try {
            $sql = "DELETE FROM users WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression définitive de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Restaure un utilisateur supprimé
     */
    public function restoreUser($id)
    {
        try {
            $sql = "UPDATE users SET statut = 'actif', delete_at = NULL WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la restauration de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère les utilisateurs supprimés
     */
    public function getDeletedUsers()
    {
        try {
            $sql = "SELECT id, nom, prenom, email, role, statut, add_date, delete_at FROM users WHERE statut = 'supprimé' ORDER BY delete_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des utilisateurs supprimés: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Recherche des utilisateurs
     */
    public function searchUsers($search)
    {
        try {
            $sql = "SELECT id, nom, prenom, email, role, statut, add_date FROM users 
                    WHERE nom LIKE :search OR prenom LIKE :search OR email LIKE :search 
                    ORDER BY add_date DESC";
            $stmt = $this->db->prepare($sql);
            $searchTerm = "%$search%";
            $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la recherche d'utilisateurs: " . $e->getMessage());
            return [];
        }
    }
}
?>
