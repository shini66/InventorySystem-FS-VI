<?php

namespace App\Models;

use Database\Factories\MovementFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movement extends Model
{
    /** @use HasFactory<MovementFactory> */
    use HasFactory;

    protected $fillable = ['product_id', 'type', 'quantity', 'supplier', 'reason', 'moved_at'];

    protected function casts(): array
    {
        return ['quantity' => 'integer', 'moved_at' => 'datetime'];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeForCompany(Builder $query, int $companyId): Builder
    {
        return $query->whereHas('product', fn (Builder $productQuery) => $productQuery->forCompany($companyId));
    }
}
