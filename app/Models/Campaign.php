<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Campaign extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'confirmation_deadline',
        'delivery_deadline',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'confirmation_deadline' => 'datetime: d/m/Y',
            'delivery_deadline' => 'datetime: d/m/Y',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function promises(): HasMany
    {
        return $this->hasMany(Promise::class);
    }

    public function promiseItems(): HasManyThrough
    {
        return $this->hasManyThrough(PromiseItem::class, Item::class);
    }
}
