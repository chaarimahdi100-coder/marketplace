@extends('layouts.app')

@section('content')
<div style="display:grid; grid-template-columns:250px 1fr; min-height:calc(100vh - 70px);">

    {{-- Sidebar --}}
    <div class="bg-blue-900 text-white p-6">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-teal-500 rounded-full flex items-center justify-content text-2xl font-bold mx-auto mb-3">
                {{ strtoupper(substr($prestataire->nom,0,2)) }}
            </div>
            <div class="font-bold">{{ $prestataire->nom }}</div>
            <div class="text-xs bg-teal-500 px-3 py-1 rounded-full mt-2 inline-block">Prestataire</div>
        </div>
        <nav class="space-y-2">
            <a href="#stats"        class="flex items-center gap-3 px-4 py-3 rounded-xl bg-teal-600 text-sm">📊 Dashboard</a>
            <a href="#reservations" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-teal-600 text-sm transition">📅 Réservations</a>
            <a href="#services"     class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-teal-600 text-sm transition">🛠️ Services</a>
            <a href="#dispos"       class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-teal-600 text-sm transition">📆 Disponibilités</a>
            <a href="/messagerie"   class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-teal-600 text-sm transition">💬 Messages</a>
            <a href="/profil/prestataire/editer" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-teal-600 text-sm transition">✏️ Mon profil</a>
        </nav>
    </div>

    {{-- Contenu --}}
    <div class="p-8 bg-gray-50">
        <h2 class="text-2xl font-bold text-blue-900 mb-1">Bonjour, {{ $prestataire->nom }} ! 👋</h2>
        <p class="text-gray-500 mb-6">Gérez vos services et disponibilités</p>

        @if(session('succes'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6">✅ {{ session('succes') }}</div>
        @endif

        {{-- Stats --}}
        <div class="grid grid-cols-4 gap-4 mb-8" id="stats">
            <div class="bg-white rounded-2xl shadow p-6 border-t-4 border-teal-500">
                <div class="text-3xl mb-2">📅</div>
                <div class="text-3xl font-bold text-blue-900">{{ $reservations->count() }}</div>
                <div class="text-gray-400 text-sm">Réservations</div>
            </div>
            <div class="bg-white rounded-2xl shadow p-6 border-t-4 border-teal-500">
                <div class="text-3xl mb-2">🛠️</div>
                <div class="text-3xl font-bold text-blue-900">{{ $services->count() }}</div>
                <div class="text-gray-400 text-sm">Services</div>
            </div>
            <div class="bg-white rounded-2xl shadow p-6 border-t-4 border-teal-500">
                <div class="text-3xl mb-2">⭐</div>
                <div class="text-3xl font-bold text-blue-900">{{ number_format($prestataire->note_globale ?? 0,1) }}/5</div>
                <div class="text-gray-400 text-sm">Note moyenne</div>
            </div>
            <div class="bg-white rounded-2xl shadow p-6 border-t-4 border-teal-500">
                <div class="text-3xl mb-2">💶</div>
                <div class="text-3xl font-bold text-blue-900">{{ number_format($revenus,0) }} TND</div>
                <div class="text-gray-400 text-sm">Revenus</div>
            </div>
        </div>

        {{-- Réservations --}}
        <div class="bg-white rounded-2xl shadow overflow-hidden mb-6" id="reservations">
            <div class="px-6 py-4 border-b">
                <h3 class="font-bold text-blue-900">📅 Réservations reçues</h3>
            </div>
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $r)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium">{{ $r->service->titre }}</td>
                        <td class="px-6 py-4">
                            @if($r->client)
                                {{ $r->client->prenom ?? '' }} {{ $r->client->nom ?? '' }}
                            @else
                                Client inconnu
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ \Carbon\Carbon::parse($r->date ?? now())->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                {{ $r->statut=='confirmée' ? 'bg-green-100 text-green-800' : ($r->statut=='annulée' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ $r->statut }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($r->statut == 'en attente')
                            <form method="POST" action="/reservation/confirmer" style="display:flex; gap:6px;">
                                @csrf
                                <input type="hidden" name="id_resa" value="{{ $r->id }}">
                                <button name="action" value="confirmer"
                                        class="bg-green-500 text-white px-3 py-1 rounded-full text-xs">✅</button>
                                <button name="action" value="refuser"
                                        class="bg-red-500 text-white px-3 py-1 rounded-full text-xs">❌</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">Aucune réservation</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Services --}}
        <div class="bg-white rounded-2xl shadow overflow-hidden mb-6" id="services">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="font-bold text-blue-900">🛠️ Mes services</h3>
                <button onclick="document.getElementById('modalService').style.display='flex'"
                        class="bg-teal-500 text-white px-4 py-2 rounded-full text-sm">+ Ajouter</button>
            </div>
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Titre</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Catégorie</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Prix</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $s)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium">{{ $s->titre }}</td>
                        <td class="px-6 py-4">
                            <span class="bg-teal-100 text-teal-700 px-3 py-1 rounded-full text-xs">{{ $s->categorie }}</span>
                        </td>
                        <td class="px-6 py-4 font-bold">{{ $s->prix }} TND</td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="/service/{{ $s->id }}/editer"
                               class="text-blue-500 text-sm hover:underline">✏️ Éditer</a>
                            <form method="POST" action="/service/supprimer" style="display:inline;">
                                @csrf
                                <input type="hidden" name="id_service" value="{{ $s->id }}">
                                <button class="text-red-500 text-sm hover:underline"
                                        onclick="return confirm('Supprimer ?')">🗑️ Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">Aucun service</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Disponibilités --}}
        <div class="bg-white rounded-2xl shadow p-6" id="dispos">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-blue-900">📆 Mes disponibilités</h3>
            </div>
            <form method="POST" action="/disponibilite/ajouter"
                  class="flex gap-4 mb-6 flex-wrap items-end">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Début</label>
                    <input type="datetime-local" name="date_debut" required
                           class="border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Fin</label>
                    <input type="datetime-local" name="date_fin" required
                           class="border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500">
                </div>
                <button type="submit"
                        class="bg-teal-500 text-white px-6 py-3 rounded-xl font-bold hover:bg-teal-600 transition">
                    + Ajouter
                </button>
            </form>
            
            <table class="w-full mb-6">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Début</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Fin</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($disponibilites as $d)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($d->date_debut)->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($d->date_fin)->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="/disponibilite/{{ $d->id }}/editer"
                               class="text-blue-500 text-sm hover:underline">✏️ Éditer</a>
                            <form method="POST" action="/disponibilite/{{ $d->id }}/supprimer" style="display:inline;">
                                @csrf
                                <button class="text-red-500 text-sm hover:underline"
                                        onclick="return confirm('Supprimer ?')">🗑️ Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="3" class="px-6 py-8 text-center text-gray-400">Aucune disponibilité</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div id="calendar"></div>
        </div>
    </div>
</div>

{{-- Modal ajouter service --}}
<div id="modalService"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:50; align-items:center; justify-content:center;">
    <div class="bg-white rounded-2xl p-8 w-full max-w-md relative">
        <button onclick="document.getElementById('modalService').style.display='none'"
                style="position:absolute; top:16px; right:16px; background:none; border:none; font-size:20px; cursor:pointer;">✕</button>
        <h3 class="text-xl font-bold text-blue-900 mb-6">Ajouter un service</h3>
        <form method="POST" action="/service/ajouter">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Titre</label>
                <input type="text" name="titre" required
                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500">
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Catégorie</label>
                    <select name="categorie" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none">
                        <option>Nettoyage</option>
                        <option>Cuisine</option>
                        <option>Jardinage</option>
                        <option>Plomberie</option>
                        <option>Electricité</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Prix (TND)</label>
                    <input type="number" name="prix" required min="1"
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500">
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Description</label>
                <textarea name="description"
                          class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500 h-24"></textarea>
            </div>
            <button type="submit"
                    class="w-full bg-teal-500 text-white py-3 rounded-xl font-bold hover:bg-teal-600 transition">
                Créer le service
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var dispos = @json($disponibilites);
    var events = dispos.map(d => ({
        title: '✅ Disponible',
        start: d.date_debut,
        end:   d.date_fin,
        color: '#0D9E8B'
    }));
    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        locale: 'fr',
        events: events
    });
    calendar.render();
});
</script>
@endsection