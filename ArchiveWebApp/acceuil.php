<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceuil</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .library-bg {
            background-image: url(public/images/background.jpg);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .text-blue-gradient {
            background-image: linear-gradient(180deg, rgba(255,255,255,0.8) 0%, rgba(100,200,255,0.2) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
    </style>
</head>
<body class="bg-gray-800 text-white">

    <!-- Section d'en-tête (Header) -->
    <header class="absolute top-0 left-0 w-full z-10 p-6 sm:p-8">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-xl font-bold">RPI-PAD Archiv'Web</a>
            <nav class="hidden md:flex space-x-6">
                <a href="#about" class="text-gray-300 hover:text-white transition-colors duration-300">Browse</a>
                <a href="#projects" class="text-gray-300 hover:text-white transition-colors duration-300">Organize</a>
                <a href="#contact" class="text-gray-300 hover:text-white transition-colors duration-300">Share</a>
            </nav>
            <button class="md:hidden text-gray-300 hover:text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>
    </header>

    <!-- Section principale avec l'image de fond et le contenu -->
    <main class="h-screen w-full flex items-center justify-center library-bg relative">
        <div class="absolute inset-0 bg-gray-900 opacity-70"></div>
        <div class="relative z-10 text-center px-4">
            <h1 class="text-5xl sm:text-7xl font-bold leading-tight tracking-wider uppercase text-blue-gradient">
                RPI-PAD Archiv'Web
            </h1>
            <p class="mt-4 sm:mt-6 text-base sm:text-xl max-w-2xl mx-auto text-gray-300">
                Votre solution pour gérer et oraginser vos documents.
            </p>
            <div class="mt-8 sm:mt-10">
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-full shadow-lg transition-colors duration-300">
                    <a href="login.php">COMMENCER</a>
                </button>
            </div>
        </div>
    </main>

</body>
</html>
