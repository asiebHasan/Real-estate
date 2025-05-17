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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('zipcode');
            $table->enum('property_type', ['house', 'apartment', 'condo', 'land', 'commercial']);
            $table->enum('rent_or_sale', ['rent', 'sale']);
            $table->integer('bedrooms')->nullable(true);
            $table->integer('bathrooms')->nullable(true);
            $table->integer('square_feet')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
