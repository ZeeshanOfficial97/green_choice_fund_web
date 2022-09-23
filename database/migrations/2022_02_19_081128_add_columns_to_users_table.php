<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('country_code', 125)->nullable();
            $table->string('contact_no', 125)->nullable();

            $table->string('profile_photo_path', 2048)->nullable();

            $table->string('privacy_policy_version', 125)->nullable();
            $table->boolean('is_notification_enabled')->default(true);
            $table->boolean('status')->default(true);
            $table->string('stripe_user_id', 250)->nullable();

            $table->unsignedBigInteger('social_account_id')->index()->nullable();
            $table->foreign('social_account_id')->references('id')->on('social_accounts')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->unsignedBigInteger('user_type_id')->index()->nullable();
            $table->foreign('user_type_id')->references('id')->on('lookups')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn('isSubscriptionActive');
            $table->dropColumn('status');
            $table->dropColumn('is_notification_enabled');
            $table->dropColumn('privacy_policy_version');


            $table->dropColumn('profile_photo_path');

            $table->dropColumn('contact_no');
            $table->dropColumn('country_code');
        });
    }
}
