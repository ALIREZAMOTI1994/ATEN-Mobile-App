<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Rfq */
class RfqResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'rfq_number' => $this->rfq_number,
            'type' => $this->type,
            'status' => $this->status,
            'company_name' => $this->company_name,
            'contact_name' => $this->contact_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'country' => $this->country,
            'message' => $this->message,
            'submitted_at' => $this->submitted_at?->toIso8601String(),
            'items' => $this->whenLoaded('items', fn () => $this->items->map(fn ($item) => [
                'product_slug' => $item->product?->slug,
                'product_name' => $item->product_name,
                'sku' => $item->sku,
                'quantity' => $item->quantity,
                'unit' => $item->unit,
                'size' => $item->size,
                'notes' => $item->notes,
            ])),
        ];
    }
}
