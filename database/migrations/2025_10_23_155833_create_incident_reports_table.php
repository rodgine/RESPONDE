<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incident_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference_code')->unique();
            $table->string('incident_type');
            $table->string('location');
            $table->longText('landmark_photos')->nullable(); 
            $table->longText('proof_photos')->nullable();
            $table->string('status')->default('Pending');
            $table->timestamp('date_reported')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_reports');
    }
};
