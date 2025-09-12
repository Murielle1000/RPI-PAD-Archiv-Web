<?php

    class DBRepository
    {
        private $pdo;
        public $errorMessage = null;

        public function __construct()
        {
            $host = 'localhost';
            $dbname = 'archiveweb';
            $username = 'root';
            $password = '';

            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

            try {
                $this->pdo = new PDO($dsn, $username, $password);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $error) {
                $this->errorMessage = "Erreur de connexion à la base de données : " . $error->getMessage();
                $this->pdo = null;
            }
        }

        public function getConnection()
        {
            return $this->pdo;
        }
    }
?>