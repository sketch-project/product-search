<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name')->fulltext();
            $table->text('description')->fulltext();
            $table->string('category')->fulltext();
            $table->decimal('price', 10, 2)->index();
            $table->unsignedInteger('stock');
            $table->text('keywords')->fulltext();
            $table->timestamps();
        });

        if (Storage::disk('database-data')->exists('products.json')) {
            $products = Storage::disk('database-data')->json('products.json');
            collect($products)->lazy()->chunk(200)->each(function ($chunk) {
                DB::table('products')->insert($chunk->toArray());
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
