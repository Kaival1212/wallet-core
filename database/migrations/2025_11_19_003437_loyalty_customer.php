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
        Schema::create('loyalty_customers', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("email");
            $table->integer("loyalty_points")->default(0);
            $table->enum("wallet_type", ['google', 'apple']);
            $table->string('qr_code')->unique()->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
