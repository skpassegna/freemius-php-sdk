<?php

namespace Freemius\SDK\Api\Endpoints;

use Freemius\SDK\Api\Authentication\AuthenticatorInterface;
use Freemius\SDK\Api\Http\HttpClientInterface;
use Freemius\SDK\Entities\Plan;
use Freemius\SDK\Exceptions\ApiException;

/**
 * Endpoint for interacting with Freemius plans.
 */
class PlansEndpoint
{
    private HttpClientInterface $httpClient;
    private AuthenticatorInterface $authenticator;
    private int $developerId;

    /**
     * PlansEndpoint constructor.
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
     * Retrieve a list of plans for a plugin.
     *
     * @param int   $pluginId The plugin ID.
     * @param array $params   Optional query parameters (e.g., 'fields', 'count').
     *
     * @return Plan[] An array of Plan entities.
     * @throws ApiException If the API request fails.
     */
    public function getPlans(int $pluginId, array $params = []): array
    {
        $url = sprintf(
            '/developers/%d/plugins/%d/plans.json',
            $this->developerId,
            $pluginId
        );

        $response = $this->httpClient->get(
            $url,
            $params,
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        if (!isset($response['plans']) || !is_array($response['plans'])) {
            throw new ApiException($response, 'Invalid API response: missing plans data.');
        }

        $plans = [];
        foreach ($response['plans'] as $planData) {
            $plans[] = new Plan(
                $planData['id'],
                $planData['plugin_id'],
                $planData['name'],
                $planData['title'],
                $planData['description'],
                $planData['is_free_localhost'],
                $planData['license_type'],
                $planData['trial_period'],
                $planData['is_require_subscription'],
                $planData['support_kb'],
                $planData['support_forum'],
                $planData['support_email'],
                $planData['support_phone'],
                $planData['support_skype'],
                $planData['is_success_manager'],
                $planData['is_featured'],
                $planData['is_https_support'],
                $planData['created'],
                $planData['updated']
            );
        }

        return $plans;
    }

    /**
     * Retrieve a specific plan.
     *
     * @param int   $pluginId The plugin ID.
     * @param int   $planId   The plan ID.
     * @param array $params   Optional query parameters (e.g., 'fields').
     *
     * @return Plan The Plan entity.
     * @throws ApiException If the API request fails.
     */
    public function getPlan(int $pluginId, int $planId, array $params = []): Plan
    {
        $url = sprintf(
            '/developers/%d/plugins/%d/plans/%d.json',
            $this->developerId,
            $pluginId,
            $planId
        );

        $response = $this->httpClient->get(
            $url,
            $params,
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        return new Plan(
            $response['id'],
            $response['plugin_id'],
            $response['name'],
            $response['title'],
            $response['description'],
            $response['is_free_localhost'],
            $response['license_type'],
            $response['trial_period'],
            $response['is_require_subscription'],
            $response['support_kb'],
            $response['support_forum'],
            $response['support_email'],
            $response['support_phone'],
            $response['support_skype'],
            $response['is_success_manager'],
            $response['is_featured'],
            $response['is_https_support'],
            $response['created'],
            $response['updated']
        );
    }

    /**
     * Create a new plan.
     *
     * @param int   $pluginId The plugin ID.
     * @param array $data     The plan data.
     *
     * @return Plan The created Plan entity.
     * @throws ApiException If the API request fails.
     */
    public function createPlan(int $pluginId, array $data): Plan
    {
        $url = sprintf(
            '/developers/%d/plugins/%d/plans.json',
            $this->developerId,
            $pluginId
        );

        $response = $this->httpClient->post(
            $url,
            $data,
            $this->authenticator->getAuthHeaders('POST', $url, $data)
        );

        return new Plan(
            $response['id'],
            $response['plugin_id'],
            $response['name'],
            $response['title'],
            $response['description'],
            $response['is_free_localhost'],
            $response['license_type'],
            $response['trial_period'],
            $response['is_require_subscription'],
            $response['support_kb'],
            $response['support_forum'],
            $response['support_email'],
            $response['support_phone'],
            $response['support_skype'],
            $response['is_success_manager'],
            $response['is_featured'],
            $response['is_https_support'],
            $response['created'],
            $response['updated']
        );
    }

    /**
     * Update an existing plan.
     *
     * @param int   $pluginId The plugin ID.
     * @param int   $planId   The plan ID.
     * @param array $data     The plan data to update.
     *
     * @return Plan The updated Plan entity.
     * @throws ApiException If the API request fails.
     */
    public function updatePlan(int $pluginId, int $planId, array $data): Plan
    {
        $url = sprintf(
            '/developers/%d/plugins/%d/plans/%d.json',
            $this->developerId,
            $pluginId,
            $planId
        );

        $response = $this->httpClient->put(
            $url,
            $data,
            $this->authenticator->getAuthHeaders('PUT', $url, $data)
        );

        return new Plan(
            $response['id'],
            $response['plugin_id'],
            $response['name'],
            $response['title'],
            $response['description'],
            $response['is_free_localhost'],
            $response['license_type'],
            $response['trial_period'],
            $response['is_require_subscription'],
            $response['support_kb'],
            $response['support_forum'],
            $response['support_email'],
            $response['support_phone'],
            $response['support_skype'],
            $response['is_success_manager'],
            $response['is_featured'],
            $response['is_https_support'],
            $response['created'],
            $response['updated']
        );
    }

    /**
     * Delete a plan.
     *
     * @param int $pluginId The plugin ID.
     * @param int $planId   The plan ID.
     *
     * @throws ApiException If the API request fails.
     */
    public function deletePlan(int $pluginId, int $planId): void
    {
        $url = sprintf(
            '/developers/%d/plugins/%d/plans/%d.json',
            $this->developerId,
            $pluginId,
            $planId
        );

        $this->httpClient->delete(
            $url,
            $this->authenticator->getAuthHeaders('DELETE', $url)
        );
    }
}