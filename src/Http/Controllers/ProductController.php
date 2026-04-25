<?php

namespace Whilesmart\Products\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Whilesmart\OwnerAccess\Concerns\AuthorizesOwnerController;
use Whilesmart\Products\Http\Requests\StoreProductRequest;
use Whilesmart\Products\Http\Requests\UpdateProductRequest;
use Whilesmart\Products\Http\Resources\ProductResource;
use Whilesmart\Products\Models\Product;

class ProductController extends Controller
{
    use AuthorizesOwnerController;

    public function index(Request $request): JsonResponse
    {
        $query = $this->scopeAccessibleOwners(Product::query(), $request->user());

        if ($request->filled('owner_type') && $request->filled('owner_id')) {
            $query->where('owner_type', $request->input('owner_type'))
                ->where('owner_id', $request->input('owner_id'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->filled('q')) {
            $term = $request->input('q');
            $query->where(function ($q) use ($term) {
                $q->where('name', 'ilike', "%{$term}%")
                    ->orWhere('sku', 'ilike', "%{$term}%")
                    ->orWhere('description', 'ilike', "%{$term}%");
            });
        }

        $products = $query->orderBy('name')
            ->paginate((int) $request->input('per_page', 25));

        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($products)->response()->getData(true),
        ]);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = Product::create($request->validated());

        return response()->json([
            'success' => true,
            'data' => new ProductResource($product),
        ], 201);
    }

    public function show(Product $product, Request $request): JsonResponse
    {
        $this->authorizeAccessTo($product, $request->user());

        return response()->json([
            'success' => true,
            'data' => new ProductResource($product),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $product->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => new ProductResource($product),
        ]);
    }

    public function destroy(Product $product, Request $request): JsonResponse
    {
        $this->authorizeAccessTo($product, $request->user());
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted.',
        ]);
    }
}
