<?php

namespace Freemius\SDK\Entities;

/**
 * Represents a Freemius coupon.
 */
class Coupon
{
    public readonly int $id;
    public readonly int $plugin_id;
    public readonly string $code;
    public readonly int $discount;
    public readonly string $type;
    public readonly ?int $plan_id;
    public readonly ?int $pricing_id;
    public readonly int $redemptions;
    public readonly ?int $max_redemptions;
    public readonly ?string $expiry;
    public readonly string $status;
    public readonly string $created;
    public readonly string $updated;

    /**
     * Coupon constructor.
     *
     * @param int $id The coupon ID.
     * @param int $plugin_id The plugin ID.
     * @param string $code The coupon code.
     * @param int $discount The discount amount or percentage.
     * @param string $type The discount type ('percentage' or 'fixed').
     * @param int|null $plan_id The plan ID the coupon applies to (optional).
     * @param int|null $pricing_id The pricing ID the coupon applies to (optional).
     * @param int $redemptions The number of times the coupon has been redeemed.
     * @param int|null $max_redemptions The maximum number of redemptions (optional).
     * @param string|null $expiry The coupon expiry date (optional).
     * @param string $status The coupon status ('active' or 'inactive').
     * @param string $created The creation timestamp.
     * @param string $updated The last update timestamp.
     */
    public function __construct(
        int $id,
        int $plugin_id,
        string $code,
        int $discount,
        string $type,
        ?int $plan_id,
        ?int $pricing_id,
        int $redemptions,
        ?int $max_redemptions,
        ?string $expiry,
        string $status,
        string $created,
        string $updated
    ) {
        $this->id = $id;
        $this->plugin_id = $plugin_id;
        $this->code = $code;
        $this->discount = $discount;
        $this->type = $type;
        $this->plan_id = $plan_id;
        $this->pricing_id = $pricing_id;
        $this->redemptions = $redemptions;
        $this->max_redemptions = $max_redemptions;
        $this->expiry = $expiry;
        $this->status = $status;
        $this->created = $created;
        $this->updated = $updated;
    }
}