<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEulasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('infographics', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name', 250);
        //     $table->string('description', 2000)->nullable();
        //     $table->string('file_url', 2048);
        //     $table->boolean('status')->default(true);


        //     $table->timestamps();
        //     $table->softDeletes();
        // });

        Schema::create('eulas', function (Blueprint $table) {
            $table->id();
            $table->string('name', 250);
            $table->string('description', 2000)->nullable();
            $table->string('file_url', 2048);
            $table->boolean('status')->default(true);


            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eulas');
        // Schema::dropIfExists('infographics');
    }
}
