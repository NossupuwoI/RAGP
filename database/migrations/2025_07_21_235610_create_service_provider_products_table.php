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
    public function up(): void
    {
        Schema::create('service_provider_products', function (Blueprint $table) {
            $table->id();

          
            $table->foreignId('service_provider_id') ->constrained('service_providers') ->onDelete('cascade');
            $table->foreignId('service_type_id') ->constrained('service_types') ->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
             $table->boolean('price_fixed')->default(true); 
            // Champ price au format JSON (min, max, amount)
            $table->json('price');
            $table->float('commission', 5, 2)->default(0);
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
        Schema::dropIfExists('service_provider_products');
    }
};
