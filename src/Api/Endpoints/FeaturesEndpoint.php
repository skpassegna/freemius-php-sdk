<?php

namespace Freemius\SDK\Api\Endpoints;

use Freemius\SDK\Authentication\AuthenticatorInterface;
use Freemius\SDK\Http\HttpClientInterface;
use Freemius\SDK\Entities\Feature;
use Freemius\SDK\Exceptions\ApiException;

/**
 * Endpoint for interacting with Freemius features.
 */
class FeaturesEndpoint
{
    private HttpClientInterface $httpClient;
    private AuthenticatorInterface $authenticator;
    private int $developerId;
    private string $scope;

    /**
     * FeaturesEndpoint constructor.
     *
     * @param HttpClientInterface $httpClient The HTTP client to use for API requests.
     * @param AuthenticatorInterface $authenticator The authenticator to use for API requests.
     * @param int $developerId The Freemius developer ID.
     * @param string $scope The API scope.
     */
    public function __construct(
        HttpClientInterface $httpClient,
        AuthenticatorInterface $authenticator,
        int $developerId,
        string $scope
    ) {
        $this->httpClient = $httpClient;
        $this->authenticator = $authenticator;
        $this->developerId = $developerId;
        $this->scope = $scope;
    }

    /**
     * Retrieve a list of features for a plugin.
     *
     * @param int   $pluginId The plugin ID.
     * @param array $params   Optional query parameters (e.g., 'plan_id', 'fields', 'count').
     *
     * @return Feature[] An array of Feature entities.
     * @throws ApiException If the API request fails.
     */
    public function getFeatures(int $pluginId, array $params = []): array
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/features.json',
            $this->scope,
            $this->developerId,
            $pluginId
        );

        $response = $this->httpClient->get(
            $url,
            $params,
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        if (!isset($response['features']) || !is_array($response['features'])) {
            throw new ApiException($response, 'Invalid API response: missing features data.');
        }

        $features = [];
        foreach ($response['features'] as $featureData) {
            $features[] = new Feature(
                $featureData['id'],
                $featureData['plugin_id'],
                $featureData['title'],
                $featureData['description'] ?? null,
                $featureData['is_featured'],
                $featureData['created'],
                $featureData['updated'] ?? null
            );
        }

        return $features;
    }

    /**
     * Retrieve a specific feature.
     *
     * @param int   $pluginId  The plugin ID.
     * @param int   $featureId The feature ID.
     * @param array $params    Optional query parameters (e.g., 'fields').
     *
     * @return Feature The Feature entity.
     * @throws ApiException If the API request fails.
     */
    public function getFeature(int $pluginId, int $featureId, array $params = []): Feature
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/features/%d.json',
            $this->scope,
            $this->developerId,
            $pluginId,
            $featureId
        );

        $response = $this->httpClient->get(
            $url,
            $params,
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        return new Feature(
            $response['id'],
            $response['plugin_id'],
            $response['title'],
            $response['description'] ?? null,
            $response['is_featured'],
            $response['created'],
            $response['updated'] ?? null
        );
    }

    /**
     * Create a new feature.
     *
     * @param int   $pluginId The plugin ID.
     * @param array $data     The feature data.
     *
     * @return Feature The created Feature entity.
     * @throws ApiException If the API request fails.
     */
    public function createFeature(int $pluginId, array $data): Feature
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/features.json',
            $this->scope,
            $this->developerId,
            $pluginId
        );

        $response = $this->httpClient->post(
            $url,
            $data,
            $this->authenticator->getAuthHeaders('POST', $url, $data)
        );

        return new Feature(
            $response['id'],
            $response['plugin_id'],
            $response['title'],
            $response['description'] ?? null,
            $response['is_featured'],
            $response['created'],
            $response['updated'] ?? null
        );
    }

    /**
     * Update an existing feature.
     *
     * @param int   $pluginId  The plugin ID.
     * @param int   $featureId The feature ID.
     * @param array $data      The feature data to update.
     *
     * @return Feature The updated Feature entity.
     * @throws ApiException If the API request fails.
     */
    public function updateFeature(int $pluginId, int $featureId, array $data): Feature
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/features/%d.json',
            $this->scope,
            $this->developerId,
            $pluginId,
            $featureId
        );

        $response = $this->httpClient->put(
            $url,
            $data,
            $this->authenticator->getAuthHeaders('PUT', $url, $data)
        );

        return new Feature(
            $response['id'],
            $response['plugin_id'],
            $response['title'],
            $response['description'] ?? null,
            $response['is_featured'],
            $response['created'],
            $response['updated'] ?? null
        );
    }

    /**
     * Delete a feature.
     *
     * @param int $pluginId  The plugin ID.
     * @param int $featureId The feature ID.
     *
     * @throws ApiException If the API request fails.
     */
    public function deleteFeature(int $pluginId, int $featureId): void
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/features/%d.json',
            $this->scope,
            $this->developerId,
            $pluginId,
            $featureId
        );

        $this->httpClient->delete(
            $url,
            $this->authenticator->getAuthHeaders('DELETE', $url)
        );
    }

    /**
     * Add a feature to a plan.
     *
     * @param int   $pluginId  The plugin ID.
     * @param int   $planId    The plan ID.
     * @param int   $featureId The feature ID.
     * @param array $data      The feature data for the plan.
     *
     * @return array An array containing the plan's feature data.
     * @throws ApiException If the API request fails.
     */
    public function addFeatureToPlan(int $pluginId, int $planId, int $featureId, array $data): array
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/plans/%d/features/%d.json',
            $this->scope,
            $this->developerId,
            $pluginId,
            $planId,
            $featureId
        );

        $response = $this->httpClient->post(
            $url,
            $data,
            $this->authenticator->getAuthHeaders('POST', $url, $data)
        );

        return $response;
    }

    /**
     * Update a plan's feature value.
     *
     * @param int   $pluginId  The plugin ID.
     * @param int   $planId    The plan ID.
     * @param int   $featureId The feature ID.
     * @param array $data      The feature data to update.
     *
     * @return array An array containing the updated plan's feature data.
     * @throws ApiException If the API request fails.
     */
    public function updatePlanFeatureValue(int $pluginId, int $planId, int $featureId, array $data): array
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/plans/%d/features/%d.json',
            $this->scope,
            $this->developerId,
            $pluginId,
            $planId,
            $featureId
        );

        $response = $this->httpClient->put(
            $url,
            $data,
            $this->authenticator->getAuthHeaders('PUT', $url, $data)
        );

        return $response;
    }

    /**
     * Remove a feature from a plan.
     *
     * @param int $pluginId  The plugin ID.
     * @param int $planId    The plan ID.
     * @param int $featureId The feature ID.
     *
     * @throws ApiException If the API request fails.
     */
    public function removeFeatureFromPlan(int $pluginId, int $planId, int $featureId): void
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/plans/%d/features/%d.json',
            $this->scope,
            $this->developerId,
            $pluginId,
            $planId,
            $featureId
        );

        $this->httpClient->delete(
            $url,
            $this->authenticator->getAuthHeaders('DELETE', $url)
        );
    }
}