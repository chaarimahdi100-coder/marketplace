<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Prestataire;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('chatbot');
    }

    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $question = strtolower(trim($request->message));
        $answer = $this->generateAnswer($question);

        return response()->json(['answer' => $answer]);
    }

    protected function generateAnswer(string $question): string
    {
        $normalized = mb_strtolower(trim($question), 'UTF-8');
        $normalized = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $normalized);
        $normalized = preg_replace('/\s+/', ' ', $normalized);

        // Réponses sur les services
        if (str_contains($normalized, 'quels services') || str_contains($normalized, 'les services') || str_contains($normalized, 'services disponibles')) {
            $serviceCount = Service::count();
            $categories = Service::distinct('categorie')->pluck('categorie');
            return "Nous proposons $serviceCount services ! Les catégories sont: " . implode(', ', $categories->toArray()) . ". Visitez la page Services pour les explorer !";
        }

        // Réponses sur les prestataires
        if (str_contains($normalized, 'prestataires') || str_contains($normalized, 'trouver un prestataire')) {
            $prestataireCount = Prestataire::count();
            return "Nous avons $prestataireCount prestataires en Tunisie ! Consultez la carte sur l'accueil pour les localiser et voir leurs services.";
        }

        // Réponses sur la réservation
        if (str_contains($normalized, 'réserver') || str_contains($normalized, 'reservation') || str_contains($normalized, 'rdv')) {
            return 'Pour réserver : 1) Allez sur Services 2) Choisissez un service 3) Cliquez sur "Réserver" 4) Sélectionnez une date 5) Confirmez la réservation.';
        }

        // Réponses sur le paiement
        if (str_contains($normalized, 'payer') || str_contains($normalized, 'paiement') || str_contains($normalized, 'prix')) {
            $avgPrice = Service::avg('prix');
            return "Nos services coûtent entre 30 et 150 TND. Pour payer une réservation, allez dans votre dashboard et cliquez sur le bouton Payer !";
        }

        // Réponses sur la messagerie
        if (str_contains($normalized, 'message') || str_contains($normalized, 'messagerie') || str_contains($normalized, 'contacter')) {
            return 'Vous pouvez contacter les prestataires via la messagerie ! Cliquez sur "Contacter" à côté d\'un service, ou utilisez la messagerie depuis votre dashboard.';
        }

        // Réponses sur le profil
        if (str_contains($normalized, 'profil') || str_contains($normalized, 'dashboard') || str_contains($normalized, 'mon compte')) {
            return 'Connectez-vous pour accéder à votre dashboard. Les clients gèrent les réservations. Les prestataires gèrent les services et disponibilités.';
        }

        // Réponses sur la carte
        if (str_contains($normalized, 'carte') || str_contains($normalized, 'localiser')) {
            return 'La carte interactive affiche tous les prestataires en Tunisie avec leurs services ! Vous pouvez les cliquer pour plus d\'infos.';
        }

        // Réponses sur l'inscription
        if (str_contains($normalized, 'inscription') || str_contains($normalized, 'créer compte')) {
            return 'Pour vous inscrire: 1) Cliquez sur "Inscription" 2) Choisissez "Client" ou "Prestataire" 3) Remplissez le formulaire 4) Confirmez !';
        }

        // Réponses générales
        if (str_contains($normalized, 'bonjour') || str_contains($normalized, 'salut') || str_contains($normalized, 'coucou')) {
            return 'Bonjour ! Bienvenue sur notre marketplace ! Je suis votre assistant IA. Posez-moi vos questions sur les services, prestataires, réservations ou comment utiliser la plateforme !';
        }

        if (str_contains($normalized, 'merci') || str_contains($normalized, 'super') || str_contains($normalized, 'bien')) {
            return 'De rien ! Si vous avez d\'autres questions sur notre marketplace, n\'hésitez pas à demander !';
        }

        if (str_contains($normalized, 'au revoir') || str_contains($normalized, 'bye') || str_contains($normalized, 'adieu')) {
            return 'À bientôt ! N\'hésitez pas à revenir si vous avez besoin.';
        }

        if (str_contains($normalized, 'aide') || str_contains($normalized, 'comment') || str_contains($normalized, 'quoi')) {
            return 'Je peux vous aider sur : les services, prestataires, réservations, paiements, messagerie, profil, et l\'inscription. Qu\'est-ce qui vous intéresse ?';
        }

        return 'C\'est une bonne question ! Pour plus d\'informations, visitez les pages Services, explorez la carte ou contactez directement un prestataire. Comment puis-je vous aider ?';
    }
}
