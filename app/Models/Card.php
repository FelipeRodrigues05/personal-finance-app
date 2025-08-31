<?php declare(strict_types = 1);

namespace App\Models;

use App\Enums\CardType;
use Database\Factories\CardFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Card extends Model
{
    /** @use HasFactory<CardFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'limit',
        'used',
        'type',
        'color',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function scopeFromUser(Builder $query): Builder {
        return $query->where('user_id', auth()->user()->id);
    }

    protected function casts(): array
    {
        return [
            'type' => CardType::class,
        ];
    }
}
