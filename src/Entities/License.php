<?php

namespace Freemius\SDK\Entities;

/**
 * Represents a Freemius license.
 */
class License
{
    public int $id;
    public int $pluginId;
    public int $userId;
    public int $planId;
    public int $pricingId;
    public int $quota;
    public string $activated;
    public ?string $activatedLocal;
    public ?string $expiration;
    public bool $isFreeLocalhost;
    public bool $isBlockFeatures;
    public bool $isCancelled;
    public string $created;
    public string $updated;

    /**
     * License constructor.
     *
     * @param int         $id              The license ID.
     * @param int         $pluginId        The plugin ID.
     * @param int         $userId          The user ID.
     * @param int         $planId          The plan ID.
     * @param int         $pricingId        The pricing ID.
     * @param int         $quota           The license quota (number of sites).
     * @param string      $activated       The activation timestamp.
     * @param string|null $activatedLocal  The local activation timestamp (optional).
     * @param string|null $expiration      The expiration timestamp (optional).
     * @param bool        $isFreeLocalhost Whether the license is free for localhost installs.
     * @param bool        $isBlockFeatures  Whether the license blocks features.
     * @param bool        $isCancelled      Whether the license is cancelled.
     * @param string      $created         The creation timestamp.
     * @param string      $updated         The last update timestamp.
     */
    public function __construct(
        int $id,
        int $pluginId,
        int $userId,
        int $planId,
        int $pricingId,
        int $quota,
        string $activated,
        ?string $activatedLocal = null,
        ?string $expiration = null,
        bool $isFreeLocalhost = false,
        bool $isBlockFeatures = false,
        bool $isCancelled = false,
        string $created,
        string $updated
    ) {
        $this->id              = $id;
        $this->pluginId        = $pluginId;
        $this->userId          = $userId;
        $this->planId          = $planId;
        $this->pricingId        = $pricingId;
        $this->quota           = $quota;
        $this->activated       = $activated;
        $this->activatedLocal  = $activatedLocal;
        $this->expiration      = $expiration;
        $this->isFreeLocalhost = $isFreeLocalhost;
        $this->isBlockFeatures  = $isBlockFeatures;
        $this->isCancelled      = $isCancelled;
        $this->created         = $created;
        $this->updated         = $updated;
    }
}