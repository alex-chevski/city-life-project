<?php

declare(strict_types=1);

namespace App\Models\User;

use App\Services\Auth\Tokenizer;
use DateTimeImmutable;
use DateTimeZone;
use DomainException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $verify_token
 * @property string $expires
 * @property string $status
 * @protected string $role
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';

    public const ROLE_USER = 'user';
    public const ROLE_ADMIN = 'admin';

    protected $fillable = [
        'name', 'email', 'password', 'verify_token', 'expires', 'status', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    private $passwordResetToken;
    private $passwordHash;

    public static function register(string $name, string $email, string $password, Token $token): self
    {
        return static::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'verify_token' => $token->getValue(),
            'expires' => $token->getExpires(),
            'status' => self::STATUS_WAIT,
            'role' => self::ROLE_USER,
        ]);
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function verify(): void
    {
        if (!$this->isWait()) {
            throw new DomainException('User is already verified.');
        }

        $this->update([
            'status' => self::STATUS_ACTIVE,
            'verify_token' => null,
            'expires' => null,
        ]);
    }

    /**
     * undocumented function.
     *
     * @param mixed $role
     */
    public function changeRole($role): void
    {
        if (!\in_array($role, [self::ROLE_USER, self::ROLE_ADMIN], true)) {
            throw new InvalidArgumentException('Undefined role "' . $role . '"');
        }

        if ($this->role === $role) {
            throw new DomainException('Role is already assigned.');
        }

        $this->update(['role' => $role]);
    }

    /**
     * undocumented function.
     */
    public function requestPasswordReset(Tokenizer $tokenizer, DateTimeImmutable $date): void
    {
        if (!$this->isActive()) {
            throw new DomainException('User is not active.');
        }

        if (!$this->isFirstTryRequest()) {
            $this->checkRepeatRequest($tokenizer->generateOld($this->verify_token, new DateTimeImmutable($this->expires)), $date);
        }

        $token = $tokenizer->generateNew($date);

        // for tests
        $this->passwordResetToken = $token;

        $this->update(['verify_token' => $token->getValue(), 'expires' => $token->getExpires()]);
    }

    public function checkRepeatRequest(Token $token, DateTimeImmutable $date): void
    {
        if ($token->isAlreadyRequest($date)) {
            throw new DomainException('Resetting is already requested. ');
        }

        // for tests
        $this->passwordResetToken = $token;
    }

    /**
     * undocumented function.
     */
    public function registerCommand(string $name, string $email, string $password, Token $token)
    {
        // self::checkRepeatRequest($token,  new DateTimeImmutable('now', new DateTimeZone('Europe/Moscow')));

        return self::register(
            $name,
            $email,
            $password,
            $token
        );
    }

    public function getByEmail(string $email): self
    {
        $user = static::where('email', $email)->first();
        if ($user === null) {
            throw new DomainException('User is not found.');
        }
        return $user;
    }

    public function findByPasswordResetToken(string $token): self
    {
        $user = static::where('verify_token', $token)->first();
        if ($user === null) {
            throw new DomainException('Token is not found. ');
        }

        return $user;
    }

    /**
     * undocumented function.
     */
    public function resetPassword(string $token, DateTimeImmutable $date, string $hash, Token $passwordResetToken): void
    {
        if ($passwordResetToken->getValue() === null) {
            throw new DomainException('Resetting is not requested. ');
        }

        $passwordResetToken->validate($token, $date);

        $this->update(['verify_token' => null, 'expires' => null, 'password' => Hash::make($hash)]);

        // for tests
        $this->passwordHash = $hash;
    }

    /**
     * undocumented function.
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getPasswordResetToken()
    {
        return $this->passwordResetToken;
    }

    private function isFirstTryRequest()
    {
        return !($this->verify_token && $this->expires);
    }
}
