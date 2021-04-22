<?php

namespace App\Entity\Banner;

use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Entity\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property int $region_id
 * @property string $name
 * @property int $views
 * @property int $limit
 * @property int $clicks
 * @property int $cost
 * @property string $url
 * @property string $format
 * @property string $file
 * @property string $status
 * @property Carbon $published_at
 *
 * @property Region|null $region
 * @property Category $category
 *
 * @method Builder active()
 * @method Builder forUser(User $user)
 */
class Banner extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_CLOSED = 'closed';
    public const STATUS_ORDERED = 'ordered';
    public const STATUS_MODERATION = 'moderation';
    public const STATUS_MODERATED = 'moderated';

    protected $table = 'banner_banners';

    protected $guarded = ['id'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public static function statusesList(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_CLOSED => 'Closed',
            self::STATUS_ORDERED => 'Payment',
            self::STATUS_MODERATION => 'On moderation',
            self::STATUS_MODERATED => 'Moderated',
        ];
    }

    public static function formatsList(): array
    {
        return [
            '200x400',
        ];
    }

    public function view(): void
    {
        $this->assertIsActive();

        $this->views++;

        if ($this->views >= $this->limit) {
            $this->status = self::STATUS_CLOSED;
        }

        $this->save();
    }

    public function click(): void
    {
        $this->assertIsActive();

        $this->clicks++;

        $this->save();
    }

    public function sendToModeration(): void
    {
        if (! $this->isDraft()) {
            throw new \DomainException('Banner is not a draft.');
        }

        $this->update([
            'status' => self::STATUS_MODERATION,
        ]);
    }

    public function cancelModeration(): void
    {
        if (! $this->isOnModeration()) {
            throw new \DomainException('Banner was not sent for moderation.');
        }

        $this->update([
            'status' => self::STATUS_DRAFT,
        ]);
    }

    public function moderate(): void
    {
        if (! $this->isOnModeration()) {
            throw new \DomainException('Banner was not sent for moderation.');
        }

        $this->update([
            'status' => self::STATUS_MODERATED,
        ]);
    }

    public function reject(string $reason): void
    {
        $this->update([
            'status' => self::STATUS_DRAFT,
            'reject_reason' => $reason,
        ]);
    }

    public function order(int $cost): void
    {
        if (! $this->isModerated()) {
            throw new \DomainException('Banner is not moderated.');
        }

        $this->update([
            'cost' => $cost,
            'status' => self::STATUS_ORDERED,
        ]);
    }

    public function pay(Carbon $date): void
    {
        if (! $this->isOrdered()) {
            throw new \DomainException('Banner is not ordered.');
        }

        $this->update([
            'published_at' => $date,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public function getWidth(): int
    {
        return explode('x', $this->format)[0];
    }

    public function getHeight(): int
    {
        return explode('x', $this->format)[1];
    }

    public function canBeChanged(): bool
    {
        return $this->isDraft();
    }

    public function canBeRemoved(): bool
    {
        return $this->isDraft();
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }

    public function isOrdered(): bool
    {
        return $this->status === self::STATUS_ORDERED;
    }

    public function isOnModeration(): bool
    {
        return $this->status === self::STATUS_MODERATION;
    }

    public function isModerated(): bool
    {
        return $this->status === self::STATUS_MODERATED;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    private function assertIsActive(): void
    {
        if (! $this->isActive()) {
            throw new \DomainException('Banner is not active.');
        }
    }
}
