<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solutions', function (Blueprint $table) {
            $table->id();

            $table->string('name', 250);
            $table->string('description', 2048)->nullable();

            $table->boolean('published')->default(false);
            $table->boolean('status')->default(true);

            $table->unsignedBigInteger('sub_category_id')->nullable()->index();
            $table->foreign('sub_category_id')->references('id')->on('sub_categories')
                ->onUpdate('cascade')
                ->onDelete('set null');

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
        Schema::dropIfExists('solutions');
    }
}
