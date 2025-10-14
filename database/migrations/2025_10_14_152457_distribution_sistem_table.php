<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       // tabel users
        Schema::create('users', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->boolean('active')->default(true);
            $table->enum('role', ['admin', 'barista']);
            $table->timestamps(0);
            $table->softDeletes();
        });

        // tabel products
        Schema::create('products', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->boolean('active')->default(true);
            $table->timestamps(0);
            $table->softDeletes();
        });

        // distribution
        Schema::create('distributions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('barista_id');
            $table->integer('total_qty');
            $table->decimal('estimated_result', 10, 2);
            $table->text('notes')->nullable();
            $table->string('created_by');
            $table->timestamps(0);
            $table->softDeletes();

            // relational table
            $table->foreign('barista_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
        });

        // distribution detail
        Schema::create('distribution_details', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('distribution_id')->nullable();
            $table->string('product_id');
            $table->integer('qty');
            $table->decimal('price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('created_by');
            $table->timestamps(0);
            $table->softDeletes();

            // relational table
            $table->foreign('distribution_id')->references('id')->on('distributions')->onDelete('set null');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('restrict');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('users');
        Schema::drop('products');
        Schema::drop('distributions');
        Schema::drop('distribution_details');
    }
};
