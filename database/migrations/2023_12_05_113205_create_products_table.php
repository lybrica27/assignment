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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignID('category_id')->constrained()->onDelete('cascade');
            $table->double('price',8,2);
            $table->text('description')->nullable();
            $table->enum('condition', ['New','Used','Good Second Hand'])->default('New');
            $table->enum('type', ['Sell','Buy','Exchange'])->default('Sell');
            $table->enum('publish', ['Yes','No'])->default('No');
            $table->string('owner_name')->nullable();
            $table->integer('owner_contact')->nullable();
            $table->string('owner_address')->nullable();
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
        Schema::dropIfExists('products');
    }
};
