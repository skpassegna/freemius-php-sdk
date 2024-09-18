<?php

namespace Freemius\SDK\Entities;

/**
 * Represents a Freemius subscription.
 */
class Subscription
{
    public int $id;
    public int $user_id;
    public int $plan_id;
    public int $license_id;
    public string $status;
    public string $billing_cycle;
    public string $payment_method;
    public ?string $next_payment;
    public string $created;
    public string $updated;

    /**
     * Subscription constructor.
     *
     * @param int         $id            The subscription ID.
     * @param int         $user_id        The user ID.
     * @param int         $plan_id        The plan ID.
     * @param int         $license_id     The license ID.
     * @param string      $status        The subscription status.
     * @param string      $billing_cycle  The billing cycle (e.g., 'annual', 'monthly').
     * @param string      $payment_method The payment method (e.g., 'paypal', 'stripe').
     * @param string|null $next_payment   The next payment timestamp (optional).
     * @param string      $created       The creation timestamp.
     * @param string      $updated       The last update timestamp.
     */
    public function __construct(
        int $id,
        int $user_id,
        int $plan_id,
        int $license_id,
        string $status,
        string $billing_cycle,
        string $payment_method,
        ?string $next_payment = null,
        string $created,
        string $updated
    ) {
        $this->id            = $id;
        $this->user_id        = $user_id;
        $this->plan_id        = $plan_id;
        $this->license_id     = $license_id;
        $this->status        = $status;
        $this->billing_cycle  = $billing_cycle;
        $this->payment_method = $payment_method;
        $this->next_payment   = $next_payment;
        $this->created       = $created;
        $this->updated       = $updated;
    }
}