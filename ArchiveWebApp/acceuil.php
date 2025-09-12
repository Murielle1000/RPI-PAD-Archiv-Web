<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RPI-PAD Archiv'Web - Gestion Documentaire</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .hero-bg {
            background-image: url('public/images/background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .text-gradient {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .primary-gradient {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        }
        .secondary-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-white/90 backdrop-blur-md shadow-sm z-50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <img src="public/images/logo.jpg" alt="RPI-PAD Logo" class="w-10 h-10 rounded-lg object-cover">
                    <span class="text-xl font-bold text-gray-800">RPI-PAD Archiv'Web</span>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="#accueil" class="text-gray-600 hover:text-blue-600 transition-colors">Accueil</a>
                    <a href="#fonctionnalites" class="text-gray-600 hover:text-blue-600 transition-colors">Fonctionnalités</a>
                    <a href="#apropos" class="text-gray-600 hover:text-blue-600 transition-colors">À propos</a>
                    <a href="#contact" class="text-gray-600 hover:text-blue-600 transition-colors">Contact</a>
                </div>
                <a href="login.php" class="primary-gradient text-white px-6 py-2 rounded-full hover:shadow-lg transition-all duration-300">
                    Se connecter
                </a>
            </div>
        </div>
    </nav>

    <!-- Section Hero -->
    <section id="accueil" class="min-h-screen flex items-center justify-center hero-bg relative overflow-hidden">
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="container mx-auto px-6 text-center relative z-10">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight">
                    RPI-PAD <span class="text-yellow-300">Archiv'Web</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-200 mb-8 max-w-3xl mx-auto leading-relaxed">
                    Votre solution complète pour gérer, organiser et archiver tous vos documents administratifs de manière sécurisée et efficace.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="login.php" class="bg-white text-blue-600 px-8 py-4 rounded-full font-semibold text-lg hover:bg-gray-100 transition-all duration-300 shadow-lg">
                        <i class="fas fa-rocket mr-2"></i>Commencer maintenant
                    </a>
                    <a href="#fonctionnalites" class="border-2 border-white text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-white hover:text-blue-600 transition-all duration-300">
                        <i class="fas fa-info-circle mr-2"></i>Découvrir
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 text-white animate-bounce">
            <i class="fas fa-chevron-down text-2xl"></i>
        </div>
    </section>

    <!-- Section Fonctionnalités -->
    <section id="fonctionnalites" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">
                    Fonctionnalités <span class="text-gradient">Principales</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Découvrez les outils puissants qui font de RPI-PAD Archiv'Web la solution idéale pour votre gestion documentaire.
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Gestion des Documents -->
                <div class="bg-white p-8 rounded-2xl shadow-lg card-hover border border-gray-100">
                    <div class="w-16 h-16 primary-gradient rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-file-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Gestion des Documents</h3>
                    <p class="text-gray-600 mb-6">
                        Organisez vos documents par catégories : Lois, Décrets, Arrêtés, Ordonnances, Décisions, Notes, Résolutions, Conventions et autres.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Classification automatique</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Recherche avancée</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Versioning des documents</li>
                    </ul>
                </div>

                <!-- Gestion des Utilisateurs -->
                <div class="bg-white p-8 rounded-2xl shadow-lg card-hover border border-gray-100">
                    <div class="w-16 h-16 secondary-gradient rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Gestion des Utilisateurs</h3>
                    <p class="text-gray-600 mb-6">
                        Contrôlez l'accès à votre système avec une gestion complète des utilisateurs et des permissions.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Rôles et permissions</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Authentification sécurisée</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Gestion des sessions</li>
                    </ul>
                </div>

                <!-- Audit et Traçabilité -->
                <div class="bg-white p-8 rounded-2xl shadow-lg card-hover border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-shield-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Audit et Traçabilité</h3>
                    <p class="text-gray-600 mb-6">
                        Suivez toutes les actions effectuées dans le système pour une traçabilité complète et une sécurité maximale.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Journal des activités</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Historique détaillé</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Rapports d'audit</li>
                    </ul>
                </div>

                <!-- Corbeille et Récupération -->
                <div class="bg-white p-8 rounded-2xl shadow-lg card-hover border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-trash-restore text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Corbeille et Récupération</h3>
                    <p class="text-gray-600 mb-6">
                        Système de corbeille intelligent permettant la récupération des documents supprimés par erreur.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Suppression douce</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Restauration facile</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Suppression définitive</li>
                    </ul>
                </div>

                <!-- Interface Moderne -->
                <div class="bg-white p-8 rounded-2xl shadow-lg card-hover border border-gray-100">
                    <div class="w-16 h-16 primary-gradient rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-desktop text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Interface Moderne</h3>
                    <p class="text-gray-600 mb-6">
                        Une interface utilisateur intuitive et responsive, conçue pour une expérience utilisateur optimale.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Design responsive</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Navigation intuitive</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Thème moderne</li>
                    </ul>
                </div>

                <!-- Sécurité Avancée -->
                <div class="bg-white p-8 rounded-2xl shadow-lg card-hover border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-lock text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Sécurité Avancée</h3>
                    <p class="text-gray-600 mb-6">
                        Protection maximale de vos données avec des protocoles de sécurité de niveau entreprise.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Chiffrement des données</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Authentification forte</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Contrôle d'accès</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Statistiques -->
    <section class="py-20 primary-gradient">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                    Chiffres <span class="text-yellow-300">Impressionnants</span>
                </h2>
                <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                    Découvrez l'impact de notre solution sur la gestion documentaire.
                </p>
            </div>
            
            <div class="grid md:grid-cols-4 gap-8 text-center">
                <div class="floating">
                    <div class="text-5xl font-bold text-white mb-2">9+</div>
                    <div class="text-blue-100 text-lg">Types de Documents</div>
                </div>
                <div class="floating" style="animation-delay: 0.5s;">
                    <div class="text-5xl font-bold text-white mb-2">100%</div>
                    <div class="text-blue-100 text-lg">Sécurisé</div>
                </div>
                <div class="floating" style="animation-delay: 1s;">
                    <div class="text-5xl font-bold text-white mb-2">24/7</div>
                    <div class="text-blue-100 text-lg">Disponible</div>
                </div>
                <div class="floating" style="animation-delay: 1.5s;">
                    <div class="text-5xl font-bold text-white mb-2">∞</div>
                    <div class="text-blue-100 text-lg">Documents</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section À propos -->
    <section id="apropos" class="py-20 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-8">
                    À propos de <span class="text-gradient">RPI-PAD Archiv'Web</span>
                </h2>
                <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                    RPI-PAD Archiv'Web est une solution de gestion documentaire développée spécialement pour les administrations publiques. 
                    Notre plateforme offre une approche moderne et sécurisée pour organiser, archiver et gérer tous types de documents administratifs.
                </p>
                <div class="grid md:grid-cols-3 gap-8 mt-12">
                    <div class="text-center">
                        <div class="w-20 h-20 primary-gradient rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-bullseye text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Mission</h3>
                        <p class="text-gray-600">Simplifier la gestion documentaire des administrations publiques</p>
                    </div>
                    <div class="text-center">
                        <div class="w-20 h-20 secondary-gradient rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-eye text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Vision</h3>
                        <p class="text-gray-600">Devenir la référence en matière de solutions d'archivage numérique</p>
                    </div>
                    <div class="text-center">
                        <div class="w-20 h-20 primary-gradient rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-heart text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Valeurs</h3>
                        <p class="text-gray-600">Sécurité, efficacité et innovation au service du public</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Contact -->
    <section id="contact" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-8">
                    Prêt à <span class="text-gradient">commencer</span> ?
                </h2>
                <p class="text-xl text-gray-600 mb-12">
                    Rejoignez les administrations qui font confiance à RPI-PAD Archiv'Web pour leur gestion documentaire.
                </p>
                <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
                    <a href="login.php" class="primary-gradient text-white px-10 py-4 rounded-full font-semibold text-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-sign-in-alt mr-2"></i>Se connecter
                    </a>
                    <a href="mailto:contact@rpi-pad.gov" class="border-2 border-gray-300 text-gray-700 px-10 py-4 rounded-full font-semibold text-lg hover:border-blue-600 hover:text-blue-600 transition-all duration-300">
                        <i class="fas fa-envelope mr-2"></i>Nous contacter
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="public/images/logo.jpg" alt="RPI-PAD Logo" class="w-10 h-10 rounded-lg object-cover">
                        <span class="text-xl font-bold">RPI-PAD Archiv'Web</span>
                    </div>
                    <p class="text-gray-400">
                        Solution de gestion documentaire pour les administrations publiques.
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Fonctionnalités</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Gestion des documents</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Gestion des utilisateurs</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Audit et traçabilité</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Corbeille</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Support</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Documentation</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Aide</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-envelope mr-2"></i>contact@pad.cm</li>
                        <li><i class="fas fa-phone mr-2"></i>+237 233 420 133</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i>Douala, Cameroun</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 RPI-PAD Archiv'Web. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Smooth scrolling pour les liens d'ancrage
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Animation au scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observer les cartes de fonctionnalités
        document.querySelectorAll('.card-hover').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    </script>

</body>
</html>
