<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertFavoritesTable extends Migration
{
    public function up(): void
    {
        Schema::create('advert_favorites', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('CASCADE');
            $table->foreignId('advert_id')->constrained('advert_adverts')->onDelete('CASCADE');
            $table->primary(['user_id', 'advert_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advert_favorites');
    }
}
