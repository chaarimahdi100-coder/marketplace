<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Avis;
use App\Models\Service;

class AvisController extends Controller
{
    // Page des avis
    public function index($id)
    {
        $service   = Service::with('prestataire')->findOrFail($id);
        $avis_list = Avis::with('client')
                     ->where('service_id', $id)
                     ->latest()
                     ->get();
        $moyenne   = $avis_list->avg('note') ?? 0;

        return view('avis', compact('service', 'avis_list', 'moyenne'));
    }

    // Publier un avis
    public function store(Request $request, $id)
    {
        // Vérifier que l'utilisateur a un profil client
        if (!auth()->user()->client) {
            return back()->with('erreur', 'Vous devez compléter votre profil client pour laisser un avis.');
        }

        $request->validate([
            'note'        => 'required|integer|min:1|max:5',
            'commentaire' => 'required|string|min:5|max:1000',
        ]);

        // Vérifier doublon
        $existe = Avis::where('client_id', auth()->user()->client->id)
                  ->where('service_id', $id)
                  ->exists();

        if ($existe) {
            return back()->with('erreur', 'Vous avez déjà laissé un avis pour ce service.');
        }

        Avis::create([
            'note'        => $request->note,
            'commentaire' => $request->commentaire,
            'client_id'   => auth()->user()->client->id,
            'service_id'  => $id,
        ]);

        // Recalculer note globale
        $moyenne = Avis::whereHas('service', function($q) use ($id) {
            $q->where('id', $id);
        })->avg('note');

        Service::find($id)->prestataire->update(['note_globale' => $moyenne]);

        return back()->with('succes', 'Avis publié avec succès ! 🎉');
    }
}
