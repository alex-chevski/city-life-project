<?php

declare(strict_types=1);

namespace App\Models\User;

use App\Services\Auth\Tokenizer;
use Carbon\Carbon;
use DomainException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

/**
 * @property int $id
 * @property string $name
 * @property string $lastName
 * @property string $email
 * @property string $phone
 * @property bool $phone_verified
 * @property string $password
 * @property string $verify_token
 * @property string $phone_verify_token
 * @property Carbon $phone_verify_token_expire
 * @property Carbon $expires
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
        'name', 'last_name', 'email', 'phone', 'password', 'verify_token', 'expires', 'status', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'expires' => 'datetime',
        'phone_verified' => 'boolean',
        'phone_verify_token_expire' => 'datetime',
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
    public function requestPasswordReset(Tokenizer $tokenizer, Carbon $date): void
    {

        if (!$this->isActive()) {
            throw new DomainException('User is not active.');
        }
        if (!$this->isFirstTryRequest($typeVerify = 'mail')) {
            $this->checkRepeatRequest($tokenizer->generate($this->expires, 'default', $this->verify_token), $date);
        }

        $token = $tokenizer->generate($date, $typeVerify);
        // for tests
        $this->passwordResetToken = $token;
        $this->update(['verify_token' => $token->getValue(), 'expires' => $token->getExpires()]);

    }

    public function checkRepeatRequest(Token $token, Carbon $date): void
    {
        if ($token->isAlreadyRequest($date)) {
            throw new DomainException('Resetting is already requested. ');
        }

        // for tests
        $this->passwordResetToken = $token;
    }


    public function requestPhoneVerification(Tokenizer $tokenizer, Carbon $date){
        if(empty($this->phone)){
            throw new DomainException('Phone number is empty. ');
        }

        if (!$this->isFirstTryRequest($type = 'sms')) {
            $this->checkRepeatRequest($tokenizer->generate($this->phone_verify_token_expire, 'default', $this->phone_verify_token), $date);
        }

        $token = $tokenizer->generate($date, $type);

        $this->phone_verified = false;
        $this->phone_verify_token = $token->getValue();
        $this->phone_verify_token_expire = $token->getExpires();
        $this->saveOrFail();
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

    /**
     * undocumented function.
     */
    public function resetPassword(string $token, Carbon $now, string $hash, Token $correctTokenPrev): void
    {
        $correctTokenPrev->validate($token, $now);

        $this->update(['verify_token' => null, 'expires' => null, 'password' => Hash::make($hash)]);

        // for tests
        $this->passwordHash = $hash;
    }

    /**
     * undocumented function.
     */
    public function unverifyPhone(): void
    {

        $this->phone_verified = false;
        $this->phone_verify_token = null;
        $this->phone_verify_token_expire = null;
        $this->saveOrFail();

    }

    public function verifyPhone(string $token, Carbon $now, Token $correctTokenPrev): void
    {
        $correctTokenPrev->validate($token, $now);

        $this->phone_verified = true;
        $this->phone_verify_token = null;
        $this->phone_verify_token_expire = null;
        $this->saveOrFail();
    }

    public function isPhoneVerified(): bool
    {
        return $this->phone_verified;
    }


    public function getByEmail(string $email): self
    {
        $user = static::where('email', $email)->first();
        if ($user === null) {
            throw new DomainException('User is not found.');
        }
        return $user;
    }

    public function getByPhone(string $phone): self
    {
        $user = static::where('phone', $phone)->first();
        if ($user === null) {
            throw new DomainException('User is not found.');
        }
        return $user;
    }


    public function getUser($id): self
    {
        $user = static::findOrFail($id);
        if ($user === null) {
            throw new DomainException('User is not found.');
        }
        return $user;
    }


    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getPasswordResetToken()
    {
        return $this->passwordResetToken;
    }

    public function findByPasswordResetToken(string $token): self
    {
        $user = static::where('verify_token', $token)->first();
        if ($user === null) {
            throw new DomainException('Incorrect verify token. ');
        }
        return $user;
    }

    private function isFirstTryRequest(string $type)
    {
        switch($type){
            case 'sms':
                return !($this->phone_verify_token && $this->phone_verify_token_expire);
            case 'mail':
                return !($this->verify_token && $this->expires);
            default:
                throw new InvalidArgumentException('Undefined type request. ');
        }
    }
}
