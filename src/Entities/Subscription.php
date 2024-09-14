<?php

namespace Freemius\SDK\Entities;

/**
 * Represents a Freemius subscription.
 */
class Subscription
{
    public int $id;
    public int $userId;
    public int $planId;
    public int $licenseId;
    public string $status;
    public string $billingCycle;
    public string $paymentMethod;
    public ?string $nextPayment;
    public string $created;
    public string $updated;

    /**
     * Subscription constructor.
     *
     * @param int         $id            The subscription ID.
     * @param int         $userId        The user ID.
     * @param int         $planId        The plan ID.
     * @param int         $licenseId     The license ID.
     * @param string      $status        The subscription status.
     * @param string      $billingCycle  The billing cycle (e.g., 'annual', 'monthly').
     * @param string      $paymentMethod The payment method (e.g., 'paypal', 'stripe').
     * @param string|null $nextPayment   The next payment timestamp (optional).
     * @param string      $created       The creation timestamp.
     * @param string      $updated       The last update timestamp.
     */
    public function __construct(
        int $id,
        int $userId,
        int $planId,
        int $licenseId,
        string $status,
        string $billingCycle,
        string $paymentMethod,
        ?string $nextPayment = null,
        string $created,
        string $updated
    ) {
        $this->id            = $id;
        $this->userId        = $userId;
        $this->planId        = $planId;
        $this->licenseId     = $licenseId;
        $this->status        = $status;
        $this->billingCycle  = $billingCycle;
        $this->paymentMethod = $paymentMethod;
        $this->nextPayment   = $nextPayment;
        $this->created       = $created;
        $this->updated       = $updated;
    }
}