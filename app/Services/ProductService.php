<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class ProductService
{
    public function __construct(private readonly ProductRepository $productRepository) {}

    public function searchProduct(Request $request)
    {
        return $this->productRepository->searchProduct($request);
    }
}
