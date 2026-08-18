<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'sku' => $this->sku,
            'name' => ['en' => $this->name_en, 'fa' => $this->name_fa],
            'category' => new CategoryResource($this->whenLoaded('category')),
            'material' => $this->material,
            'summary' => $this->summary_en,
            'description' => $this->description_en,
            'applications' => $this->applications ?? [],
            'industries' => IndustryResource::collection($this->whenLoaded('industries')),
            'images' => $this->whenLoaded('images', fn () => $this->images->pluck('path')->values()),
            'specs' => $this->specs,
            'size_range' => $this->size_range,
            'length_range' => $this->length_range,
            'pressure' => $this->pressure,
            'food_grade' => $this->food_grade,
            'medical_grade' => $this->medical_grade,
            'featured' => $this->featured,
            'availability' => $this->availability,
            'catalog_page' => $this->catalog_page,
            'qr_code_url' => route('products.qrcode', $this->slug),
            'related' => ProductListResource::collection($this->whenLoaded('related')),
        ];
    }
}
