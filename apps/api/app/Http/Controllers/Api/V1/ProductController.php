<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate([
            'category' => ['nullable', 'string', 'exists:categories,slug'],
            'industry' => ['nullable', 'string', 'exists:industries,slug'],
            'q' => ['nullable', 'string', 'max:255'],
            'food_grade' => ['nullable', 'boolean'],
            'medical_grade' => ['nullable', 'boolean'],
            'featured' => ['nullable', 'boolean'],
            'availability' => ['nullable', 'in:In stock,Made to order,On request'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $query = Product::query()->with(['category', 'images']);

        if (! empty($validated['category'])) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $validated['category']));
        }

        if (! empty($validated['industry'])) {
            $query->whereHas('industries', fn ($q) => $q->where('slug', $validated['industry']));
        }

        if (! empty($validated['q'])) {
            $term = '%'.$validated['q'].'%';
            $query->where(function ($q) use ($term) {
                $q->where('name_en', 'like', $term)
                    ->orWhere('name_fa', 'like', $term)
                    ->orWhere('sku', 'like', $term)
                    ->orWhere('material', 'like', $term)
                    ->orWhere('summary_en', 'like', $term);
            });
        }

        if (array_key_exists('food_grade', $validated)) {
            $query->where('food_grade', $validated['food_grade']);
        }

        if (array_key_exists('medical_grade', $validated)) {
            $query->where('medical_grade', $validated['medical_grade']);
        }

        if (array_key_exists('featured', $validated)) {
            $query->where('featured', $validated['featured']);
        }

        if (! empty($validated['availability'])) {
            $query->where('availability', $validated['availability']);
        }

        $products = $query->orderBy('featured', 'desc')
            ->orderBy('name_en')
            ->paginate($validated['per_page'] ?? 24);

        return ProductListResource::collection($products);
    }

    public function show(Product $product): ProductResource
    {
        $product->load(['category', 'images', 'industries']);
        $product->setRelation('related', Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['category', 'images'])
            ->limit(4)
            ->get());

        return new ProductResource($product);
    }

    public function qrcode(Product $product): Response
    {
        $url = rtrim(config('app.frontend_url'), '/')."/products/{$product->slug}";

        $result = (new Builder(
            writer: new PngWriter,
            data: $url,
            size: 320,
            margin: 10,
        ))->build();

        return response($result->getString(), 200)
            ->header('Content-Type', $result->getMimeType())
            ->header('Cache-Control', 'public, max-age=86400');
    }
}
