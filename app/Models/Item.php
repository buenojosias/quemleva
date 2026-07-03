<?php

namespace App\Models;

use App\Enums\CategoryEnum;
use App\Enums\UnitEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $fillable = [
        'campaign_id',
        'category',
        'name',
        'unit',
        'required_quantity',
        'delivery_date',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'category' => CategoryEnum::class,
            'unit' => UnitEnum::class,
            'required_quantity' => 'integer',
            'delivery_date' => 'date',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PromiseItem::class);
    }
}
