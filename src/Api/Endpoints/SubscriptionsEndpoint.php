<?php

namespace Freemius\SDK\Api\Endpoints;

use Freemius\SDK\Authentication\AuthenticatorInterface;
use Freemius\SDK\Http\HttpClientInterface;
use Freemius\SDK\Entities\Subscription;
use Freemius\SDK\Exceptions\ApiException;
use Freemius\SDK\Enums\Scope;

/**
 * Endpoint for interacting with Freemius subscriptions.
 */
class SubscriptionsEndpoint
{
    private HttpClientInterface $httpClient;
    private AuthenticatorInterface $authenticator;
    private Scope $scope;
    private int $scopeId;
    private string $apiVersion;

    /**
     * SubscriptionsEndpoint constructor.
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
     * Retrieve a list of subscriptions for a plugin.
     *
     * @param int $pluginId The plugin ID.
     * @param array $params Optional query parameters (e.g., 'user_id', 'plan_id', 'status', 'fields', 'count').
     *
     * @return Subscription[] An array of Subscription entities.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getSubscriptions(int $pluginId, array $params = []): array
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/subscriptions.json',
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

        if (!isset($response['subscriptions']) || !is_array($response['subscriptions'])) {
            throw new ApiException($response, 'Invalid API response: missing subscriptions data.');
        }

        $subscriptions = [];
        foreach ($response['subscriptions'] as $subscriptionData) {
            $subscriptions[] = new Subscription(
                $subscriptionData['id'],
                $subscriptionData['user_id'],
                $subscriptionData['plan_id'],
                $subscriptionData['license_id'],
                $subscriptionData['status'],
                $subscriptionData['billing_cycle'],
                $subscriptionData['payment_method'],
                $subscriptionData['next_payment'] ?? null,
                $subscriptionData['created'],
                $subscriptionData['updated']
            );
        }

        return $subscriptions;
    }

    /**
     * Retrieve a specific subscription.
     *
     * @param int $pluginId The plugin ID.
     * @param int $subscriptionId The subscription ID.
     * @param array $params Optional query parameters (e.g., 'fields').
     *
     * @return Subscription The Subscription entity.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getSubscription(int $pluginId, int $subscriptionId, array $params = []): Subscription
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/subscriptions/%d.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId,
            $subscriptionId
        );

        $response = $this->httpClient->get(
            $url,
            $params,
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        return new Subscription(
            $response['id'],
            $response['user_id'],
            $response['plan_id'],
            $response['license_id'],
            $response['status'],
            $response['billing_cycle'],
            $response['payment_method'],
            $response['next_payment'] ?? null,
            $response['created'],
            $response['updated']
        );
    }

    /**
     * Cancel a subscription.
     *
     * @param int $pluginId The plugin ID.
     * @param int $subscriptionId The subscription ID.
     * @param array $params Optional query parameters (e.g., 'fields').
     *
     * @return Subscription The cancelled Subscription entity.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function cancelSubscription(int $pluginId, int $subscriptionId, array $params = []): Subscription
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/subscriptions/%d.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId,
            $subscriptionId
        );

        $response = $this->httpClient->delete(
            $url,
            $this->authenticator->getAuthHeaders('DELETE', $url)
        );

        return new Subscription(
            $response['id'],
            $response['user_id'],
            $response['plan_id'],
            $response['license_id'],
            $response['status'],
            $response['billing_cycle'],
            $response['payment_method'],
            $response['next_payment'] ?? null,
            $response['created'],
            $response['updated']
        );
    }

    /**
     * Sync a subscription with the payment gateway.
     *
     * @param int $pluginId The plugin ID.
     * @param int $subscriptionId The subscription ID.
     * @param array $params Optional query parameters (e.g., 'fields').
     *
     * @return Subscription The synced Subscription entity.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function syncSubscription(int $pluginId, int $subscriptionId, array $params = []): Subscription
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/subscriptions/%d/sync.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId,
            $subscriptionId
        );

        $response = $this->httpClient->put(
            $url,
            [],
            $this->authenticator->getAuthHeaders('PUT', $url)
        );

        return new Subscription(
            $response['id'],
            $response['user_id'],
            $response['plan_id'],
            $response['license_id'],
            $response['status'],
            $response['billing_cycle'],
            $response['payment_method'],
            $response['next_payment'] ?? null,
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