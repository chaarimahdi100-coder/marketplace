@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-8 py-12">
    <div class="bg-white rounded-2xl shadow-md p-8">
        <h2 class="text-2xl font-bold text-blue-900 mb-6">✏️ Éditer le service</h2>

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
            @foreach($errors->all() as $error)
                <p class="text-sm">❌ {{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form method="POST" action="/service/{{ $service->id }}/mettre-a-jour">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Titre</label>
                <input type="text" name="titre" required
                       value="{{ old('titre', $service->titre) }}"
                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500">
            </div>

            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Catégorie</label>
                    <select name="categorie" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500">
                        <option value="Nettoyage" {{ $service->categorie == 'Nettoyage' ? 'selected' : '' }}>Nettoyage</option>
                        <option value="Cuisine" {{ $service->categorie == 'Cuisine' ? 'selected' : '' }}>Cuisine</option>
                        <option value="Jardinage" {{ $service->categorie == 'Jardinage' ? 'selected' : '' }}>Jardinage</option>
                        <option value="Plomberie" {{ $service->categorie == 'Plomberie' ? 'selected' : '' }}>Plomberie</option>
                        <option value="Electricité" {{ $service->categorie == 'Electricité' ? 'selected' : '' }}>Electricité</option>
                        <option value="Bien-être" {{ $service->categorie == 'Bien-être' ? 'selected' : '' }}>Bien-être</option>
                        <option value="Informatique" {{ $service->categorie == 'Informatique' ? 'selected' : '' }}>Informatique</option>
                        <option value="Éducation" {{ $service->categorie == 'Éducation' ? 'selected' : '' }}>Éducation</option>
                        <option value="Photographie" {{ $service->categorie == 'Photographie' ? 'selected' : '' }}>Photographie</option>
                        <option value="Sport" {{ $service->categorie == 'Sport' ? 'selected' : '' }}>Sport</option>
                        <option value="Traduction" {{ $service->categorie == 'Traduction' ? 'selected' : '' }}>Traduction</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Prix (TND)</label>
                    <input type="number" name="prix" required min="1" step="0.01"
                           value="{{ old('prix', $service->prix) }}"
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="5"
                          class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500">{{ old('description', $service->description) }}</textarea>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-teal-500 text-white px-8 py-3 rounded-xl font-bold hover:bg-teal-600 transition">
                    💾 Mettre à jour
                </button>
                <a href="/dashboard/prestataire" class="border-2 border-gray-300 px-8 py-3 rounded-xl hover:bg-gray-50 transition">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
