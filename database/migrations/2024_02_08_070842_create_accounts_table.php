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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->text('email');
            $table->text('password')->nullable();
            $table->integer('role')->default(1); // 0 for admin, 1 for user and 2 for partner
            $table->boolean('isActived')->default(false);
            
            $table->string('provider')->default('credential');
            $table->text('type')->nullable();
            $table->text('providerAccountId')->nullable();
            $table->text('access_token')->nullable();
            $table->text('token_type')->nullable();
            // $table->text('scope')->nullable();
            // $table->text('id_token')->nullable();
            $table->integer('expires_at')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
