<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Prestataire;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password'),
        ]);

        if (Service::count() === 0) {
            $prestataire = Prestataire::firstOrCreate([
                'user_id' => $user->id,
            ], [
                'nom' => 'Prestataire Test',
                'localisation' => 'Tunis',
                'note_globale' => 4,
                'description' => 'Prestataire de test pour démonstration.',
                'lat' => 36.8065,
                'lng' => 10.1815,
            ]);

            Service::create([
                'titre' => 'Nettoyage appartement',
                'categorie' => 'Nettoyage',
                'prix' => 50,
                'description' => 'Nettoyage complet d&#039;un appartement jusqu&#039;à 80m².',
                'prestataire_id' => $prestataire->id,
            ]);

            Service::create([
                'titre' => 'Jardinage express',
                'categorie' => 'Jardinage',
                'prix' => 40,
                'description' => 'Tonte de pelouse, désherbage et entretien de massif.',
                'prestataire_id' => $prestataire->id,
            ]);

            Service::create([
                'titre' => 'Réparation plomberie',
                'categorie' => 'Plomberie',
                'prix' => 70,
                'description' => 'Intervention rapide pour fuite ou dépannage sanitaire.',
                'prestataire_id' => $prestataire->id,
            ]);

            // Prestataire Sfax
            $user2 = User::create([
                'name' => 'User Sfax',
                'email' => 'sfax@example.com',
                'password' => bcrypt('password'),
            ]);
            $prestataire2 = Prestataire::create([
                'user_id' => $user2->id,
                'nom' => 'Prestataire Sfax',
                'localisation' => 'Sfax',
                'note_globale' => 4.5,
                'description' => 'Services de qualité à Sfax.',
                'lat' => 34.7398,
                'lng' => 10.7600,
            ]);

            Service::create([
                'titre' => 'Cuisine tunisienne',
                'categorie' => 'Cuisine',
                'prix' => 60,
                'description' => 'Préparation de plats traditionnels tunisiens.',
                'prestataire_id' => $prestataire2->id,
            ]);

            // Prestataire Sousse
            $user3 = User::create([
                'name' => 'User Sousse',
                'email' => 'sousse@example.com',
                'password' => bcrypt('password'),
            ]);
            $prestataire3 = Prestataire::create([
                'user_id' => $user3->id,
                'nom' => 'Prestataire Sousse',
                'localisation' => 'Sousse',
                'note_globale' => 4.2,
                'description' => 'Spécialiste en nettoyage à Sousse.',
                'lat' => 35.8256,
                'lng' => 10.6369,
            ]);

            Service::create([
                'titre' => 'Nettoyage bureau',
                'categorie' => 'Nettoyage',
                'prix' => 45,
                'description' => 'Nettoyage professionnel de bureaux et espaces commerciaux.',
                'prestataire_id' => $prestataire3->id,
            ]);

            // Prestataire Kairouan
            $user4 = User::create([
                'name' => 'User Kairouan',
                'email' => 'kairouan@example.com',
                'password' => bcrypt('password'),
            ]);
            $prestataire4 = Prestataire::create([
                'user_id' => $user4->id,
                'nom' => 'Prestataire Kairouan',
                'localisation' => 'Kairouan',
                'note_globale' => 4.8,
                'description' => 'Jardinage et entretien extérieur.',
                'lat' => 35.6781,
                'lng' => 10.0963,
            ]);

            Service::create([
                'titre' => 'Entretien jardin',
                'categorie' => 'Jardinage',
                'prix' => 55,
                'description' => 'Aménagement et entretien de jardins.',
                'prestataire_id' => $prestataire4->id,
            ]);

            // Prestataire Bizerte
            $user5 = User::create([
                'name' => 'User Bizerte',
                'email' => 'bizerte@example.com',
                'password' => bcrypt('password'),
            ]);
            $prestataire5 = Prestataire::create([
                'user_id' => $user5->id,
                'nom' => 'Prestataire Bizerte',
                'localisation' => 'Bizerte',
                'note_globale' => 4.0,
                'description' => 'Électricité et plomberie à Bizerte.',
                'lat' => 37.2744,
                'lng' => 9.8739,
            ]);

            Service::create([
                'titre' => 'Réparation électrique',
                'categorie' => 'Electricité',
                'prix' => 80,
                'description' => 'Installation et réparation électrique.',
                'prestataire_id' => $prestataire5->id,
            ]);

            // Prestataire Gabès
            $user6 = User::create([
                'name' => 'User Gabès',
                'email' => 'gabes@example.com',
                'password' => bcrypt('password'),
            ]);
            $prestataire6 = Prestataire::create([
                'user_id' => $user6->id,
                'nom' => 'Prestataire Gabès',
                'localisation' => 'Gabès',
                'note_globale' => 4.3,
                'description' => 'Services divers à Gabès.',
                'lat' => 33.8815,
                'lng' => 10.0982,
            ]);

            Service::create([
                'titre' => 'Nettoyage voiture',
                'categorie' => 'Nettoyage',
                'prix' => 30,
                'description' => 'Lavage et nettoyage intérieur/extérieur de véhicules.',
                'prestataire_id' => $prestataire6->id,
            ]);

            // Prestataire Monastir
            $user7 = User::create([
                'name' => 'User Monastir',
                'email' => 'monastir@example.com',
                'password' => bcrypt('password'),
            ]);
            $prestataire7 = Prestataire::create([
                'user_id' => $user7->id,
                'nom' => 'Prestataire Monastir',
                'localisation' => 'Monastir',
                'note_globale' => 4.6,
                'description' => 'Cuisine et nettoyage à Monastir.',
                'lat' => 35.7780,
                'lng' => 10.8262,
            ]);

            Service::create([
                'titre' => 'Cours de cuisine',
                'categorie' => 'Cuisine',
                'prix' => 50,
                'description' => 'Apprentissage de recettes tunisiennes.',
                'prestataire_id' => $prestataire7->id,
            ]);

            // Prestataire Mahdia
            $user8 = User::create([
                'name' => 'User Mahdia',
                'email' => 'mahdia@example.com',
                'password' => bcrypt('password'),
            ]);
            $prestataire8 = Prestataire::create([
                'user_id' => $user8->id,
                'nom' => 'Prestataire Mahdia',
                'localisation' => 'Mahdia',
                'note_globale' => 4.1,
                'description' => 'Jardinage et plomberie à Mahdia.',
                'lat' => 35.5047,
                'lng' => 11.0622,
            ]);

            Service::create([
                'titre' => 'Réparation robinet',
                'categorie' => 'Plomberie',
                'prix' => 65,
                'description' => 'Réparation de robinets et canalisations.',
                'prestataire_id' => $prestataire8->id,
            ]);

            // Prestataire Nabeul
            $user9 = User::create([
                'name' => 'User Nabeul',
                'email' => 'nabeul@example.com',
                'password' => bcrypt('password'),
            ]);
            $prestataire9 = Prestataire::create([
                'user_id' => $user9->id,
                'nom' => 'Prestataire Nabeul',
                'localisation' => 'Nabeul',
                'note_globale' => 4.7,
                'description' => 'Électricité et jardinage à Nabeul.',
                'lat' => 36.4513,
                'lng' => 10.7357,
            ]);

            Service::create([
                'titre' => 'Installation éclairage',
                'categorie' => 'Electricité',
                'prix' => 75,
                'description' => 'Installation d\'éclairage intérieur et extérieur.',
                'prestataire_id' => $prestataire9->id,
            ]);

            // Prestataire Hammamet
            $user10 = User::create([
                'name' => 'User Hammamet',
                'email' => 'hammamet@example.com',
                'password' => bcrypt('password'),
            ]);
            $prestataire10 = Prestataire::create([
                'user_id' => $user10->id,
                'nom' => 'Prestataire Hammamet',
                'localisation' => 'Hammamet',
                'note_globale' => 4.4,
                'description' => 'Nettoyage et cuisine à Hammamet.',
                'lat' => 36.4000,
                'lng' => 10.6167,
            ]);

            Service::create([
                'titre' => 'Préparation événement',
                'categorie' => 'Cuisine',
                'prix' => 90,
                'description' => 'Service de traiteur pour événements.',
                'prestataire_id' => $prestataire10->id,
            ]);

            // Services supplémentaires
            Service::create([
                'titre' => 'Massage relaxant',
                'categorie' => 'Bien-être',
                'prix' => 100,
                'description' => 'Massage professionnel pour relaxation.',
                'prestataire_id' => $prestataire->id, // Tunis
            ]);

            Service::create([
                'titre' => 'Réparation ordinateur',
                'categorie' => 'Informatique',
                'prix' => 85,
                'description' => 'Dépannage et réparation d\'ordinateurs.',
                'prestataire_id' => $prestataire2->id, // Sfax
            ]);

            Service::create([
                'titre' => 'Cours de danse',
                'categorie' => 'Éducation',
                'prix' => 40,
                'description' => 'Cours de danse traditionnelle tunisienne.',
                'prestataire_id' => $prestataire3->id, // Sousse
            ]);

            Service::create([
                'titre' => 'Photographie événement',
                'categorie' => 'Photographie',
                'prix' => 150,
                'description' => 'Service de photographie pour mariages et événements.',
                'prestataire_id' => $prestataire4->id, // Kairouan
            ]);

            Service::create([
                'titre' => 'Coaching sportif',
                'categorie' => 'Sport',
                'prix' => 60,
                'description' => 'Séances de coaching personnalisé.',
                'prestataire_id' => $prestataire5->id, // Bizerte
            ]);

            Service::create([
                'titre' => 'Traduction documents',
                'categorie' => 'Traduction',
                'prix' => 50,
                'description' => 'Traduction de documents en arabe/français.',
                'prestataire_id' => $prestataire6->id, // Gabès
            ]);
        }
    }
}
