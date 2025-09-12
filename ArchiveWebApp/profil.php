<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once 'model/UtilisateursRepository.php';
require_once 'model/AuditRepository.php';
$usersRepo = new UsersRepository();
$auditRepo = new AuditRepository();
$user = $usersRepo->getUserById($_SESSION['user_id']);
$logs = $auditRepo->getFilteredAuditLogs(['user_id' => $_SESSION['user_id']], 50, 0);
?>
<!DOCTYPE html>
<html lang="fr">
<?php require_once("view/sections/admin/head.php"); ?>
<body id="page-top">
<div id="wrapper">
    <?php require_once("view/sections/admin/menu.php"); ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Mon Profil</h1>
                <div class="row">
                    <div class="col-lg-5 mb-4">
                        <div class="card card-rpi shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold">Mes informations</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Nom :</strong> <?= htmlspecialchars($user['nom']) ?></p>
                                <p><strong>Prénom :</strong> <?= htmlspecialchars($user['prenom']) ?></p>
                                <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
                                <p><strong>Rôle :</strong> <span class="badge badge-rpi-<?= $user['role'] === 'admin' ? 'accent-2' : 'primary' ?>"><?= ucfirst($user['role']) ?></span></p>
                                <p><strong>Statut :</strong> <span class="badge badge-rpi-<?= $user['statut'] === 'actif' ? 'accent-3' : 'accent-4' ?>"><?= ucfirst($user['statut']) ?></span></p>
                                <p><strong>Date de création :</strong> <?= date('d/m/Y H:i', strtotime($user['add_date'])) ?></p>
                                <a href="#" class="btn btn-rpi-secondary mt-2"><i class="fas fa-edit mr-1"></i>Modifier mon profil</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 mb-4">
                        <div class="card card-rpi shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold">Mon historique d'actions</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-rpi table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Date/Heure</th>
                                                <th>Action</th>
                                                <th>Détails</th>
                                                <th>IP</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($logs) > 0): ?>
                                                <?php foreach ($logs as $log): ?>
                                                    <tr>
                                                        <td><?= date('d/m/Y H:i:s', strtotime($log['action_date'])) ?></td>
                                                        <td><span class="badge badge-rpi-<?= getActionBadgeClass($log['action']) ?>"><?= formatAction($log['action']) ?></span></td>
                                                        <td><?= htmlspecialchars($log['details']) ?></td>
                                                        <td><code><?= htmlspecialchars($log['ip_address']) ?></code></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr><td colspan="4" class="text-center">Aucune action trouvée.</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                        <span>© 2025 RPI-PAD Archiv'Web</span>
                </div>
            </div>
        </footer>
    </div>
</div>
<?php
function getActionBadgeClass($action) {
    switch ($action) {
        case 'CONNEXION': return 'accent-3';
        case 'DÉCONNEXION': return 'accent-2';
        case 'AJOUT_DOCUMENT': return 'primary';
        case 'AJOUT_UTILISATEUR': return 'accent-5';
        case 'MODIFICATION_DOCUMENT': return 'warning';
        case 'MODIFICATION_UTILISATEUR': return 'accent-4';
        case 'SUPPRESSION_DOCUMENT': return 'danger';
        case 'SUPPRESSION_UTILISATEUR': return 'accent-7';
        case 'CONSULTATION_DOCUMENT': return 'info';
        case 'CHANGEMENT_STATUT_UTILISATEUR': return 'accent-6';
        default: return 'secondary';
    }
}
function formatAction($action) {
    $actions = [
        'CONNEXION' => 'Connexion',
        'DÉCONNEXION' => 'Déconnexion',
        'AJOUT_DOCUMENT' => 'Ajout Document',
        'MODIFICATION_DOCUMENT' => 'Modification Document',
        'SUPPRESSION_DOCUMENT' => 'Suppression Document',
        'AJOUT_UTILISATEUR' => 'Ajout Utilisateur',
        'MODIFICATION_UTILISATEUR' => 'Modification Utilisateur',
        'SUPPRESSION_UTILISATEUR' => 'Suppression Utilisateur',
        'CHANGEMENT_STATUT_UTILISATEUR' => 'Changement Statut',
        'CONSULTATION_DOCUMENT' => 'Consultation Document'
    ];
    return $actions[$action] ?? $action;
}
?>
</body>
</html>
