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
                                        <a href="#" class="btn btn-sm btn-rpi-secondary" onclick="openEditModal(<?= $doc['id'] ?>, '<?= $documentType ?>')">
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
            <?php elseif ($documentType === 'autre'): ?>
                <a class="btn btn-dark fw-bold" href="#modalAddAutre" data-toggle="modal" data-target="#modalAddAutre">
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

<!-- Modal d'édition -->
<div class="modal fade" id="modalEditDocument" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Modifier le Document</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form id="formEditDocument" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="editDocumentId" name="id">
                    
                    <!-- Titre -->
                    <div class="form-group">
                        <label for="editDocTitle">Titre</label>
                        <input type="text" class="form-control" id="editDocTitle" name="title" placeholder="Titre du document" required>
                    </div>

                    <!-- Type -->
                    <div class="form-group">
                        <label for="editDocType">Type</label>
                        <select class="form-control" id="editDocType" name="type" required onchange="toggleCustomType()">
                            <option value="lois">Lois</option>
                            <option value="décrets">Décrets</option>
                            <option value="arrêtés">Arrêtés</option>
                            <option value="ordonnance">Ordonnance</option>
                            <option value="note de service">Note de service</option>
                            <option value="décision">Décision</option>
                            <option value="résolution">Résolution</option>
                            <option value="convention">Convention</option>
                            <option value="autre">Autre (personnalisé)</option>
                        </select>
                    </div>

                    <!-- Type personnalisé (visible seulement pour "autre") -->
                    <div class="form-group" id="customTypeGroup" style="display: none;">
                        <label for="editDocCustomType">Type personnalisé</label>
                        <input type="text" class="form-control" id="editDocCustomType" name="custom_type" placeholder="Saisissez le type de document">
                        <small class="form-text text-muted">Ex: Rapport, Manuel, Guide, Procédure, etc.</small>
                    </div>

                    <!-- Catégorie -->
                    <div class="form-group">
                        <label for="editDocCategory">Catégorie</label>
                        <select class="form-control" id="editDocCategory" name="categorie" required>
                            <option value="--">--</option>
                            <option value="présidentiel">Présidence</option>
                            <option value="ministériel">Ministère</option>
                            <option value="gouvernement">Gouvernement</option>
                            <option value="Direction Générale">Direction Générale</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="editDocDescription">Description</label>
                        <textarea class="form-control" id="editDocDescription" name="description" rows="3" placeholder="Description du document"></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-dismiss="modal">Annuler</button>
                    <button class="btn btn-primary" type="submit">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal(documentId, documentType) {
    // Récupérer les données du document via AJAX
    fetch(`controller/edit${capitalizeFirst(documentType)}.php?id=${documentId}&ajax=1`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Erreur: ' + data.error);
                return;
            }
            
            // Remplir le formulaire avec les données
            document.getElementById('editDocumentId').value = data.id;
            document.getElementById('editDocTitle').value = data.titre;
            document.getElementById('editDocCategory').value = data.categorie;
            document.getElementById('editDocDescription').value = data.description;
            
            // Gérer le type selon le type de document
            // Vérifier si c'est un type prédéfini ou un type personnalisé
            const predefinedTypes = ['lois', 'décrets', 'arrêtés', 'ordonnance', 'note de service', 'décision', 'résolution', 'convention'];
            if (predefinedTypes.includes(data.type)) {
                document.getElementById('editDocType').value = data.type;
                document.getElementById('editDocCustomType').value = '';
            } else {
                // C'est un type personnalisé (document "autre")
                document.getElementById('editDocType').value = 'autre';
                document.getElementById('editDocCustomType').value = data.type;
            }
            toggleCustomType();
            
            // Configurer l'action du formulaire
            document.getElementById('formEditDocument').action = `controller/edit${capitalizeFirst(documentType)}.php`;
            
            // Ouvrir la modale
            $('#modalEditDocument').modal('show');
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du chargement des données du document');
        });
}

function capitalizeFirst(str) {
    // Gérer les cas spéciaux pour les noms de contrôleurs
    const specialCases = {
        'ordonnances': 'Ordonnance',
        'décrets': 'Décrets',
        'arrêtés': 'Arrétés',
        'décisions': 'Décisions',
        'résolutions': 'Résolutions',
        'conventions': 'Conventions',
        'notes': 'Notes',
        'lois': 'Lois',
        'autre': 'Autre'
    };
    
    if (specialCases[str]) {
        return specialCases[str];
    }
    
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function toggleCustomType() {
    const typeSelect = document.getElementById('editDocType');
    const customTypeGroup = document.getElementById('customTypeGroup');
    const customTypeInput = document.getElementById('editDocCustomType');
    
    if (typeSelect.value === 'autre') {
        customTypeGroup.style.display = 'block';
        customTypeInput.required = true;
    } else {
        customTypeGroup.style.display = 'none';
        customTypeInput.required = false;
        customTypeInput.value = '';
    }
}

// Gérer la soumission du formulaire d'édition
document.getElementById('formEditDocument').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const action = this.action;
    
    // Si c'est un type personnalisé, utiliser la valeur du champ personnalisé
    const typeSelect = document.getElementById('editDocType');
    if (typeSelect.value === 'autre') {
        const customType = document.getElementById('editDocCustomType').value;
        if (customType) {
            formData.set('type', customType);
        }
    }
    
    fetch(action, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            // Fermer la modale
            $('#modalEditDocument').modal('hide');
            // Recharger la page pour voir les modifications
            location.reload();
        } else {
            alert('Erreur lors de la modification du document');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la modification du document');
    });
});
</script>
