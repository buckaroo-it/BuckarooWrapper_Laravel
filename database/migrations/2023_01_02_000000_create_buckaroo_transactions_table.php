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
            $table->string('payable_id');
            $table->string('payable_type');

            $table->string('payment_method_id');

            $table->string('transaction_key');
            $table->string('related_transaction_key')->nullable();
            $table->string('status_code');
            $table->string('status_subcode')->nullable();
            $table->string('status_subcode_description')->nullable();
            $table->string('order');
            $table->string('invoice');
            $table->boolean('test');
            $table->string('currency');
            $table->decimal('amount');
            $table->enum('status', BuckarooTransactionStatus::cases());
            $table->string('service_action');

            $table->timestamps();

            $table->index(['payable_id', 'payable_type']);
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
