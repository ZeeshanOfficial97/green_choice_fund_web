<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionInquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institution_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 250);
            $table->string('email', 250);
            $table->string('country_code', 125);
            $table->string('contact_no', 125);
            $table->string('address', 500)->nullable();;
            $table->longText('company_url')->nullable();
            $table->longText('description')->nullable();

            $table->boolean('status')->default(true);

            $table->unsignedBigInteger('contact_reason_id')->index()->nullable();
            $table->foreign('contact_reason_id')->references('id')->on('lookups')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users')
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
        Schema::dropIfExists('institution_inquiries');
    }
}
