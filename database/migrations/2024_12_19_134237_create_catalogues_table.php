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
        Schema::create('catalogues', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('url', 500)->nullable();
            $table->string('image', 500)->nullable();
            $table->string('category', 200)->nullable();
            $table->string('brand', 200)->nullable();
            $table->decimal('price', 20, 2)->default(0);
            $table->string('tag', 300)->nullable();
            $table->float('rating', 1)->default(0);
            $table->unsignedInteger('rating_rounded')->default(0);
            $table->unsignedInteger('reviews_count')->default(0);
            $table->timestamps();
        });

        if (Storage::disk('database-data')->exists('catalogues.json')) {
            $products = Storage::disk('database-data')->json('catalogues.json');
            collect($products)->lazy()->chunk(200)->each(function ($chunk) {
                $items = array_map(function($item) {
                    return [
                        'title' => $item['title'],
                        'description' => $item['description'],
                        'url' => $item['url'],
                        'image' => $item['images'][0] ?? null,
                        'category' => $item['category'],
                        'brand' => $item['brand'],
                        'price' => $item['price'] ?? 0,
                        'tag' => $item['tag'],
                        'rating' => $item['rating'],
                        'rating_rounded' => $item['rating_rounded'],
                        'reviews_count' => $item['reviews_count'],
                    ];
                }, $chunk->toArray());
                DB::table('catalogues')->insert($items);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogues');
    }
};
