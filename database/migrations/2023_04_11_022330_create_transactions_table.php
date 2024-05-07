<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_type_id');
            $table->unsignedBigInteger('status_id');
            $table->string('invoice_no')->nullable();
            $table->date('date');

            $table->string('customer_name', 255);
            $table->string('customer_phone', 15);
            $table->text('customer_address');

            $table->double('sub_total')->nullable();
            $table->double('pajak')->nullable();
            $table->double('biaya_pengiriman')->nullable();
            $table->double('discount')->nullable();
            $table->double('total')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->foreign('payment_type_id')->references('id')->on('payment_types');
            $table->foreign('status_id')->references('id')->on('statuses');
        });

        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');

            $table->json('item_id');
            $table->json('qty_item');
            $table->string('item_name', 255);
            $table->integer('qty');
            $table->float('discount');
            $table->double('price');
            $table->double('total');

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');

            $table->foreign('transaction_id')->references('id')->on('transactions');
        });

        // Schema::create('vendors', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name', 255)->nullable();

        //     $table->timestamp('created_at')->nullable();
        //     $table->timestamp('updated_at')->nullable();
        //     $table->timestamp('deleted_at')->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_types');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('transaction_details');
    }
}
