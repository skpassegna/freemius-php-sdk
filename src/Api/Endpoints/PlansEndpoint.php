<?php

namespace Freemius\SDK\Api\Endpoints;

use Freemius\SDK\Authentication\AuthenticatorInterface;
use Freemius\SDK\Http\HttpClientInterface;
use Freemius\SDK\Entities\Plan;
use Freemius\SDK\Exceptions\ApiException;
use Freemius\SDK\Enums\Scope;

/**
 * Endpoint for interacting with Freemius plans.
 */
class PlansEndpoint
{
    private HttpClientInterface $httpClient;
    private AuthenticatorInterface $authenticator;
    private Scope $scope;
    private int $scopeId;
    private string $apiVersion;

    /**
     * PlansEndpoint constructor.
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
     * Retrieve a list of plans for a plugin.
     *
     * @param int $pluginId The plugin ID.
     * @param array $params Optional query parameters (e.g., 'fields', 'count').
     *
     * @return Plan[] An array of Plan entities.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getPlans(int $pluginId, array $params = []): array
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/plans.json',
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
                $planData['description'] ?? null,
                $planData['is_free_localhost'],
                $planData['license_type'],
                $planData['trial_period'] ?? null,
                $planData['is_require_subscription'],
                $planData['support_kb'] ?? null,
                $planData['support_forum'] ?? null,
                $planData['support_email'] ?? null,
                $planData['support_phone'] ?? null,
                $planData['support_skype'] ?? null,
                $planData['is_success_manager'],
                $planData['is_featured'],
                $planData['is_https_support'],
                $planData['created'],
                $planData['updated'] ?? null
            );
        }

        return $plans;
    }

    /**
     * Retrieve a specific plan.
     *
     * @param int $pluginId The plugin ID.
     * @param int $planId The plan ID.
     * @param array $params Optional query parameters (e.g., 'fields').
     *
     * @return Plan The Plan entity.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getPlan(int $pluginId, int $planId, array $params = []): Plan
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/plans/%d.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
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
            $response['description'] ?? null,
            $response['is_free_localhost'],
            $response['license_type'],
            $response['trial_period'] ?? null,
            $response['is_require_subscription'],
            $response['support_kb'] ?? null,
            $response['support_forum'] ?? null,
            $response['support_email'] ?? null,
            $response['support_phone'] ?? null,
            $response['support_skype'] ?? null,
            $response['is_success_manager'],
            $response['is_featured'],
            $response['is_https_support'],
            $response['created'],
            $response['updated'] ?? null
        );
    }

    /**
     * Create a new plan.
     *
     * @param int $pluginId The plugin ID.
     * @param array $data The plan data.
     *
     * @return Plan The created Plan entity.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function createPlan(int $pluginId, array $data): Plan
    {
        $this->validateScope([Scope::DEVELOPER]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/plans.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
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
            $response['description'] ?? null,
            $response['is_free_localhost'],
            $response['license_type'],
            $response['trial_period'] ?? null,
            $response['is_require_subscription'],
            $response['support_kb'] ?? null,
            $response['support_forum'] ?? null,
            $response['support_email'] ?? null,
            $response['support_phone'] ?? null,
            $response['support_skype'] ?? null,
            $response['is_success_manager'],
            $response['is_featured'],
            $response['is_https_support'],
            $response['created'],
            $response['updated'] ?? null
        );
    }

    /**
     * Update an existing plan.
     *
     * @param int $pluginId The plugin ID.
     * @param int $planId The plan ID.
     * @param array $data The plan data to update.
     *
     * @return Plan The updated Plan entity.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function updatePlan(int $pluginId, int $planId, array $data): Plan
    {
        $this->validateScope([Scope::DEVELOPER]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/plans/%d.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
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
            $response['description'] ?? null,
            $response['is_free_localhost'],
            $response['license_type'],
            $response['trial_period'] ?? null,
            $response['is_require_subscription'],
            $response['support_kb'] ?? null,
            $response['support_forum'] ?? null,
            $response['support_email'] ?? null,
            $response['support_phone'] ?? null,
            $response['support_skype'] ?? null,
            $response['is_success_manager'],
            $response['is_featured'],
            $response['is_https_support'],
            $response['created'],
            $response['updated'] ?? null
        );
    }

    /**
     * Delete a plan.
     *
     * @param int $pluginId The plugin ID.
     * @param int $planId The plan ID.
     *
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function deletePlan(int $pluginId, int $planId): void
    {
        $this->validateScope([Scope::DEVELOPER]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/plans/%d.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId,
            $planId
        );

        $this->httpClient->delete(
            $url,
            $this->authenticator->getAuthHeaders('DELETE', $url)
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