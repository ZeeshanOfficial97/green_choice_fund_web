<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubCategoriesMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_categories_media', function (Blueprint $table) {
            $table->id();

            $table->string('media_url', 2048);

            $table->unsignedBigInteger('sub_category_id')->nullable()->index();
            $table->foreign('sub_category_id')->references('id')->on('sub_categories')
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
        Schema::dropIfExists('sub_categories_media');
    }
}
