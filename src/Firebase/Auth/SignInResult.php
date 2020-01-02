<?php

declare(strict_types=1);

namespace Kreait\Firebase\Auth;

use Kreait\Clock;
use Kreait\Clock\SystemClock;

final class SignInResult
{
    /** @var IdToken|null */
    private $idToken;

    /** @var AccessToken|null */
    private $accessToken;

    /** @var RefreshToken|null */
    private $refreshToken;

    /** @var array */
    private $data = [];

    private function __construct()
    {
    }

    public static function fromData(array $data, Clock $clock = null): self
    {
        $expiresAt = null;
        $clock = $clock ?: new SystemClock();

        if ($expiresIn = $data['expiresIn'] ?? $data['expires_in'] ?? null) {
            $expiresAt = $clock->now()->modify("+{$expiresIn} seconds");
        }

        if ($idToken = $data['idToken'] ?? $data['id_token'] ?? null) {
            $idToken = IdToken::fromString($idToken)->withExpiry($expiresAt);
        }

        if ($accessToken = $data['accessToken'] ?? $data['access_token'] ?? null) {
            $accessToken = AccessToken::fromString($accessToken)->withExpiry($expiresAt);
        }

        if ($refreshToken = $data['refreshToken'] ?? $data['refresh_token'] ?? null) {
            $refreshToken = RefreshToken::fromString($refreshToken)->withExpiry($expiresAt);
        }

        $instance = new self();
        $instance->idToken = $idToken;
        $instance->accessToken = $accessToken;
        $instance->refreshToken = $refreshToken;
        $instance->data = $data;

        return $instance;
    }

    /**
     * @return IdToken|null
     */
    public function idToken()
    {
        return $this->idToken;
    }

    /**
     * @return AccessToken|null
     */
    public function accessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return RefreshToken|null
     */
    public function refreshToken()
    {
        return $this->refreshToken;
    }

    public function data(): array
    {
        return $this->data;
    }
}
