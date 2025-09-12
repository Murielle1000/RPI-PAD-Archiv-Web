<?php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: login.php');
    exit;
}
if ($_SESSION['role'] !== 'admin') {
    header('Location: profil.php');
    exit;
}

// Récupérer l'ID du document depuis l'URL
$documentId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($documentId <= 0) {
    header('Location: admin.php?error=invalid_document');
    exit;
}

// Récupérer les informations du document
require_once("model/DocumentsRepository.php");
$docRepo = new DocumentsRepository();
$document = $docRepo->getById($documentId);

if (!$document) {
    header('Location: admin.php?error=document_not_found');
    exit;
}

// Récupérer l'historique des actions
require_once("model/AuditRepository.php");
$auditRepo = new AuditRepository();

$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;

$history = $auditRepo->getDocumentAuditHistory($documentId, $perPage, $offset);
$total = $auditRepo->countDocumentAuditLogs($documentId);
$pages = ceil($total / $perPage);
?>
<!DOCTYPE html>
<html lang="en">

<?php require_once("view/sections/admin/head.php"); ?> 

<body id="page-top">

     <div id="wrapper">

        <!-- Sidebar -->
       <?php require_once("view/sections/admin/menu.php"); ?> 
        <!-- End of Sidebar -->

       <!-- Content Wrapper -->

        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">HISTORIQUE DU DOCUMENT</h1>
                        <a href="admin.php" class="d-none d-sm-inline-block btn btn-sm btn-rpi-secondary shadow-sm">
                            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour
                        </a>
                    </div>

                    <!-- Informations du document -->
                    <div class="card card-rpi shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold">Informations du Document</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Titre :</strong> <?= htmlspecialchars($document['titre']) ?></p>
                                    <p><strong>Type :</strong> <?= htmlspecialchars($document['type']) ?></p>
                                    <p><strong>Catégorie :</strong> <?= htmlspecialchars($document['categorie']) ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Description :</strong> <?= htmlspecialchars($document['description']) ?></p>
                                    <p><strong>Date d'ajout :</strong> <?= date('d/m/Y H:i:s', strtotime($document['add_date'])) ?></p>
                                    <p><strong>Total d'actions :</strong> <span class="badge badge-rpi-primary"><?= $total ?></span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Historique des actions -->
                    <div class="card card-rpi shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold">Historique des Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-rpi table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date/Heure</th>
                                            <th>Utilisateur</th>
                                            <th>Action</th>
                                            <th>Détails</th>
                                            <th>IP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($history) > 0): ?>
                                            <?php foreach ($history as $log): ?>
                                                <tr>
                                                    <td><?= date('d/m/Y H:i:s', strtotime($log['action_date'])) ?></td>
                                                    <td>
                                                        <div>
                                                            <strong><?= htmlspecialchars($log['nom'] . ' ' . $log['prenom']) ?></strong>
                                                            <br><small class="text-muted"><?= htmlspecialchars($log['email']) ?></small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-rpi-<?= getActionBadgeClass($log['action']) ?>">
                                                            <?= formatAction($log['action']) ?>
                                                        </span>
                                                    </td>
                                                    <td><?= htmlspecialchars($log['details']) ?></td>
                                                    <td><code><?= htmlspecialchars($log['ip_address']) ?></code></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center">Aucune action enregistrée pour ce document.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <?php if ($pages > 1): ?>
                            <nav>
                                <ul class="pagination pagination-rpi justify-content-center">
                                    <?php for ($i = 1; $i <= $pages; $i++): ?>
                                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                            <a class="page-link" href="?id=<?= $documentId ?>&page=<?= $i ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>© 2025 RPI-PAD Archiv'Web</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>

    <!-- Bootstrap core JavaScript-->
    <?php require_once("view/sections/admin/script.php"); ?> 

</body>

</html>

<?php
// Fonctions utilitaires pour l'affichage (réutilisées depuis historique.php)
function getActionBadgeClass($action) {
    switch ($action) {
        case 'CONNEXION':
            return 'accent-3';
        case 'DÉCONNEXION':
            return 'accent-2';
        case 'AJOUT_DOCUMENT':
            return 'primary';
        case 'AJOUT_UTILISATEUR':
            return 'accent-5';
        case 'MODIFICATION_DOCUMENT':
            return 'warning';
        case 'MODIFICATION_UTILISATEUR':
            return 'accent-4';
        case 'SUPPRESSION_DOCUMENT':
            return 'danger';
        case 'SUPPRESSION_UTILISATEUR':
            return 'accent-7';
        case 'CONSULTATION_DOCUMENT':
            return 'info';
        case 'CHANGEMENT_STATUT_UTILISATEUR':
            return 'accent-6';
        default:
            return 'secondary';
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
