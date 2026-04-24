@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-8 py-12">
    <div class="bg-white rounded-2xl shadow-md p-8">
        <h2 class="text-2xl font-bold text-blue-900 mb-6">✏️ Éditer mon profil</h2>

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
            @foreach($errors->all() as $error)
                <p class="text-sm">❌ {{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form method="POST" action="/profil/client/mettre-a-jour">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Prénom</label>
                <input type="text" name="prenom" required
                       value="{{ old('prenom', $client->prenom) }}"
                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Nom</label>
                <input type="text" name="nom" required
                       value="{{ old('nom', $client->nom) }}"
                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Adresse</label>
                <input type="text" name="adresse"
                       value="{{ old('adresse', $client->adresse) }}"
                       placeholder="123 rue de la Paix, Tunis"
                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500">
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-teal-500 text-white px-8 py-3 rounded-xl font-bold hover:bg-teal-600 transition">
                    💾 Mettre à jour
                </button>
                <a href="/dashboard/client" class="border-2 border-gray-300 px-8 py-3 rounded-xl hover:bg-gray-50 transition">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
