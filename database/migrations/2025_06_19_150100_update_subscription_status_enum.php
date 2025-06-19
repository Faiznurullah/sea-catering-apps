<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateSubscriptionStatusEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update the status enum to include rejected
        DB::statement("ALTER TABLE subscriptions MODIFY COLUMN status ENUM('pending', 'active', 'paused', 'cancelled', 'rejected') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert back to original enum
        DB::statement("ALTER TABLE subscriptions MODIFY COLUMN status ENUM('pending', 'active', 'paused', 'cancelled') NOT NULL DEFAULT 'pending'");
    }
}
