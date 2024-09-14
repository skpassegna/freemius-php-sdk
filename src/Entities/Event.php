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
    public ?int $installId;
    public ?int $userId;
    public ?int $licenseId;
    public ?array $data;

    /**
     * Event constructor.
     *
     * @param int         $id        The event ID.
     * @param string      $date      The event timestamp.
     * @param string      $type      The event type.
     * @param int|null    $installId The install ID (optional).
     * @param int|null    $userId    The user ID (optional).
     * @param int|null    $licenseId The license ID (optional).
     * @param array|null $data      Additional event data (optional).
     */
    public function __construct(
        int $id,
        string $date,
        string $type,
        ?int $installId = null,
        ?int $userId = null,
        ?int $licenseId = null,
        ?array $data = null
    ) {
        $this->id        = $id;
        $this->date      = $date;
        $this->type      = $type;
        $this->installId = $installId;
        $this->userId    = $userId;
        $this->licenseId = $licenseId;
        $this->data      = $data;
    }
}