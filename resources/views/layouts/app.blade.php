<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MarketPlace</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

<nav class="bg-blue-900 text-white px-8 py-4 flex justify-between items-center sticky top-0 z-50 shadow-lg">
    
    <a href="/" class="text-xl font-bold">
        Market<span class="text-teal-400">Place</span>
    </a>

    <div class="flex gap-4 items-center">
        <a href="/" class="hover:text-teal-400">Accueil</a>
        <a href="/services" class="hover:text-teal-400">Services</a>
        <a href="/messagerie" class="hover:text-teal-400">Messages</a>
        <a href="/chatbot" class="hover:text-teal-400">Assistant IA</a>

        @auth
            @if(auth()->user()->client)
                <a href="/dashboard/client" class="hover:text-teal-400">Mon espace</a>
            @elseif(auth()->user()->prestataire)
                <a href="/dashboard/prestataire" class="hover:text-teal-400">Mon espace</a>
            @else
                <a href="/inscription/client" class="hover:text-teal-400">Mon espace</a>
            @endif
            <form method="POST" action="/logout" style="display:inline">
                @csrf
                <button class="text-gray-400 hover:text-white">Déconnexion</button>
            </form>
        @else
            <a href="/login" class="hover:text-teal-400">Connexion</a>
            <a href="/inscription/client"
               class="bg-teal-500 px-4 py-2 rounded-full hover:bg-teal-600 text-sm">
                👤 Client
            </a>
            <a href="/inscription/prestataire"
               class="bg-blue-600 px-4 py-2 rounded-full hover:bg-blue-700 text-sm">
                🔧 Prestataire
            </a>
        @endauth
    </div>
</nav>

<main>
    @yield('content')
</main>

<footer class="bg-blue-900 text-white text-center py-6 mt-16">
    <p>© 2026 <strong class="text-teal-400">MarketPlace</strong> — Plateforme de services et réservations en ligne.</p>
</footer>

@yield('scripts')

</body>
</html>