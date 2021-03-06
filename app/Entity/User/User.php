<?php

namespace App\Entity\User;

use App\Entity\Adverts\Advert\Advert;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property boolean $phone_auth
 * @property bool $phone_verified
 * @property string $password
 * @property string $verify_token
 * @property string $phone_verify_token
 * @property Carbon $phone_verify_token_expire
 * @property string $role
 * @property string $status
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';

    public const ROLE_ADMIN = 'admin';
    public const ROLE_MODERATOR = 'moderator';
    public const ROLE_USER = 'user';

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'phone',
        'password',
        'verify_token',
        'status',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'phone_auth' => 'boolean',
        'phone_verified' => 'boolean',
        'phone_verify_token_expire' => 'datetime',
    ];

    public static function rolesList(): array
    {
        return [
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_MODERATOR => 'Moderator',
            self::ROLE_USER => 'User',
        ];
    }

    public static function statusesList(): array
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_WAIT => 'Waiting',
        ];
    }

    public static function register(string $name, string $email, string $password): self
    {
        return static::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'verify_token' => Str::uuid(),
            'role' => self::ROLE_USER,
            'status' => self::STATUS_WAIT,
        ]);
    }

    public static function registerByNetwork(string $identity, string $network): self
    {
        $user = static::create([
            'name' => $identity,
            'email' => null,
            'password' => null,
            'verify_token' => null,
            'role' => self::ROLE_USER,
            'status' => self::STATUS_ACTIVE,
        ]);

        $user->networks()->create([
            'identity' => $identity,
            'network' => $network,
        ]);

        return $user;
    }

    public static function new(string $name, string $email): self
    {
        return static::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt(Str::random()),
            'role' => self::ROLE_USER,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function verify(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('User is already verified.');
        }

        $this->update([
            'status' => self::STATUS_ACTIVE,
            'verify_token' => null,
        ]);
    }

    public function changeRole(string $role): void
    {
        if (!array_key_exists($role, self::rolesList())) {
            throw new \InvalidArgumentException('Undefined role "' . $role . '"');
        }
        if ($this->role === $role) {
            throw new \DomainException('Role is already assigned.');
        }

        $this->update(['role' => $role]);
    }

    public function requestPhoneVerification(Carbon $now): string
    {
        if (is_null($this->phone)) {
            throw new \DomainException('Phone number is empty.');
        }
        if ($this->phone_verify_token !== null && $this->phone_verify_token_expire !== null
            && $this->phone_verify_token_expire->gt($now)) {
            throw new \DomainException('Token is already requested.');
        }

        $this->phone_verified = false;
        $this->phone_verify_token = (string)random_int(10000, 99999);
        $this->phone_verify_token_expire = $now->copy()->addSeconds(300);
        $this->saveOrFail();

        return $this->phone_verify_token;
    }

    public function verifyPhone($token, Carbon $now): void
    {
        if ($token !== $this->phone_verify_token) {
            throw new \DomainException('Incorrect verify token.');
        }
        if ($this->phone_verify_token_expire->lt($now)) {
            throw new \DomainException('Token is expired.');
        }

        $this->phone_verified = true;
        $this->phone_verify_token = null;
        $this->phone_verify_token_expire = null;
        $this->saveOrFail();
    }

    public function unverifyPhone(): void
    {
        $this->phone_auth = false;
        $this->phone_verified = false;
        $this->phone_verify_token = null;
        $this->phone_verify_token_expire = null;
        $this->saveOrFail();
    }

    public function enablePhoneAuth(): void
    {
        if ($this->phone !== null && !$this->isPhoneVerified()) {
            throw new \DomainException('Phone number is don\'t verified.');
        }

        $this->phone_auth = true;
        $this->saveOrFail();
    }

    public function disablePhoneAuth(): void
    {
        $this->phone_auth = false;
        $this->saveOrFail();
    }

    public function addToFavorites(int $advertId): void
    {
        if ($this->hasInFavorites($advertId)) {
            throw new \DomainException('This advert is already added to favorites.');
        }

        $this->favorites()->attach($advertId);
    }

    public function removeFromFavorites(int $advertId): void
    {
        $this->favorites()->detach($advertId);
    }

    public function hasInFavorites(int $advertId): bool
    {
        return $this->favorites()->where('id', $advertId)->exists();
    }

    public function isPhoneVerified(): bool
    {
        return $this->phone_verified;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isModerator(): bool
    {
        return $this->role === self::ROLE_MODERATOR;
    }

    public function isPhoneAuthEnabled(): bool
    {
        return $this->phone_auth;
    }

    public function hasFilledProfile(): bool
    {
        return !empty($this->name) && !empty($this->last_name) && $this->isPhoneVerified();
    }

    public function favorites()
    {
        return $this->belongsToMany(Advert::class, 'advert_favorites', 'user_id', 'advert_id');
    }

    public function networks()
    {
        return $this->hasMany(Network::class);
    }

    public function scopeByNetwork(Builder $query, string $identity, string $network): Builder
    {
        return $query->whereHas('networks', function (Builder $query) use ($identity, $network) {
            $query->where('identity', $identity)->where('network', $network);
        });
    }

    public function findForPassport(string $email)
    {
        return $this->where('email', $email)->where('status', self::STATUS_ACTIVE)->first();
    }
}
