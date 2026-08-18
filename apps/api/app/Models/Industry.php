<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Industry extends Model
{
    use HasFactory;

    protected $fillable = ['slug', 'name_en', 'name_fa'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_industry');
    }
}
