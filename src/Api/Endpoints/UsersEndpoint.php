<?php

namespace Freemius\SDK\Api\Endpoints;

use Freemius\SDK\Authentication\AuthenticatorInterface;
use Freemius\SDK\Http\HttpClientInterface;
use Freemius\SDK\Entities\User;
use Freemius\SDK\Exceptions\ApiException;
use Freemius\SDK\Enums\Scope;

/**
 * Endpoint for interacting with Freemius users.
 */
class UsersEndpoint
{
    private HttpClientInterface $httpClient;
    private AuthenticatorInterface $authenticator;
    private Scope $scope;
    private int $scopeId;
    private string $apiVersion;

    /**
     * UsersEndpoint constructor.
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
     * Retrieve a list of users for a plugin.
     *
     * @param int $pluginId The plugin ID.
     * @param string|null $fields Comma-separated list of fields to include in the response.
     * @param int|null $count Maximum number of users to retrieve.
     * @param string|null $email Filter users by email address.
     * @param string|null $filter Filter users by status (e.g., 'active', 'pending', 'cancelled', 'expired', 'refunded', 'fraud').
     * @param string|null $search Search users by email, first name, or last name.
     *
     * @return User[] An array of User entities.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getUsers(
        int $pluginId,
        ?string $fields = null,
        ?int $count = null,
        ?string $email = null,
        ?string $filter = null,
        ?string $search = null
    ): array {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $params = [];
        if ($fields !== null) {
            $params['fields'] = $fields;
        }
        if ($count !== null) {
            $params['count'] = $count;
        }
        if ($email !== null) {
            $params['email'] = $email;
        }
        if ($filter !== null) {
            $params['filter'] = $filter;
        }
        if ($search !== null) {
            $params['search'] = $search;
        }

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/users.json',
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

        if (!isset($response['users']) || !is_array($response['users'])) {
            throw new ApiException($response, 'Invalid API response: missing users data.');
        }

        $users = [];
        foreach ($response['users'] as $userData) {
            $users[] = new User(
                $userData['id'],
                $userData['email'],
                $userData['first'],
                $userData['last'],
                $userData['public_key'],
                $userData['secret_key'],
                $userData['is_verified'],
                $userData['picture'] ?? null,
                $userData['created'],
                $userData['updated'] ?? null
            );
        }

        return $users;
    }

    /**
     * Retrieve a specific user.
     *
     * @param int $pluginId The plugin ID.
     * @param int $userId The user ID.
     * @param string|null $fields Comma-separated list of fields to include in the response.
     *
     * @return User The User entity.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getUser(int $pluginId, int $userId, ?string $fields = null): User
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $params = [];
        if ($fields !== null) {
            $params['fields'] = $fields;
        }

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/users/%d.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId,
            $userId
        );
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $response = $this->httpClient->get(
            $url,
            [], // Parameters are now included in the URL
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        return new User(
            $response['id'],
            $response['email'],
            $response['first'],
            $response['last'],
            $response['public_key'],
            $response['secret_key'],
            $response['is_verified'],
            $response['picture'] ?? null,
            $response['created'],
            $response['updated'] ?? null
        );
    }

    /**
     * Create a new user.
     *
     * @param int $pluginId The plugin ID.
     * @param array $data The user data.
     *
     * @return User The created User entity.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function createUser(int $pluginId, array $data): User
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/users.json',
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

        return new User(
            $response['id'],
            $response['email'],
            $response['first'],
            $response['last'],
            $response['public_key'],
            $response['secret_key'],
            $response['is_verified'],
            $response['picture'] ?? null,
            $response['created'],
            $response['updated'] ?? null
        );
    }

    /**
     * Update an existing user.
     *
     * @param int $pluginId The plugin ID.
     * @param int $userId The user ID.
     * @param array $data The user data to update.
     *
     * @return User The updated User entity.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function updateUser(int $pluginId, int $userId, array $data): User
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/users/%d.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId,
            $userId
        );

        $response = $this->httpClient->put(
            $url,
            $data,
            $this->authenticator->getAuthHeaders('PUT', $url, $data)
        );

        return new User(
            $response['id'],
            $response['email'],
            $response['first'],
            $response['last'],
            $response['public_key'],
            $response['secret_key'],
            $response['is_verified'],
            $response['picture'] ?? null,
            $response['created'],
            $response['updated'] ?? null
        );
    }

    /**
     * Download a CSV file of users for a plugin.
     *
     * @param int $pluginId The plugin ID.
     * @param string|null $fields Comma-separated list of fields to include in the CSV file.
     * @param int|null $count Maximum number of users to include in the CSV file.
     * @param string|null $email Filter users by email address.
     * @param string|null $filter Filter users by status (e.g., 'active', 'pending', 'cancelled', 'expired', 'refunded', 'fraud').
     * @param string|null $search Search users by email, first name, or last name.
     *
     * @return string The CSV content.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function downloadUsersCSV(
        int $pluginId,
        ?string $fields = null,
        ?int $count = null,
        ?string $email = null,
        ?string $filter = null,
        ?string $search = null
    ): string {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $params = [];
        if ($fields !== null) {
            $params['fields'] = $fields;
        }
        if ($count !== null) {
            $params['count'] = $count;
        }
        if ($email !== null) {
            $params['email'] = $email;
        }
        if ($filter !== null) {
            $params['filter'] = $filter;
        }
        if ($search !== null) {
            $params['search'] = $search;
        }

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/users.csv',
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