<?php
/**
 * Script pour initialiser des logs d'audit de test
 * À exécuter une seule fois pour peupler la table audit_logs
 */

require_once 'controller/AuditHelper.php';

$auditHelper = new AuditHelper();

// Logs de test
$testLogs = [
    ['action' => 'CONNEXION', 'details' => 'Connexion de l\'utilisateur: admin@rpi.com', 'ip' => '192.168.1.100'],
    ['action' => 'AJOUT_DOCUMENT', 'details' => 'Ajout du document \'Loi sur la protection des données\' (Type: lois)', 'ip' => '192.168.1.100'],
    ['action' => 'MODIFICATION_DOCUMENT', 'details' => 'Modification du document \'Décret présidentiel 2024\' (Type: décrets)', 'ip' => '192.168.1.100'],
    ['action' => 'CONSULTATION_DOCUMENT', 'details' => 'Consultation du document \'Arrêté ministériel\' (Type: arrêtés)', 'ip' => '192.168.1.100'],
    ['action' => 'AJOUT_UTILISATEUR', 'details' => 'Ajout de l\'utilisateur: user@example.com', 'ip' => '192.168.1.100'],
    ['action' => 'MODIFICATION_UTILISATEUR', 'details' => 'Modification de l\'utilisateur: user@example.com', 'ip' => '192.168.1.100'],
    ['action' => 'CHANGEMENT_STATUT_UTILISATEUR', 'details' => 'Changement de statut de l\'utilisateur: user@example.com (Nouveau statut: bloque)', 'ip' => '192.168.1.100'],
    ['action' => 'DÉCONNEXION', 'details' => 'Déconnexion de l\'utilisateur: admin@rpi.com', 'ip' => '192.168.1.100'],
];

echo "Initialisation des logs d'audit...\n";

// Vérifier si l'utilisateur ID 1 existe, sinon utiliser l'ID 2
$userId = 1;
require_once 'model/UsersRepository.php';
$usersRepo = new UsersRepository();
$user = $usersRepo->getUserById($userId);
if (!$user) {
    $userId = 2;
    $user = $usersRepo->getUserById($userId);
    if (!$user) {
        echo "✗ Aucun utilisateur trouvé pour créer les logs de test.\n";
        exit;
    }
}

echo "Utilisation de l'utilisateur ID: $userId\n";

foreach ($testLogs as $log) {
    $result = $auditHelper->logCustomAction($userId, $log['action'], $log['details'], null, null);
    if ($result) {
        echo "✓ Log ajouté: " . $log['action'] . "\n";
    } else {
        echo "✗ Erreur lors de l'ajout du log: " . $log['action'] . "\n";
    }
}

echo "Initialisation terminée !\n";
echo "Vous pouvez maintenant accéder à la page d'historique.\n";
?>
