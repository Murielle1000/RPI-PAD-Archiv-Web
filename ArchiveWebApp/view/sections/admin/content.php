<div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <?php if (session_status() === PHP_SESSION_NONE) session_start();
                        $nom = isset($_SESSION['nom']) ? $_SESSION['nom'] : (isset($_SESSION['user_id']) ? '' : '');
                        if (!$nom && isset($_SESSION['user_id'])) {
                            require_once __DIR__ . '/../../../model/UtilisateursRepository.php';
                            $repo = new UsersRepository();
                            $u = $repo->getUserById($_SESSION['user_id']);
                            $nom = $u ? $u['nom'] : '';
                            $_SESSION['nom'] = $nom;
                        }
                    ?>
                   

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                       

                         <!-- Bienvenue personnalisé -->
                    <div class="d-none d-sm-inline-block mr-auto ml-md-3 my-2 my-md-0 mw-100 text-center">
                        <span class="h4 font-weight-bold text-rpi-primary"><i class="fas fa-smile mr-2"></i>Bienvenue ! </span>
                    </div>
                        

                        <div class="topbar-divider d-none d-sm-block"></div>

                       

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">RPI-PAD Archiv'Web</h1>
                    </div>

                    <?php
                    // Récupération des statistiques des documents
                    require_once("model/DocumentsRepository.php");
                    $docRepo = new DocumentsRepository();
                    
                    // Compter les documents par type
                    $lois = count($docRepo->getByType('lois'));
                    $decrets = count($docRepo->getByType('décrets'));
                    $arretes = count($docRepo->getByType('arrêtés'));
                    $ordonnances = count($docRepo->getByType('ordonnance'));
                    $decisions = count($docRepo->getByType('décision'));
                    $notes = count($docRepo->getByType('note de service'));
                    $resolutions = count($docRepo->getByType('résolution'));
                    $conventions = count($docRepo->getByType('convention'));
                    $autres = count($docRepo->getByType('autre'));
                    // Total des documents
                    $total = $lois + $decrets + $arretes + $ordonnances + $decisions + $notes + $resolutions + $conventions + $autres;
                    ?>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Lois Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card-rpi border-left-primary h-100 py-2 fade-in-up">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="stat-label">
                                                Lois</div>
                                            <div class="stat-number"><?= $lois ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-gavel fa-2x stat-icon primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Décrets Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card-rpi border-left-success h-100 py-2 fade-in-up" style="animation-delay: 0.1s;">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="stat-label">
                                                Décrets</div>
                                            <div class="stat-number"><?= $decrets ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-scroll fa-2x stat-icon success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Arrêtés Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card-rpi border-left-info h-100 py-2 fade-in-up" style="animation-delay: 0.2s;">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="stat-label">
                                                Arrêtés</div>
                                            <div class="stat-number"><?= $arretes ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-file-signature fa-2x stat-icon info"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ordonnances Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card-rpi border-left-warning h-100 py-2 fade-in-up" style="animation-delay: 0.3s;">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="stat-label">
                                                Ordonnances</div>
                                            <div class="stat-number"><?= $ordonnances ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x stat-icon warning"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Conventions Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card-rpi border-left-accent-5 h-100 py-2 fade-in-up" style="animation-delay: 0.8s;">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="stat-label">
                                                Conventions</div>
                                            <div class="stat-number"><?= $conventions ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-handshake fa-2x stat-icon accent-5"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Autres Documents Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card-rpi border-left-secondary h-100 py-2 fade-in-up" style="animation-delay: 0.9s;">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="stat-label">
                                                Autres</div>
                                            <div class="stat-number"><?= $autres ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-folder-open fa-2x stat-icon secondary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Second Row -->
                    <div class="row">

                        <!-- Décisions Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card-rpi border-left-accent-2 h-100 py-2 fade-in-up" style="animation-delay: 0.4s;">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="stat-label">
                                                Décisions</div>
                                            <div class="stat-number"><?= $decisions ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-balance-scale fa-2x stat-icon accent-2"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes de Service Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card-rpi border-left-accent-3 h-100 py-2 fade-in-up" style="animation-delay: 0.5s;">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="stat-label">
                                                Notes de Service</div>
                                            <div class="stat-number"><?= $notes ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-sticky-note fa-2x stat-icon accent-3"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Résolutions Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card-rpi border-left-accent-4 h-100 py-2 fade-in-up" style="animation-delay: 0.6s;">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="stat-label">
                                                Résolutions</div>
                                            <div class="stat-number"><?= $resolutions ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-handshake fa-2x stat-icon accent-4"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Documents Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card-rpi border-left-accent h-100 py-2 fade-in-up" style="animation-delay: 0.7s;">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="stat-label">
                                                Total Documents</div>
                                            <div class="stat-number"><?= $total ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-archive fa-2x stat-icon accent"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Quick Actions Card -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Actions Rapides</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <a href="listeLois.php" class="btn btn-rpi-primary btn-block">
                                                <i class="fas fa-gavel mr-2"></i>Gérer les Lois
                                            </a>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <a href="listeDécrets.php" class="btn btn-success btn-block">
                                                <i class="fas fa-scroll mr-2"></i>Gérer les Décrets
                                            </a>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <a href="listeArrétés.php" class="btn btn-info btn-block">
                                                <i class="fas fa-file-signature mr-2"></i>Gérer les Arrêtés
                                            </a>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <a href="listeOrdonnances.php" class="btn btn-warning btn-block">
                                                <i class="fas fa-clipboard-list mr-2"></i>Gérer les Ordonnances
                                            </a>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <a href="listeDécisions.php" class="btn btn-dark btn-block">
                                                <i class="fas fa-balance-scale mr-2"></i>Gérer les Décisions
                                            </a>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <a href="listeNotes.php" class="btn btn-rpi-secondary btn-block">
                                                <i class="fas fa-sticky-note mr-2"></i>Gérer les Notes
                                            </a>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <a href="listeRésolutions.php" class="btn btn-danger btn-block">
                                                <i class="fas fa-handshake mr-2"></i>Gérer les Résolutions
                                            </a>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <a href="listeConventions.php" class="btn btn-dark btn-block">
                                                <i class="fas fa-handshake mr-2"></i>Gérer les Conventions
                                            </a>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <a href="autre.php" class="btn btn-secondary btn-block">
                                                <i class="fas fa-folder-open mr-2"></i>Gérer les Autres
                                            </a>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <a href="utilisateurs.php" class="btn btn-rpi-primary btn-block">
                                                <i class="fas fa-users mr-2"></i>Gérer les Utilisateurs
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Info Card -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Informations Système</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 15rem;"
                                            src="public/images/logo.jpg" alt="RPI-PAD Logo">
                                    </div>
                                    <p class="text-center"><strong>RPI-PAD Archiv'Web</strong></p>
                                    <p class="text-center text-muted">Système d'Archivage Électronique</p>
                                    <hr>
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="h5 mb-0 font-weight-bold text-primary"><?= $total ?></div>
                                            <small class="text-muted">Documents</small>
                                        </div>
                                        <div class="col-6">
                                            <div class="h5 mb-0 font-weight-bold text-success">9</div>
                                            <small class="text-muted">Types</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity Row -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Activité Récente</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <i class="fas fa-chart-line fa-3x text-gray-300 mb-3"></i>
                                        <h5 class="text-gray-600">Tableau de bord en temps réel</h5>
                                        <p class="text-muted">Les statistiques sont mises à jour automatiquement à chaque chargement de la page.</p>
                                        <a href="#" class="btn btn-primary" onclick="location.reload()">
                                            <i class="fas fa-sync-alt mr-2"></i>Actualiser les données
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>