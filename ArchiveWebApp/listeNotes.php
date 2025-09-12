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
                        <form class="form-inline">
                            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                                <i class="fa fa-bars"></i>
                            </button>
                        </form>
                        <ul class="navbar-nav ml-auto">
                            <div class="topbar-divider d-none d-sm-block"></div>
                        </ul>
                    </nav>
                    <!-- End of Topbar -->

                    <!-- Begin Page Content -->
                    <div class="container-fluid">

                        <!-- Page Heading -->
                        <h1 class="h3 mb-2 text-gray-800">NOTES DE SERVICE</h1>
                        <div class="d-sm-flex align-items-center justify-content-space-between mb-4" >
                            <a class="btn btn-sm btn-dark fw-bold" href="#modalAddNote" data-toggle="modal" data-target="#modalAddNote" style="margin-left: 0px">
                                <i class="fas fa-fw fa-plus fa-sm fa-fw mr-2 text-gray-400"></i>
                                Ajouter
                            </a>
                        </div>

                        <!-- Pour le tableau -->

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

                            // Récupération des notes de service
                            $notes = $repo->getByType('note de service');

                            // Recherche par titre
                            if ($search !== '') {
                                $notes = array_filter($notes, function($doc) use ($search) {
                                    return stripos($doc['titre'], $search) !== false;
                                });
                            }

                            // Filtre catégorie
                            if ($categorieFilter) {
                                $notes = array_filter($notes, function($doc) use ($categorieFilter) {
                                    return $doc['categorie'] === $categorieFilter;
                                });
                            }

                            // Tri alphabétique
                            if ($alphaOrder) {
                                usort($notes, function($a, $b) use ($alphaOrder) {
                                    if ($alphaOrder === 'asc') {
                                        return strcmp($a['titre'], $b['titre']);
                                    } else {
                                        return strcmp($b['titre'], $a['titre']);
                                    }
                                });
                            }

                            // Tri chronologique
                            if ($dateOrder) {
                                usort($notes, function($a, $b) use ($dateOrder) {
                                    if ($dateOrder === 'asc') {
                                        return strtotime($a['add_date']) - strtotime($b['add_date']);
                                    } else {
                                        return strtotime($b['add_date']) - strtotime($a['add_date']);
                                    }
                                });
                            }

                            // Pagination
                            $total = count($notes);
                            $pages = ceil($total / $perPage);
                            $notes = array_slice($notes, ($page - 1) * $perPage, $perPage);
                        ?>


                        <?php
                            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
                                $id = intval($_POST['id']);
                                $titre = $_POST['title'];
                                $type = $_POST['type'];
                                $categorie = $_POST['categorie'];
                                $description = $_POST['description'];
                                $repo->updateDocument($id, $titre, $type, $categorie, $description);
                                header("Location: listeNotes.php?success=1");
                                exit;
                            } 
                        ?> 



                        <div class="card shadow mb-4" style="border: none; background: linear-gradient(135deg, #2563eb 0%, #60a5fa 100%); color: #fff;">
                            <div class="card-header py-3" style="background: #1e3a8a;">
                                <h6 class="m-0 font-weight-bold" style="color: #fff;">Liste des Notes de Service</h6>
                                <form method="get" class="form-inline mt-2" id="filterForm">
                                    <div class="input-group mr-2">
                                        <input type="text" name="search" class="form-control" style="background: #eff6ff; color: #1e3a8a; border: none;" placeholder="Rechercher par titre..." value="<?= htmlspecialchars($search) ?>">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-light">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <select name="categorie" class="form-control mr-2" style="background: #3b82f6; color: #fff; border: none;" onchange="document.getElementById('filterForm').submit();">
                                        <option value="">Toutes les catégories</option>
                                        <option value="présidentiel" <?= $categorieFilter=='présidentiel'?'selected':''; ?>>Présidence</option>
                                        <option value="ministériel" <?= $categorieFilter=='ministériel'?'selected':''; ?>>Ministère</option>
                                        <option value="direction générale" <?= $categorieFilter=='direction générale'?'selected':''; ?>>Direction Générale</option>
                                        <option value="gouvernement" <?= $categorieFilter=='gouvernement'?'selected':''; ?>>Gouvernement</option>
                                    </select>
                                    <select name="alpha_order" class="form-control mr-2" style="background: #3b82f6; color: #fff; border: none;" onchange="document.getElementById('filterForm').submit();">
                                        <option value="">Ordre alphabétique</option>
                                        <option value="asc" <?= $alphaOrder=='asc'?'selected':''; ?>>A → Z</option>
                                        <option value="desc" <?= $alphaOrder=='desc'?'selected':''; ?>>Z → A</option>
                                    </select>
                                    <select name="date_order" class="form-control mr-2" style="background: #3b82f6; color: #fff; border: none;" onchange="document.getElementById('filterForm').submit();">
                                        <option value="desc" <?= $dateOrder=='desc'?'selected':''; ?>>Plus récentes</option>
                                        <option value="asc" <?= $dateOrder=='asc'?'selected':''; ?>>Plus anciennes</option>
                                    </select>
                                </form>
                            </div>
                            <div class="card-body" style="background: #2563eb;">
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="background: #eff6ff; color: #1e3a8a;">
                                        <thead style="background: #3b82f6; color: #fff;">
                                            <tr>
                                                <th>Titre</th>
                                                <th>Type</th>
                                                <th>Catégorie</th>
                                                <th>Description</th>
                                                <th>Date d'ajout</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($notes) > 0): ?>
                                                <?php foreach ($notes as $note): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($note['titre']) ?></td>
                                                        <td><?= htmlspecialchars($note['type']) ?></td>
                                                        <td><?= htmlspecialchars($note['categorie']) ?></td>
                                                        <td><?= htmlspecialchars($note['description']) ?></td>
                                                        <td><?= date('d/m/Y H:i', strtotime($note['add_date'])) ?></td>
                                                        <td>
                                                            <?php if (!empty($note['url_fichier'])): ?>
                                                                <a href="controller/openDocument.php?id=<?= $note['id'] ?>" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-folder-open"></i></a>
                                                            <?php else: ?>
                                                                <span class="text-muted">Fichier non disponible</span>
                                                            <?php endif; ?>
                                                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                                                <a href="documentHistory.php?id=<?= $note['id'] ?>" class="btn btn-sm btn-info" title="Historique des actions"><i class="fas fa-history"></i></a>
                                                                <a href="controller/editNotes.php?id=<?= $note['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                                                <a href="controller/deleteNotes.php?id=<?= $note['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce document ?');"><i class="fas fa-trash"></i></a>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">Aucune note de service trouvée.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Pagination -->
                                <nav>
                                    <ul class="pagination justify-content-center">
                                        <?php for ($i = 1; $i <= $pages; $i++): ?>
                                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                                <a class="page-link" style="color: #2563eb;" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                </nav>
                            </div>
                            
                        </div>

                    </div>
                    <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->

                <!-- Modal add Note de service -->
                <div class="modal fade" id="modalAddNote" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-lg" role="document" >
                        <div class="modal-content">
                            
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Ajouter une Note de service</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Fermer">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            
                            <form action="controller/addNotes.php" method="POST" enctype="multipart/form-data" id="formAddDocument">
                                <div class="modal-body">
                                    
                                    <!-- Titre -->
                                    <div class="form-group">
                                        <label for="docTitle">Titre </label>
                                        <input type="text" class="form-control" id="docTitle" name="title" placeholder="Titre du document" required>
                                    </div>

                                    <!-- Type -->
                                    <div class="form-group">
                                        <label for="docType">Type </label>
                                        <select class="form-control" id="docType" name="type" required>
                                            <option value="note de service" selected>Note de service</option>
                                            <option value="arrêtés">Arrêtés</option>
                                            <option value="ordonnance">Ordonnance</option>
                                            <option value="décrets">Décrets</option>
                                            <option value="lois">Lois</option>
                                            <option value="décision">Décision</option>
                                            <option value="résolution">Résolution</option>
                                            <option value="convention">Convention</option>
                                        </select>
                                    </div>

                                    <!-- Catégorie -->
                                    <div class="form-group">
                                        <label for="docCategory">Catégorie</label>
                                        <select class="form-control" id="docCategory" name="categorie" required>
                                            <option value="--">--</option>
                                            <option value="présidentiel">Présidence</option>
                                            <option value="ministériel">Ministère</option>
                                            <option value="direction générale">Direction Générale</option>
                                            <option value="gouvernement">Gouvernement</option>
                                        </select>
                                    </div>

                                    <!-- Description -->
                                    <div class="form-group">
                                        <label for="docDescription">Description</label>
                                        <textarea class="form-control" id="docDescription" name="description" rows="3" placeholder="Description du document"></textarea>
                                    </div>

                                    <!-- Fichier -->
                                    <div class="form-group">
                                        <label for="docFile">Importer le fichier</label>
                                        <input type="file" class="form-control-file" id="docFile" name="url_fichier" required>
                                        <small class="form-text text-muted">Le titre sera automatiquement rempli avec le nom du fichier choisi (modifiable).</small>
                                    </div>

                                </div>
                                
                                <div class="modal-footer">
                                    <button class="btn btn-danger" type="reset" >Annuler</button>
                                    <button class="btn btn-primary" type="submit">Enregistrer</button>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>


            
                


                <!-- Script pour remplir le titre automatiquement -->
                <script>
                    document.getElementById('docFile').addEventListener('change', function(event) {
                        let fileName = event.target.files[0]?.name || '';
                        if (fileName) {
                            let nameWithoutExtension = fileName.split('.').slice(0, -1).join('.');
                            document.getElementById('docTitle').value = nameWithoutExtension;
                        }
                    });
                </script>

                




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