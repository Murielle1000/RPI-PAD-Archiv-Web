
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
                        <h1 class="h3 mb-2 text-gray-800">DÉCRETS</h1>
                        <div class="d-sm-flex align-items-center justify-content-space-between mb-4">
                            <a class="btn btn-sm btn-dark fw-bold" href="#modalAddDecret" data-toggle="modal" data-target="#modalAddDecret" style="margin-left: 0px">
                                <i class="fas fa-fw fa-plus fa-sm fa-fw mr-2 text-gray-400"></i>
                                Ajouter
                            </a>
                        </div>

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

                            // Récupération des décrets
                            $decrets = $repo->getByType('décrets');

                            // Recherche par titre
                            if ($search !== '') {
                                $decrets = array_filter($decrets, function($doc) use ($search) {
                                    return stripos($doc['titre'], $search) !== false;
                                });
                            }

                            // Filtre catégorie
                            if ($categorieFilter) {
                                $decrets = array_filter($decrets, function($doc) use ($categorieFilter) {
                                    return $doc['categorie'] === $categorieFilter;
                                });
                            }

                            // Tri alphabétique
                            if ($alphaOrder) {
                                usort($decrets, function($a, $b) use ($alphaOrder) {
                                    if ($alphaOrder === 'asc') {
                                        return strcmp($a['titre'], $b['titre']);
                                    } else {
                                        return strcmp($b['titre'], $a['titre']);
                                    }
                                });
                            }

                            // Tri chronologique
                            if ($dateOrder) {
                                usort($decrets, function($a, $b) use ($dateOrder) {
                                    if ($dateOrder === 'asc') {
                                        return strtotime($a['add_date']) - strtotime($b['add_date']);
                                    } else {
                                        return strtotime($b['add_date']) - strtotime($a['add_date']);
                                    }
                                });
                            }

                            // Pagination
                            $total = count($decrets);
                            $pages = ceil($total / $perPage);
                            $decrets = array_slice($decrets, ($page - 1) * $perPage, $perPage);
                        ?>

                    <?php
                    // Variables pour le template
                    $pageTitle = "Liste des Décrets";
                    $documentType = "décrets";
                    $documents = $decrets;
                    $currentPage = $page;
                    include "view/sections/admin/document-card-template.php";
                    ?>
                        
                    </div>
                    <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->

        <!-- Modal add Décret -->
    <div class="modal fade" id="modalAddDecret" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document" >
            <div class="modal-content">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter un Décret</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <form action="controller/addDécrets.php" method="POST" enctype="multipart/form-data" id="formAddDocument">
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
                                <option value="décrets" selected>Décrets</option>
                                <option value="lois">Lois</option>
                                <option value="arrêtés">Arrêtés</option>
                                <option value="ordonnance">Ordonnance</option>
                                <option value="note de service">Note de service</option>
                                <option value="décision">Décision</option>
                                <option value="résolution">Résolution</option>
                            </select>
                        </div>

                        <!-- Catégorie -->
                        <div class="form-group">
                            <label for="docCategory">Catégorie</label>
                            <select class="form-control" id="docCategory" name="categorie" required>
                                <option value="">-- Sélectionnez une catégorie --</option>
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