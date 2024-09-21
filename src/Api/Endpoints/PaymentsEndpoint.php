<?php

namespace Freemius\SDK\Api\Endpoints;

use Freemius\SDK\Authentication\AuthenticatorInterface;
use Freemius\SDK\Http\HttpClientInterface;
use Freemius\SDK\Entities\Payment;
use Freemius\SDK\Exceptions\ApiException;
use Freemius\SDK\Enums\Scope;

/**
 * Endpoint for interacting with Freemius payments.
 */
class PaymentsEndpoint
{
    private HttpClientInterface $httpClient;
    private AuthenticatorInterface $authenticator;
    private Scope $scope;
    private int $scopeId;
    private string $apiVersion;

    /**
     * PaymentsEndpoint constructor.
     *
     * @param HttpClientInterface $httpClient
     * @param AuthenticatorInterface $authenticator
     * @param Scope $scope
     * @param int $scopeId
     * @param string $apiVersion
     */
    public function __construct(
        HttpClientInterface $httpClient,
        AuthenticatorInterface $authenticator,
        Scope $scope,
        int $scopeId,
        string $apiVersion
    ) {
        $this->httpClient = $httpClient;
        $this->authenticator = $authenticator;
        $this->scope = $scope;
        $this->scopeId = $scopeId;
        $this->apiVersion = $apiVersion;
    }

    /**
     * Retrieve a list of payments for a plugin.
     *
     * @param int $pluginId The plugin ID.
     * @param array $params Optional query parameters (e.g., 'user_id', 'license_id', 'subscription_id', 'status', 'fields', 'count').
     *
     * @return Payment[] An array of Payment entities.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getPayments(int $pluginId, array $params = []): array
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/payments.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
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
     * @param int $pluginId The plugin ID.
     * @param int $paymentId The payment ID.
     * @param array $params Optional query parameters (e.g., 'fields').
     *
     * @return Payment The Payment entity.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getPayment(int $pluginId, int $paymentId, array $params = []): Payment
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/payments/%d.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
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
     * @param int $pluginId The plugin ID.
     * @param int $paymentId The payment ID.
     * @param array $params Optional query parameters (e.g., 'fields').
     *
     * @return Payment The refunded Payment entity.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function refundPayment(int $pluginId, int $paymentId, array $params = []): Payment
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/payments/%d/refund.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
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

    /**
     * Validate the current scope against allowed scopes.
     *
     * @param Scope[] $allowedScopes
     *
     * @throws ApiException If the scope is invalid.
     */
    private function validateScope(array $allowedScopes): void
    {
        if (!in_array($this->scope, $allowedScopes)) {
            throw new ApiException([], 'Invalid scope for this method.');
        }
    }
}