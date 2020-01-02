<?php

declare(strict_types=1);

namespace Kreait\Firebase\Auth;

use DateTimeInterface;
use Kreait\Clock;

trait TokenTrait
{
    /** @var string */
    private $value;

    /** @var DateTimeInterface|null */
    private $expiresAt;

    private function __construct()
    {
    }

    /**
     * @return static
     */
    public static function fromString(string $value)
    {
        $instance = new self();
        $instance->value = $value;

        return $instance;
    }

    /**
     * @return static
     */
    public function withExpiry(DateTimeInterface $expiresAt = null)
    {
        $instance = self::fromString($this->value);
        $instance->expiresAt = $expiresAt;

        return $instance;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isExpired(Clock $clock = null): bool
    {
        $clock = $clock ?: new Clock\SystemClock();

        return $this->expiresAt && ($this->expiresAt < $clock->now());
    }

    public function toString(): string
    {
        return $this->value;
    }
}
