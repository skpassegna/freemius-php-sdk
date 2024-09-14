<?php

namespace Freemius\SDK\Api\Endpoints;

use Freemius\SDK\Authentication\AuthenticatorInterface;
use Freemius\SDK\Http\HttpClientInterface;
use Freemius\SDK\Entities\Payment;
use Freemius\SDK\Exceptions\ApiException;

/**
 * Endpoint for interacting with Freemius payments.
 */
class PaymentsEndpoint
{
    private HttpClientInterface $httpClient;
    private AuthenticatorInterface $authenticator;
    private int $developerId;

    /**
     * PaymentsEndpoint constructor.
     *
     * @param HttpClientInterface   $httpClient   The HTTP client to use for API requests.
     * @param AuthenticatorInterface $authenticator The authenticator to use for API requests.
     * @param int                    $developerId  The Freemius developer ID.
     */
    public function __construct(
        HttpClientInterface $httpClient,
        AuthenticatorInterface $authenticator,
        int $developerId
    ) {
        $this->httpClient   = $httpClient;
        $this->authenticator = $authenticator;
        $this->developerId  = $developerId;
    }

    /**
     * Retrieve a list of payments for a plugin.
     *
     * @param int   $pluginId The plugin ID.
     * @param array $params   Optional query parameters (e.g., 'user_id', 'license_id', 'subscription_id', 'status', 'fields', 'count').
     *
     * @return Payment[] An array of Payment entities.
     * @throws ApiException If the API request fails.
     */
    public function getPayments(int $pluginId, array $params = []): array
    {
        $url = sprintf(
            '/developers/%d/plugins/%d/payments.json',
            $this->developerId,
            $pluginId
        );

        $response = $this->httpClient->get(
            $url,
            $params,
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        if (!isset($response['payments']) || !is_array($response['payments'])) {
            throw new ApiException($response, 'Invalid API response: missing payments data.');
        }

        $payments = [];
        foreach ($response['payments'] as $paymentData) {
            $payments[] = new Payment(
                $paymentData['id'],
                $paymentData['user_id'],
                $paymentData['license_id'],
                $paymentData['subscription_id'] ?? null,
                $paymentData['plan_id'],
                $paymentData['pricing_id'],
                $paymentData['gross'],
                $paymentData['currency'],
                $paymentData['gateway'],
                $paymentData['transaction_id'],
                $paymentData['status'],
                $paymentData['created'],
                $paymentData['updated']
            );
        }

        return $payments;
    }

    /**
     * Retrieve a specific payment.
     *
     * @param int   $pluginId The plugin ID.
     * @param int   $paymentId The payment ID.
     * @param array $params   Optional query parameters (e.g., 'fields').
     *
     * @return Payment The Payment entity.
     * @throws ApiException If the API request fails.
     */
    public function getPayment(int $pluginId, int $paymentId, array $params = []): Payment
    {
        $url = sprintf(
            '/developers/%d/plugins/%d/payments/%d.json',
            $this->developerId,
            $pluginId,
            $paymentId
        );

        $response = $this->httpClient->get(
            $url,
            $params,
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        return new Payment(
            $response['id'],
            $response['user_id'],
            $response['license_id'],
            $response['subscription_id'] ?? null,
            $response['plan_id'],
            $response['pricing_id'],
            $response['gross'],
            $response['currency'],
            $response['gateway'],
            $response['transaction_id'],
            $response['status'],
            $response['created'],
            $response['updated']
        );
    }

    /**
     * Refund a payment.
     *
     * @param int   $pluginId The plugin ID.
     * @param int   $paymentId The payment ID.
     * @param array $params   Optional query parameters (e.g., 'fields').
     *
     * @return Payment The refunded Payment entity.
     * @throws ApiException If the API request fails.
     */
    public function refundPayment(int $pluginId, int $paymentId, array $params = []): Payment
    {
        $url = sprintf(
            '/developers/%d/plugins/%d/payments/%d/refund.json',
            $this->developerId,
            $pluginId,
            $paymentId
        );

        $response = $this->httpClient->post(
            $url,
            [],
            $this->authenticator->getAuthHeaders('POST', $url)
        );

        return new Payment(
            $response['id'],
            $response['user_id'],
            $response['license_id'],
            $response['subscription_id'] ?? null,
            $response['plan_id'],
            $response['pricing_id'],
            $response['gross'],
            $response['currency'],
            $response['gateway'],
            $response['transaction_id'],
            $response['status'],
            $response['created'],
            $response['updated']
        );
    }
}