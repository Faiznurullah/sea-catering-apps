<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPauseDatesToSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->date('pause_start_date')->nullable()->after('end_date');
            $table->date('pause_end_date')->nullable()->after('pause_start_date');
            $table->decimal('paused_days_total', 8, 2)->default(0)->after('pause_end_date'); // Total hari yang di-pause
            $table->decimal('refund_amount', 12, 2)->default(0)->after('paused_days_total'); // Jumlah refund/kredit
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
            $table->dropColumn(['pause_start_date', 'pause_end_date', 'paused_days_total', 'refund_amount']);
        });
    }
}
