<?php

namespace App\Http\Controllers\Api\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Product\ShowProductDetailRequest;
use App\Http\Requests\Api\Product\StoreProductRequest;
use App\Http\Requests\Api\Product\UpdateProductRequest;
use App\Services\Product\ProductService;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        return $this->productService->getAll();
    }

    public function show($id)
    {
        return $this->productService->findById($id);
    }

    public function store(StoreProductRequest $request)
    {
        return $this->productService->create($request->all());
    }

    public function update(UpdateProductRequest $request, $id)
    {
        return $this->productService->update($id, $request->all());
    }

    public function destroy($id)
    {
        return $this->productService->delete($id)->getContent();
    }
}
