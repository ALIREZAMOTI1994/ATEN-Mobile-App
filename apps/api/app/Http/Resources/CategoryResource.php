<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Category */
class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'name' => ['en' => $this->name_en, 'fa' => $this->name_fa],
            'blurb' => ['en' => $this->blurb_en, 'fa' => $this->blurb_fa],
            'image' => $this->image_path,
            'product_count' => $this->whenCounted('products'),
        ];
    }
}
