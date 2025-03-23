<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Add the new `product_name` column as nullable
        Schema::table('transaction_products', function (Blueprint $table) {
            $table->string('product_name')->nullable()->after('product_id');
        });

        // Step 2: Populate the `product_name` column with values from `products.name`
        DB::table('transaction_products')
            ->join('products', 'transaction_products.product_id', '=', 'products.id')
            ->update(['transaction_products.product_name' => DB::raw('products.name')]);

        // Step 3: Modify the `product_name` column to be NOT NULL
        Schema::table('transaction_products', function (Blueprint $table) {
            $table->string('product_name')->nullable(false)->change();
        });

        // Step 4: Drop the `product_id` column
        Schema::table('transaction_products', function (Blueprint $table) {
            $table->dropForeign(['product_id']); // Drop foreign key constraint if it exists
            $table->dropColumn('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Re-add the `product_id` column
        Schema::table('transaction_products', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->after('id');
        });

        // Step 2: Populate the `product_id` column with values from `products.id` based on `product_name`
        DB::table('transaction_products')
            ->join('products', 'transaction_products.product_name', '=', 'products.name')
            ->update(['transaction_products.product_id' => DB::raw('products.id')]);

        // Step 3: Drop the `product_name` column
        Schema::table('transaction_products', function (Blueprint $table) {
            $table->dropColumn('product_name');
        });
    }
};
