<?php declare(strict_types = 1);

namespace App\Models;

use Database\Factories\GroupFactory;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Group extends Model implements HasAvatar
{
    /** @use HasFactory<GroupFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'creator_id',
        'name',
        'description',
        'avatar_url',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }
}
