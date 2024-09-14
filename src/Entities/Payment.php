<?php

namespace Freemius\SDK\Entities;

/**
 * Represents a Freemius payment.
 */
class Payment
{
    public int $id;
    public int $userId;
    public int $licenseId;
    public ?int $subscriptionId;
    public int $planId;
    public int $pricingId;
    public float $gross;
    public string $currency;
    public string $gateway;
    public string $transactionId;
    public string $status;
    public string $created;
    public string $updated;

    /**
     * Payment constructor.
     *
     * @param int         $id             The payment ID.
     * @param int         $userId         The user ID.
     * @param int         $licenseId      The license ID.
     * @param int|null    $subscriptionId The subscription ID (optional).
     * @param int         $planId         The plan ID.
     * @param int         $pricingId       The pricing ID.
     * @param float       $gross          The gross amount of the payment.
     * @param string      $currency       The currency of the payment.
     * @param string      $gateway        The payment gateway used.
     * @param string      $transactionId  The payment gateway transaction ID.
     * @param string      $status         The payment status.
     * @param string      $created        The creation timestamp.
     * @param string      $updated        The last update timestamp.
     */
    public function __construct(
        int $id,
        int $userId,
        int $licenseId,
        ?int $subscriptionId,
        int $planId,
        int $pricingId,
        float $gross,
        string $currency,
        string $gateway,
        string $transactionId,
        string $status,
        string $created,
        string $updated
    ) {
        $this->id             = $id;
        $this->userId         = $userId;
        $this->licenseId      = $licenseId;
        $this->subscriptionId = $subscriptionId;
        $this->planId         = $planId;
        $this->pricingId       = $pricingId;
        $this->gross          = $gross;
        $this->currency       = $currency;
        $this->gateway        = $gateway;
        $this->transactionId  = $transactionId;
        $this->status         = $status;
        $this->created        = $created;
        $this->updated        = $updated;
    }
}