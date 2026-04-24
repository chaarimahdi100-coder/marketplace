<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('disponibilites', function (Blueprint $table) {
            $table->id();
            $table->datetime('date_debut');
            $table->datetime('date_fin');
            $table->foreignId('prestataire_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('disponibilites');
    }
};