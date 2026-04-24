<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disponibilite;

class DisponibiliteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin'   => 'required|date|after:date_debut',
        ]);

        $prestataire = auth()->user()->prestataire;

        Disponibilite::create([
            'date_debut'     => $request->date_debut,
            'date_fin'       => $request->date_fin,
            'prestataire_id' => $prestataire->id,
        ]);

        return redirect()->route('dashboard.prestataire')
                         ->with('succes', 'Disponibilité ajoutée !');
    }

    public function destroy($id)
    {
        $prestataire = auth()->user()->prestataire;
        $disponibilite = Disponibilite::find($id);

        if (!$disponibilite || $disponibilite->prestataire_id != $prestataire->id) {
            abort(403, 'Accès refusé');
        }

        $disponibilite->delete();
        return redirect()->route('dashboard.prestataire')
                         ->with('succes', 'Disponibilité supprimée !');
    }

    public function edit($id)
    {
        $prestataire = auth()->user()->prestataire;
        $disponibilite = Disponibilite::find($id);

        if (!$disponibilite || $disponibilite->prestataire_id != $prestataire->id) {
            abort(403, 'Accès refusé');
        }

        return view('edit_disponibilite', compact('disponibilite'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin'   => 'required|date|after:date_debut',
        ]);

        $prestataire = auth()->user()->prestataire;
        $disponibilite = Disponibilite::find($id);

        if (!$disponibilite || $disponibilite->prestataire_id != $prestataire->id) {
            abort(403, 'Accès refusé');
        }

        $disponibilite->update([
            'date_debut' => $request->date_debut,
            'date_fin'   => $request->date_fin,
        ]);

        return redirect()->route('dashboard.prestataire')
                         ->with('succes', 'Disponibilité modifiée !');
    }
}
