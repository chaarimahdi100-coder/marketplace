<nav class="bg-blue-900 text-white px-8 py-4 flex justify-between items-center sticky top-0 z-50 shadow-lg">
    
    <a href="/" class="text-xl font-bold">
        Market<span class="text-teal-400">Place</span>
    </a>

    <div class="flex gap-4 items-center">
        <a href="/" class="hover:text-teal-400 transition">Accueil</a>
        <a href="/services" class="hover:text-teal-400 transition">Services</a>
        <a href="/messagerie" class="hover:text-teal-400 transition">Messages</a>
        <a href="/chatbot" class="hover:text-teal-400 transition">Assistant IA</a>

        @auth
            @if(auth()->user()->client)
                <a href="/dashboard/client" class="hover:text-teal-400 transition">Mon espace</a>
            @else
                <a href="/dashboard/prestataire" class="hover:text-teal-400 transition">Mon espace</a>
            @endif
            <form method="POST" action="/logout" style="display:inline">
                @csrf
                <button class="text-gray-400 hover:text-white">Déconnexion</button>
            </form>
        @else
            <a href="/login" class="hover:text-teal-400">Connexion</a>
            <a href="/register" class="bg-teal-500 px-4 py-2 rounded-full hover:bg-teal-600">S'inscrire</a>
        @endauth
    </div>
</nav>