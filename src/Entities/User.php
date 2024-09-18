<?php

namespace Freemius\SDK\Entities;

/**
 * Represents a Freemius user.
 */
class User
{
    public int $id;
    public string $email;
    public string $first;
    public string $last;
    public string $public_key;
    public string $secret_key;
    public bool $is_verified;
    public ?string $picture;
    public string $created;
    public ?string $updated;

    /**
     * User constructor.
     *
     * @param int         $id        The user ID.
     * @param string      $email      The user's email address.
     * @param string      $first  The user's first name.
     * @param string      $last   The user's last name.
     * @param string      $public_key  The user's public key.
     * @param string      $secret_key  The user's secret key.
     * @param bool        $is_verified Whether the user's email address is verified.
     * @param string|null $picture    The URL of the user's profile picture (optional).
     * @param string      $created    The creation timestamp.
     * @param string|null $updated    The last update timestamp (optional).
     */
    public function __construct(
        int $id,
        string $email,
        string $first,
        string $last,
        string $public_key,
        string $secret_key,
        bool $is_verified,
        ?string $picture,
        string $created,
        ?string $updated
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->first = $first;
        $this->last = $last;
        $this->public_key = $public_key;
        $this->secret_key = $secret_key;
        $this->is_verified = $is_verified;
        $this->picture = $picture;
        $this->created = $created;
        $this->updated = $updated;
    }
}