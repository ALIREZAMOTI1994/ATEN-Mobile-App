<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactMessageRequest;
use App\Notifications\ContactMessageSubmitted;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    public function store(StoreContactMessageRequest $request): JsonResponse
    {
        $data = $request->validated();

        Notification::route('mail', config('mail.sales_inbox'))
            ->notify(new ContactMessageSubmitted(
                $data['name'],
                $data['company_name'] ?? null,
                $data['email'],
                $data['phone'] ?? null,
                $data['message'],
            ));

        return response()->json(['message' => 'Thank you, we will be in touch shortly.'], 201);
    }
}
