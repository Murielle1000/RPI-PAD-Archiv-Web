
<?php
require_once("../model/DocumentsRepository.php");
$repo = new DocumentsRepository();

// Récupère l'id du document
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['title'] ?? '';
    $type = $_POST['type'] ?? '';
    $categorie = $_POST['categorie'] ?? '';
    $description = $_POST['description'] ?? '';
    // Si tu veux permettre de changer le fichier, ajoute la gestion ici

    $success = $repo->updateDocument($id, $titre, $type, $categorie, $description);

    if ($success) {
        // Enregistrer l'action dans l'audit
        session_start();
        require_once 'AuditHelper.php';
        $auditHelper = new AuditHelper();
        $auditHelper->logDocumentEdit($_SESSION['user_id'] ?? 1, $id, $titre, $type);
        
        header("Location: ../listeDécrets.php?success=1");
        exit;
    } else {
        header("Location: ../listeDécrets.php?error=1");
        exit;
    }
}

// Récupère les infos du document à éditer
$document = $repo->getById($id);
if (!$document) {
    header("Location: ../listeDécrets.php?error=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once("../view/sections/admin/head.php"); ?>
<head>
    <title>Édition</title>
    <!-- Inclure le CDN de Tailwind CSS pour un style rapide et moderne -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Définir une police par défaut et l'image de fond */
        body {
            font-family: 'Inter', sans-serif;
            background-image: url("../public/images/background.jpg");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
            z-index: 1; /* S'assurer que le contenu est au-dessus de l'overlay */
        }
        
        body::before {
            content: '';
            position: fixed; /* Utiliser fixed pour l'overlay sur l'image fixe */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4); /* Overlay noir semi-transparent */
            z-index: -1;
        }
    </style>
</head>
    <body class="flex items-center justify-center min-h-screen bg-slate-900 text-gray-800 p-4"> 
        
            <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-2xl">
                <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Éditer</h2>
                <form action="" method="POST" class="space-y-4">
                    <!-- Champ Titre -->
                    <div>
                        <label for="docTitle" class="block text-sm font-medium text-gray-700">Titre</label>
                        <input type="text" class="mt-1 block w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="docTitle" name="title" value="<?= htmlspecialchars($document['titre']) ?>" required>
                    </div>

                    <!-- Champ Type -->
                    <div>
                        <label for="docType" class="block text-sm font-medium text-gray-700">Type</label>
                        <select class="mt-1 block w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="docType" name="type" required>
                            <option value="note de service" <?= $document['type']=='note de service'?'selected':''; ?>>Note de service</option>
                            <option value="arrêtés" <?= $document['type']=='arrêtés'?'selected':''; ?>>Arrêtés</option>
                            <option value="ordonnance" <?= $document['type']=='ordonnance'?'selected':''; ?>>Ordonnance</option>
                            <option value="décrets" <?= $document['type']=='décrets'?'selected':''; ?>>Décrets</option>
                            <option value="lois" <?= $document['type']=='lois'?'selected':''; ?>>Lois</option>
                            <option value="décision" <?= $document['type']=='décision'?'selected':''; ?>>Décision</option>
                            <option value="résolution" <?= $document['type']=='résolution'?'selected':''; ?>>Résolution</option>
                        </select>
                    </div>

                    <!-- Champ Catégorie -->
                    <div>
                        <label for="docCategory" class="block text-sm font-medium text-gray-700">Catégorie</label>
                        <select class="mt-1 block w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="docCategory" name="categorie" required>
                            <option value="présidentiel" <?= $document['categorie']=='présidentiel'?'selected':''; ?>>Présidence</option>
                            <option value="ministériel" <?= $document['categorie']=='ministériel'?'selected':''; ?>>Ministère</option>
                            <option value="direction générale" <?= $document['categorie']=='direction générale'?'selected':''; ?>>Direction Générale</option>
                            <option value="gouvernement" <?= $document['categorie']=='gouvernement'?'selected':''; ?>>Gouvernement</option>
                        </select>
                    </div>

                    <!-- Champ Description -->
                    <div>
                        <label for="docDescription" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea class="mt-1 block w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm resize-none" id="docDescription" name="description" rows="3"><?= htmlspecialchars($document['description']) ?></textarea>
                    </div>

                    <!-- Boutons -->
                    <div class="flex justify-end space-x-4">
                        <button type="submit" class="w-1/2 md:w-auto px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Enregistrer les modifications
                        </button>
                        <a href="../listeDécrets.php" class="w-1/2 md:w-auto px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        
    <?php require_once("../view/sections/admin/script.php"); ?>
    </body>
</html>














