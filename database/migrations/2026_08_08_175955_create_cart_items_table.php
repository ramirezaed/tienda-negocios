<?php

//esta table se crea para evitar una relacion de N <-> N entre producto y carrito

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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            //cascadaOndelete-> cuando se borra un carrito se borra el cart_items
            $table->foreignId('cart_id')->constrained('cart')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products');
            $table->unsignedInteger('quantity');
            $table->timestamps();
            //dentro de un carrito el un cart_id o producto_id solo puede aparecer una vez
            $table->unique(['cart_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
