<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolutionsMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('solutions_media', function (Blueprint $table) {
            $table->id();

            $table->string('media_url', 2048);

            $table->unsignedBigInteger('solution_id')->nullable()->index();
            $table->foreign('solution_id')->references('id')->on('solutions')
                ->onUpdate('cascade')
                ->onDelete('set null');

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
        Schema::dropIfExists('product_stocks');
    }
}
