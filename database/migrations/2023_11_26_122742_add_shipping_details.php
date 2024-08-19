<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('shipping_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained("user")->onDelete('cascade');
            $table->foreignId('order_id')->constrained("orders")->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('apt');
            $table->string('country');
            $table->string('postal_code');
            $table->string('card_number');
            $table->string('expiry');
            $table->string('cvc');
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
        Schema::dropIfExists('shipping_details');
    }
};
