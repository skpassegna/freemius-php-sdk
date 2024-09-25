<?php

namespace Freemius\SDK\Api\Endpoints;

use Freemius\SDK\Authentication\AuthenticatorInterface;
use Freemius\SDK\Http\HttpClientInterface;
use Freemius\SDK\Entities\License;
use Freemius\SDK\Exceptions\ApiException;
use Freemius\SDK\Enums\Scope;

/**
 * Endpoint for interacting with Freemius licenses.
 */
class LicensesEndpoint
{
    private HttpClientInterface $httpClient;
    private AuthenticatorInterface $authenticator;
    private Scope $scope;
    private int $scopeId;
    private string $apiVersion;

    /**
     * LicensesEndpoint constructor.
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
     * Retrieve a list of licenses for a plugin.
     *
     * @param int $pluginId The plugin ID.
     * @param string|null $fields Comma-separated list of fields to include in the response.
     * @param string|null $filter Filter licenses by status (e.g., 'active', 'cancelled', 'expired', 'refunded', 'fraud').
     * @param string|null $search Search licenses by license key.
     * @param int|null $count Maximum number of licenses to retrieve.
     *
     * @return License[] An array of License entities.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getLicenses(
        int $pluginId,
        ?string $fields = null,
        ?string $filter = null,
        ?string $search = null,
        ?int $count = null
    ): array {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $params = [];
        if ($fields !== null) {
            $params['fields'] = $fields;
        }
        if ($filter !== null) {
            $params['filter'] = $filter;
        }
        if ($search !== null) {
            $params['search'] = $search;
        }
        if ($count !== null) {
            $params['count'] = $count;
        }

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/licenses.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId
        );
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $response = $this->httpClient->get(
            $url,
            [], // Parameters are now in the URL
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
     * @param string|null $fields Comma-separated list of fields to include in the response.
     *
     * @return License The License entity.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getLicense(int $pluginId, int $licenseId, ?string $fields = null): License
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $params = [];
        if ($fields !== null) {
            $params['fields'] = $fields;
        }

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/licenses/%d.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId,
            $licenseId
        );
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $response = $this->httpClient->get(
            $url,
            [], // Parameters are now in the URL
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
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function updateLicense(int $pluginId, int $licenseId, array $data): License
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/licenses/%d.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
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
     * @param string|null $fields Comma-separated list of fields to include in the response.
     *
     * @return License The deleted License entity.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function deleteLicense(int $pluginId, int $licenseId, ?string $fields = null): License
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $params = [];
        if ($fields !== null) {
            $params['fields'] = $fields;
        }

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/licenses/%d.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId,
            $licenseId
        );
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

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
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function deactivateLicenseFromAllInstalls(int $pluginId, int $licenseId): License
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/licenses/%d/installs.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
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
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function activateLicenseForInstall(int $pluginId, int $installId, int $licenseId): License
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/installs/%d/licenses/%d.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
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
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function deactivateLicenseFromInstall(int $pluginId, int $installId, int $licenseId): License
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/installs/%d/licenses/%d.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
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