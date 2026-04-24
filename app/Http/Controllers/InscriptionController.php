<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\Prestataire;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class InscriptionController extends Controller
{
    // Afficher formulaire client
    public function formClient()
    {
        return view('auth.inscription_client');
    }

    // Enregistrer client
    public function storeClient(Request $request)
    {
        $request->validate([
            'nom'      => 'required|string|max:50',
            'prenom'   => 'required|string|max:50',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'adresse'  => 'nullable|string',
        ]);

        $user = User::create([
            'name'     => $request->prenom.' '.$request->nom,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Client::create([
            'user_id' => $user->id,
            'nom'     => $request->nom,
            'prenom'  => $request->prenom,
            'adresse' => $request->adresse ?? '',
        ]);

        Auth::login($user);
        return redirect('/dashboard/client')
               ->with('succes', 'Bienvenue !');
    }

    // Afficher formulaire prestataire
    public function formPrestataire()
    {
        return view('auth.inscription_prestataire');
    }

    // Enregistrer prestataire
    public function storePrestataire(Request $request)
    {
        $request->validate([
            'nom'          => 'required|string|max:50',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:6|confirmed',
            'localisation' => 'nullable|string',
            'description'  => 'nullable|string',
        ]);

        $user = User::create([
            'name'     => $request->nom,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Prestataire::create([
            'user_id'      => $user->id,
            'nom'          => $request->nom,
            'localisation' => $request->localisation ?? '',
            'note_globale' => 0,
            'description'  => $request->description ?? '',
            'lat'          => 33.8869,
            'lng'          => 9.5375,
        ]);

        Auth::login($user);
        return redirect('/dashboard/prestataire')
               ->with('succes', 'Bienvenue !');
    }
}
