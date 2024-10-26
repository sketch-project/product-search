<?php

namespace App\Repositories;

use App\Enums\SearchType;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductRepository
{
    public function __construct(private readonly Product $product) {}

    public function searchProduct(Request $request)
    {
        $baseQuery = $this->product->query()
            ->when($request->filled('search'), function (Builder $baseQuery) use ($request) {
                $search = $request->string('search');
                $searchTypes = $request->input('search_types', collect(SearchType::cases())->map(fn ($item) => $item->value)->all());

                $singleSpaceKeywords = trim(preg_replace('/\s+/', ' ', $search));
                $isEnclosedWithQuotes = preg_match('/^".*"$/', $singleSpaceKeywords) || preg_match("/^'.*'$/", $singleSpaceKeywords);
                if ($isEnclosedWithQuotes) {
                    $keywords = [trim($search, '"\'')];
                } else {
                    $keywords = $this->powerSet(explode(' ', $singleSpaceKeywords));
                    foreach ($keywords as &$keyword) {
                        $keyword = implode(' ', $keyword);
                    }
                }
                $baseQuery->where(function (Builder $query) use ($searchTypes, $keywords) {
                    if (in_array('exact-match', $searchTypes)) {
                        foreach ($keywords as $keywordQuery) {
                            $query
                                ->orWhere('product_name', '=', "$keywordQuery")
                                ->orWhere('description', '=', "$keywordQuery")
                                ->orWhere('keywords', '=', "$keywordQuery")
                                ->orWhere('category', '=', "$keywordQuery");
                        }
                    }
                    if (in_array('partial-match', $searchTypes)) {
                        foreach ($keywords as $keywordQuery) {
                            $query
                                ->orWhere('product_name', 'like', "%$keywordQuery%")
                                ->orWhere('description', 'like', "%$keywordQuery%")
                                ->orWhere('keywords', 'like', "%$keywordQuery%")
                                ->orWhere('category', 'like', "%$keywordQuery%");
                        }
                    }
                    if (in_array('phonetic', $searchTypes)) {
                        foreach ($keywords as $keywordQuery) {
                            $query
                                ->orWhereRaw('SOUNDEX(product_name)=SOUNDEX(?)', $keywordQuery)
                                ->orWhereRaw('SOUNDEX(description)=SOUNDEX(?)', $keywordQuery)
                                ->orWhereRaw('SOUNDEX(keywords)=SOUNDEX(?)', $keywordQuery)
                                ->orWhereRaw('SOUNDEX(category)=SOUNDEX(?)', $keywordQuery);
                        }
                    }
                    if (in_array('fulltext', $searchTypes)) {
                        foreach ($keywords as $keywordQuery) {
                            $query
                                ->orWhereRaw('MATCH(product_name) AGAINST(? WITH QUERY EXPANSION)', $keywordQuery)
                                ->orWhereRaw('MATCH(description) AGAINST(? WITH QUERY EXPANSION)', $keywordQuery)
                                ->orWhereRaw('MATCH(keywords) AGAINST(? WITH QUERY EXPANSION)', $keywordQuery)
                                ->orWhereRaw('MATCH(category) AGAINST(? WITH QUERY EXPANSION)', $keywordQuery);
                        }
                    }
                });
            })
            ->when($request->string('category')->toString(), function (Builder $baseQuery, string $category) {
                $baseQuery->where('category', $category);
            })
            ->when($request->integer('price_from'), function (Builder $baseQuery, int $price) {
                $baseQuery->where('price', '>=', $price);
            })
            ->when($request->integer('price_to'), function (Builder $baseQuery, int $price) {
                $baseQuery->where('price', '<=', $price);
            });

        return $baseQuery->paginate(16);
    }

    private function powerSet($data)
    {
        $setSize = count($data);
        $pow_set_size = pow(2, $setSize);
        $return = [];
        for ($counter = 0; $counter < $pow_set_size; $counter++) {
            $tmpStr = [];
            for ($j = 0; $j < $setSize; $j++) {
                if ($counter & (1 << $j)) {
                    $tmpStr[] = $data[$j];
                }
            }
            if (! empty($tmpStr)) {
                $return[] = $tmpStr;
            }
        }

        return $return;
    }
}
