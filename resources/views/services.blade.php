@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-8 py-12">
    <h2 class="text-3xl font-bold text-blue-900 mb-6">🔍 Rechercher un service</h2>

    {{-- Filtres --}}
    <form method="GET" action="/services"
          class="bg-white p-6 rounded-2xl shadow-md mb-8 flex gap-4 flex-wrap items-end">
        <div class="flex-1 min-w-48">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Recherche</label>
            <input type="text" name="recherche"
                   value="{{ request('recherche') }}"
                   placeholder="Nettoyage, cuisine..."
                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500">
        </div>
        <div class="flex-1 min-w-40">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Catégorie</label>
            <select name="categorie"
                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500">
                <option value="">Toutes</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('categorie') == $cat ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-36">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Prix max (TND)</label>
            <input type="number" name="prix_max"
                   value="{{ request('prix_max') }}"
                   placeholder="500"
                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500">
        </div>
        <button type="submit"
                class="bg-teal-500 text-white px-6 py-3 rounded-xl hover:bg-teal-600 transition font-bold">
            Filtrer
        </button>
        <a href="/services"
           class="border-2 border-gray-300 px-6 py-3 rounded-xl hover:bg-gray-50 transition">
            Reset
        </a>
    </form>

    <p class="text-gray-500 mb-6"><strong>{{ $services->count() }}</strong> service(s) trouvé(s)</p>

    @if($services->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <div class="text-6xl mb-4">🔍</div>
            <p class="text-xl">Aucun service trouvé</p>
            <a href="/services"
               class="mt-4 inline-block bg-teal-500 text-white px-6 py-3 rounded-full">
               Voir tout
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($services as $service)
            <div class="bg-white rounded-2xl shadow-md p-6 hover:-translate-y-1 transition-transform border-t-4 border-teal-500">
                <span class="bg-teal-100 text-teal-700 text-xs font-bold px-3 py-1 rounded-full">
                    {{ $service->categorie }}
                </span>
                <h3 class="text-lg font-bold text-blue-900 mt-3 mb-2">{{ $service->titre }}</h3>
                <p class="text-gray-500 text-sm mb-3">{{ Str::limit($service->description, 80) }}</p>
                <p class="text-sm text-gray-400">👤 {{ $service->prestataire->nom }}</p>
                <div class="text-yellow-400 my-2">
                    @for($i=1;$i<=5;$i++)
                        {{ $i<=round($service->prestataire->note_globale)?'★':'☆' }}
                    @endfor
                    <small class="text-gray-400">({{ $service->avis_count }} avis)</small>
                </div>
                <div class="text-2xl font-bold text-blue-900 mb-4">{{ $service->prix }} TND</div>
                <div class="flex gap-2">
                    <a href="/reservation/{{ $service->id }}"
                       class="bg-teal-500 text-white px-4 py-2 rounded-full text-sm hover:bg-teal-600 transition">
                       Réserver
                    </a>
                    <a href="/messagerie?avec={{ $service->prestataire->user_id }}"
                       class="border border-teal-500 text-teal-500 px-4 py-2 rounded-full text-sm hover:bg-teal-50 transition">
                       Contacter
                    </a>
                    <a href="/avis/{{ $service->id }}"
                       class="border border-teal-500 text-teal-500 px-4 py-2 rounded-full text-sm hover:bg-teal-50 transition">
                       Avis
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    {{-- Carte --}}
    <div class="mt-12">
        <h2 class="text-2xl font-bold text-blue-900 mb-4">📍 Carte</h2>
        <div id="map" style="height:400px; border-radius:16px;"></div>
    </div>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
var map = L.map('map').setView([33.8869, 9.5375], 6);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
var services = @json($services);
services.forEach(s => {
    var lat = s.prestataire?.lat || (33.8 + Math.random()*0.2);
    var lng = s.prestataire?.lng || (9.5  + Math.random()*0.2);
    // Créer une icône de personne avec initiales
    var initials = (s.prestataire?.nom || 'P').substring(0, 2).toUpperCase();
    var customIcon = L.divIcon({
        html: '<div style="background: linear-gradient(135deg, #0D9E8B 0%, #0a7a6b 100%); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); cursor: pointer;"><i class="fas fa-user" style="font-size:24px;"></i></div>',
        iconSize: [50, 50],
        className: 'person-marker'
    });
    L.marker([lat,lng], {icon: customIcon}).addTo(map)
     .bindPopup('<div style="text-align:center;"><b>'+s.titre+'</b><br><strong>'+s.prix+' TND</strong><br>👤 '+s.prestataire?.nom+'</div>');
});
</script>
@endsection