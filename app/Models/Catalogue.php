<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Laravel\Scout\Searchable;

class Catalogue extends Model
{
    use Searchable;

    /**
     * Get the name of the index associated with the model.
     */
    public function searchableAs(): string
    {
        return 'catalogue_index';
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        $item = $this->toArray();

        return [
            'id' => $item['id'],
            'title' => $item['title'],
            'image' => $item['image'],
            'category' => $item['category'],
            'brand' => $item['brand'],
            'price' => (float) $item['price'],
            'tag' => $item['tag'],
            'rating' => (float) $item['rating'],
            'rating_rounded' => (int) $item['rating_rounded'],
            'reviews_count' => (int) $item['reviews_count'],
            'created_at' => Date::parse($item['created_at'])->unix(),
        ];
    }

    /**
     * Get the value used to index the model.
     */
    public function getScoutKey(): mixed
    {
        return $this->id;
    }

    /**
     * Get the key name used to index the model.
     */
    public function getScoutKeyName(): mixed
    {
        return 'id';
    }
}
