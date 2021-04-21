<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner_banners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('CASCADE');
            $table->foreignId('category_id')->constrained('advert_categories');
            $table->foreignId('region_id')->nullable()->constrained();
            $table->string('name');
            $table->integer('views')->nullable();
            $table->integer('limit');
            $table->integer('clicks')->nullable();
            $table->integer('cost')->nullable();
            $table->string('url');
            $table->string('format');
            $table->string('file');
            $table->string('status', 16);
            $table->timestamps();
            $table->timestamp('published_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banner_banners');
    }
}
