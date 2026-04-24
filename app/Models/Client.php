<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['user_id', 'nom', 'prenom', 'adresse'];

    // Un client appartient à un utilisateur
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Un client a plusieurs réservations
    public function reservations() {
        return $this->hasMany(Reservation::class);
    }

    // Un client a plusieurs avis
    public function avis() {
        return $this->hasMany(Avis::class);
    }
}
