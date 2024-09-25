<?php

namespace Freemius\SDK\Api\Endpoints;

use Freemius\SDK\Authentication\AuthenticatorInterface;
use Freemius\SDK\Http\HttpClientInterface;
use Freemius\SDK\Entities\Install;
use Freemius\SDK\Exceptions\ApiException;
use Freemius\SDK\Enums\Scope;

/**
 * Endpoint for interacting with Freemius installs (sites).
 */
class InstallsEndpoint
{
    private HttpClientInterface $httpClient;
    private AuthenticatorInterface $authenticator;
    private Scope $scope;
    private int $scopeId;
    private string $apiVersion;

    /**
     * InstallsEndpoint constructor.
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
     * Retrieve a list of installs for a plugin.
     *
     * @param int $pluginId The plugin ID.
     * @param int|null $userId Filter installs by user ID. Applicable for 'developer', 'plugin', and 'user' scopes.
     * @param string|null $filter Filter installs by status (e.g., 'active', 'cancelled', 'expired', 'refunded', 'fraud').
     * @param string|null $search Search installs by URL or title.
     * @param int|null $reasonId Filter installs by uninstall reason ID.
     * @param string|null $fields Comma-separated list of fields to include in the response.
     * @param int|null $count Maximum number of installs to retrieve.
     *
     * @return Install[] An array of Install entities.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getInstalls(
        int $pluginId,
        ?int $userId = null,
        ?string $filter = null,
        ?string $search = null,
        ?int $reasonId = null,
        ?string $fields = null,
        ?int $count = null
    ): array {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN, Scope::INSTALL, Scope::USER]);

        $params = [];
        if (in_array($this->scope, [Scope::DEVELOPER, Scope::PLUGIN, Scope::USER]) && $userId !== null) {
            $params['user_id'] = $userId;
        }
        if ($filter !== null) {
            $params['filter'] = $filter;
        }
        if ($search !== null) {
            $params['search'] = $search;
        }
        if ($reasonId !== null) {
            $params['reason_id'] = $reasonId;
        }
        if ($fields !== null) {
            $params['fields'] = $fields;
        }
        if ($count !== null) {
            $params['count'] = $count;
        }

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/installs.json',
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
            [], // Parameters are now included in the URL
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        if (!isset($response['installs']) || !is_array($response['installs'])) {
            throw new ApiException($response, 'Invalid API response: missing installs data.');
        }

        $installs = [];
        foreach ($response['installs'] as $installData) {
            $installs[] = new Install(
                $installData['id'],
                $installData['plugin_id'],
                $installData['user_id'],
                $installData['url'],
                $installData['title'],
                $installData['version'],
                $installData['plan_id'] ?? null,
                $installData['license_id'] ?? null,
                $installData['trial_plan_id'] ?? null,
                $installData['trial_ends'] ?? null,
                $installData['subscription_id'] ?? null,
                $installData['gross'],
                $installData['country_code'],
                $installData['language'] ?? null,
                $installData['platform_version'] ?? null,
                $installData['sdk_version'] ?? null,
                $installData['programming_language_version'] ?? null,
                $installData['is_active'],
                $installData['is_disconnected'] ?? false,
                $installData['is_premium'],
                $installData['is_uninstalled'],
                $installData['is_locked'],
                $installData['source'],
                $installData['upgraded'] ?? null,
                $installData['last_seen_at'] ?? null,
                $installData['last_served_update_version'] ?? null,
                $installData['secret_key'],
                $installData['public_key'],
                $installData['created'],
                $installData['updated'],
                $installData['charset'] ?? null
            );
        }

        return $installs;
    }

    /**
     * Retrieve a specific install.
     *
     * @param int $pluginId The plugin ID.
     * @param int $installId The install ID.
     * @param string|null $fields Comma-separated list of fields to include in the response.
     *
     * @return Install The Install entity.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getInstall(int $pluginId, int $installId, ?string $fields = null): Install
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN, Scope::INSTALL, Scope::USER]);

        $params = [];
        if ($fields !== null) {
            $params['fields'] = $fields;
        }

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/installs/%d.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId,
            $installId
        );
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $response = $this->httpClient->get(
            $url,
            [], // Parameters are now included in the URL
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        return new Install(
            $response['id'],
            $response['plugin_id'],
            $response['user_id'],
            $response['url'],
            $response['title'],
            $response['version'],
            $response['plan_id'] ?? null,
            $response['license_id'] ?? null,
            $response['trial_plan_id'] ?? null,
            $response['trial_ends'] ?? null,
            $response['subscription_id'] ?? null,
            $response['gross'],
            $response['country_code'],
            $response['language'] ?? null,
            $response['platform_version'] ?? null,
            $response['sdk_version'] ?? null,
            $response['programming_language_version'] ?? null,
            $response['is_active'],
            $response['is_disconnected'] ?? false,
            $response['is_premium'],
            $response['is_uninstalled'],
            $response['is_locked'],
            $response['source'],
            $response['upgraded'] ?? null,
            $response['last_seen_at'] ?? null,
            $response['last_served_update_version'] ?? null,
            $response['secret_key'],
            $response['public_key'],
            $response['created'],
            $response['updated'],
            $response['charset'] ?? null
        );
    }

    /**
     * Create a new install.
     *
     * @param int $pluginId The plugin ID.
     * @param int $userId The user ID.
     * @param array $data The install data.
     *
     * @return Install The created Install entity.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function createInstall(int $pluginId, int $userId, array $data): Install
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN, Scope::USER]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/users/%d/installs.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId,
            $userId
        );

        $response = $this->httpClient->post(
            $url,
            $data,
            $this->authenticator->getAuthHeaders('POST', $url, $data)
        );

        return new Install(
            $response['id'],
            $response['plugin_id'],
            $response['user_id'],
            $response['url'],
            $response['title'],
            $response['version'],
            $response['plan_id'] ?? null,
            $response['license_id'] ?? null,
            $response['trial_plan_id'] ?? null,
            $response['trial_ends'] ?? null,
            $response['subscription_id'] ?? null,
            $response['gross'],
            $response['country_code'],
            $response['language'] ?? null,
            $response['platform_version'] ?? null,
            $response['sdk_version'] ?? null,
            $response['programming_language_version'] ?? null,
            $response['is_active'],
            $response['is_disconnected'] ?? false,
            $response['is_premium'],
            $response['is_uninstalled'],
            $response['is_locked'],
            $response['source'],
            $response['upgraded'] ?? null,
            $response['last_seen_at'] ?? null,
            $response['last_served_update_version'] ?? null,
            $response['secret_key'],
            $response['public_key'],
            $response['created'],
            $response['updated'],
            $response['charset'] ?? null
        );
    }

    /**
     * Update an existing install.
     *
     * @param int $pluginId The plugin ID.
     * @param int $installId The install ID.
     * @param array $data The install data to update.
     *
     * @return Install The updated Install entity.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function updateInstall(int $pluginId, int $installId, array $data): Install
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN, Scope::INSTALL]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/installs/%d.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId,
            $installId
        );

        $response = $this->httpClient->put(
            $url,
            $data,
            $this->authenticator->getAuthHeaders('PUT', $url, $data)
        );

        return new Install(
            $response['id'],
            $response['plugin_id'],
            $response['user_id'],
            $response['url'],
            $response['title'],
            $response['version'],
            $response['plan_id'] ?? null,
            $response['license_id'] ?? null,
            $response['trial_plan_id'] ?? null,
            $response['trial_ends'] ?? null,
            $response['subscription_id'] ?? null,
            $response['gross'],
            $response['country_code'],
            $response['language'] ?? null,
            $response['platform_version'] ?? null,
            $response['sdk_version'] ?? null,
            $response['programming_language_version'] ?? null,
            $response['is_active'],
            $response['is_disconnected'] ?? false,
            $response['is_premium'],
            $response['is_uninstalled'],
            $response['is_locked'],
            $response['source'],
            $response['upgraded'] ?? null,
            $response['last_seen_at'] ?? null,
            $response['last_served_update_version'] ?? null,
            $response['secret_key'],
            $response['public_key'],
            $response['created'],
            $response['updated'],
            $response['charset'] ?? null
        );
    }

    /**
     * Delete an install (uninstall).
     *
     * @param int $pluginId The plugin ID.
     * @param int $installId The install ID.
     *
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function deleteInstall(int $pluginId, int $installId): void
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/installs/%d.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId,
            $installId
        );

        $this->httpClient->delete(
            $url,
            $this->authenticator->getAuthHeaders('DELETE', $url)
        );
    }

    /**
     * Retrieve an install's uninstall details.
     *
     * @param int $pluginId The plugin ID.
     * @param int $installId The install ID.
     * @param string|null $fields Comma-separated list of fields to include in the response.
     *
     * @return array An array containing the install's uninstall details.
     * @throws ApiException If the API request fails.
     */
    public function getUninstallDetails(int $pluginId, int $installId, ?string $fields = null): array
    {
        $params = [];
        if ($fields !== null) {
            $params['fields'] = $fields;
        }

        $url = sprintf(
            '/%s/plugins/%d/installs/%d/uninstall.json',
            $this->apiVersion,
            $pluginId,
            $installId
        );
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $response = $this->httpClient->get(
            $url,
            [], // Parameters are now included in the URL
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        return $response;
    }

    /**
     * Downgrade an install's plan to the plugin's default plan.
     *
     * @param int $pluginId The plugin ID.
     * @param int $installId The install ID.
     * @param string|null $fields Comma-separated list of fields to include in the response.
     *
     * @return Install The updated Install entity.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function downgradePlan(int $pluginId, int $installId, ?string $fields = null): Install
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $params = [];
        if ($fields !== null) {
            $params['fields'] = $fields;
        }

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/installs/%d/downgrade.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId,
            $installId
        );
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $response = $this->httpClient->put(
            $url,
            [], // Parameters are now included in the URL
            $this->authenticator->getAuthHeaders('PUT', $url)
        );

        return new Install(
            $response['id'],
            $response['plugin_id'],
            $response['user_id'],
            $response['url'],
            $response['title'],
            $response['version'],
            $response['plan_id'] ?? null,
            $response['license_id'] ?? null,
            $response['trial_plan_id'] ?? null,
            $response['trial_ends'] ?? null,
            $response['subscription_id'] ?? null,
            $response['gross'],
            $response['country_code'],
            $response['language'] ?? null,
            $response['platform_version'] ?? null,
            $response['sdk_version'] ?? null,
            $response['programming_language_version'] ?? null,
            $response['is_active'],
            $response['is_disconnected'] ?? false,
            $response['is_premium'],
            $response['is_uninstalled'],
            $response['is_locked'],
            $response['source'],
            $response['upgraded'] ?? null,
            $response['last_seen_at'] ?? null,
            $response['last_served_update_version'] ?? null,
            $response['secret_key'],
            $response['public_key'],
            $response['created'],
            $response['updated'],
            $response['charset'] ?? null
        );
    }

    /**
     * Retrieve updates for an install.
     *
     * @param int $pluginId The plugin ID.
     * @param int $installId The install ID.
     * @param string $version The current plugin version.
     * @param string|null $fields Comma-separated list of fields to include in the response.
     * @param int|null $count Maximum number of updates to retrieve.
     *
     * @return array An array containing update information.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getUpdates(
        int $pluginId,
        int $installId,
        string $version,
        ?string $fields = null,
        ?int $count = null
    ): array {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN, Scope::INSTALL]);

        $params = [];
        if ($fields !== null) {
            $params['fields'] = $fields;
        }
        if ($count !== null) {
            $params['count'] = $count;
        }
        $params['version'] = $version;

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/installs/%d/updates.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId,
            $installId
        );
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $response = $this->httpClient->get(
            $url,
            [], // Parameters are now included in the URL
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        return $response;
    }

    /**
     * Download a specific plugin version for an install.
     *
     * @param int $pluginId The plugin ID.
     * @param int $installId The install ID.
     * @param int $tagId The tag/version ID.
     * @param bool|null $isPremium Whether to download the premium version.
     *
     * @return string The plugin zip file content.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function downloadVersion(int $pluginId, int $installId, int $tagId, ?bool $isPremium = null): string
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN, Scope::INSTALL]);

        $params = [];
        if ($isPremium !== null) {
            $params['is_premium'] = $isPremium;
        }

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/installs/%d/updates/%d.zip',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId,
            $installId,
            $tagId
        );
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $response = $this->httpClient->get(
            $url,
            [], // Parameters are now included in the URL
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        return $response;
    }

    /**
     * Send a confirmation email for an install's user ownership change.
     *
     * @param int $pluginId The plugin ID.
     * @param int $installId The install ID.
     * @param int $userId The user ID.
     * @param array $data The ownership change data.
     *
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function sendOwnershipChangeConfirmation(int $pluginId, int $installId, int $userId, array $data): void
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/installs/%d/users/%d/ownership-change.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId,
            $installId,
            $userId
        );

        $this->httpClient->put(
            $url,
            $data,
            $this->authenticator->getAuthHeaders('PUT', $url, $data)
        );
    }

    /**
     * Sync an install.
     *
     * @param int $pluginId The plugin ID.
     * @param int $installId The install ID.
     * @param array $data The install data.
     *
     * @return Install The synced Install entity.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function syncInstall(int $pluginId, int $installId, array $data): Install
    {
        $this->validateScope([Scope::INSTALL]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/installs/%d/sync.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId,
            $installId
        );

        $response = $this->httpClient->put(
            $url,
            $data,
            $this->authenticator->getAuthHeaders('PUT', $url, $data)
        );

        return new Install(
            $response['id'],
            $response['plugin_id'],
            $response['user_id'],
            $response['url'],
            $response['title'],
            $response['version'],
            $response['plan_id'] ?? null,
            $response['license_id'] ?? null,
            $response['trial_plan_id'] ?? null,
            $response['trial_ends'] ?? null,
            $response['subscription_id'] ?? null,
            $response['gross'],
            $response['country_code'],
            $response['language'] ?? null,
            $response['platform_version'] ?? null,
            $response['sdk_version'] ?? null,
            $response['programming_language_version'] ?? null,
            $response['is_active'],
            $response['is_disconnected'] ?? false,
            $response['is_premium'],
            $response['is_uninstalled'],
            $response['is_locked'],
            $response['source'],
            $response['upgraded'] ?? null,
            $response['last_seen_at'] ?? null,
            $response['last_served_update_version'] ?? null,
            $response['secret_key'],
            $response['public_key'],
            $response['created'],
            $response['updated'],
            $response['charset'] ?? null
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