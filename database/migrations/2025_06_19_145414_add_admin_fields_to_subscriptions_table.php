<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminFieldsToSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->text('admin_notes')->nullable()->after('allergies');
            $table->text('rejection_reason')->nullable()->after('admin_notes');
            $table->timestamp('approved_at')->nullable()->after('rejection_reason');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['admin_notes', 'rejection_reason', 'approved_at', 'rejected_at']);
        });
    }
}
