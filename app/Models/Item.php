<?php

namespace App\Models;

use App\Enums\CategoryEnum;
use App\Enums\UnitEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'category',
        'name',
        'complement',
        'unit',
        'required_quantity',
        'promised_quantity',
        'received_quantity',
        'delivery_date',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'category' => CategoryEnum::class,
            'unit' => UnitEnum::class,
            'required_quantity' => 'integer',
            'promised_quantity' => 'integer',
            'received_quantity' => 'integer',
            'delivery_date' => 'date',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function promisses(): HasMany
    {
        return $this->hasMany(PromiseItem::class);
    }
}
