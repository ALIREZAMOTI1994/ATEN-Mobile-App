<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRfqRequest;
use App\Http\Resources\RfqResource;
use App\Models\Product;
use App\Models\Rfq;
use App\Notifications\RfqSubmitted;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class RfqController extends Controller
{
    public function store(StoreRfqRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();

        $rfq = DB::transaction(function () use ($data) {
            $rfq = Rfq::create([
                'rfq_number' => Rfq::generateRfqNumber(),
                'user_id' => Auth::id(),
                'type' => $data['type'],
                'status' => 'submitted',
                'company_name' => $data['company_name'],
                'contact_name' => $data['contact_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'country' => $data['country'] ?? null,
                'message' => $data['message'] ?? null,
                'submitted_at' => now(),
            ]);

            foreach ($data['items'] as $item) {
                $product = ! empty($item['product_slug'])
                    ? Product::where('slug', $item['product_slug'])->first()
                    : null;

                $rfq->items()->create([
                    'product_id' => $product?->id,
                    'product_name' => $item['product_name'] ?? $product?->name_en,
                    'sku' => $product?->sku,
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'] ?? 'pcs',
                    'size' => $item['size'] ?? null,
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            return $rfq;
        });

        $rfq->load('items.product');
        $rfq->notify(new RfqSubmitted);

        return (new RfqResource($rfq))
            ->response()
            ->setStatusCode(HttpResponse::HTTP_CREATED);
    }

    public function show(Request $request, string $rfqNumber): RfqResource
    {
        $request->validate(['email' => ['required', 'email']]);

        $rfq = Rfq::with('items.product')
            ->where('rfq_number', $rfqNumber)
            ->where('email', $request->query('email'))
            ->firstOrFail();

        return new RfqResource($rfq);
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $rfqs = $request->user()
            ->rfqs()
            ->with('items.product')
            ->latest()
            ->paginate(20);

        return RfqResource::collection($rfqs);
    }
}
