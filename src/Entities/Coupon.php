<?php

namespace Freemius\SDK\Entities;

/**
 * Represents a Freemius coupon.
 */
class Coupon
{
    public int $id;
    public int $pluginId;
    public string $code;
    public int $discount;
    public string $type;
    public ?int $planId;
    public ?int $pricingId;
    public int $redemptions;
    public ?int $maxRedemptions;
    public ?string $expiry;
    public string $status;
    public string $created;
    public string $updated;

    /**
     * Coupon constructor.
     *
     * @param int         $id             The coupon ID.
     * @param int         $pluginId       The plugin ID.
     * @param string      $code           The coupon code.
     * @param int         $discount       The discount amount or percentage.
     * @param string      $type           The discount type ('percentage' or 'fixed').
     * @param int|null    $planId         The plan ID the coupon applies to (optional).
     * @param int|null    $pricingId       The pricing ID the coupon applies to (optional).
     * @param int         $redemptions    The number of times the coupon has been redeemed.
     * @param int|null    $maxRedemptions The maximum number of redemptions (optional).
     * @param string|null $expiry         The coupon expiry date (optional).
     * @param string      $status         The coupon status ('active' or 'inactive').
     * @param string      $created        The creation timestamp.
     * @param string      $updated        The last update timestamp.
     */
    public function __construct(
        int $id,
        int $pluginId,
        string $code,
        int $discount,
        string $type,
        ?int $planId = null,
        ?int $pricingId = null,
        int $redemptions = 0,
        ?int $maxRedemptions = null,
        ?string $expiry = null,
        string $status = 'active',
        string $created,
        string $updated
    ) {
        $this->id             = $id;
        $this->pluginId       = $pluginId;
        $this->code           = $code;
        $this->discount       = $discount;
        $this->type           = $type;
        $this->planId         = $planId;
        $this->pricingId       = $pricingId;
        $this->redemptions    = $redemptions;
        $this->maxRedemptions = $maxRedemptions;
        $this->expiry         = $expiry;
        $this->status         = $status;
        $this->created        = $created;
        $this->updated        = $updated;
    }
}