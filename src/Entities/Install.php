<?php

namespace Freemius\SDK\Entities;

/**
 * Represents a Freemius install (site).
 */
class Install
{
    public int $id;
    public int $plugin_id;
    public int $user_id;
    public string $url;
    public string $title;
    public string $version;
    public ?int $plan_id;
    public ?int $license_id;
    public ?int $trial_plan_id;
    public ?string $trial_ends;
    public ?int $subscription_id;
    public float $gross;
    public string $country_code;
    public ?string $language;
    public ?string $platform_version;
    public ?string $sdk_version;
    public ?string $programming_language_version;
    public bool $is_active;
    public bool $is_disconnected;
    public bool $is_premium;
    public bool $is_uninstalled;
    public bool $is_locked;
    public int $source;
    public ?string $upgraded;
    public ?string $last_seen_at;
    public ?string $last_served_update_version;
    public string $secret_key;
    public string $public_key;
    public string $created;
    public string $updated;
    public ?string $charset;

    /**
     * Install constructor.
     *
     * @param int         $id                        The install ID.
     * @param int         $plugin_id                  The plugin ID.
     * @param int         $user_id                    The user ID.
     * @param string      $url                       The site URL.
     * @param string      $title                     The site title.
     * @param string      $version                   The plugin version installed on the site.
     * @param int|null    $plan_id                    The active plan ID (optional).
     * @param int|null    $license_id                 The license ID (optional).
     * @param int|null    $trial_plan_id               The trial plan ID (optional).
     * @param string|null $trial_ends                  The trial end timestamp (optional).
     * @param int|null    $subscription_id            The subscription ID (optional).
     * @param float       $gross                     The total gross revenue from the install.
     * @param string      $country_code              The site's country code.
     * @param string|null $language                  The site's language (optional).
     * @param string|null $platform_version            The platform version (optional).
     * @param string|null $sdk_version                 The Freemius SDK version (optional).
     * @param string|null $programming_language_version The programming language version (optional).
     * @param bool        $is_active                  Whether the plugin is active on the site.
     * @param bool        $is_disconnected            Whether the install is disconnected.
     * @param bool        $is_premium                 Whether the install is using the premium version.
     * @param bool        $is_uninstalled             Whether the plugin is uninstalled from the site.
     * @param bool        $is_locked                  Whether the install is locked.
     * @param int         $source                    The install source.
     * @param string|null $upgraded                  The upgrade timestamp (optional).
     * @param string|null $last_seen_at                 The last seen timestamp (optional).
     * @param string|null $last_served_update_version    The last served update version (optional).
     * @param string      $secret_key                 The install secret key.
     * @param string      $public_key                 The install public key.
     * @param string      $created                   The creation timestamp.
     * @param string      $updated                   The last update timestamp.
     * @param string|null $charset                   The site's character encoding (optional).
     */
    public function __construct(
        int $id,
        int $plugin_id,
        int $user_id,
        string $url,
        string $title,
        string $version,
        ?int $plan_id = null,
        ?int $license_id = null,
        ?int $trial_plan_id = null,
        ?string $trial_ends = null,
        ?int $subscription_id = null,
        float $gross,
        string $country_code,
        ?string $language = null,
        ?string $platform_version = null,
        ?string $sdk_version = null,
        ?string $programming_language_version = null,
        bool $is_active,
        bool $is_disconnected,
        bool $is_premium,
        bool $is_uninstalled,
        bool $is_locked,
        int $source,
        ?string $upgraded = null,
        ?string $last_seen_at = null,
        ?string $last_served_update_version = null,
        string $secret_key,
        string $public_key,
        string $created,
        string $updated,
        ?string $charset = null
    ) {
        $this->id                        = $id;
        $this->plugin_id                  = $plugin_id;
        $this->user_id                    = $user_id;
        $this->url                       = $url;
        $this->title                     = $title;
        $this->version                   = $version;
        $this->plan_id                    = $plan_id;
        $this->license_id                 = $license_id;
        $this->trial_plan_id               = $trial_plan_id;
        $this->trial_ends                  = $trial_ends;
        $this->subscription_id            = $subscription_id;
        $this->gross                     = $gross;
        $this->country_code              = $country_code;
        $this->language                  = $language;
        $this->platform_version            = $platform_version;
        $this->sdk_version                 = $sdk_version;
        $this->programming_language_version = $programming_language_version;
        $this->is_active                  = $is_active;
        $this->is_disconnected            = $is_disconnected;
        $this->is_premium                 = $is_premium;
        $this->is_uninstalled             = $is_uninstalled;
        $this->is_locked                  = $is_locked;
        $this->source                    = $source;
        $this->upgraded                  = $upgraded;
        $this->last_seen_at                 = $last_seen_at;
        $this->last_served_update_version    = $last_served_update_version;
        $this->secret_key                 = $secret_key;
        $this->public_key                 = $public_key;
        $this->created                   = $created;
        $this->updated                   = $updated;
        $this->charset                   = $charset;
    }
}