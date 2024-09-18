<?php

namespace Freemius\SDK\Entities;

/**
 * Represents a Freemius payment.
 */
class Payment
{
    public int $id;
    public int $user_id;
    public int $license_id;
    public ?int $subscription_id;
    public int $plan_id;
    public int $pricing_id;
    public float $gross;
    public string $currency;
    public string $gateway;
    public string $transaction_id;
    public string $status;
    public string $created;
    public string $updated;

    /**
     * Payment constructor.
     *
     * @param int         $id             The payment ID.
     * @param int         $user_id         The user ID.
     * @param int         $license_id      The license ID.
     * @param int|null    $subscription_id The subscription ID (optional).
     * @param int         $plan_id         The plan ID.
     * @param int         $pricing_id       The pricing ID.
     * @param float       $gross          The gross amount of the payment.
     * @param string      $currency       The currency of the payment.
     * @param string      $gateway        The payment gateway used.
     * @param string      $transaction_id  The payment gateway transaction ID.
     * @param string      $status         The payment status.
     * @param string      $created        The creation timestamp.
     * @param string      $updated        The last update timestamp.
     */
    public function __construct(
        int $id,
        int $user_id,
        int $license_id,
        ?int $subscription_id,
        int $plan_id,
        int $pricing_id,
        float $gross,
        string $currency,
        string $gateway,
        string $transaction_id,
        string $status,
        string $created,
        string $updated
    ) {
        $this->id             = $id;
        $this->user_id         = $user_id;
        $this->license_id      = $license_id;
        $this->subscription_id = $subscription_id;
        $this->plan_id         = $plan_id;
        $this->pricing_id       = $pricing_id;
        $this->gross          = $gross;
        $this->currency       = $currency;
        $this->gateway        = $gateway;
        $this->transaction_id  = $transaction_id;
        $this->status         = $status;
        $this->created        = $created;
        $this->updated        = $updated;
    }
}