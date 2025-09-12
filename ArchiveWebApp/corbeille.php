<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
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
                    <h1 class="h3 mb-2 text-gray-800">CORBEILLE</h1>
                    <p class="mb-4">Documents supprimés - Possibilité de restauration</p>
                    
                    <!-- Information pour les administrateurs -->
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle"></i>
                        <strong>Accès administrateur :</strong> Cette section est réservée aux administrateurs. 
                        Seuls les utilisateurs avec le rôle "admin" peuvent gérer la corbeille.
                    </div>

                    <!-- Messages de succès/erreur -->
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php if ($_GET['success'] === 'restored'): ?>
                                <i class="fas fa-check-circle"></i> Document "<?= htmlspecialchars($_GET['title'] ?? '') ?>" restauré avec succès !
                            <?php elseif ($_GET['success'] === 'permanently_deleted'): ?>
                                <i class="fas fa-trash"></i> Document "<?= htmlspecialchars($_GET['title'] ?? '') ?>" supprimé définitivement !
                            <?php endif; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php if ($_GET['error'] === 'restore_failed'): ?>
                                <i class="fas fa-exclamation-triangle"></i> Erreur lors de la restauration du document.
                            <?php elseif ($_GET['error'] === 'delete_failed'): ?>
                                <i class="fas fa-exclamation-triangle"></i> Erreur lors de la suppression définitive du document.
                            <?php elseif ($_GET['error'] === 'document_not_found'): ?>
                                <i class="fas fa-exclamation-triangle"></i> Document non trouvé.
                            <?php elseif ($_GET['error'] === 'invalid_id'): ?>
                                <i class="fas fa-exclamation-triangle"></i> ID de document invalide.
                            <?php endif; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php
                        require_once("model/DocumentsRepository.php");
                        $repo = new DocumentsRepository();

                        // Récupération des filtres
                        $search = $_GET['search'] ?? '';
                        $categorieFilter = $_GET['categorie'] ?? '';
                        $alphaOrder = $_GET['alpha_order'] ?? '';
                        $dateOrder = $_GET['date_order'] ?? 'desc';
                        $page = max(1, intval($_GET['page'] ?? 1));
                        $perPage = 10;

                        // Récupération des documents supprimés
                        $deletedDocuments = $repo->getDeletedDocuments();

                        // Recherche par titre
                        if ($search !== '') {
                            $deletedDocuments = array_filter($deletedDocuments, function($doc) use ($search) {
                                return stripos($doc['titre'], $search) !== false;
                            });
                        }

                        // Filtre catégorie
                        if ($categorieFilter) {
                            $deletedDocuments = array_filter($deletedDocuments, function($doc) use ($categorieFilter) {
                                return $doc['categorie'] === $categorieFilter;
                            });
                        }

                        // Tri alphabétique
                        if ($alphaOrder) {
                            usort($deletedDocuments, function($a, $b) use ($alphaOrder) {
                                if ($alphaOrder === 'asc') {
                                    return strcmp($a['titre'], $b['titre']);
                                } else {
                                    return strcmp($b['titre'], $a['titre']);
                                }
                            });
                        }

                        // Tri chronologique
                        if ($dateOrder) {
                            usort($deletedDocuments, function($a, $b) use ($dateOrder) {
                                if ($dateOrder === 'asc') {
                                    return strtotime($a['deleted_at']) - strtotime($b['deleted_at']);
                                } else {
                                    return strtotime($b['deleted_at']) - strtotime($a['deleted_at']);
                                }
                            });
                        }

                        // Pagination
                        $total = count($deletedDocuments);
                        $pages = ceil($total / $perPage);
                        $deletedDocuments = array_slice($deletedDocuments, ($page - 1) * $perPage, $perPage);
                    ?>

                    <!-- Filtres et recherche -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <form method="GET" class="d-flex">
                                <input type="text" class="form-control" name="search" placeholder="Rechercher par titre..." value="<?= htmlspecialchars($search) ?>">
                                <button type="submit" class="btn btn-primary ml-2">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form method="GET" class="d-flex">
                                <select name="categorie" class="form-control mr-2">
                                    <option value="">Toutes les catégories</option>
                                    <option value="présidentiel" <?= $categorieFilter === 'présidentiel' ? 'selected' : '' ?>>Présidence</option>
                                    <option value="ministériel" <?= $categorieFilter === 'ministériel' ? 'selected' : '' ?>>Ministère</option>
                                    <option value="gouvernement" <?= $categorieFilter === 'gouvernement' ? 'selected' : '' ?>>Gouvernement</option>
                                    <option value="Direction Générale" <?= $categorieFilter === 'Direction Générale' ? 'selected' : '' ?>>Direction Générale</option>
                                </select>
                                <button type="submit" class="btn btn-secondary">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Tri -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="btn-group" role="group">
                                <a href="?alpha_order=asc" class="btn btn-outline-primary btn-sm">A-Z</a>
                                <a href="?alpha_order=desc" class="btn btn-outline-primary btn-sm">Z-A</a>
                                <a href="?date_order=desc" class="btn btn-outline-secondary btn-sm">Plus récent</a>
                                <a href="?date_order=asc" class="btn btn-outline-secondary btn-sm">Plus ancien</a>
                            </div>
                        </div>
                    </div>

                    <!-- Liste des documents supprimés -->
                    <div class="row">
                        <?php if (empty($deletedDocuments)): ?>
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body text-center py-5">
                                        <i class="fas fa-trash fa-3x text-gray-300 mb-3"></i>
                                        <h5 class="text-gray-600">Corbeille vide</h5>
                                        <p class="text-muted">Aucun document supprimé pour le moment.</p>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php foreach ($deletedDocuments as $doc): ?>
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card border-left-danger h-100">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                        <?= htmlspecialchars($doc['type']) ?>
                                                    </div>
                                                    <div class="h6 mb-0 font-weight-bold text-gray-800">
                                                        <?= htmlspecialchars($doc['titre']) ?>
                                                    </div>
                                                    <div class="text-xs text-muted mb-1">
                                                        Catégorie: <?= htmlspecialchars($doc['categorie']) ?>
                                                    </div>
                                                    <div class="text-xs text-muted mb-1">
                                                        Supprimé le: <?= date('d/m/Y H:i', strtotime($doc['deleted_at'])) ?>
                                                    </div>
                                                    <?php if ($doc['deleted_by_name']): ?>
                                                        <div class="text-xs text-muted mb-2">
                                                            Par: <?= htmlspecialchars($doc['deleted_by_prenom'] . ' ' . $doc['deleted_by_name']) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if ($doc['description']): ?>
                                                        <div class="text-xs text-muted mb-2">
                                                            <?= htmlspecialchars(substr($doc['description'], 0, 100)) ?>
                                                            <?= strlen($doc['description']) > 100 ? '...' : '' ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <div class="btn-group w-100" role="group">
                                                    <a href="controller/restoreDocument.php?id=<?= $doc['id'] ?>" 
                                                       class="btn btn-success btn-sm" 
                                                       onclick="return confirm('Êtes-vous sûr de vouloir restaurer ce document ?')">
                                                        <i class="fas fa-undo"></i> Restaurer
                                                    </a>
                                                    <a href="controller/permanentDeleteDocument.php?id=<?= $doc['id'] ?>" 
                                                       class="btn btn-danger btn-sm" 
                                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer définitivement ce document ? Cette action est irréversible.')">
                                                        <i class="fas fa-trash"></i> Supprimer
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($pages > 1): ?>
                        <nav aria-label="Pagination">
                            <ul class="pagination justify-content-center">
                                <?php for ($i = 1; $i <= $pages; $i++): ?>
                                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&categorie=<?= urlencode($categorieFilter) ?>&alpha_order=<?= urlencode($alphaOrder) ?>&date_order=<?= urlencode($dateOrder) ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>

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
