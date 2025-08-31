<?php declare(strict_types = 1);

namespace App\Models;

use App\Enums\TransactionTypeEnum;
use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Transaction extends Model
{
    /** @use HasFactory<TransactionFactory> */
    use HasFactory;

    protected $fillable = [
        'value',
        'type',
        'transaction_date',
        'description',
        'category_id',
        'user_id',
        'image_path',
        'card_id',
        'used_card'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function card(): BelongsTo {
        return $this->belongsTo(Card::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFromUser(Builder $query)
    {
        return $query->where('user_id', auth()->user()->id);
    }

    protected function casts(): array
    {
        return [
            'type'             => TransactionTypeEnum::class,
            'transaction_date' => 'datetime',
        ];
    }
}
