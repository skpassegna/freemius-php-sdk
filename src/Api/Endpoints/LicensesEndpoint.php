<?php

namespace Freemius\SDK\Api\Endpoints;

use Freemius\SDK\Authentication\AuthenticatorInterface;
use Freemius\SDK\Http\HttpClientInterface;
use Freemius\SDK\Entities\License;
use Freemius\SDK\Exceptions\ApiException;

/**
 * Endpoint for interacting with Freemius licenses.
 */
class LicensesEndpoint
{
    private HttpClientInterface $httpClient;
    private AuthenticatorInterface $authenticator;
    private int $developerId;
    private string $scope;

    /**
     * LicensesEndpoint constructor.
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
     * Retrieve a list of licenses for a plugin.
     *
     * @param int $pluginId The plugin ID.
     * @param array $params Optional query parameters (e.g., 'fields', 'filter', 'search', 'count').
     *
     * @return License[] An array of License entities.
     * @throws ApiException If the API request fails.
     */
    public function getLicenses(int $pluginId, array $params = []): array
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/licenses.json',
            $this->scope,
            $this->developerId,
            $pluginId
        );

        $response = $this->httpClient->get(
            $url,
            $params,
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        if (!isset($response['licenses']) || !is_array($response['licenses'])) {
            throw new ApiException($response, 'Invalid API response: missing licenses data.');
        }

        $licenses = [];
        foreach ($response['licenses'] as $licenseData) {
            $licenses[] = new License(
                $licenseData['id'],
                $licenseData['plugin_id'],
                $licenseData['user_id'],
                $licenseData['plan_id'],
                $licenseData['pricing_id'],
                $licenseData['quota'],
                $licenseData['activated'],
                $licenseData['activated_local'] ?? null,
                $licenseData['expiration'] ?? null,
                $licenseData['is_free_localhost'],
                $licenseData['is_block_features'],
                $licenseData['is_cancelled'],
                $licenseData['created'],
                $licenseData['updated']
            );
        }

        return $licenses;
    }

    /**
     * Retrieve a specific license.
     *
     * @param int $pluginId The plugin ID.
     * @param int $licenseId The license ID.
     * @param array $params Optional query parameters (e.g., 'fields').
     *
     * @return License The License entity.
     * @throws ApiException If the API request fails.
     */
    public function getLicense(int $pluginId, int $licenseId, array $params = []): License
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/licenses/%d.json',
            $this->scope,
            $this->developerId,
            $pluginId,
            $licenseId
        );

        $response = $this->httpClient->get(
            $url,
            $params,
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        return new License(
            $response['id'],
            $response['plugin_id'],
            $response['user_id'],
            $response['plan_id'],
            $response['pricing_id'],
            $response['quota'],
            $response['activated'],
            $response['activated_local'] ?? null,
            $response['expiration'] ?? null,
            $response['is_free_localhost'],
            $response['is_block_features'],
            $response['is_cancelled'],
            $response['created'],
            $response['updated']
        );
    }

    /**
     * Update an existing license.
     *
     * @param int $pluginId The plugin ID.
     * @param int $licenseId The license ID.
     * @param array $data The license data to update.
     *
     * @return License The updated License entity.
     * @throws ApiException If the API request fails.
     */
    public function updateLicense(int $pluginId, int $licenseId, array $data): License
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/licenses/%d.json',
            $this->scope,
            $this->developerId,
            $pluginId,
            $licenseId
        );

        $response = $this->httpClient->put(
            $url,
            $data,
            $this->authenticator->getAuthHeaders('PUT', $url, $data)
        );

        return new License(
            $response['id'],
            $response['plugin_id'],
            $response['user_id'],
            $response['plan_id'],
            $response['pricing_id'],
            $response['quota'],
            $response['activated'],
            $response['activated_local'] ?? null,
            $response['expiration'] ?? null,
            $response['is_free_localhost'],
            $response['is_block_features'],
            $response['is_cancelled'],
            $response['created'],
            $response['updated']
        );
    }

    /**
     * Delete (cancel) a license.
     *
     * @param int $pluginId The plugin ID.
     * @param int $licenseId The license ID.
     * @param array $params Optional query parameters (e.g., 'fields').
     *
     * @return License The deleted License entity.
     * @throws ApiException If the API request fails.
     */
    public function deleteLicense(int $pluginId, int $licenseId, array $params = []): License
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/licenses/%d.json',
            $this->scope,
            $this->developerId,
            $pluginId,
            $licenseId
        );

        $response = $this->httpClient->delete(
            $url,
            $this->authenticator->getAuthHeaders('DELETE', $url)
        );

        return new License(
            $response['id'],
            $response['plugin_id'],
            $response['user_id'],
            $response['plan_id'],
            $response['pricing_id'],
            $response['quota'],
            $response['activated'],
            $response['activated_local'] ?? null,
            $response['expiration'] ?? null,
            $response['is_free_localhost'],
            $response['is_block_features'],
            $response['is_cancelled'],
            $response['created'],
            $response['updated']
        );
    }

    /**
     * Deactivate a license from all installs.
     *
     * @param int $pluginId The plugin ID.
     * @param int $licenseId The license ID.
     *
     * @return License The updated License entity.
     * @throws ApiException If the API request fails.
     */
    public function deactivateLicenseFromAllInstalls(int $pluginId, int $licenseId): License
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/licenses/%d/installs.json',
            $this->scope,
            $this->developerId,
            $pluginId,
            $licenseId
        );

        $response = $this->httpClient->delete(
            $url,
            $this->authenticator->getAuthHeaders('DELETE', $url)
        );

        return new License(
            $response['id'],
            $response['plugin_id'],
            $response['user_id'],
            $response['plan_id'],
            $response['pricing_id'],
            $response['quota'],
            $response['activated'],
            $response['activated_local'] ?? null,
            $response['expiration'] ?? null,
            $response['is_free_localhost'],
            $response['is_block_features'],
            $response['is_cancelled'],
            $response['created'],
            $response['updated']
        );
    }

    /**
     * Activate a license for an install.
     *
     * @param int $pluginId The plugin ID.
     * @param int $installId The install ID.
     * @param int $licenseId The license ID.
     *
     * @return License The updated License entity.
     * @throws ApiException If the API request fails.
     */
    public function activateLicenseForInstall(int $pluginId, int $installId, int $licenseId): License
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/installs/%d/licenses/%d.json',
            $this->scope,
            $this->developerId,
            $pluginId,
            $installId,
            $licenseId
        );

        $response = $this->httpClient->put(
            $url,
            [],
            $this->authenticator->getAuthHeaders('PUT', $url)
        );

        return new License(
            $response['id'],
            $response['plugin_id'],
            $response['user_id'],
            $response['plan_id'],
            $response['pricing_id'],
            $response['quota'],
            $response['activated'],
            $response['activated_local'] ?? null,
            $response['expiration'] ?? null,
            $response['is_free_localhost'],
            $response['is_block_features'],
            $response['is_cancelled'],
            $response['created'],
            $response['updated']
        );
    }

    /**
     * Deactivate a license from an install.
     *
     * @param int $pluginId The plugin ID.
     * @param int $installId The install ID.
     * @param int $licenseId The license ID.
     *
     * @return License The updated License entity.
     * @throws ApiException If the API request fails.
     */
    public function deactivateLicenseFromInstall(int $pluginId, int $installId, int $licenseId): License
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/installs/%d/licenses/%d.json',
            $this->scope,
            $this->developerId,
            $pluginId,
            $installId,
            $licenseId
        );

        $response = $this->httpClient->delete(
            $url,
            $this->authenticator->getAuthHeaders('DELETE', $url)
        );

        return new License(
            $response['id'],
            $response['plugin_id'],
            $response['user_id'],
            $response['plan_id'],
            $response['pricing_id'],
            $response['quota'],
            $response['activated'],
            $response['activated_local'] ?? null,
            $response['expiration'] ?? null,
            $response['is_free_localhost'],
            $response['is_block_features'],
            $response['is_cancelled'],
            $response['created'],
            $response['updated']
        );
    }
}