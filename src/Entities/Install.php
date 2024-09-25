<?php

namespace Freemius\SDK\Entities;

/**
 * Represents a Freemius install (site).
 */
class Install
{
    public readonly int $id;
    public readonly int $plugin_id;
    public readonly int $user_id;
    public readonly string $url;
    public readonly string $title;
    public readonly string $version;
    public readonly ?int $plan_id;
    public readonly ?int $license_id;
    public readonly ?int $trial_plan_id;
    public readonly ?string $trial_ends;
    public readonly ?int $subscription_id;
    public readonly float $gross;
    public readonly string $country_code;
    public readonly ?string $language;
    public readonly ?string $platform_version;
    public readonly ?string $sdk_version;
    public readonly ?string $programming_language_version;
    public readonly bool $is_active;
    public readonly bool $is_disconnected;
    public readonly bool $is_premium;
    public readonly bool $is_uninstalled;
    public readonly bool $is_locked;
    public readonly int $source;
    public readonly ?string $upgraded;
    public readonly ?string $last_seen_at;
    public readonly ?string $last_served_update_version;
    public readonly string $secret_key;
    public readonly string $public_key;
    public readonly string $created;
    public readonly string $updated;
    public readonly ?string $charset;

    /**
     * Install constructor.
     *
     * @param int $id The install ID.
     * @param int $plugin_id The plugin ID.
     * @param int $user_id The user ID.
     * @param string $url The site URL.
     * @param string $title The site title.
     * @param string $version The plugin version installed on the site.
     * @param int|null $plan_id The active plan ID (optional).
     * @param int|null $license_id The license ID (optional).
     * @param int|null $trial_plan_id The trial plan ID (optional).
     * @param string|null $trial_ends The trial end timestamp (optional).
     * @param int|null $subscription_id The subscription ID (optional).
     * @param float $gross The total gross revenue from the install.
     * @param string $country_code The site's country code.
     * @param string|null $language The site's language (optional).
     * @param string|null $platform_version The platform version (optional).
     * @param string|null $sdk_version The Freemius SDK version (optional).
     * @param string|null $programming_language_version The programming language version (optional).
     * @param bool $is_active Whether the plugin is active on the site.
     * @param bool $is_disconnected Whether the install is disconnected.
     * @param bool $is_premium Whether the install is using the premium version.
     * @param bool $is_uninstalled Whether the plugin is uninstalled from the site.
     * @param bool $is_locked Whether the install is locked.
     * @param int $source The install source.
     * @param string|null $upgraded The upgrade timestamp (optional).
     * @param string|null $last_seen_at The last seen timestamp (optional).
     * @param string|null $last_served_update_version The last served update version (optional).
     * @param string $secret_key The install secret key.
     * @param string $public_key The install public key.
     * @param string $created The creation timestamp.
     * @param string $updated The last update timestamp.
     * @param string|null $charset The site's character encoding (optional).
     */
    public function __construct(
        int $id,
        int $plugin_id,
        int $user_id,
        string $url,
        string $title,
        string $version,
        ?int $plan_id,
        ?int $license_id,
        ?int $trial_plan_id,
        ?string $trial_ends,
        ?int $subscription_id,
        float $gross,
        string $country_code,
        ?string $language,
        ?string $platform_version,
        ?string $sdk_version,
        ?string $programming_language_version,
        bool $is_active,
        bool $is_disconnected,
        bool $is_premium,
        bool $is_uninstalled,
        bool $is_locked,
        int $source,
        ?string $upgraded,
        ?string $last_seen_at,
        ?string $last_served_update_version,
        string $secret_key,
        string $public_key,
        string $created,
        string $updated,
        ?string $charset
    ) {
        $this->id = $id;
        $this->plugin_id = $plugin_id;
        $this->user_id = $user_id;
        $this->url = $url;
        $this->title = $title;
        $this->version = $version;
        $this->plan_id = $plan_id;
        $this->license_id = $license_id;
        $this->trial_plan_id = $trial_plan_id;
        $this->trial_ends = $trial_ends;
        $this->subscription_id = $subscription_id;
        $this->gross = $gross;
        $this->country_code = $country_code;
        $this->language = $language;
        $this->platform_version = $platform_version;
        $this->sdk_version = $sdk_version;
        $this->programming_language_version = $programming_language_version;
        $this->is_active = $is_active;
        $this->is_disconnected = $is_disconnected;
        $this->is_premium = $is_premium;
        $this->is_uninstalled = $is_uninstalled;
        $this->is_locked = $is_locked;
        $this->source = $source;
        $this->upgraded = $upgraded;
        $this->last_seen_at = $last_seen_at;
        $this->last_served_update_version = $last_served_update_version;
        $this->secret_key = $secret_key;
        $this->public_key = $public_key;
        $this->created = $created;
        $this->updated = $updated;
        $this->charset = $charset;
    }
}