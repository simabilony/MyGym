<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores');
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->null0nDelete();
            $table->string('number')->unique();
            $table->string('payment_method');
            $table->enum('status', ['pending', 'processing', 'delivering', 'completed', 'cancelled'])
            ->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed']) ->default('pending');
            $table->float('shipping')->default(0); $table->float('tax')->default(0);
            $table->float('discount')->default(0);
            $table->float('tot')->default(0);
            $table->timestamps();
});
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
