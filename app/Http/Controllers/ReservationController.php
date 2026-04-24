<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Service;
use App\Models\Paiement;

class ReservationController extends Controller
{
    // Page de réservation
    public function index($id)
    {
        $service  = Service::with('prestataire')->findOrFail($id);
        $creneaux = Reservation::where('service_id', $id)
                    ->where('statut', '!=', 'annulée')
                    ->get(['date', 'heure']);

        $reservationPending = null;
        if (auth()->check()) {
            $client = auth()->user()->client;
            if ($client) {
                $reservationPending = Reservation::where('service_id', $id)
                    ->where('client_id', $client->id)
                    ->where('statut', 'en attente')
                    ->latest()
                    ->first();
            }
        }

        return view('reservation', compact('service', 'creneaux', 'reservationPending'));
    }

    // Créer une réservation
    public function store(Request $request, $id)
    {
        $request->validate([
            'date'  => 'required|date|after_or_equal:today',
            'heure' => 'required',
        ]);

        // Vérifier si créneau disponible
        $existe = Reservation::where('service_id', $id)
                  ->where('date',  $request->date)
                  ->where('heure', $request->heure)
                  ->where('statut', '!=', 'annulée')
                  ->exists();

        if ($existe) {
            return back()->with('erreur', 'Ce créneau est déjà pris !');
        }

        $client = auth()->user()->client;

        $reservation = Reservation::create([
            'date'       => $request->date,
            'heure'      => $request->heure,
            'statut'     => 'en attente',
            'client_id'  => $client->id,
            'service_id' => $id,
        ]);

        return redirect()->route('reservation', $id)
                         ->with('id_resa', $reservation->id)
                         ->with('succes', 'Réservation créée ! Procédez au paiement.');
    }

    // Payer
    public function payer(Request $request)
    {
        $reservation = Reservation::findOrFail($request->id_reservation);

        Paiement::create([
            'montant'        => $reservation->service->prix,
            'methode'        => 'carte bancaire',
            'statut'         => 'payé',
            'reservation_id' => $reservation->id,
        ]);

        $reservation->update(['statut' => 'confirmée']);

        return redirect()->route('dashboard.client')
                         ->with('succes', 'Paiement effectué avec succès !');
    }

    // Annuler (client)
    public function annuler(Request $request)
    {
        $client = auth()->user()->client;
        Reservation::where('id', $request->id_resa)
                   ->where('client_id', $client->id)
                   ->update(['statut' => 'annulée']);

        return redirect()->route('dashboard.client')
                         ->with('succes', 'Réservation annulée.');
    }

    // Confirmer (prestataire)
    public function confirmer(Request $request)
    {
        $statut = $request->action === 'confirmer' ? 'confirmée' : 'annulée';
        Reservation::findOrFail($request->id_resa)
                   ->update(['statut' => $statut]);

        return redirect()->route('dashboard.prestataire')
                         ->with('succes', 'Réservation mise à jour.');
    }
}
