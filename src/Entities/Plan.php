<?php

namespace Freemius\SDK\Entities;

/**
 * Represents a Freemius plan.
 */
class Plan
{
    public int $id;
    public int $pluginId;
    public string $name;
    public string $title;
    public ?string $description;
    public bool $isFreeLocalhost;
    public int $licenseType;
    public ?int $trialPeriod;
    public bool $isRequireSubscription;
    public ?string $supportKb;
    public ?string $supportForum;
    public ?string $supportEmail;
    public ?string $supportPhone;
    public ?string $supportSkype;
    public bool $isSuccessManager;
    public bool $isFeatured;
    public bool $isHttpsSupport;
    public string $created;
    public ?string $updated;

    /**
     * Plan constructor.
     *
     * @param int         $id                   The plan ID.
     * @param int         $pluginId             The plugin ID.
     * @param string      $name                 The plan name.
     * @param string      $title                The plan title.
     * @param string|null $description          The plan description (optional).
     * @param bool        $isFreeLocalhost      Whether the plan is free for localhost installs.
     * @param int         $licenseType           The license type (0: per domain, 1: per subdomain).
     * @param int|null    $trialPeriod           The trial period in days (optional).
     * @param bool        $isRequireSubscription Whether a subscription is required even with a trial.
     * @param string|null $supportKb            The support knowledge base URL (optional).
     * @param string|null $supportForum         The support forum URL (optional).
     * @param string|null $supportEmail          The support email address (optional).
     * @param string|null $supportPhone          The support phone number (optional).
     * @param string|null $supportSkype          The support Skype username (optional).
     * @param bool        $isSuccessManager     Whether the plan includes a success manager.
     * @param bool        $isFeatured            Whether the plan is featured.
     * @param bool        $isHttpsSupport       Whether the plan includes HTTPS support.
     * @param string      $created              The creation timestamp.
     * @param string|null $updated              The last update timestamp (optional).
     */
    public function __construct(
        int $id,
        int $pluginId,
        string $name,
        string $title,
        ?string $description = null,
        bool $isFreeLocalhost = false,
        int $licenseType = 0,
        ?int $trialPeriod = null,
        bool $isRequireSubscription = false,
        ?string $supportKb = null,
        ?string $supportForum = null,
        ?string $supportEmail = null,
        ?string $supportPhone = null,
        ?string $supportSkype = null,
        bool $isSuccessManager = false,
        bool $isFeatured = false,
        bool $isHttpsSupport = false,
        string $created,
        ?string $updated = null
    ) {
        $this->id                   = $id;
        $this->pluginId             = $pluginId;
        $this->name                 = $name;
        $this->title                = $title;
        $this->description          = $description;
        $this->isFreeLocalhost      = $isFreeLocalhost;
        $this->licenseType           = $licenseType;
        $this->trialPeriod           = $trialPeriod;
        $this->isRequireSubscription = $isRequireSubscription;
        $this->supportKb            = $supportKb;
        $this->supportForum         = $supportForum;
        $this->supportEmail          = $supportEmail;
        $this->supportPhone          = $supportPhone;
        $this->supportSkype          = $supportSkype;
        $this->isSuccessManager     = $isSuccessManager;
        $this->isFeatured            = $isFeatured;
        $this->isHttpsSupport       = $isHttpsSupport;
        $this->created              = $created;
        $this->updated              = $updated;
    }
}