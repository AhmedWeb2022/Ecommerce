<?php


namespace App\Services\Product;

use App\Traits\ApiResponseTrait;
use App\Params\Product\ProductParam;
use App\Http\Resources\Product\ProductResource;
use App\Repositories\Product\ProductRepository;

class ProductService
{
    use ApiResponseTrait;
    protected $productParam;
    protected $productRepository;
    public function __construct(ProductParam $productParam, ProductRepository $productRepository)
    {
        $this->productParam = $productParam;
        $this->productRepository = $productRepository;
    }

    public function getAll()
    {
        $response = $this->productRepository->getAll();
        if (!$response['status']) {
            return $this->error($response['message']);
        }
        return $this->success(ProductResource::collection($response['data']), 'Products retrieved successfully');
    }

    public function findById(int $id)
    {
        $response = $this->productRepository->findById($id);
        // dd($response);
        if (!$response['status']) {
            return $this->error($response['message']);
        }
        return $this->success(new ProductResource($response['data']), $response['message']);
    }

    public function create(array $data)
    {
        $productParam = $this->productParam->setParams($data);
        $response = $this->productRepository->create($productParam->toArray());

        if (!$response['status']) {
            return $this->error($response['message']);
        }

        return $this->success(new ProductResource($response['data']), $response['message'], 201);
    }

    public function update(int $id, array $data)
    {
        $productParam = $this->productParam->setParams($data);

        $response = $this->productRepository->update($id, $productParam->toArray());

        if (!$response['status']) {
            return $this->error($response['message']);
        }
        return $this->success($response['data'], 'Product updated successfully');
    }

    public function delete(int $id)
    {
        $response = $this->productRepository->delete($id);
        if (!$response['status']) {
            return $this->error($response['message']);
        }
        return $this->successMessage($response['message']);
    }
}
