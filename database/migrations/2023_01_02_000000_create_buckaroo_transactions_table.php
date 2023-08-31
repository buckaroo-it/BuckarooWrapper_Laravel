<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buckaroo_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('brq_statuscode')->nullable();
            $table->string('brq_statuscode_detail')->nullable();
            $table->string('brq_statusmessage')->nullable();
            $table->string('brq_transactions')->nullable();
            $table->timestamps();
        });

        Schema::create('buckaroo_transaction_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('brq_statuscode')->nullable();
            $table->string('brq_statuscode_detail')->nullable();
            $table->string('brq_statusmessage')->nullable();
            $table->string('brq_transactions')->nullable();
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
        Schema::dropIfExists('buckaroo_transactions');
    }
};

