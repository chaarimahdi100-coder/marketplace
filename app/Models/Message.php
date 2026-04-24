<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['contenu', 'lu', 'expediteur_id', 'destinataire_id'];

    // Message envoyé par
    public function expediteur() {
        return $this->belongsTo(User::class, 'expediteur_id');
    }

    // Message reçu par
    public function destinataire() {
        return $this->belongsTo(User::class, 'destinataire_id');
    }
}
