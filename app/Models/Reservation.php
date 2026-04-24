<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['date', 'heure', 'statut', 'client_id', 'service_id'];

    // Une réservation appartient à un client
    public function client() {
        return $this->belongsTo(Client::class);
    }

    // Une réservation appartient à un service
    public function service() {
        return $this->belongsTo(Service::class);
    }

    // Une réservation a un paiement
    public function paiement() {
        return $this->hasOne(Paiement::class);
    }
}
