@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-8 py-12">
    <h2 class="text-3xl font-bold text-blue-900 mb-6">💬 Messagerie</h2>

    <div class="bg-white rounded-2xl shadow-md overflow-hidden" style="height:600px; display:grid; grid-template-columns:280px 1fr;">

        {{-- Liste conversations --}}
        <div style="border-right:1px solid #F1F5F9; overflow-y:auto;">
            <div class="bg-blue-900 text-white px-5 py-4 font-bold">Conversations</div>

            @forelse($contacts as $conv)
            <a href="/messagerie?avec={{ $conv->id }}"
               class="block px-5 py-4 border-b hover:bg-teal-50 transition {{ $avec == $conv->id ? 'bg-teal-50 border-l-4 border-teal-500' : '' }}">
                <div class="font-bold text-blue-900 text-sm">👤 {{ $conv->name }}</div>
                <div class="text-gray-400 text-xs mt-1">Cliquer pour voir</div>
            </a>
            @empty
                <div class="p-5 text-gray-400 text-sm text-center">Aucun autre utilisateur</div>
            @endforelse
        </div>

        {{-- Zone messages --}}
        <div style="display:flex; flex-direction:column;">
            @if($contact)
                <div class="px-5 py-4 bg-gray-50 border-b font-bold text-blue-900">
                    💬 {{ $contact->name }}
                </div>

                {{-- Messages --}}
                <div style="flex:1; overflow-y:auto; padding:20px; display:flex; flex-direction:column; gap:10px;" id="msgs">
                    @forelse($messages as $msg)
                        @if($msg->expediteur_id == auth()->id())
                            <div style="align-self:flex-end; background:#0D9E8B; color:white; padding:10px 16px; border-radius:18px 18px 4px 18px; max-width:70%; font-size:14px;">
                                {{ $msg->contenu }}
                                <div style="font-size:10px; opacity:0.7; margin-top:4px;">{{ $msg->created_at->format('H:i') }}</div>
                            </div>
                        @else
                            <div style="align-self:flex-start; background:#F1F5F9; padding:10px 16px; border-radius:18px 18px 18px 4px; max-width:70%; font-size:14px;">
                                {{ $msg->contenu }}
                                <div style="font-size:10px; opacity:0.5; margin-top:4px;">{{ $msg->created_at->format('H:i') }}</div>
                            </div>
                        @endif
                    @empty
                        <div class="text-center text-gray-400 m-auto">👋 Démarrez la conversation !</div>
                    @endforelse
                </div>

                {{-- Zone saisie --}}
                <div style="padding:16px; border-top:1px solid #F1F5F9;">
                    <form method="POST" action="/messagerie" style="display:flex; gap:10px;">
                        @csrf
                        <input type="hidden" name="destinataire_id" value="{{ $avec }}">
                        <input type="text" name="contenu" placeholder="Écrire un message..."
                               required autocomplete="off"
                               style="flex:1; padding:11px 18px; border:2px solid #E2E8F0; border-radius:50px; outline:none; font-size:14px;">
                        <button type="submit"
                                style="background:#0D9E8B; color:white; padding:11px 20px; border-radius:50px; border:none; cursor:pointer; font-weight:bold;">
                            ✈️
                        </button>
                    </form>
                </div>
            @else
                <div style="display:flex; align-items:center; justify-content:center; flex:1; color:#94A3B8; flex-direction:column; gap:16px;">
                    <div style="font-size:48px;">💬</div>
                    <p>Sélectionnez une conversation</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
var msgs = document.getElementById('msgs');
if(msgs) msgs.scrollTop = msgs.scrollHeight;
</script>
@endsection