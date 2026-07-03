<?php

namespace App\Models;

use App\Enums\PromiseItemStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromiseItem extends Model
{
    protected $fillable = [
        'promise_id',
        'item_id',
        'promised_quantity',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'promised_quantity' => 'integer',
            'status' => PromiseItemStatusEnum::class,
        ];
    }

    public function promise(): BelongsTo
    {
        return $this->belongsTo(Promise::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
