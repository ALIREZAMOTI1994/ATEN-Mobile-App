<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Rfq extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'rfq_number',
        'user_id',
        'type',
        'status',
        'company_name',
        'contact_name',
        'email',
        'phone',
        'country',
        'message',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RfqItem::class);
    }

    public function getRouteKeyName(): string
    {
        return 'rfq_number';
    }

    public static function generateRfqNumber(): string
    {
        do {
            $candidate = sprintf('RFQ-%s-%s', now()->format('Y'), Str::upper(Str::random(6)));
        } while (self::where('rfq_number', $candidate)->exists());

        return $candidate;
    }
}
