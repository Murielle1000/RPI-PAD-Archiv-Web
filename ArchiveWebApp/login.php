<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Archivage Électronique</title>
    <!-- Inclure la police Inter depuis Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Inclure Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: url(public/images/background.jpg);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-slate-900 text-gray-800 p-4">

    <!-- Conteneur principal de la carte de connexion -->
    <div class="bg-white p-8 sm:p-10 rounded-3xl shadow-2xl w-full max-w-sm sm:max-w-md mx-auto">
        <!-- En-tête de la carte -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2 text-blue-gradient">
                <span class="text-lime-500">RPI-PAD</span> <span class="text-sky-400">Archiv'Web</span>
            </h1>
            <p class="text-gray-500 text-lg">
                Connectez-vous à votre espace
            </p>
        </div>

        <!-- Formulaire de connexion -->
        <?php require_once("view/login/formulaire.php"); ?>

        <!-- Espace pour les messages -->
        <div id="message" class="mt-4 text-center text-sm font-medium"></div>

        <!-- Lien mot de passe oublié -->
        <div class="mt-6 text-center">
            <a href="#" class="font-medium text-sky-400 hover:text-sky-500 transition-colors">
                Mot de passe oublié ?
            </a>
        </div>
    </div>

     <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            // Empêche l'envoi du formulaire par défaut
            event.preventDefault();

            // Récupère les valeurs des champs
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const messageDiv = document.getElementById('message');

            // Nettoie les messages précédents
            messageDiv.textContent = '';
            messageDiv.className = 'mt-4 text-center text-sm font-medium';

            // Effectue la requête POST vers le fichier log.php
            // Assurez-vous que le fichier login.php est dans le même répertoire
            // et que l'application est exécutée sur un serveur web local.
            fetch('log.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Stocke le rôle de l'utilisateur pour une utilisation future
                    sessionStorage.setItem('userRole', data.role);
                    messageDiv.textContent = 'Connexion réussie ! Redirection...';
                    messageDiv.classList.add('text-green-600');
                    // Redirection vers la nouvelle page d'accueil
                    window.location.href = 'admin.php'; 
                } else {
                    messageDiv.textContent = data.message;
                    messageDiv.classList.add('text-red-600');
                    
                    // Si le compte est bloqué, on peut ajouter un style spécial
                    if (data.message.includes('bloqué')) {
                        messageDiv.classList.add('bg-red-50', 'border', 'border-red-200', 'rounded-lg', 'p-3');
                    }
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                messageDiv.textContent = 'Une erreur s\'est produite. Veuillez réessayer.';
                messageDiv.classList.add('text-red-600');
            });
        });
    </script>
   
</body>
</html>
