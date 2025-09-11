<?php declare(strict_types = 1);

namespace App\Models;

use App\Enums\Recurrence;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Investment extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'amount',
        'tax',
        'recurrence',
    ];

    public function casts(): array
    {
        return [
            'recurrence' => Recurrence::class,
        ];
    }

    public function scopeFromUser(Builder $query): Builder
    {
        return $query->where('user_id', auth()->user()->id);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
