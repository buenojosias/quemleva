<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $fillable = [
        'campaign_id',
        'name',
        'unit',
        'quantity',
        'delivery_date',
        'note',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PromiseItem::class);
    }
}
