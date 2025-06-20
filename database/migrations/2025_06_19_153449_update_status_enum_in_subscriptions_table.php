<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateStatusEnumInSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        DB::statement("ALTER TABLE subscriptions MODIFY status ENUM('pending', 'active', 'paused', 'cancelled', 'rejected') DEFAULT 'pending'");
    }

    public function down()
    {
        // Balik ke enum sebelumnya (jika perlu rollback)
        DB::statement("ALTER TABLE subscriptions MODIFY status ENUM('pending', 'active', 'paused', 'cancelled') DEFAULT 'pending'");
    }
    
}
