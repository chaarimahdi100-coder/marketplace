<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disponibilite extends Model
{
    // Nom exact de la table dans la base de données
    protected $table = 'disponibilites';

    protected $fillable = [
        'date_debut',
        'date_fin',
        'prestataire_id'
    ];

    public function prestataire()
    {
        return $this->belongsTo(Prestataire::class);
    }
}