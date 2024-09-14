<?php

namespace Freemius\SDK\Entities;

/**
 * Represents a Freemius user.
 */
class User
{
    public int $id;
    public string $email;
    public string $firstName;
    public string $lastName;
    public string $publicKey;
    public string $secretKey;
    public bool $isVerified;
    public ?string $picture;
    public string $created;
    public ?string $updated;

    /**
     * User constructor.
     *
     * @param int         $id        The user ID.
     * @param string      $email      The user's email address.
     * @param string      $firstName  The user's first name.
     * @param string      $lastName   The user's last name.
     * @param string      $publicKey  The user's public key.
     * @param string      $secretKey  The user's secret key.
     * @param bool        $isVerified Whether the user's email address is verified.
     * @param string|null $picture    The URL of the user's profile picture (optional).
     * @param string      $created    The creation timestamp.
     * @param string|null $updated    The last update timestamp (optional).
     */
    public function __construct(
        int $id,
        string $email,
        string $firstName,
        string $lastName,
        string $publicKey,
        string $secretKey,
        bool $isVerified,
        ?string $picture = null,
        string $created,
        ?string $updated = null
    ) {
        $this->id         = $id;
        $this->email      = $email;
        $this->firstName  = $firstName;
        $this->lastName   = $lastName;
        $this->publicKey  = $publicKey;
        $this->secretKey  = $secretKey;
        $this->isVerified = $isVerified;
        $this->picture    = $picture;
        $this->created    = $created;
        $this->updated    = $updated;
    }
}