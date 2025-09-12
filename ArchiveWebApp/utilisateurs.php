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
                     <h1 class="h3 mb-2 text-gray-800">GESTION DES UTILISATEURS</h1>
                     
                     <!-- Messages de notification -->
                     <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php
                            switch($_GET['success']) {
                                case 'user_added':
                                    echo 'Utilisateur ajouté avec succès !';
                                    break;
                                case 'user_updated':
                                    echo 'Utilisateur modifié avec succès !';
                                    break;
                                case 'user_deleted':
                                    echo 'Utilisateur supprimé avec succès !';
                                    break;
                                case 'user_blocked':
                                    echo 'Utilisateur bloqué avec succès !';
                                    break;
                                case 'user_unblocked':
                                    echo 'Utilisateur débloqué avec succès !';
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
                     
                     <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php
                            switch($_GET['error']) {
                                case 'missing_fields':
                                    echo 'Tous les champs sont obligatoires !';
                                    break;
                                case 'invalid_email':
                                    echo 'Adresse email invalide !';
                                    break;
                                case 'email_exists':
                                    echo 'Cette adresse email existe déjà !';
                                    break;
                                case 'add_failed':
                                    echo 'Erreur lors de l\'ajout de l\'utilisateur !';
                                    break;
                                case 'update_failed':
                                    echo 'Erreur lors de la modification de l\'utilisateur !';
                                    break;
                                case 'delete_failed':
                                    echo 'Erreur lors de la suppression de l\'utilisateur !';
                                    break;
                                case 'status_update_failed':
                                    echo 'Erreur lors du changement de statut !';
                                    break;
                                case 'database_error':
                                    echo 'Erreur de base de données !';
                                    if (isset($_GET['debug'])) {
                                        echo '<br><small>Détails: ' . htmlspecialchars($_GET['debug']) . '</small>';
                                    }
                                    break;
                                case 'missing_id':
                                    echo 'ID utilisateur manquant !';
                                    break;
                                case 'user_not_found':
                                    echo 'Utilisateur non trouvé !';
                                    break;
                                case 'cannot_delete_self':
                                    echo 'Vous ne pouvez pas supprimer votre propre compte !';
                                    break;
                                case 'access_denied':
                                    echo 'Accès refusé ! Cette action nécessite des droits administrateur.';
                                    break;
                                default:
                                    echo 'Une erreur est survenue !';
                            }
                            ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                     <?php endif; ?>

                    <?php
                        require_once("controller/UtilisateursController.php");
                        $controller = new UsersController();
                        $data = $controller->index();
                        $users = $data['users'];
                        $total = $data['total'];
                        $pages = $data['pages'];
                        $currentPage = $data['currentPage'];
                        $filters = $data['filters'];
                    ?>

                    <div class="card card-rpi shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold">Liste des Utilisateurs</h6>
                            <form method="get" class="form-inline mt-2" id="filterForm">
                                <div class="input-group mr-2">
                                    <input type="text" name="search" class="form-control form-control-rpi" placeholder="Rechercher par nom, prénom ou email..." value="<?= htmlspecialchars($filters['search']) ?>">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-rpi-secondary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <select name="role" class="form-control form-control-rpi-dark mr-2" onchange="document.getElementById('filterForm').submit();">
                                    <option value="">Tous les rôles</option>
                                    <option value="admin" <?= $filters['role']=='admin'?'selected':''; ?>>Administrateur</option>
                                    <option value="user" <?= $filters['role']=='user'?'selected':''; ?>>Utilisateur</option>
                                </select>
                                <select name="statut" class="form-control form-control-rpi-dark mr-2" onchange="document.getElementById('filterForm').submit();">
                                    <option value="">Tous les statuts</option>
                                    <option value="actif" <?= $filters['statut']=='actif'?'selected':''; ?>>Actif</option>
                                    <option value="bloque" <?= $filters['statut']=='bloque'?'selected':''; ?>>Bloqué</option>
                                </select>
                                <select name="alpha_order" class="form-control form-control-rpi-dark mr-2" onchange="document.getElementById('filterForm').submit();">
                                    <option value="">Ordre alphabétique</option>
                                    <option value="asc" <?= $filters['alpha_order']=='asc'?'selected':''; ?>>A → Z</option>
                                    <option value="desc" <?= $filters['alpha_order']=='desc'?'selected':''; ?>>Z → A</option>
                                </select>
                                <select name="date_order" class="form-control form-control-rpi-dark mr-2" onchange="document.getElementById('filterForm').submit();">
                                    <option value="desc" <?= $filters['date_order']=='desc'?'selected':''; ?>>Plus récents</option>
                                    <option value="asc" <?= $filters['date_order']=='asc'?'selected':''; ?>>Plus anciens</option>
                                </select>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-rpi table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Prénom</th>
                                            <th>Email</th>
                                            <th>Rôle</th>
                                            <th>Statut</th>
                                            <th>Date de création</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($users) > 0): ?>
                                            <?php foreach ($users as $user): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($user['nom']) ?></td>
                                                    <td><?= htmlspecialchars($user['prenom']) ?></td>
                                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                                    <td>
                                                        <span class="badge badge-rpi-<?= $user['role'] == 'admin' ? 'accent-2' : 'primary' ?>">
                                                            <?= ucfirst($user['role']) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-rpi-<?= $user['statut'] == 'actif' ? 'accent-3' : 'accent-4' ?>">
                                                            <?= ucfirst($user['statut']) ?>
                                                        </span>
                                                    </td>
                                                    <td><?= date('d/m/Y H:i', strtotime($user['add_date'])) ?></td>
                                                    <td>
                                                        <a href="#modalViewUser<?= $user['id'] ?>" data-toggle="modal" data-target="#modalViewUser<?= $user['id'] ?>" class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i> 
                                                        </a>
                                                        <a href="#modalEditUser<?= $user['id'] ?>" data-toggle="modal" data-target="#modalEditUser<?= $user['id'] ?>" class="btn btn-sm btn-warning">
                                                            <i class="fas fa-edit"></i> 
                                                        </a>
                                                        <?php if ($user['statut'] == 'actif'): ?>
                                                            <a href="controller/toggleUtilisateurStatus.php?id=<?= $user['id'] ?>&statut=bloque" class="btn btn-sm btn-secondary" onclick="return confirm('Bloquer cet utilisateur ?');">
                                                                <i class="fas fa-ban"></i> 
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="controller/toggleUtilisateurStatus.php?id=<?= $user['id'] ?>&statut=actif" class="btn btn-sm btn-success" onclick="return confirm('Débloquer cet utilisateur ?');">
                                                                <i class="fas fa-check"></i> 
                                                            </a>
                                                        <?php endif; ?>
                                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                            <a href="controller/deleteUtilisateur.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet utilisateur ? Cette action est irréversible.');">
                                                                <i class="fas fa-trash"></i> 
                                                            </a>
                                                        <?php else: ?>
                                                            <button class="btn btn-sm btn-secondary" disabled title="Vous ne pouvez pas supprimer votre propre compte">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center">Aucun utilisateur trouvé.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination -->
                            <?php if ($pages > 1): ?>
                            <nav>
                                <ul class="pagination justify-content-center">
                                    <?php for ($i = 1; $i <= $pages; $i++): ?>
                                        <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                            <a class="page-link text-rpi-primary" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer">
                            <a class="btn btn-sm btn-dark fw-bold float-right" href="#modalAddUser" data-toggle="modal" data-target="#modalAddUser" style="margin-left: 10px">
                                <i class="fas fa-fw fa-plus fa-sm fa-fw mr-2 text-gray-400"></i>
                                Ajouter un utilisateur
                            </a>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Modal pour consulter un utilisateur -->
            <?php foreach ($users as $user): ?>
            <div class="modal fade" id="modalViewUser<?= $user['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Détails de l'utilisateur</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Fermer">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?php
                                $userDetails = $controller->show($user['id']);
                                if ($userDetails):
                            ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Nom:</strong> <?= htmlspecialchars($userDetails['nom']) ?><br>
                                    <strong>Prénom:</strong> <?= htmlspecialchars($userDetails['prenom']) ?><br>
                                    <strong>Email:</strong> <?= htmlspecialchars($userDetails['email']) ?><br>
                                </div>
                                <div class="col-md-6">
                                    <strong>Rôle:</strong> <span class="badge badge-rpi-<?= $userDetails['role'] == 'admin' ? 'accent-2' : 'primary' ?>"><?= ucfirst($userDetails['role']) ?></span><br>
                                    <strong>Statut:</strong> <span class="badge badge-rpi-<?= $userDetails['statut'] == 'actif' ? 'accent-3' : 'accent-4' ?>"><?= ucfirst($userDetails['statut']) ?></span><br>
                                    <strong>Date de création:</strong> <?= date('d/m/Y H:i', strtotime($userDetails['add_date'])) ?><br>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- Modal pour modifier un utilisateur -->
            <?php foreach ($users as $user): ?>
            <div class="modal fade" id="modalEditUser<?= $user['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modifier l'utilisateur</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Fermer">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="controller/updateUtilisateur.php" method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                
                                <div class="form-group">
                                    <label for="nom<?= $user['id'] ?>">Nom</label>
                                    <input type="text" class="form-control" id="nom<?= $user['id'] ?>" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="prenom<?= $user['id'] ?>">Prénom</label>
                                    <input type="text" class="form-control" id="prenom<?= $user['id'] ?>" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="email<?= $user['id'] ?>">Email</label>
                                    <input type="email" class="form-control" id="email<?= $user['id'] ?>" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="role<?= $user['id'] ?>">Rôle</label>
                                    <select class="form-control" id="role<?= $user['id'] ?>" name="role" required>
                                        <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>Utilisateur</option>
                                        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Administrateur</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="statut<?= $user['id'] ?>">Statut</label>
                                    <select class="form-control" id="statut<?= $user['id'] ?>" name="statut" required>
                                        <option value="actif" <?= $user['statut'] == 'actif' ? 'selected' : '' ?>>Actif</option>
                                        <option value="bloque" <?= $user['statut'] == 'bloque' ? 'selected' : '' ?>>Bloqué</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                                <button class="btn btn-primary" type="submit">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- Modal pour ajouter un utilisateur -->
            <div class="modal fade" id="modalAddUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ajouter un utilisateur</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Fermer">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="controller/addUtilisateur.php" method="POST" id="formAddUser">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="nom">Nom</label>
                                    <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom de l'utilisateur" required>
                                </div>

                                <div class="form-group">
                                    <label for="prenom">Prénom</label>
                                    <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prénom de l'utilisateur" required>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email de l'utilisateur" required>
                                </div>

                                <div class="form-group">
                                    <label for="password">Mot de passe</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe" required>
                                </div>

                                <div class="form-group">
                                    <label for="role">Rôle</label>
                                    <select class="form-control" id="role" name="role" required>
                                        <option value="">-- Sélectionnez un rôle --</option>
                                        <option value="user">Utilisateur</option>
                                        <option value="admin">Administrateur</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-danger" type="reset">Annuler</button>
                                <button class="btn btn-primary" type="submit">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

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
