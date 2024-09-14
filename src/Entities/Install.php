<?php

namespace Freemius\SDK\Entities;

/**
 * Represents a Freemius install (site).
 */
class Install
{
    public int $id;
    public int $pluginId;
    public int $userId;
    public string $url;
    public string $title;
    public string $version;
    public ?int $planId;
    public ?int $licenseId;
    public ?int $trialPlanId;
    public ?string $trialEnds;
    public ?int $subscriptionId;
    public float $gross;
    public string $countryCode;
    public ?string $language;
    public ?string $platformVersion;
    public ?string $sdkVersion;
    public ?string $programmingLanguageVersion;
    public bool $isActive;
    public bool $isDisconnected;
    public bool $isPremium;
    public bool $isUninstalled;
    public bool $isLocked;
    public int $source;
    public ?string $upgraded;
    public ?string $lastSeenAt;
    public ?string $lastServedUpdateVersion;
    public string $secretKey;
    public string $publicKey;
    public string $created;
    public string $updated;
    public ?string $charset;

    /**
     * Install constructor.
     *
     * @param int         $id                        The install ID.
     * @param int         $pluginId                  The plugin ID.
     * @param int         $userId                    The user ID.
     * @param string      $url                       The site URL.
     * @param string      $title                     The site title.
     * @param string      $version                   The plugin version installed on the site.
     * @param int|null    $planId                    The active plan ID (optional).
     * @param int|null    $licenseId                 The license ID (optional).
     * @param int|null    $trialPlanId               The trial plan ID (optional).
     * @param string|null $trialEnds                  The trial end timestamp (optional).
     * @param int|null    $subscriptionId            The subscription ID (optional).
     * @param float       $gross                     The total gross revenue from the install.
     * @param string      $countryCode              The site's country code.
     * @param string|null $language                  The site's language (optional).
     * @param string|null $platformVersion            The platform version (optional).
     * @param string|null $sdkVersion                 The Freemius SDK version (optional).
     * @param string|null $programmingLanguageVersion The programming language version (optional).
     * @param bool        $isActive                  Whether the plugin is active on the site.
     * @param bool        $isDisconnected            Whether the install is disconnected.
     * @param bool        $isPremium                 Whether the install is using the premium version.
     * @param bool        $isUninstalled             Whether the plugin is uninstalled from the site.
     * @param bool        $isLocked                  Whether the install is locked.
     * @param int         $source                    The install source.
     * @param string|null $upgraded                  The upgrade timestamp (optional).
     * @param string|null $lastSeenAt                 The last seen timestamp (optional).
     * @param string|null $lastServedUpdateVersion    The last served update version (optional).
     * @param string      $secretKey                 The install secret key.
     * @param string      $publicKey                 The install public key.
     * @param string      $created                   The creation timestamp.
     * @param string      $updated                   The last update timestamp.
     * @param string|null $charset                   The site's character encoding (optional).
     */
    public function __construct(
        int $id,
        int $pluginId,
        int $userId,
        string $url,
        string $title,
        string $version,
        ?int $planId = null,
        ?int $licenseId = null,
        ?int $trialPlanId = null,
        ?string $trialEnds = null,
        ?int $subscriptionId = null,
        float $gross,
        string $countryCode,
        ?string $language = null,
        ?string $platformVersion = null,
        ?string $sdkVersion = null,
        ?string $programmingLanguageVersion = null,
        bool $isActive,
        bool $isDisconnected,
        bool $isPremium,
        bool $isUninstalled,
        bool $isLocked,
        int $source,
        ?string $upgraded = null,
        ?string $lastSeenAt = null,
        ?string $lastServedUpdateVersion = null,
        string $secretKey,
        string $publicKey,
        string $created,
        string $updated,
        ?string $charset = null
    ) {
        $this->id                        = $id;
        $this->pluginId                  = $pluginId;
        $this->userId                    = $userId;
        $this->url                       = $url;
        $this->title                     = $title;
        $this->version                   = $version;
        $this->planId                    = $planId;
        $this->licenseId                 = $licenseId;
        $this->trialPlanId               = $trialPlanId;
        $this->trialEnds                  = $trialEnds;
        $this->subscriptionId            = $subscriptionId;
        $this->gross                     = $gross;
        $this->countryCode              = $countryCode;
        $this->language                  = $language;
        $this->platformVersion            = $platformVersion;
        $this->sdkVersion                 = $sdkVersion;
        $this->programmingLanguageVersion = $programmingLanguageVersion;
        $this->isActive                  = $isActive;
        $this->isDisconnected            = $isDisconnected;
        $this->isPremium                 = $isPremium;
        $this->isUninstalled             = $isUninstalled;
        $this->isLocked                  = $isLocked;
        $this->source                    = $source;
        $this->upgraded                  = $upgraded;
        $this->lastSeenAt                 = $lastSeenAt;
        $this->lastServedUpdateVersion    = $lastServedUpdateVersion;
        $this->secretKey                 = $secretKey;
        $this->publicKey                 = $publicKey;
        $this->created                   = $created;
        $this->updated                   = $updated;
        $this->charset                   = $charset;
    }
}