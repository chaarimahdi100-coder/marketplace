@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-8 py-12">
    <h2 class="text-3xl font-bold text-blue-900 mb-8">📅 Réserver un service</h2>

    {{-- Info service --}}
    <div class="bg-white rounded-2xl shadow-md p-6 mb-8 border-t-4 border-teal-500">
        <span class="bg-teal-100 text-teal-700 text-xs font-bold px-3 py-1 rounded-full">
            {{ $service->categorie }}
        </span>
        <h3 class="text-2xl font-bold text-blue-900 mt-3">{{ $service->titre }}</h3>
        <p class="text-gray-500 mt-2">{{ $service->description }}</p>
        <div class="flex gap-8 mt-4">
            <div>
                <div class="text-xs text-gray-400 uppercase">Prestataire</div>
                <div class="font-bold">👤 {{ $service->prestataire->nom }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 uppercase">Prix</div>
                <div class="text-3xl font-bold text-teal-500">{{ $service->prix }} TND</div>
            </div>
        </div>
    </div>

    {{-- Messages --}}
    @if(session('succes'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6">
            ✅ {{ session('succes') }}
        </div>
    @endif
    @if(session('erreur'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6">
            ❌ {{ session('erreur') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        {{-- Formulaire réservation --}}
        @if(session('id_resa') || isset($reservationPending))
        {{-- Formulaire paiement --}}
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="text-xl font-bold text-blue-900 mb-4">💳 Paiement</h3>
            <form method="POST" action="/paiement">
                @csrf
                <input type="hidden" name="id_reservation" value="{{ session('id_resa') ?? $reservationPending->id }}">
                @if(isset($reservationPending))
                    <p class="mb-4 text-sm text-gray-500">Réservation en attente : <strong>{{ \Carbon\Carbon::parse($reservationPending->date)->format('d/m/Y') }}</strong> à <strong>{{ substr($reservationPending->heure,0,5) }}</strong></p>
                @endif
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Numéro de carte</label>
                    <input type="text" placeholder="1234 5678 9012 3456" maxlength="19"
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500">
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Expiration</label>
                        <input type="text" placeholder="MM/AA" maxlength="5"
                               class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">CVV</label>
                        <input type="text" placeholder="123" maxlength="3"
                               class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500">
                    </div>
                </div>
                <button type="submit"
                        class="w-full bg-teal-500 text-white py-3 rounded-xl font-bold hover:bg-teal-600 transition">
                    🔒 Payer {{ $service->prix }} TND
                </button>
            </form>
        </div>
        @else
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="text-xl font-bold text-blue-900 mb-4">Choisir un créneau</h3>
            <form method="POST" action="/reservation/{{ $service->id }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Date</label>
                    <input type="date" name="date" required
                           min="{{ date('Y-m-d') }}"
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500">
                </div>
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Heure</label>
                    <select name="heure" required
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500">
                        <option value="">-- Choisir --</option>
                        @foreach(['08:00:00','09:00:00','10:00:00','11:00:00','14:00:00','15:00:00','16:00:00','17:00:00'] as $h)
                            <option value="{{ $h }}">{{ substr($h,0,5) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                        class="w-full bg-teal-500 text-white py-3 rounded-xl font-bold hover:bg-teal-600 transition">
                    ✅ Confirmer la réservation
                </button>
            </form>
        </div>
        @endif

        {{-- Calendrier --}}
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="text-xl font-bold text-blue-900 mb-4">📅 Créneaux réservés</h3>
            <div id="calendar"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var creneaux = @json($creneaux);
    var events   = creneaux.map(c => ({
        title: '🔒 Réservé',
        date:  c.date,
        color: '#EF4444'
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