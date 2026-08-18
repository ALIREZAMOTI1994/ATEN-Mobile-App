<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'sku' => $this->sku,
            'name' => ['en' => $this->name_en, 'fa' => $this->name_fa],
            'category' => $this->whenLoaded('category', fn () => [
                'slug' => $this->category->slug,
                'name' => ['en' => $this->category->name_en, 'fa' => $this->category->name_fa],
            ]),
            'material' => $this->material,
            'summary' => $this->summary_en,
            'thumbnail' => $this->whenLoaded('images', fn () => optional($this->images->first())->path),
            'size_range' => $this->size_range,
            'pressure' => $this->pressure,
            'food_grade' => $this->food_grade,
            'medical_grade' => $this->medical_grade,
            'featured' => $this->featured,
            'availability' => $this->availability,
        ];
    }
}
