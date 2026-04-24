<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Reservation;
use App\Models\Paiement;
use App\Models\Disponibilite;
use App\Models\Client;
use App\Models\Prestataire;

class DashboardController extends Controller
{
    public function client()
    {
        $client = auth()->user()->client;

        if (!$client) {
            $client = Client::create([
                'user_id' => auth()->id(),
                'nom'     => auth()->user()->name,
                'prenom'  => '',
                'adresse' => '',
            ]);
        }

        $reservations = Reservation::with(['service.prestataire'])
                        ->where('client_id', $client->id)
                        ->latest()->get();

        $paiements = Paiement::whereHas('reservation', function($q) use ($client) {
                        $q->where('client_id', $client->id);
                     })->with('reservation.service')->latest()->get();

        $nb_avis  = $client->avis()->count();
        $depenses = $paiements->where('statut', 'payé')->sum('montant');

        return view('dashboard_client', compact(
            'client', 'reservations', 'paiements', 'nb_avis', 'depenses'
        ));
    }

    public function prestataire()
    {
        $prestataire = auth()->user()->prestataire;

        if (!$prestataire) {
            abort(403, 'Accès refusé - Vous n\'êtes pas un prestataire');
        }

        $services = Service::withCount('reservations')
                    ->where('prestataire_id', $prestataire->id)
                    ->get();

        $reservations = Reservation::with(['service', 'client'])
                        ->whereHas('service', function($q) use ($prestataire) {
                            $q->where('prestataire_id', $prestataire->id);
                        })->latest()->get();

        $disponibilites = Disponibilite::where('prestataire_id', $prestataire->id)
                          ->get();

        $revenus = Paiement::whereHas('reservation.service', function($q) use ($prestataire) {
                        $q->where('prestataire_id', $prestataire->id);
                   })->where('statut', 'payé')->sum('montant') ?? 0;

        return view('dashboard_prestataire', compact(
            'prestataire', 'services', 'reservations', 'disponibilites', 'revenus'
        ));
    }

    // Éditer le profil client
    public function editClientProfile()
    {
        $client = auth()->user()->client;

        if (!$client) {
            abort(403, 'Accès refusé');
        }

        return view('edit_client_profile', compact('client'));
    }

    // Mettre à jour le profil client
    public function updateClientProfile(Request $request)
    {
        $request->validate([
            'nom'     => 'required|string|max:50',
            'prenom'  => 'required|string|max:50',
            'adresse' => 'nullable|string',
        ]);

        $client = auth()->user()->client;

        if (!$client) {
            abort(403, 'Accès refusé');
        }

        $client->update($request->only(['nom', 'prenom', 'adresse']));
        auth()->user()->update(['name' => $request->prenom . ' ' . $request->nom]);

        return redirect()->route('dashboard.client')
                         ->with('succes', 'Profil mis à jour !');
    }

    // Éditer le profil prestataire
    public function editPrestataireProfile()
    {
        $prestataire = auth()->user()->prestataire;

        if (!$prestataire) {
            abort(403, 'Accès refusé');
        }

        return view('edit_prestataire_profile', compact('prestataire'));
    }

    // Mettre à jour le profil prestataire
    public function updatePrestataireProfile(Request $request)
    {
        $request->validate([
            'nom'          => 'required|string|max:50',
            'localisation' => 'nullable|string',
            'description'  => 'nullable|string',
        ]);

        $prestataire = auth()->user()->prestataire;

        if (!$prestataire) {
            abort(403, 'Accès refusé');
        }

        $prestataire->update($request->only(['nom', 'localisation', 'description']));
        auth()->user()->update(['name' => $request->nom]);

        return redirect()->route('dashboard.prestataire')
                         ->with('succes', 'Profil mis à jour !');
    }
}
