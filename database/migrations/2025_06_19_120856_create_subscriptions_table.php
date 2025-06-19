<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama lengkap user
            $table->string('phone'); // Nomor telepon aktif
            $table->foreignId('meal_plan_id')->constrained('meal_plans')->onDelete('cascade');
            $table->text('allergies')->nullable(); // Alergi/pantangan makanan
            $table->decimal('total_price', 12, 2); // Total harga subscription
            $table->enum('status', ['active', 'inactive', 'pending', 'cancelled'])->default('pending');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
