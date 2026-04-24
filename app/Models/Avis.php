<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    protected $fillable = ['note', 'commentaire', 'client_id', 'service_id'];

    // Un avis appartient à un client
    public function client() {
        return $this->belongsTo(Client::class);
    }

    // Un avis appartient à un service
    public function service() {
        return $this->belongsTo(Service::class);
    }
}
