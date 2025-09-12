<?php

?>

<div class="card card-rpi shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold"><?= $pageTitle ?? 'Liste des Documents' ?></h6>
        <form method="get" class="form-inline mt-2" id="filterForm">
            <div class="input-group mr-2">
                <input type="text" name="search" class="form-control form-control-rpi" placeholder="Rechercher par titre..." value="<?= htmlspecialchars($search ?? '') ?>">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-rpi-secondary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <select name="categorie" class="form-control form-control-rpi-dark mr-2" onchange="document.getElementById('filterForm').submit();">
                <option value="">Toutes les catégories</option>
                <option value="présidentiel" <?= ($categorieFilter ?? '')=='présidentiel'?'selected':''; ?>>Présidence</option>
                <option value="ministériel" <?= ($categorieFilter ?? '')=='ministériel'?'selected':''; ?>>Ministère</option>
                <option value="direction générale" <?= ($categorieFilter ?? '')=='direction générale'?'selected':''; ?>>Direction Générale</option>
                <option value="gouvernement" <?= ($categorieFilter ?? '')=='gouvernement'?'selected':''; ?>>Gouvernement</option>
            </select>
            <select name="alpha_order" class="form-control form-control-rpi-dark mr-2" onchange="document.getElementById('filterForm').submit();">
                <option value="">Ordre alphabétique</option>
                <option value="asc" <?= ($alphaOrder ?? '')=='asc'?'selected':''; ?>>A → Z</option>
                <option value="desc" <?= ($alphaOrder ?? '')=='desc'?'selected':''; ?>>Z → A</option>
            </select>
            <select name="date_order" class="form-control form-control-rpi-dark mr-2" onchange="document.getElementById('filterForm').submit();">
                <option value="desc" <?= ($dateOrder ?? 'desc')=='desc'?'selected':''; ?>>Plus récentes</option>
                <option value="asc" <?= ($dateOrder ?? '')=='asc'?'selected':''; ?>>Plus anciennes</option>
            </select>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-rpi table-bordered">
                <thead>
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
                    <?php if (isset($documents) && count($documents) > 0): ?>
                        <?php foreach ($documents as $doc): ?>
                            <tr>
                                <td><?= htmlspecialchars($doc['titre']) ?></td>
                                <td><?= htmlspecialchars($doc['type']) ?></td>
                                <td><?= htmlspecialchars($doc['categorie']) ?></td>
                                <td><?= htmlspecialchars($doc['description']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($doc['add_date'])) ?></td>
                                <td>
                                    <?php if (!empty($doc['url_fichier'])): ?>
                                        <a href="controller/openDocument.php?id=<?= $doc['id'] ?>" target="_blank" class="btn btn-sm btn-rpi-primary">
                                            <i class="fas fa-folder-open"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">Fichier non disponible</span>
                                    <?php endif; ?>
                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                        <a href="documentHistory.php?id=<?= $doc['id'] ?>" class="btn btn-sm btn-rpi-info" title="Historique des actions">
                                            <i class="fas fa-history"></i>
                                        </a>
                                        <a href="controller/edit<?= ucfirst($documentType) ?>.php?id=<?= $doc['id'] ?>" class="btn btn-sm btn-rpi-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="controller/delete<?= ucfirst($documentType) ?>.php?id=<?= $doc['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce document ?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Aucun document trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if (isset($pages) && $pages > 1): ?>
        <nav>
            <ul class="pagination pagination-rpi justify-content-center">
                <?php for ($i = 1; $i <= $pages; $i++): ?>
                    <li class="page-item <?= $i == ($currentPage ?? 1) ? 'active' : '' ?>">
                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
        
        <?php endif; ?>
    </div>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <div class="d-flex justify-content-end mt-3">
            <?php if ($documentType === 'lois'): ?>
                <a class="btn btn-dark fw-bold" href="#modalAddLois" data-toggle="modal" data-target="#modalAddLois">
                    <i class="fas fa-fw fa-plus fa-sm fa-fw mr-2 text-gray-400"></i> Ajouter
                </a>
            <?php elseif ($documentType === 'decrets'): ?>
                <a class="btn btn-dark fw-bold" href="#modalAddDecrets" data-toggle="modal" data-target="#modalAddDecrets">
                    <i class="fas fa-fw fa-plus fa-sm fa-fw mr-2 text-gray-400"></i> Ajouter
                </a>
            <?php elseif ($documentType === 'arretes'): ?>
                <a class="btn btn-dark fw-bold" href="#modalAddArretes" data-toggle="modal" data-target="#modalAddArretes">
                    <i class="fas fa-fw fa-plus fa-sm fa-fw mr-2 text-gray-400"></i> Ajouter
                </a>
            <?php elseif ($documentType === 'ordonnances'): ?>
                <a class="btn btn-dark fw-bold" href="#modalAddOrdonnances" data-toggle="modal" data-target="#modalAddOrdonnances">
                    <i class="fas fa-fw fa-plus fa-sm fa-fw mr-2 text-gray-400"></i> Ajouter
                </a>
            <?php elseif ($documentType === 'decisions'): ?>
                <a class="btn btn-dark fw-bold" href="#modalAddDecisions" data-toggle="modal" data-target="#modalAddDecisions">
                    <i class="fas fa-fw fa-plus fa-sm fa-fw mr-2 text-gray-400"></i> Ajouter
                </a>
            <?php elseif ($documentType === 'notes'): ?>
                <a class="btn btn-dark fw-bold" href="#modalAddNotes" data-toggle="modal" data-target="#modalAddNotes">
                    <i class="fas fa-fw fa-plus fa-sm fa-fw mr-2 text-gray-400"></i> Ajouter
                </a>
            <?php elseif ($documentType === 'resolutions'): ?>
                <a class="btn btn-dark fw-bold" href="#modalAddResolutions" data-toggle="modal" data-target="#modalAddResolutions">
                    <i class="fas fa-fw fa-plus fa-sm fa-fw mr-2 text-gray-400"></i> Ajouter
                </a>
            <?php elseif ($documentType === 'users'): ?>
                <a class="btn btn-dark fw-bold" href="#modalAddUser" data-toggle="modal" data-target="#modalAddUser">
                    <i class="fas fa-fw fa-plus fa-sm fa-fw mr-2 text-gray-400"></i> Ajouter un utilisateur
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
