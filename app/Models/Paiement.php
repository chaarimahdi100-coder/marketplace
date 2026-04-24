<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = ['montant', 'methode', 'statut', 'reservation_id'];

    // Un paiement appartient à une réservation
    public function reservation() {
        return $this->belongsTo(Reservation::class);
    }
}
