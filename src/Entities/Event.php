<?php

namespace Freemius\SDK\Entities;

/**
 * Represents a Freemius event.
 */
class Event
{
    public int $id;
    public string $date;
    public string $type;
    public ?int $install_id;
    public ?int $user_id;
    public ?int $license_id;
    public ?array $data;

    /**
     * Event constructor.
     *
     * @param int         $id        The event ID.
     * @param string      $date      The event timestamp.
     * @param string      $type      The event type.
     * @param int|null    $install_id The install ID (optional).
     * @param int|null    $user_id    The user ID (optional).
     * @param int|null    $license_id The license ID (optional).
     * @param array|null $data      Additional event data (optional).
     */
    public function __construct(
        int $id,
        string $date,
        string $type,
        ?int $install_id = null,
        ?int $user_id = null,
        ?int $license_id = null,
        ?array $data = null
    ) {
        $this->id        = $id;
        $this->date      = $date;
        $this->type      = $type;
        $this->install_id = $install_id;
        $this->user_id    = $user_id;
        $this->license_id = $license_id;
        $this->data      = $data;
    }
}