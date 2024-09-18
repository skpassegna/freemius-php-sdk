<?php

namespace Freemius\SDK\Api\Endpoints;

use Freemius\SDK\Authentication\AuthenticatorInterface;
use Freemius\SDK\Http\HttpClientInterface;
use Freemius\SDK\Entities\Install;
use Freemius\SDK\Exceptions\ApiException;

/**
 * Endpoint for interacting with Freemius installs (sites).
 */
class InstallsEndpoint
{
    private HttpClientInterface $httpClient;
    private AuthenticatorInterface $authenticator;
    private int $developerId;
    private string $scope;

    /**
     * InstallsEndpoint constructor.
     *
     * @param HttpClientInterface   $httpClient   The HTTP client to use for API requests.
     * @param AuthenticatorInterface $authenticator The authenticator to use for API requests.
     * @param int                    $developerId  The Freemius developer ID.
     * @param string                 $scope        The API scope.
     */
    public function __construct(
        HttpClientInterface $httpClient,
        AuthenticatorInterface $authenticator,
        int $developerId,
        string $scope
    ) {
        $this->httpClient   = $httpClient;
        $this->authenticator = $authenticator;
        $this->developerId  = $developerId;
        $this->scope        = $scope;
    }

    /**
     * Retrieve a list of installs for a plugin.
     *
     * @param int   $pluginId The plugin ID.
     * @param array $params   Optional query parameters (e.g., 'user_id', 'filter', 'search', 'reason_id', 'fields', 'count').
     *
     * @return Install[] An array of Install entities.
     * @throws ApiException If the API request fails.
     */
    public function getInstalls(int $pluginId, array $params = []): array
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/installs.json',
            $this->scope,
            $this->developerId,
            $pluginId
        );

        $response = $this->httpClient->get(
            $url,
            $params,
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
     * @param int   $pluginId  The plugin ID.
     * @param int   $installId The install ID.
     * @param array $params    Optional query parameters (e.g., 'fields').
     *
     * @return Install The Install entity.
     * @throws ApiException If the API request fails.
     */
    public function getInstall(int $pluginId, int $installId, array $params = []): Install
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/installs/%d.json',
            $this->scope,
            $this->developerId,
            $pluginId,
            $installId
        );

        $response = $this->httpClient->get(
            $url,
            $params,
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
     * @param int   $pluginId The plugin ID.
     * @param int   $userId   The user ID.
     * @param array $data     The install data.
     *
     * @return Install The created Install entity.
     * @throws ApiException If the API request fails.
     */
    public function createInstall(int $pluginId, int $userId, array $data): Install
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/users/%d/installs.json',
            $this->scope,
            $this->developerId,
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
     * @param int   $pluginId  The plugin ID.
     * @param int   $installId The install ID.
     * @param array $data      The install data to update.
     *
     * @return Install The updated Install entity.
     * @throws ApiException If the API request fails.
     */
    public function updateInstall(int $pluginId, int $installId, array $data): Install
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/installs/%d.json',
            $this->scope,
            $this->developerId,
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
     * @param int $pluginId  The plugin ID.
     * @param int $installId The install ID.
     *
     * @throws ApiException If the API request fails.
     */
    public function deleteInstall(int $pluginId, int $installId): void
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/installs/%d.json',
            $this->scope,
            $this->developerId,
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
     * @param int   $pluginId  The plugin ID.
     * @param int   $installId The install ID.
     * @param array $params    Optional query parameters (e.g., 'fields').
     *
     * @return array An array containing the install's uninstall details.
     * @throws ApiException If the API request fails.
     */
    public function getUninstallDetails(int $pluginId, int $installId, array $params = []): array
    {
        // This endpoint doesn't include the scope or developer ID in the URL
        $url = sprintf(
            '/v1/plugins/%d/installs/%d/uninstall.json',
            $pluginId,
            $installId
        );

        $response = $this->httpClient->get(
            $url,
            $params,
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        return $response;
    }

    /**
     * Downgrade an install's plan to the plugin's default plan.
     *
     * @param int   $pluginId  The plugin ID.
     * @param int   $installId The install ID.
     * @param array $params    Optional query parameters (e.g., 'fields').
     *
     * @return Install The updated Install entity.
     * @throws ApiException If the API request fails.
     */
    public function downgradePlan(int $pluginId, int $installId, array $params = []): Install
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/installs/%d/downgrade.json',
            $this->scope,
            $this->developerId,
            $pluginId,
            $installId
        );

        $response = $this->httpClient->put(
            $url,
            [],
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
     * @param int    $pluginId  The plugin ID.
     * @param int    $installId The install ID.
     * @param string $version   The current plugin version.
     * @param array  $params    Optional query parameters (e.g., 'fields', 'count').
     *
     * @return array An array containing update information.
     * @throws ApiException If the API request fails.
     */
    public function getUpdates(int $pluginId, int $installId, string $version, array $params = []): array
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/installs/%d/updates.json',
            $this->scope,
            $this->developerId,
            $pluginId,
            $installId
        );

        $params['version'] = $version;

        $response = $this->httpClient->get(
            $url,
            $params,
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        return $response;
    }

    /**
     * Download a specific plugin version for an install.
     *
     * @param int    $pluginId  The plugin ID.
     * @param int    $installId The install ID.
     * @param int    $tagId     The tag/version ID.
     * @param bool   $isPremium Whether to download the premium version.
     *
     * @return string The plugin zip file content.
     * @throws ApiException If the API request fails.
     */
    public function downloadVersion(int $pluginId, int $installId, int $tagId, bool $isPremium = false): string
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/installs/%d/updates/%d.zip',
            $this->scope,
            $this->developerId,
            $pluginId,
            $installId,
            $tagId
        );

        $response = $this->httpClient->get(
            $url,
            ['is_premium' => $isPremium],
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        return $response;
    }

    /**
     * Send a confirmation email for an install's user ownership change.
     *
     * @param int   $pluginId  The plugin ID.
     * @param int   $installId The install ID.
     * @param int   $userId    The user ID.
     * @param array $data      The ownership change data.
     *
     * @throws ApiException If the API request fails.
     */
    public function sendOwnershipChangeConfirmation(int $pluginId, int $installId, int $userId, array $data): void
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/installs/%d/users/%d/ownership-change.json',
            $this->scope,
            $this->developerId,
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
}