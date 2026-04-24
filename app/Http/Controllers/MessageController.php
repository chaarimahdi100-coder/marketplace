<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;

class MessageController extends Controller
{
    // Page messagerie
    public function index(Request $request)
    {
        $user_id = auth()->id();

        // Tous les autres utilisateurs pour démarrer une conversation
        $contacts = User::where('id', '!=', $user_id)->get();

        // Conversation active
        $avec     = $request->avec ?? ($contacts->first()->id ?? null);
        $messages = [];
        $contact  = null;

        if ($avec) {
            $messages = Message::where(function($q) use ($user_id, $avec) {
                $q->where('expediteur_id', $user_id)
                  ->where('destinataire_id', $avec);
            })->orWhere(function($q) use ($user_id, $avec) {
                $q->where('expediteur_id', $avec)
                  ->where('destinataire_id', $user_id);
            })->orderBy('created_at')->get();

            $contact = User::find($avec);

            // Marquer comme lus
            Message::where('expediteur_id', $avec)
                   ->where('destinataire_id', $user_id)
                   ->update(['lu' => true]);
        }

        return view('messagerie', compact('contacts', 'messages', 'contact', 'avec'));
    }

    // Envoyer un message
    public function store(Request $request)
    {
        $request->validate([
            'contenu'          => 'required|string',
            'destinataire_id'  => 'required|exists:users,id',
        ]);

        Message::create([
            'contenu'          => $request->contenu,
            'lu'               => false,
            'expediteur_id'    => auth()->id(),
            'destinataire_id'  => $request->destinataire_id,
        ]);

        return redirect()->route('messagerie', ['avec' => $request->destinataire_id]);
    }
}
