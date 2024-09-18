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
    public string $public_key;
    public string $secret_key;
    public ?int $default_plan_id;
    public ?string $plans;
    public ?string $features;
    public ?int $money_back_period;
    public string $created;
    public ?string $updated;

    /**
     * Plugin constructor.
     *
     * @param int         $id              The plugin ID.
     * @param string      $title           The plugin title.
     * @param string      $slug            The plugin slug.
     * @param string      $public_key       The plugin public key.
     * @param string      $secret_key       The plugin secret key.
     * @param int|null    $default_plan_id   The default plan ID (optional).
     * @param string|null $plans           Comma-separated list of plan IDs (optional).
     * @param string|null $features        Comma-separated list of feature IDs (optional).
     * @param int|null    $money_back_period The money-back period in days (optional).
     * @param string      $created         The creation timestamp.
     * @param string|null $updated         The last update timestamp (optional).
     */
    public function __construct(
        int $id,
        string $title,
        string $slug,
        string $public_key,
        string $secret_key,
        ?int $default_plan_id = null,
        ?string $plans = null,
        ?string $features = null,
        ?int $money_back_period = null,
        string $created,
        ?string $updated = null
    ) {
        $this->id             = $id;
        $this->title          = $title;
        $this->slug           = $slug;
        $this->public_key      = $public_key;
        $this->secret_key      = $secret_key;
        $this->default_plan_id  = $default_plan_id;
        $this->plans          = $plans;
        $this->features       = $features;
        $this->money_back_period = $money_back_period;
        $this->created        = $created;
        $this->updated        = $updated;
    }
}