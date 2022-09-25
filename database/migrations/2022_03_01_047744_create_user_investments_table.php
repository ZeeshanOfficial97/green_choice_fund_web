<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInvestmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_investments', function (Blueprint $table) {
            $table->id();
            $table->string('investment_num', 250)->index();;
            $table->string('name', 250);
            $table->string('email', 250);
            $table->string('country_code', 125);
            $table->string('contact_no', 125);
            $table->string('address', 2000);
            $table->date('dob');
            $table->decimal('invested_amount', 18, 2);

            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->string('stripe_charge_id', 500)->nullable()->index();

            $table->integer('investment_status')->default(0);
            $table->boolean('status')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('user_investment_solutions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('investment_id')->nullable()->index();
            $table->foreign('investment_id')->references('id')->on('user_investments')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->unsignedBigInteger('solution_id')->nullable()->index();
            $table->foreign('solution_id')->references('id')->on('solutions')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users')
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
        Schema::dropIfExists('user_investment_solutions');
        Schema::dropIfExists('user_investments');
    }
}
