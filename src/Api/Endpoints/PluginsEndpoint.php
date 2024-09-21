<?php

namespace Freemius\SDK\Api\Endpoints;

use Freemius\SDK\Authentication\AuthenticatorInterface;
use Freemius\SDK\Http\HttpClientInterface;
use Freemius\SDK\Entities\Plugin;
use Freemius\SDK\Exceptions\ApiException;
use Freemius\SDK\Enums\Scope;

/**
 * Endpoint for interacting with Freemius plugins.
 */
class PluginsEndpoint
{
    private HttpClientInterface $httpClient;
    private AuthenticatorInterface $authenticator;
    private Scope $scope;
    private int $scopeId;
    private string $apiVersion;

    /**
     * PluginsEndpoint constructor.
     *
     * @param HttpClientInterface $httpClient
     * @param AuthenticatorInterface $authenticator
     * @param int $developerId
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
     * Retrieve a list of plugins for the developer.
     *
     * @param array $params Optional query parameters (e.g., 'all', 'fields', 'count', 'sort').
     *
     * @return Plugin[] An array of Plugin entities.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getPlugins(array $params = []): array
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf('/%s/%s/%d/plugins.json', $this->apiVersion, $this->scope->value, $this->scopeId);

        $response = $this->httpClient->get(
            $url,
            $params,
            $this->authenticator->getAuthHeaders('GET', $url)
        );


        if (!isset($response['plugins']) || !is_array($response['plugins'])) {
            throw new ApiException($response, 'Invalid API response: missing plugins data.');
        }

        $plugins = [];
        foreach ($response['plugins'] as $pluginData) {
            $plugins[] = new Plugin(
                $pluginData['id'],
                $pluginData['title'],
                $pluginData['slug'],
                $pluginData['public_key'],
                $pluginData['secret_key'],
                $pluginData['default_plan_id'] ?? null,
                $pluginData['money_back_period'] ?? null,
                $pluginData['created'],
                $pluginData['updated'] ?? null
            );
        }

        return $plugins;
    }

    /**
     * Retrieve a specific plugin.
     *
     * @param int $pluginId The plugin ID.
     * @param array $params Optional query parameters (e.g., 'fields').
     *
     * @return Plugin The Plugin entity.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getPlugin(int $pluginId, array $params = []): Plugin
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d.json',
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

        return new Plugin(
            $response['id'],
            $response['title'],
            $response['slug'],
            $response['public_key'],
            $response['secret_key'],
            $response['default_plan_id'] ?? null,
            $response['money_back_period'] ?? null,
            $response['created'],
            $response['updated'] ?? null
        );
    }

    /**
     * Create a new plugin.
     *
     * @param array $data The plugin data.
     *
     * @return Plugin The created Plugin entity.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function createPlugin(array $data): Plugin
    {
        $this->validateScope([Scope::DEVELOPER]);

        $url = sprintf('/%s/%s/%d/plugins.json', $this->apiVersion, $this->scope->value, $this->scopeId);

        $response = $this->httpClient->post(
            $url,
            $data,
            $this->authenticator->getAuthHeaders('POST', $url, $data)
        );

        return new Plugin(
            $response['id'],
            $response['title'],
            $response['slug'],
            $response['public_key'],
            $response['secret_key'],
            $response['default_plan_id'] ?? null,
            $response['money_back_period'] ?? null,
            $response['created'],
            $response['updated'] ?? null
        );
    }

    /**
     * Update an existing plugin.
     *
     * @param int $pluginId The plugin ID.
     * @param array $data The plugin data to update.
     *
     * @return Plugin The updated Plugin entity.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function updatePlugin(int $pluginId, array $data): Plugin
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId
        );

        $response = $this->httpClient->put(
            $url,
            $data,
            $this->authenticator->getAuthHeaders('PUT', $url, $data)
        );

        return new Plugin(
            $response['id'],
            $response['title'],
            $response['slug'],
            $response['public_key'],
            $response['secret_key'],
            $response['default_plan_id'] ?? null,
            $response['money_back_period'] ?? null,
            $response['created'],
            $response['updated'] ?? null
        );
    }

    /**
     * Delete a plugin.
     *
     * @param int $pluginId The plugin ID.
     *
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function deletePlugin(int $pluginId): void
    {
        $this->validateScope([Scope::DEVELOPER]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId
        );

        $this->httpClient->delete(
            $url,
            $this->authenticator->getAuthHeaders('DELETE', $url)
        );
    }

    /**
     * Regenerate a plugin's secret key.
     *
     * @param int $pluginId The plugin ID.
     *
     * @return string The new secret key.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function regenerateSecretKey(int $pluginId): string
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/secret_key.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId
        );

        $response = $this->httpClient->put(
            $url,
            [],
            $this->authenticator->getAuthHeaders('PUT', $url)
        );

        if (!isset($response['secret_key'])) {
            throw new ApiException($response, 'Invalid API response: missing secret key.');
        }

        return $response['secret_key'];
    }

    /**
     * Retrieve a plugin's status.
     *
     * @param int $pluginId The plugin ID.
     * @param bool $isUpdate Whether to check for updates.
     *
     * @return array An array containing the plugin's status information.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getPluginStatus(int $pluginId, bool $isUpdate = false): array
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/is_active.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId
        );

        $response = $this->httpClient->get(
            $url,
            ['is_update' => $isUpdate],
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        return $response;
    }

    /**
     * Retrieve a plugin's statistics.
     *
     * @param int $pluginId The plugin ID.
     * @param string|null $start Optional start date (YYYY-mm-DD HH:MM:SS).
     * @param string|null $end Optional end date (YYYY-mm-DD HH:MM:SS).
     *
     * @return array An array containing the plugin's statistics.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getPluginStatistics(int $pluginId, ?string $start = null, ?string $end = null): array
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/stats.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId
        );

        $params = [];
        if ($start) {
            $params['start'] = $start;
        }
        if ($end) {
            $params['end'] = $end;
        }

        $response = $this->httpClient->get(
            $url,
            $params,
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        return $response;
    }

    /**
     * Retrieve a plugin's bottom line performance.
     *
     * @param int $pluginId The plugin ID.
     *
     * @return array An array containing the plugin's performance data.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getPluginPerformance(int $pluginId): array
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/performance.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId
        );

        $response = $this->httpClient->get(
            $url,
            [],
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        return $response;
    }

    /**
     * Retrieve a plugin's revenues.
     *
     * @param int $pluginId The plugin ID.
     * @param string $from The start date (YYYY-MM-DD HH:MM:SS).
     * @param string $to The end date (YYYY-MM-DD HH:MM:SS).
     * @param string $interval The aggregation interval ('day', 'week', 'month').
     *
     * @return array An array containing the plugin's revenues data.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getPluginRevenues(int $pluginId, string $from, string $to, string $interval = 'day'): array
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/revenues.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId
        );

        $response = $this->httpClient->get(
            $url,
            [
                'from' => $from,
                'to' => $to,
                'interval' => $interval,
            ],
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        return $response;
    }

    /**
     * Retrieve a plugin's licenses activity.
     *
     * @param int $pluginId The plugin ID.
     * @param string $from The start date (YYYY-MM-DD HH:MM:SS).
     * @param string $to The end date (YYYY-MM-DD HH:MM:SS).
     * @param string $interval The aggregation interval ('day', 'week', 'month').
     *
     * @return array An array containing the plugin's licenses activity data.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getPluginLicensesActivity(int $pluginId, string $from, string $to, string $interval = 'day'): array
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/licenses.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId
        );

        $response = $this->httpClient->get(
            $url,
            [
                'from' => $from,
                'to' => $to,
                'interval' => $interval,
            ],
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        return $response;
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