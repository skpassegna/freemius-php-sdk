<?php

namespace Freemius\SDK\Entities;

/**
 * Represents a Freemius license.
 */
class License
{
    public readonly int $id;
    public readonly int $plugin_id;
    public readonly int $user_id;
    public readonly int $plan_id;
    public readonly int $pricing_id;
    public readonly int $quota;
    public readonly string $activated;
    public readonly ?string $activated_local;
    public readonly ?string $expiration;
    public readonly bool $is_free_localhost;
    public readonly bool $is_block_features;
    public readonly bool $is_cancelled;
    public readonly string $created;
    public readonly string $updated;

    /**
     * License constructor.
     *
     * @param int $id The license ID.
     * @param int $plugin_id The plugin ID.
     * @param int $user_id The user ID.
     * @param int $plan_id The plan ID.
     * @param int $pricing_id The pricing ID.
     * @param int $quota The license quota (number of sites).
     * @param string $activated The activation timestamp.
     * @param string|null $activated_local The local activation timestamp (optional).
     * @param string|null $expiration The expiration timestamp (optional).
     * @param bool $is_free_localhost Whether the license is free for localhost installs.
     * @param bool $is_block_features Whether the license blocks features.
     * @param bool $is_cancelled Whether the license is cancelled.
     * @param string $created The creation timestamp.
     * @param string $updated The last update timestamp.
     */
    public function __construct(
        int $id,
        int $plugin_id,
        int $user_id,
        int $plan_id,
        int $pricing_id,
        int $quota,
        string $activated,
        ?string $activated_local,
        ?string $expiration,
        bool $is_free_localhost,
        bool $is_block_features,
        bool $is_cancelled,
        string $created,
        string $updated
    ) {
        $this->id = $id;
        $this->plugin_id = $plugin_id;
        $this->user_id = $user_id;
        $this->plan_id = $plan_id;
        $this->pricing_id = $pricing_id;
        $this->quota = $quota;
        $this->activated = $activated;
        $this->activated_local = $activated_local;
        $this->expiration = $expiration;
        $this->is_free_localhost = $is_free_localhost;
        $this->is_block_features = $is_block_features;
        $this->is_cancelled = $is_cancelled;
        $this->created = $created;
        $this->updated = $updated;
    }
}