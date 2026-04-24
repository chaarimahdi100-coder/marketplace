@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-8 py-12">
    <h2 class="text-3xl font-bold text-blue-900 mb-6">🤖 Assistant IA</h2>

    <div class="bg-white rounded-3xl shadow-md p-6">
        <div id="chat-window" class="h-[520px] overflow-y-auto border border-gray-200 rounded-3xl p-6 bg-gray-50">
            <div class="max-w-md bg-blue-900 text-white p-4 rounded-3xl mb-4">
                Bonjour ! Je suis votre assistant IA. Posez-moi une question sur la réservation, le paiement ou la messagerie.
            </div>
        </div>

        <form id="chat-form" class="mt-6 flex gap-3" autocomplete="off">
            @csrf
            <input id="chat-input" name="message" type="text" placeholder="Écrire un message..."
                   class="flex-1 border-2 border-gray-200 rounded-full px-5 py-3 outline-none focus:border-teal-500">
            <button type="submit"
                    class="bg-teal-500 text-white px-6 py-3 rounded-full font-bold hover:bg-teal-600 transition">
                Envoyer
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
const chatWindow = document.getElementById('chat-window');
const chatForm = document.getElementById('chat-form');
const chatInput = document.getElementById('chat-input');

function addBubble(text, isUser = false) {
    const bubble = document.createElement('div');
    bubble.className = isUser ? 'max-w-md ml-auto bg-teal-500 text-white p-4 rounded-3xl mb-4' : 'max-w-md bg-blue-900 text-white p-4 rounded-3xl mb-4';
    bubble.textContent = text;
    chatWindow.appendChild(bubble);
    chatWindow.scrollTop = chatWindow.scrollHeight;
}

chatForm.addEventListener('submit', function(event) {
    event.preventDefault();
    const message = chatInput.value.trim();
    if (!message) return;

    addBubble(message, true);
    chatInput.value = '';
    chatInput.disabled = true;

    fetch('/chatbot', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ message })
    })
    .then(response => response.json())
    .then(data => {
        addBubble(data.answer || 'Désolé, je n’ai pas compris.');
    })
    .catch(() => {
        addBubble('Erreur de connexion. Réessayez plus tard.');
    })
    .finally(() => {
        chatInput.disabled = false;
        chatInput.focus();
    });
});
</script>
@endsection
