@extends('layouts.app')

@section('content')

{{-- HERO --}}
<div class="bg-gradient-to-r from-blue-900 to-teal-600 text-white py-20 px-8 text-center">
    <h1 class="text-5xl font-bold mb-4">
        Trouvez le service <span class="text-yellow-400">parfait</span>
    </h1>
    <p class="text-xl mb-8 opacity-90">Des prestataires qualifiés disponibles en quelques clics</p>

    {{-- Barre de recherche --}}
    <form action="/services" method="GET"
          class="flex gap-3 justify-center flex-wrap max-w-3xl mx-auto">
        <input type="text" name="recherche"
               placeholder="Quel service cherchez-vous ?"
               class="px-5 py-3 rounded-full text-gray-800 w-64 outline-none">
        <select name="categorie"
                class="px-5 py-3 rounded-full text-gray-800 outline-none">
            <option value="">Toutes catégories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}">{{ $cat }}</option>
            @endforeach
        </select>
        <input type="number" name="prix_max"
               placeholder="Budget max (TND)"
               class="px-5 py-3 rounded-full text-gray-800 w-40 outline-none">
        <button type="submit"
                class="bg-yellow-400 text-blue-900 font-bold px-8 py-3 rounded-full hover:bg-yellow-500 transition">
            🔍 Rechercher
        </button>
    </form>

    {{-- Stats --}}
    <div class="flex justify-center gap-12 mt-10">
        <div>
            <div class="text-3xl font-bold text-teal-400">{{ $nb_services }}+</div>
            <div class="text-sm opacity-70">Services</div>
        </div>
        <div>
            <div class="text-3xl font-bold text-teal-400">{{ $nb_prestataires }}+</div>
            <div class="text-sm opacity-70">Prestataires</div>
        </div>
    </div>
</div>

{{-- SERVICES EN VEDETTE --}}
<div class="max-w-6xl mx-auto px-8 py-16">
    <h2 class="text-3xl font-bold text-blue-900 mb-2">Services populaires</h2>
    <p class="text-gray-500 mb-8">Découvrez nos prestataires les mieux notés</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($services as $service)
        <div class="bg-white rounded-2xl shadow-md p-6 hover:-translate-y-1 transition-transform border-t-4 border-teal-500">

            {{-- Badge catégorie --}}
            <span class="bg-teal-100 text-teal-700 text-xs font-bold px-3 py-1 rounded-full">
                {{ $service->categorie }}
            </span>

            <h3 class="text-lg font-bold text-blue-900 mt-3 mb-2">{{ $service->titre }}</h3>
            <p class="text-gray-500 text-sm mb-3">{{ Str::limit($service->description, 80) }}</p>

            <p class="text-sm text-gray-400 mb-1">
                👤 {{ $service->prestataire->nom }}
            </p>

            {{-- Étoiles --}}
            <div class="text-yellow-400 mb-3">
                @for($i=1; $i<=5; $i++)
                    {{ $i <= round($service->prestataire->note_globale) ? '★' : '☆' }}
                @endfor
                <small class="text-gray-400">({{ $service->avis_count }} avis)</small>
            </div>

            <div class="text-2xl font-bold text-blue-900 mb-4">{{ $service->prix }} TND</div>

            <div class="flex gap-2">
                <a href="/reservation/{{ $service->id }}"
                   class="bg-teal-500 text-white px-4 py-2 rounded-full text-sm hover:bg-teal-600 transition">
                    📅 Réserver
                </a>
                <a href="/avis/{{ $service->id }}"
                   class="border border-teal-500 text-teal-500 px-4 py-2 rounded-full text-sm hover:bg-teal-50 transition">
                    ⭐ Avis
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <div class="text-center mt-10">
        <a href="/services"
           class="bg-blue-900 text-white px-10 py-3 rounded-full text-lg hover:bg-blue-800 transition">
            Voir tous les services →
        </a>
    </div>
</div>

{{-- CARTE --}}
<div class="max-w-6xl mx-auto px-8 pb-16">
    <h2 class="text-3xl font-bold text-blue-900 mb-2">📍 Prestataires près de vous</h2>
    <p class="text-gray-500 mb-6">Trouvez un prestataire dans votre quartier</p>
    <div id="map" style="height:450px; border-radius:16px;"></div>
</div>

@endsection

@section('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
var map = L.map('map').setView([33.8869, 9.5375], 6);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

fetch('/api/prestataires')
    .then(r => r.json())
    .then(data => {
        data.forEach(p => {
            if(p.lat && p.lng) {
                // Créer une icône de personne personnalisée
                var customIcon = L.divIcon({
                    html: '<div style="background: linear-gradient(135deg, #0D9E8B 0%, #0a7a6b 100%); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); cursor: pointer;"><i class="fas fa-user" style="font-size:24px;"></i></div>',
                    iconSize: [50, 50],
                    className: 'person-marker'
                });
                L.marker([p.lat, p.lng], {icon: customIcon}).addTo(map)
                 .bindPopup('<div style="text-align:center;"><b>'+p.nom+'</b><br><small>'+p.description+'</small></div>');
            }
        });
    });

if(navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(pos => {
        map.setView([pos.coords.latitude, pos.coords.longitude], 13);
        var userIcon = L.divIcon({
            html: '<div style="background: #FF6B6B; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-map-pin"></i></div>',
            iconSize: [50, 50],
            className: 'user-marker'
        });
        L.marker([pos.coords.latitude, pos.coords.longitude], {icon: userIcon})
         .addTo(map).bindPopup('📍 Vous').openPopup();
    });
}
</script>
@endsection