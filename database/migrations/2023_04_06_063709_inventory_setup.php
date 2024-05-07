<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InventorySetup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id')->nullable();
            $table->string('name');
            $table->string('display_name');
            $table->char('level', 10)->nullable();
            $table->tinyInteger('order_no')->nullable();
            $table->string('url', 50);
            $table->string('icon', 50);
            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->foreign('menu_id')->references('id')->on('menus');
        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('menu_role', function (Blueprint $table) {
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('menu_id')->references('id')->on('menus');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->primary(['menu_id', 'role_id']);
        });

        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name', 191)->unique();
            $table->text('address')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->string('color', 255)->nullable();

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::create('uoms', function (Blueprint $table) {
            $table->id();
            $table->string('code', 6)->unique();
            $table->string('name', 255)->nullable();

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->string('color', 255)->nullable();

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->text('address')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('uom_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->string('name', 255)->nullable();
            $table->text('composition')->nullable();
            $table->float('qty')->nullable();
            $table->decimal('purchase_price', 20, 5)->nullable();
            $table->decimal('sale_price', 20, 5)->nullable();
            $table->decimal('dpp', 20, 5)->nullable();
            $table->string('picture')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->foreign('uom_id')->references('id')->on('uoms');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('vendor_id')->references('id')->on('vendors');
        });

        Schema::create('parameters', function (Blueprint $table) {
            $table->id();
            $table->string('code', 255)->unique();
            $table->string('name', 255)->nullable();
            $table->string('value', 255)->nullable();

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
        Schema::dropIfExists('menu_role');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('statuses');
        Schema::dropIfExists('uoms');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('vendors');
        Schema::dropIfExists('items');
        Schema::dropIfExists('parameters');
    }
}
