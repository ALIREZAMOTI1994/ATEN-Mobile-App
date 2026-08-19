<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'slug',
        'sku',
        'name_en',
        'name_fa',
        'material',
        'summary_en',
        'description_en',
        'applications',
        'specs',
        'size_range',
        'length_range',
        'pressure',
        'food_grade',
        'medical_grade',
        'featured',
        'availability',
        'catalog_page',
    ];

    protected $casts = [
        'applications' => 'array',
        'specs' => 'array',
        'food_grade' => 'boolean',
        'medical_grade' => 'boolean',
        'featured' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function industries(): BelongsToMany
    {
        return $this->belongsToMany(Industry::class, 'product_industry');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
