<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNetworksAuth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
        });

        Schema::create('user_networks', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('CASCADE');
            $table->string('identity');
            $table->string('network');
            $table->primary(['user_id', 'identity']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_networks');

        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->change();
            $table->string('password')->change();
        });
    }
}
