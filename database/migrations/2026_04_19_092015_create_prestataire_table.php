<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('prestataires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nom');
            $table->string('localisation')->nullable();
            $table->float('note_globale')->default(0);
            $table->text('description')->nullable();
            $table->float('lat')->default(48.8566);
            $table->float('lng')->default(2.3522);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('prestataires');
    }
};