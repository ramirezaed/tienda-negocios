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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("description");
            $table->decimal("price", 8, 2);
            $table->integer("stock");
            //se agrega la clave forane en la tabla con cardinalidad mcuhos
            //muchos productos pertenecen a una categoria
            $table->foreignId("category_id")->constrained("categories");
            $table->timestamps();
            $table->softDeletes(); // campo para eliminacion logica
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
