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
                     <h1 class="h3 mb-2 text-gray-800">HISTORIQUE DES ACTIONS</h1>
                     
                     <!-- Messages de notification -->
                     <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php
                            switch($_GET['success']) {
                                case 'audit_cleared':
                                    echo 'Historique effacé avec succès !';
                                    break;
                                default:
                                    echo 'Action effectuée avec succès !';
                            }
                            ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                     <?php endif; ?>

                    <?php
                        require_once("model/AuditRepository.php");
                        $auditRepo = new AuditRepository();
                        
                        // Récupération des filtres
                        $search = $_GET['search'] ?? '';
                        $actionFilter = $_GET['action'] ?? '';
                        $userFilter = $_GET['user'] ?? '';
                        $dateFrom = $_GET['date_from'] ?? '';
                        $dateTo = $_GET['date_to'] ?? '';
                        $page = max(1, intval($_GET['page'] ?? 1));
                        $perPage = 50; // Augmenter le nombre d'éléments par page

                        // Préparation des filtres
                        $filters = [];
                        if ($search) $filters['details_search'] = $search; // Apply general search
                        if ($actionFilter) $filters['action'] = $actionFilter; // Specific action filter
                        if ($userFilter) $filters['user_id'] = $userFilter;
                        if ($dateFrom) $filters['date_from'] = $dateFrom . ' 00:00:00';
                        if ($dateTo) $filters['date_to'] = $dateTo . ' 23:59:59';

                        // Récupération des logs
                        $logs = $auditRepo->getFilteredAuditLogs($filters, $perPage, ($page - 1) * $perPage);
                        $total = $auditRepo->countAuditLogs($filters);
                        $pages = ceil($total / $perPage);
                        
                        // Debug temporaire pour vérifier le filtrage
                        if (isset($_GET['debug']) && $_GET['debug'] === '1') {
                            echo "<div class='alert alert-info'>";
                            echo "<strong>Debug - Filtres appliqués:</strong><br>";
                            echo "Recherche: " . ($search ?: 'Aucune') . "<br>";
                            echo "Action: " . ($actionFilter ?: 'Aucune') . "<br>";
                            echo "Utilisateur: " . ($userFilter ?: 'Aucun') . "<br>";
                            echo "Date début: " . ($dateFrom ?: 'Aucune') . "<br>";
                            echo "Date fin: " . ($dateTo ?: 'Aucune') . "<br>";
                            echo "Total résultats: " . $total . "<br>";
                            
                            // Debug: vérifier les actions d'utilisateurs
                            $userActions = $auditRepo->getFilteredAuditLogs(['action' => 'AJOUT_UTILISATEUR'], 10, 0);
                            echo "<br><strong>Actions d'ajout d'utilisateurs récentes:</strong><br>";
                            foreach ($userActions as $action) {
                                echo "- " . $action['action'] . " par utilisateur " . $action['user_id'] . " le " . $action['timestamp'] . "<br>";
                            }
                            echo "</div>";
                        }

                        // Récupération des utilisateurs pour le filtre
                        require_once("model/UtilisateursRepository.php");
                        $usersRepo = new UsersRepository();
                        $users = $usersRepo->getAllUsers();

                        // Récupération des statistiques
                        $stats = $auditRepo->getAuditStats();
                    ?>

                    <!-- Statistiques -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card-rpi border-left-primary h-100 py-2 fade-in-up">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="stat-label">Total Actions</div>
                                            <div class="stat-number"><?= $stats['total_actions'] ?? 0 ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-history fa-2x stat-icon primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card-rpi border-left-success h-100 py-2 fade-in-up" style="animation-delay: 0.1s;">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="stat-label">Utilisateurs Actifs</div>
                                            <div class="stat-number"><?= $stats['unique_users'] ?? 0 ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x stat-icon success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card-rpi border-left-info h-100 py-2 fade-in-up" style="animation-delay: 0.2s;">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="stat-label">Aujourd'hui</div>
                                            <div class="stat-number"><?= $stats['actions_today'] ?? 0 ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar-day fa-2x stat-icon info"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card-rpi border-left-accent h-100 py-2 fade-in-up" style="animation-delay: 0.3s;">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="stat-label">Cette Semaine</div>
                                            <div class="stat-number"><?= $stats['actions_week'] ?? 0 ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar-week fa-2x stat-icon accent"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtres et tableau -->
                    <div class="card card-rpi shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold">Journal des Activités</h6>
                            <form method="get" class="form-inline mt-2" id="filterForm">
                                <div class="input-group mr-2">
                                    <input type="text" name="search" class="form-control form-control-rpi" placeholder="Rechercher par action..." value="<?= htmlspecialchars($search) ?>">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-rpi-secondary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <select name="action" class="form-control form-control-rpi-dark mr-2" onchange="document.getElementById('filterForm').submit();">
                                    <option value="">Toutes les actions</option>
                                    <optgroup label="Connexions">
                                        <option value="CONNEXION" <?= $actionFilter=='CONNEXION'?'selected':''; ?>>Connexions</option>
                                        <option value="DÉCONNEXION" <?= $actionFilter=='DÉCONNEXION'?'selected':''; ?>>Déconnexions</option>
                                    </optgroup>
                                    <optgroup label="Documents">
                                        <option value="AJOUT_DOCUMENT" <?= $actionFilter=='AJOUT_DOCUMENT'?'selected':''; ?>>Ajouts de documents</option>
                                        <option value="MODIFICATION_DOCUMENT" <?= $actionFilter=='MODIFICATION_DOCUMENT'?'selected':''; ?>>Modifications de documents</option>
                                        <option value="SUPPRESSION_DOCUMENT" <?= $actionFilter=='SUPPRESSION_DOCUMENT'?'selected':''; ?>>Suppressions de documents</option>
                                        <option value="CONSULTATION_DOCUMENT" <?= $actionFilter=='CONSULTATION_DOCUMENT'?'selected':''; ?>>Consultations de documents</option>
                                    </optgroup>
                                    <optgroup label="Utilisateurs">
                                        <option value="AJOUT_UTILISATEUR" <?= $actionFilter=='AJOUT_UTILISATEUR'?'selected':''; ?>>Ajouts d'utilisateurs</option>
                                        <option value="MODIFICATION_UTILISATEUR" <?= $actionFilter=='MODIFICATION_UTILISATEUR'?'selected':''; ?>>Modifications d'utilisateurs</option>
                                        <option value="SUPPRESSION_UTILISATEUR" <?= $actionFilter=='SUPPRESSION_UTILISATEUR'?'selected':''; ?>>Suppressions d'utilisateurs</option>
                                        <option value="CHANGEMENT_STATUT_UTILISATEUR" <?= $actionFilter=='CHANGEMENT_STATUT_UTILISATEUR'?'selected':''; ?>>Changements de statut</option>
                                    </optgroup>
                                    <optgroup label="Corbeille">
                                        <option value="RESTAURATION_DOCUMENT" <?= $actionFilter=='RESTAURATION_DOCUMENT'?'selected':''; ?>>Restaurations de documents</option>
                                        <option value="SUPPRESSION_DÉFINITIVE_DOCUMENT" <?= $actionFilter=='SUPPRESSION_DÉFINITIVE_DOCUMENT'?'selected':''; ?>>Suppressions définitives</option>
                                    </optgroup>
                                </select>
                                <select name="user" class="form-control form-control-rpi-dark mr-2" onchange="document.getElementById('filterForm').submit();">
                                    <option value="">Tous les utilisateurs</option>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?= $user['id'] ?>" <?= $userFilter == $user['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($user['nom'] . ' ' . $user['prenom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="date" name="date_from" class="form-control form-control-rpi-dark mr-2" value="<?= htmlspecialchars($dateFrom) ?>" placeholder="Date de début">
                                <input type="date" name="date_to" class="form-control form-control-rpi-dark mr-2" value="<?= htmlspecialchars($dateTo) ?>" placeholder="Date de fin">
                                <button type="submit" class="btn btn-rpi-primary mr-2">
                                    <i class="fas fa-filter"></i> Filtrer
                                </button>
                                <a href="historique.php" class="btn btn-rpi-secondary">
                                    <i class="fas fa-times"></i> Effacer
                                </a>
                                <button type="button" class="btn btn-rpi-success" onclick="exportHistory()">
                                    <i class="fas fa-download"></i> Exporter
                                </button>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-rpi table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="15%">Date/Heure</th>
                                            <th width="20%">Utilisateur</th>
                                            <th width="15%">Action</th>
                                            <th width="20%">Document/Utilisateur Ciblé</th>
                                            <th width="25%">Détails</th>
                                            <th width="5%">IP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($logs) > 0): ?>
                                            <?php foreach ($logs as $log): ?>
                                                <tr>
                                                    <td>
                                                        <small><?= date('d/m/Y', strtotime($log['action_date'])) ?></small><br>
                                                        <strong><?= date('H:i:s', strtotime($log['action_date'])) ?></strong>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <strong><?= htmlspecialchars($log['nom'] . ' ' . $log['prenom']) ?></strong>
                                                            <br><small class="text-muted"><?= htmlspecialchars($log['email']) ?></small>
                                                            <br><small class="badge badge-secondary"><?= htmlspecialchars($log['role']) ?></small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-rpi-<?= getActionBadgeClass($log['action']) ?>">
                                                            <?= formatAction($log['action']) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if ($log['document_titre']): ?>
                                                            <div>
                                                                <strong><?= htmlspecialchars($log['document_titre']) ?></strong>
                                                                <br><small class="text-muted"><?= htmlspecialchars($log['document_type']) ?></small>
                                                            </div>
                                                        <?php elseif ($log['target_nom']): ?>
                                                            <div>
                                                                <strong><?= htmlspecialchars($log['target_nom'] . ' ' . $log['target_prenom']) ?></strong>
                                                                <br><small class="text-muted">Utilisateur ciblé</small>
                                                            </div>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <small><?= htmlspecialchars($log['details']) ?></small>
                                                    </td>
                                                    <td>
                                                        <small><code><?= htmlspecialchars($log['ip_address']) ?></code></small>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center">Aucun log trouvé.</td>
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
                                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
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

    <script>
    function exportHistory() {
        // Créer un tableau CSV avec les données actuelles
        let csvContent = "Date/Heure,Utilisateur,Email,Rôle,Action,Document/Utilisateur Ciblé,Détails,IP\n";
        
        // Récupérer les données du tableau
        const table = document.querySelector('.table tbody');
        const rows = table.querySelectorAll('tr');
        
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length >= 6) {
                const date = cells[0].textContent.trim();
                const user = cells[1].querySelector('strong').textContent.trim();
                const email = cells[1].querySelector('.text-muted').textContent.trim();
                const role = cells[1].querySelector('.badge').textContent.trim();
                const action = cells[2].textContent.trim();
                const target = cells[3].querySelector('strong') ? cells[3].querySelector('strong').textContent.trim() : '-';
                const details = cells[4].textContent.trim();
                const ip = cells[5].textContent.trim();
                
                csvContent += `"${date}","${user}","${email}","${role}","${action}","${target}","${details}","${ip}"\n`;
            }
        });
        
        // Télécharger le fichier CSV
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', 'historique_actions_' + new Date().toISOString().split('T')[0] + '.csv');
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
    </script>

</body>

</html>

<?php
// Fonctions utilitaires pour l'affichage
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
        case 'RESTAURATION_DOCUMENT':
            return 'success';
        case 'SUPPRESSION_DÉFINITIVE_DOCUMENT':
            return 'danger';
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
        'CONSULTATION_DOCUMENT' => 'Consultation Document',
        'RESTAURATION_DOCUMENT' => 'Restauration Document',
        'SUPPRESSION_DÉFINITIVE_DOCUMENT' => 'Suppression Définitive'
    ];
    
    return $actions[$action] ?? $action;
}
?>
