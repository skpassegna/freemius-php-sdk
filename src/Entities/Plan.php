<?php

namespace Freemius\SDK\Entities;

/**
 * Represents a Freemius plan.
 */
class Plan
{
    public readonly int $id;
    public readonly int $plugin_id;
    public readonly string $name;
    public readonly string $title;
    public readonly ?string $description;
    public readonly bool $is_free_localhost;
    public readonly int $license_type;
    public readonly ?int $trial_period;
    public readonly bool $is_require_subscription;
    public readonly ?string $support_kb;
    public readonly ?string $support_forum;
    public readonly ?string $support_email;
    public readonly ?string $support_phone;
    public readonly ?string $support_skype;
    public readonly bool $is_success_manager;
    public readonly bool $is_featured;
    public readonly bool $is_https_support;
    public readonly string $created;
    public readonly ?string $updated;

    /**
     * Plan constructor.
     *
     * @param int $id The plan ID.
     * @param int $plugin_id The plugin ID.
     * @param string $name The plan name.
     * @param string $title The plan title.
     * @param string|null $description The plan description (optional).
     * @param bool $is_free_localhost Whether the plan is free for localhost installs.
     * @param int $license_type The license type (0: per domain, 1: per subdomain).
     * @param int|null $trial_period The trial period in days (optional).
     * @param bool $is_require_subscription Whether a subscription is required even with a trial.
     * @param string|null $support_kb The support knowledge base URL (optional).
     * @param string|null $support_forum The support forum URL (optional).
     * @param string|null $support_email The support email address (optional).
     * @param string|null $support_phone The support phone number (optional).
     * @param string|null $support_skype The support Skype username (optional).
     * @param bool $is_success_manager Whether the plan includes a success manager.
     * @param bool $is_featured Whether the plan is featured.
     * @param bool $is_https_support Whether the plan includes HTTPS support.
     * @param string $created The creation timestamp.
     * @param string|null $updated The last update timestamp (optional).
     */
    public function __construct(
        int $id,
        int $plugin_id,
        string $name,
        string $title,
        ?string $description,
        bool $is_free_localhost,
        int $license_type,
        ?int $trial_period,
        bool $is_require_subscription,
        ?string $support_kb,
        ?string $support_forum,
        ?string $support_email,
        ?string $support_phone,
        ?string $support_skype,
        bool $is_success_manager,
        bool $is_featured,
        bool $is_https_support,
        string $created,
        ?string $updated
    ) {
        $this->id = $id;
        $this->plugin_id = $plugin_id;
        $this->name = $name;
        $this->title = $title;
        $this->description = $description;
        $this->is_free_localhost = $is_free_localhost;
        $this->license_type = $license_type;
        $this->trial_period = $trial_period;
        $this->is_require_subscription = $is_require_subscription;
        $this->support_kb = $support_kb;
        $this->support_forum = $support_forum;
        $this->support_email = $support_email;
        $this->support_phone = $support_phone;
        $this->support_skype = $support_skype;
        $this->is_success_manager = $is_success_manager;
        $this->is_featured = $is_featured;
        $this->is_https_support = $is_https_support;
        $this->created = $created;
        $this->updated = $updated;
    }
}