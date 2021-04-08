<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advert_adverts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('CASCADE');
            $table->foreignId('category_id')->constrained('advert_categories');
            $table->foreignId('region_id')->nullable()->constrained();
            $table->string('title');
            $table->integer('price');
            $table->text('address');
            $table->text('content');
            $table->string('status', 16);
            $table->text('reject_reason')->nullable();
            $table->timestamps();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
        });

        Schema::create('advert_advert_values', function (Blueprint $table) {
            $table->foreignId('advert_id')
                ->constrained('advert_adverts')
                ->onDelete('CASCADE');
            $table->foreignId('attribute_id')
                ->constrained('advert_attributes')
                ->onDelete('CASCADE');
            $table->string('value');
            $table->primary(['advert_id', 'attribute_id']);
        });

        Schema::create('advert_advert_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advert_id')
                ->constrained('advert_adverts')
                ->onDelete('CASCADE');
            $table->string('file');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advert_adverts');
        Schema::dropIfExists('advert_advert_values');
        Schema::dropIfExists('advert_advert_photos');
    }
}
