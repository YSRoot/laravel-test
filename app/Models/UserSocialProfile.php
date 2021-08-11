<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 * @property string $driver
 * @property string $driver_id
 * @property string $email
 * @property string|null $nickname
 * @property string|null $name
 * @property string|null $avatar
 * @property-read User $user
 * @method static Builder|UserSocialProfile newModelQuery()
 * @method static Builder|UserSocialProfile newQuery()
 * @method static Builder|UserSocialProfile query()
 * @mixin Model
 */
class UserSocialProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver',
        'driver_id',
        'nickname',
        'name',
        'email',
        'avatar',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
