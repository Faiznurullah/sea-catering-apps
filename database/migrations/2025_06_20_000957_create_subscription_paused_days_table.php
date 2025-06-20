<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPausedDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_paused_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->onDelete('cascade');
            $table->date('paused_date');
            $table->decimal('daily_rate', 10, 2);
            $table->decimal('refund_amount', 10, 2);
            $table->string('reason')->nullable();
            $table->string('type')->default('per_day'); // 'per_day' or 'range'
            $table->timestamps();
            
            $table->index(['subscription_id', 'paused_date']);
            $table->unique(['subscription_id', 'paused_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_paused_days');
    }
}
