<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    // Un user peut être un client
    public function client() {
        return $this->hasOne(Client::class);
    }

    // Un user peut être un prestataire
    public function prestataire() {
        return $this->hasOne(Prestataire::class);
    }

    // Messages envoyés
    public function messagesEnvoyes() {
        return $this->hasMany(Message::class, 'expediteur_id');
    }

    // Messages reçus
    public function messagesRecus() {
        return $this->hasMany(Message::class, 'destinataire_id');
    }
}