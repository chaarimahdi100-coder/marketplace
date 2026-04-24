<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Prestataire;

class ServiceController extends Controller
{
    // Page d'accueil
    public function index()
    {
        // Récupérer 6 services pour la vitrine
        $services = Service::with('prestataire')
                    ->withCount('avis')
                    ->latest()
                    ->take(6)
                    ->get();

        $nb_services     = Service::count();
        $nb_prestataires = Prestataire::count();

        // Catégories disponibles
        $categories = Service::distinct('categorie')->pluck('categorie')->sort();

        return view('home', compact('services', 'nb_services', 'nb_prestataires', 'categories'));
    }

    // Page liste des services avec filtres
    public function liste(Request $request)
    {
        $query = Service::with('prestataire')->withCount('avis');

        // Filtre par recherche
        if ($request->recherche) {
            $query->where(function($q) use ($request) {
                $q->where('titre', 'like', '%'.$request->recherche.'%')
                  ->orWhere('description', 'like', '%'.$request->recherche.'%');
            });
        }

        // Filtre par catégorie
        if ($request->categorie) {
            $query->where('categorie', $request->categorie);
        }

        // Filtre par prix
        if ($request->filled('prix_max')) {
            $query->where('prix', '<=', floatval($request->prix_max));
        }

        $services = $query->get();

        // Catégories disponibles
        $categories = Service::distinct('categorie')->pluck('categorie')->sort();

        return view('services', compact('services', 'categories'));
    }

    // Ajouter un service (prestataire)
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'titre'       => 'required|string|max:100',
            'categorie'   => 'required|string',
            'prix'        => 'required|numeric|min:1',
            'description' => 'nullable|string',
        ]);

        $prestataire = auth()->user()->prestataire;

        Service::create([
            'titre'          => $request->titre,
            'categorie'      => $request->categorie,
            'prix'           => $request->prix,
            'description'    => $request->description,
            'prestataire_id' => $prestataire->id,
        ]);

        return redirect()->route('dashboard.prestataire')
                         ->with('succes', 'Service ajouté avec succès !');
    }

    // Supprimer un service
    public function destroy(Request $request)
    {
        $prestataire = auth()->user()->prestataire;
        Service::where('id', $request->id_service)
               ->where('prestataire_id', $prestataire->id)
               ->delete();

        return redirect()->route('dashboard.prestataire')
                         ->with('succes', 'Service supprimé.');
    }

    // Afficher le formulaire d'édition d'un service
    public function editService($id)
    {
        $service = Service::find($id);
        $prestataire = auth()->user()->prestataire;

        if (!$service || $service->prestataire_id != $prestataire->id) {
            abort(403, 'Accès refusé');
        }

        return view('edit_service', compact('service'));
    }

    // Mettre à jour un service
    public function updateService(Request $request, $id)
    {
        $request->validate([
            'titre'       => 'required|string|max:100',
            'categorie'   => 'required|string',
            'prix'        => 'required|numeric|min:1',
            'description' => 'nullable|string',
        ]);

        $service = Service::find($id);
        $prestataire = auth()->user()->prestataire;

        if (!$service || $service->prestataire_id != $prestataire->id) {
            abort(403, 'Accès refusé');
        }

        $service->update($request->only(['titre', 'categorie', 'prix', 'description']));

        return redirect()->route('dashboard.prestataire')
                         ->with('succes', 'Service modifié avec succès !');
    }
}
