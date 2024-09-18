<?php

namespace Freemius\SDK\Api\Endpoints;

use Freemius\SDK\Authentication\AuthenticatorInterface;
use Freemius\SDK\Http\HttpClientInterface;
use Freemius\SDK\Entities\User;
use Freemius\SDK\Exceptions\ApiException;

/**
 * Endpoint for interacting with Freemius users.
 */
class UsersEndpoint
{
    private HttpClientInterface $httpClient;
    private AuthenticatorInterface $authenticator;
    private int $developerId;
    private string $scope;

    /**
     * UsersEndpoint constructor.
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
     * Retrieve a list of users for a plugin.
     *
     * @param int $pluginId The plugin ID.
     * @param array $params Optional query parameters (e.g., 'fields', 'count', 'email', 'filter', 'search').
     *
     * @return User[] An array of User entities.
     * @throws ApiException If the API request fails.
     */
    public function getUsers(int $pluginId, array $params = []): array
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/users.json',
            $this->scope,
            $this->developerId,
            $pluginId
        );

        $response = $this->httpClient->get(
            $url,
            $params,
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
     * @param array $params Optional query parameters (e.g., 'fields').
     *
     * @return User The User entity.
     * @throws ApiException If the API request fails.
     */
    public function getUser(int $pluginId, int $userId, array $params = []): User
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/users/%d.json',
            $this->scope,
            $this->developerId,
            $pluginId,
            $userId
        );

        $response = $this->httpClient->get(
            $url,
            $params,
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
     * @throws ApiException If the API request fails.
     */
    public function createUser(int $pluginId, array $data): User
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/users.json',
            $this->scope,
            $this->developerId,
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
     * @throws ApiException If the API request fails.
     */
    public function updateUser(int $pluginId, int $userId, array $data): User
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/users/%d.json',
            $this->scope,
            $this->developerId,
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
     * @param array $params Optional query parameters (e.g., 'fields', 'count', 'email', 'filter', 'search').
     *
     * @return string The CSV content.
     * @throws ApiException If the API request fails.
     */
    public function downloadUsersCSV(int $pluginId, array $params = []): string
    {
        $url = sprintf(
            '/v1/%s/%d/plugins/%d/users.csv',
            $this->scope,
            $this->developerId,
            $pluginId
        );

        $response = $this->httpClient->get(
            $url,
            $params,
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        return $response;
    }
}