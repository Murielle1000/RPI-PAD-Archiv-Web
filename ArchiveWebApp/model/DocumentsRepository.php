<?php
    require_once("DBRepository.php");
    class DocumentsRepository extends DBRepository 
    {
        private $pdo;

        public function __construct()
        {
            $db = new DBRepository();
            if ($db->errorMessage) {
                echo $db->errorMessage;
            } else {
                $this->pdo = $db->getConnection();
            }
        }

        // Méthodes pour gérer les documents (ajouter, supprimer, modifier, récupérer, etc.)

        // récupérer la liste des documents
        public function getAll() :array
        {
            $documents = [];
            if ($this->pdo) {
                $sql = "SELECT * FROM documents";
                try {
                    $stmt = $this->pdo->query($sql);
                    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $error) {
                    error_log("Une erreur est subvenue lors de la récupération");
                    throw $error;
                }
            }
            return $documents;
        }

        // Récupérer les documents selon le type
        public function getByType(string $type): array
        {
            $documents = [];
            if ($this->pdo) {
                $sql = "SELECT * FROM documents WHERE type = :type";
                try {
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute(['type' => $type]);
                    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $error) {
                    error_log("Erreur lors de la récupération par type");
                }
            }
            return $documents;
        }

        // Récupérer les documents selon la catégorie
        public function getByCategorie(string $categorie): array
        {
            $documents = [];
            if ($this->pdo) {
                $sql = "SELECT * FROM documents WHERE categorie = :categorie";
                try {
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute(['categorie' => $categorie]);
                    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $error) {
                    error_log("Erreur lors de la récupération par catégorie");
                }
            }
            return $documents;
        }
        // Récupérer les documents selon le type ET la catégorie
        public function getByTypeAndCategorie(string $type, string $categorie): array
        {
            $documents = [];
            if ($this->pdo) {
                $sql = "SELECT * FROM documents WHERE type = :type AND categorie = :categorie";
                try {
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([
                        'type' => $type,
                        'categorie' => $categorie
                    ]);
                    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $error) {
                    error_log("Erreur lors de la récupération par type et catégorie");
                }
            }
            return $documents;
        }

        
       
       
        public function addDocument(string $titre, string $type, string $categorie, string $url_fichier, string $description): bool
        {
            if ($this->pdo) {
                $sql = "INSERT INTO documents (titre, type, categorie, url_fichier, description, add_date)
                        VALUES (:titre, :type, :categorie, :url_fichier, :description, NOW())";
                try {
                    $stmt = $this->pdo->prepare($sql);
                    $result = $stmt->execute([
                        'titre' => $titre,
                        'type' => $type,
                        'categorie'=> $categorie,
                        'url_fichier' => $url_fichier,
                        'description' => $description
                    ]);
                    if ($result) {
                        $this->lastInsertId = $this->pdo->lastInsertId();
                    }
                    return $result;
                } catch (PDOException $error) {
                    echo "<div style='color:red'>Erreur PDO : " . $error->getMessage() . "</div>";
                }
            }
            return false;
        }

        private $lastInsertId;

        public function getLastInsertId(): int
        {
            return $this->lastInsertId ?? 0;
        }

    
    
    
        public function deleteDocument(int $id): bool
        {
            if ($this->pdo) {
                $sql = "DELETE FROM documents WHERE id = :id";
                try {
                    $stmt = $this->pdo->prepare($sql);
                    return $stmt->execute(['id' => $id]);
                } catch (PDOException $error) {
                    error_log("Erreur lors de la suppression : " . $error->getMessage());
                }
            }
            return false;
        }
    
    
   
        public function updateDocument(int $id, string $titre, string $type, string $categorie, string $description): bool
        {
            if ($this->pdo) {
                $sql = "UPDATE documents SET titre = :titre, type = :type, categorie = :categorie, description = :description WHERE id = :id";
                try {
                    $stmt = $this->pdo->prepare($sql);
                    return $stmt->execute([
                        'titre' => $titre,
                        'type' => $type,
                        'categorie' => $categorie,
                        'description' => $description,
                        'id' => $id
                    ]);
                } catch (PDOException $error) {
                    error_log("Erreur lors de la modification : " . $error->getMessage());
                }
            }
            return false;
        }

        public function getById(int $id)
        {
            if ($this->pdo) {
                $sql = "SELECT * FROM documents WHERE id = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(['id' => $id]);
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
            return null;
        }
    }    

?>