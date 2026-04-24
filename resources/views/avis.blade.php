@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-8 py-12">
    <h2 class="text-3xl font-bold text-blue-900 mb-8">⭐ Avis — {{ $service->titre }}</h2>

    {{-- Résumé --}}
    <div class="bg-white rounded-2xl shadow-md p-8 text-center mb-8">
        <div class="text-6xl font-bold text-blue-900">{{ number_format($moyenne,1) }}</div>
        <div class="text-3xl text-yellow-400 my-2">
            @for($i=1;$i<=5;$i++) {{ $i<=round($moyenne)?'★':'☆' }} @endfor
        </div>
        <p class="text-gray-500">{{ $avis_list->count() }} avis au total</p>
    </div>

    {{-- Messages --}}
    @if(session('succes'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6">✅ {{ session('succes') }}</div>
    @endif
    @if(session('erreur'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6">❌ {{ session('erreur') }}</div>
    @endif

    {{-- Formulaire avis --}}
    @auth
        @if(auth()->user()->client)
        <div class="bg-white rounded-2xl shadow-md p-6 mb-8">
            <h3 class="text-xl font-bold text-blue-900 mb-4">Laisser un avis</h3>
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>❌ {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="/avis/{{ $service->id }}" onsubmit="return validateForm()">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Note *</label>
                    <div class="flex gap-2" id="stars">
                        @for($i=1;$i<=5;$i++)
                            <button type="button"
                                    class="text-4xl text-gray-300 hover:text-yellow-400 transition star-btn"
                                    data-val="{{ $i }}"
                                    onclick="setNote({{ $i }})">★</button>
                        @endfor
                    </div>
                    <input type="hidden" name="note" id="noteVal" value="0">
                    <p id="noteError" class="text-red-500 text-sm mt-1 hidden">Veuillez sélectionner une note</p>
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Commentaire *</label>
                    <textarea name="commentaire" required
                              placeholder="Partagez votre expérience..."
                              class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-teal-500 h-28 @error('commentaire') border-red-500 @enderror"
                              value="{{ old('commentaire') }}">{{ old('commentaire') }}</textarea>
                    @error('commentaire')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex gap-3">
                    <button type="submit"
                            class="bg-teal-500 text-white px-6 py-3 rounded-xl font-bold hover:bg-teal-600 transition">
                        Publier l'avis
                    </button>
                    <a href="javascript:history.back()"
                       class="bg-gray-500 text-white px-6 py-3 rounded-xl font-bold hover:bg-gray-600 transition">
                        ← Retour
                    </a>
                </div>
            </form>
        </div>
        @else
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-xl mb-8 text-center">
            ⚠️ Vous devez <a href="/profil" class="font-bold underline">compléter votre profil client</a> pour laisser un avis.
        </div>
        @endif
    @else
        <div class="bg-blue-50 text-center p-4 rounded-xl mb-8">
            <a href="/login" class="text-teal-600 font-bold">Connectez-vous</a> pour laisser un avis
        </div>
    @endauth

    {{-- Liste avis --}}
    @forelse($avis_list as $avis)
    <div class="bg-white rounded-2xl shadow-md p-6 mb-4 border-l-4 border-yellow-400">
        <div class="flex justify-between mb-2">
            <div>
                <div class="font-bold text-blue-900">👤 {{ $avis->client->prenom }} {{ $avis->client->nom }}</div>
                <div class="text-yellow-400">
                    @for($i=1;$i<=5;$i++) {{ $i<=$avis->note?'★':'☆' }} @endfor
                </div>
            </div>
            <div class="text-gray-400 text-sm">{{ $avis->created_at->format('d/m/Y') }}</div>
        </div>
        <p class="text-gray-600">{{ $avis->commentaire }}</p>
    </div>
    @empty
        <p class="text-center text-gray-400 py-8">Aucun avis pour ce service.</p>
    @endforelse
</div>
@endsection

@section('scripts')
<script>
function setNote(val) {
    document.getElementById('noteVal').value = val;
    document.getElementById('noteError').classList.add('hidden');
    document.querySelectorAll('.star-btn').forEach((s,i) => {
        s.style.color = i < val ? '#F59E0B' : '#D1D5DB';
    });
}

function validateForm() {
    const noteVal = document.getElementById('noteVal').value;
    const noteError = document.getElementById('noteError');
    
    if (noteVal == 0 || noteVal == '') {
        noteError.classList.remove('hidden');
        return false;
    }
    return true;
}
</script>
@endsection