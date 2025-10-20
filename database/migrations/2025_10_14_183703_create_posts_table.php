<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // L'auteur du post
            $table->string('title');
            $table->text('content')->nullable(); // Texte de la prière ou description
            $table->enum('type', ['video', 'audio', 'prayer']); // Type de contenu
            $table->unsignedInteger('share_count')->default(0);
            $table->string('media_url')->nullable(); // URL du fichier (vidéo/audio) ou de l'image
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
