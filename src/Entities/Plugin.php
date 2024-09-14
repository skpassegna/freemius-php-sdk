<?php

namespace Freemius\SDK\Entities;

/**
 * Represents a Freemius plugin.
 */
class Plugin
{
    public int $id;
    public string $title;
    public string $slug;
    public string $publicKey;
    public string $secretKey;
    public ?int $defaultPlanId;
    public ?string $plans;
    public ?string $features;
    public ?int $moneyBackPeriod;
    public string $created;
    public ?string $updated;

    /**
     * Plugin constructor.
     *
     * @param int         $id              The plugin ID.
     * @param string      $title           The plugin title.
     * @param string      $slug            The plugin slug.
     * @param string      $publicKey       The plugin public key.
     * @param string      $secretKey       The plugin secret key.
     * @param int|null    $defaultPlanId   The default plan ID (optional).
     * @param string|null $plans           Comma-separated list of plan IDs (optional).
     * @param string|null $features        Comma-separated list of feature IDs (optional).
     * @param int|null    $moneyBackPeriod The money-back period in days (optional).
     * @param string      $created         The creation timestamp.
     * @param string|null $updated         The last update timestamp (optional).
     */
    public function __construct(
        int $id,
        string $title,
        string $slug,
        string $publicKey,
        string $secretKey,
        ?int $defaultPlanId = null,
        ?string $plans = null,
        ?string $features = null,
        ?int $moneyBackPeriod = null,
        string $created,
        ?string $updated = null
    ) {
        $this->id             = $id;
        $this->title          = $title;
        $this->slug           = $slug;
        $this->publicKey      = $publicKey;
        $this->secretKey      = $secretKey;
        $this->defaultPlanId  = $defaultPlanId;
        $this->plans          = $plans;
        $this->features       = $features;
        $this->moneyBackPeriod = $moneyBackPeriod;
        $this->created        = $created;
        $this->updated        = $updated;
    }
}