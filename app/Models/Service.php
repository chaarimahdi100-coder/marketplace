<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['titre', 'categorie', 'prix', 'description', 'prestataire_id'];

    // Un service appartient à un prestataire
    public function prestataire() {
        return $this->belongsTo(Prestataire::class);
    }

    // Un service a plusieurs réservations
    public function reservations() {
        return $this->hasMany(Reservation::class);
    }

    // Un service a plusieurs avis
    public function avis() {
        return $this->hasMany(Avis::class);
    }
}
