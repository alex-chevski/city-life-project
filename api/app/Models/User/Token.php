<?php

declare(strict_types=1);

namespace App\Models\User;

use Carbon\Carbon;
use DomainException;
use Webmozart\Assert\Assert;

/**
 * Class Token.
 * @author yourname
 */
class Token
{
    public function __construct(
        private string $value,
        private Carbon $expires
    ) {
        Assert::uuid($value);
        $this->value = mb_strtolower($value);
        $this->expires = $expires;
    }

    public function isExpiredTo(Carbon $date): bool
    {
        return $this->expires <= $date;
    }

    public function validate(string $value, Carbon $date): void
    {
        if (!$this->isEqualTo($value)) {
            throw new DomainException('Token is invalid.');
        }
        if ($this->isExpiredTo($date)) {
            throw new DomainException('Token is expired.');
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isAlreadyRequest(Carbon $date)
    {
        return $this->value !== null && !$this->isExpiredTo($date);
    }

    public function getExpires(): Carbon
    {
        return $this->expires;
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }

    private function isEqualTo(string $value): bool
    {
        return $this->value === $value;
    }
}
