<?php declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Family extends Model
{
    protected $fillable = [
        'name',
        'limit',
        'total',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
