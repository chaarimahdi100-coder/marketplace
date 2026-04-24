<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Client</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-900 to-teal-600 min-h-screen flex items-center justify-center p-4">

<div class="bg-white rounded-2xl shadow-xl p-10 w-full max-w-md">

    <div class="text-center mb-8">
        <div class="text-5xl mb-3">👤</div>
        <h2 class="text-2xl font-bold text-blue-900">Inscription Client</h2>
        <p class="text-gray-500 text-sm mt-1">Trouvez des services près de chez vous</p>
    </div>

    {{-- Afficher les erreurs --}}
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4">
        @foreach($errors->all() as $error)
            <p class="text-sm">❌ {{ $error }}</p>
        @endforeach
    </div>
    @endif

    <form method="POST" action="/inscription/client">
        @csrf

        {{-- Nom et Prénom --}}
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">
                    Nom *
                </label>
                <input type="text" name="nom" required
                       value="{{ old('nom') }}"
                       placeholder="Dupont"
                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500 transition">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">
                    Prénom *
                </label>
                <input type="text" name="prenom" required
                       value="{{ old('prenom') }}"
                       placeholder="Jean"
                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500 transition">
            </div>
        </div>

        {{-- Email --}}
        <div class="mb-4">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">
                Email *
            </label>
            <input type="email" name="email" required
                   value="{{ old('email') }}"
                   placeholder="jean@gmail.com"
                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500 transition">
        </div>

        {{-- Adresse --}}
        <div class="mb-4">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">
                Adresse
            </label>
            <input type="text" name="adresse"
                   value="{{ old('adresse') }}"
                   placeholder="123 rue de Paris"
                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500 transition">
        </div>

        {{-- Mot de passe --}}
        <div class="mb-4">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">
                Mot de passe * (min. 6 caractères)
            </label>
            <input type="password" name="password" required
                   placeholder="••••••"
                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500 transition">
        </div>

        {{-- Confirmer mot de passe --}}
        <div class="mb-6">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">
                Confirmer mot de passe *
            </label>
            <input type="password" name="password_confirmation" required
                   placeholder="••••••"
                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500 transition">
        </div>

        {{-- Bouton --}}
        <button type="submit"
                class="w-full bg-teal-500 text-white py-3 rounded-xl font-bold text-lg hover:bg-teal-600 transition">
            ✅ Créer mon compte client
        </button>
    </form>

    {{-- Liens --}}
    <div class="text-center mt-6 space-y-2">
        <p class="text-sm text-gray-500">
            Vous êtes prestataire ?
            <a href="/inscription/prestataire" class="text-teal-600 font-bold hover:underline">
                S'inscrire ici
            </a>
        </p>
        <p class="text-sm text-gray-500">
            Déjà inscrit ?
            <a href="/login" class="text-teal-600 font-bold hover:underline">
                Se connecter
            </a>
        </p>
    </div>
</div>

</body>
</html>