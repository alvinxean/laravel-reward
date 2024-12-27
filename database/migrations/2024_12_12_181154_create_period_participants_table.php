<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('period_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('users_id');
            $table->unsignedBigInteger('periods_id');
            $table->enum('status', ['Tunda', 'Gagal', 'Berhasil'])->default('Tunda');
            $table->date('date')->nullable();
            $table->string('failure_reason')->default('Tidak tersedia'); 
            $table->string('file_bank_statement')->default('Tidak tersedia'); 
            $table->string('bank_statement_message')->default('Tidak tersedia'); 
            $table->string('total_nominal')->default('0');
            $table->timestamps();

            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('periods_id')->references('id')->on('periods')->onDelete('cascade');

            $table->index('users_id');
            $table->index('periods_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('period_participants');
    }
}
