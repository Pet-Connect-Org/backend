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
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pet_id');
            $table->text('favoriteFood');
            $table->boolean('isFriendlyWithDog')->default(false);
            $table->boolean('isFriendlyWithCat')->default(false);
            $table->boolean('isCleanProperly')->default(false); // co di ve sinh dung cho
            $table->boolean('isHyperactive')->default(false); // co hieu dong
            $table->boolean('isFriendlyWithKid')->default(false);
            $table->boolean('isShy')->default(false);
            $table->timestamps();

            $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
