<?php

namespace App\Http\Controllers;

use App\Enums\SearchType;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $productService) {}

    public function index(Request $request)
    {
        $products = $this->productService->searchProduct($request);

        $searchMethods = collect(SearchType::cases())->map(fn ($item) => $item->value)->all();

        return view('products/index', compact('products', 'searchMethods'));
    }
}
