<?php

namespace Freemius\SDK\Api\Endpoints;

use Freemius\SDK\Api\Authentication\AuthenticatorInterface;
use Freemius\SDK\Api\Http\HttpClientInterface;
use Freemius\SDK\Entities\Subscription;
use Freemius\SDK\Exceptions\ApiException;

/**
 * Endpoint for interacting with Freemius subscriptions.
 */
class SubscriptionsEndpoint
{
    private HttpClientInterface $httpClient;
    private AuthenticatorInterface $authenticator;
    private int $developerId;

    /**
     * SubscriptionsEndpoint constructor.
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
     * Retrieve a list of subscriptions for a plugin.
     *
     * @param int   $pluginId The plugin ID.
     * @param array $params   Optional query parameters (e.g., 'user_id', 'plan_id', 'status', 'fields', 'count').
     *
     * @return Subscription[] An array of Subscription entities.
     * @throws ApiException If the API request fails.
     */
    public function getSubscriptions(int $pluginId, array $params = []): array
    {
        $url = sprintf(
            '/developers/%d/plugins/%d/subscriptions.json',
            $this->developerId,
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
                $subscriptionData['next_payment'],
                $subscriptionData['created'],
                $subscriptionData['updated']
            );
        }

        return $subscriptions;
    }

    /**
     * Retrieve a specific subscription.
     *
     * @param int   $pluginId        The plugin ID.
     * @param int   $subscriptionId The subscription ID.
     * @param array $params          Optional query parameters (e.g., 'fields').
     *
     * @return Subscription The Subscription entity.
     * @throws ApiException If the API request fails.
     */
    public function getSubscription(int $pluginId, int $subscriptionId, array $params = []): Subscription
    {
        $url = sprintf(
            '/developers/%d/plugins/%d/subscriptions/%d.json',
            $this->developerId,
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
            $response['next_payment'],
            $response['created'],
            $response['updated']
        );
    }

    /**
     * Cancel a subscription.
     *
     * @param int   $pluginId        The plugin ID.
     * @param int   $subscriptionId The subscription ID.
     * @param array $params          Optional query parameters (e.g., 'fields').
     *
     * @return Subscription The cancelled Subscription entity.
     * @throws ApiException If the API request fails.
     */
    public function cancelSubscription(int $pluginId, int $subscriptionId, array $params = []): Subscription
    {
        $url = sprintf(
            '/developers/%d/plugins/%d/subscriptions/%d.json',
            $this->developerId,
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
            $response['next_payment'],
            $response['created'],
            $response['updated']
        );
    }

    /**
     * Sync a subscription with the payment gateway.
     *
     * @param int   $pluginId        The plugin ID.
     * @param int   $subscriptionId The subscription ID.
     * @param array $params          Optional query parameters (e.g., 'fields').
     *
     * @return Subscription The synced Subscription entity.
     * @throws ApiException If the API request fails.
     */
    public function syncSubscription(int $pluginId, int $subscriptionId, array $params = []): Subscription
    {
        $url = sprintf(
            '/developers/%d/plugins/%d/subscriptions/%d/sync.json',
            $this->developerId,
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
            $response['next_payment'],
            $response['created'],
            $response['updated']
        );
    }
}