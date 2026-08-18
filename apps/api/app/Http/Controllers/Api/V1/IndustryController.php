<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\IndustryResource;
use App\Models\Industry;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndustryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $industries = Industry::orderBy('name_en')->get();

        return IndustryResource::collection($industries);
    }
}
