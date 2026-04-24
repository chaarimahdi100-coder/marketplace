<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestataire extends Model
{
    protected $fillable = ['user_id', 'nom', 'localisation', 'note_globale', 'description', 'lat', 'lng'];

    // Un prestataire appartient à un utilisateur
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Un prestataire a plusieurs services
    public function services() {
        return $this->hasMany(Service::class);
    }

    // Un prestataire a plusieurs disponibilités
    public function disponibilites() {
        return $this->hasMany(Disponibilite::class);
    }
}
