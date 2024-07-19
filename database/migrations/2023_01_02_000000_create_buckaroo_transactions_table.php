<?php

use Buckaroo\Laravel\Constants\BuckarooTransactionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buckaroo_transactions', function (Blueprint $table) {
            $table->id();

            $table->string('payment_method_id')->nullable();

            $table->string('transaction_key');
            $table->string('related_transaction_key')->nullable();
            $table->string('status_code');
            $table->string('status_subcode')->nullable();
            $table->string('status_subcode_description')->nullable();
            $table->string('order')->nullable();
            $table->string('invoice')->nullable();
            $table->boolean('is_test');
            $table->string('currency');
            $table->decimal('amount');
            $table->enum('status', BuckarooTransactionStatus::cases());
            $table->string('service_action');

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
