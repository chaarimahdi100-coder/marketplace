@extends('layouts.app')

@section('content')
<div style="display:grid; grid-template-columns:250px 1fr; min-height:calc(100vh - 70px);">

    {{-- Sidebar --}}
    <div class="bg-blue-900 text-white p-6">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-teal-500 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-3">
                {{ strtoupper(substr($client->prenom,0,1).substr($client->nom,0,1)) }}
            </div>
            <div class="font-bold">{{ $client->prenom }} {{ $client->nom }}</div>
            <div class="text-xs bg-teal-500 px-3 py-1 rounded-full mt-2 inline-block">Client</div>
        </div>
        <nav class="space-y-2">
            <a href="#stats"        class="flex items-center gap-3 px-4 py-3 rounded-xl bg-teal-600 text-white text-sm">📊 Dashboard</a>
            <a href="#reservations" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-teal-600 text-sm transition">📅 Réservations</a>
            <a href="#paiements"    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-teal-600 text-sm transition">💳 Paiements</a>
            <a href="/services"     class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-teal-600 text-sm transition">🔍 Services</a>
            <a href="/messagerie"   class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-teal-600 text-sm transition">💬 Messages</a>
            <a href="/profil/client/editer" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-teal-600 text-sm transition">✏️ Mon profil</a>
        </nav>
    </div>

    {{-- Contenu --}}
    <div class="p-8 bg-gray-50">
        <h2 class="text-2xl font-bold text-blue-900 mb-1">Bonjour, {{ $client->prenom }} ! 👋</h2>
        <p class="text-gray-500 mb-6">Voici votre espace personnel</p>

        @if(session('succes'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6">✅ {{ session('succes') }}</div>
        @endif

        {{-- Stats --}}
        <div class="grid grid-cols-3 gap-4 mb-8" id="stats">
            <div class="bg-white rounded-2xl shadow p-6 border-t-4 border-teal-500">
                <div class="text-3xl mb-2">📅</div>
                <div class="text-3xl font-bold text-blue-900">{{ $reservations->count() }}</div>
                <div class="text-gray-400 text-sm">Réservations</div>
            </div>
            <div class="bg-white rounded-2xl shadow p-6 border-t-4 border-teal-500">
                <div class="text-3xl mb-2">⭐</div>
                <div class="text-3xl font-bold text-blue-900">{{ $nb_avis }}</div>
                <div class="text-gray-400 text-sm">Avis publiés</div>
            </div>
            <div class="bg-white rounded-2xl shadow p-6 border-t-4 border-teal-500">
                <div class="text-3xl mb-2">💶</div>
                <div class="text-3xl font-bold text-blue-900">{{ number_format($depenses,0) }} TND</div>
                <div class="text-gray-400 text-sm">Total dépensé</div>
            </div>
        </div>

        {{-- Réservations --}}
        <div class="bg-white rounded-2xl shadow overflow-hidden mb-6" id="reservations">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="font-bold text-blue-900">📅 Mes réservations</h3>
                <a href="/services" class="bg-teal-500 text-white px-4 py-2 rounded-full text-sm">+ Nouvelle</a>
            </div>
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Prix</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $r)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium">{{ $r->service->titre }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ \Carbon\Carbon::parse($r->date)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 font-bold">{{ $r->service->prix }} TND</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                {{ $r->statut=='confirmée' ? 'bg-green-100 text-green-800' : ($r->statut=='annulée' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ $r->statut }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($r->statut == 'en attente')
                            <form method="POST" action="/reservation/annuler" style="display:inline">
                                @csrf
                                <input type="hidden" name="id_resa" value="{{ $r->id }}">
                                <button class="text-red-500 text-sm hover:underline"
                                        onclick="return confirm('Annuler ?')">Annuler</button>
                            </form>
                            <form method="POST" action="/paiement" style="display:inline; margin-left:0.75rem;">
                                @csrf
                                <input type="hidden" name="id_reservation" value="{{ $r->id }}">
                                <button class="text-teal-500 text-sm hover:underline">Payer</button>
                            </form>
                            @endif
                            <a href="/avis/{{ $r->service_id }}" class="text-teal-500 text-sm ml-2 hover:underline">Avis</a>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">Aucune réservation</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paiements --}}
        <div class="bg-white rounded-2xl shadow overflow-hidden" id="paiements">
            <div class="px-6 py-4 border-b">
                <h3 class="font-bold text-blue-900">💳 Historique des paiements</h3>
            </div>
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Méthode</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paiements as $p)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $p->reservation->service->titre }}</td>
                        <td class="px-6 py-4 font-bold">{{ $p->montant }} TND</td>
                        <td class="px-6 py-4 text-gray-500">{{ $p->methode }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                {{ $p->statut=='payé' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $p->statut }}
                            </span>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">Aucun paiement</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection