<?php

namespace Freemius\SDK\Authentication;

use Freemius\SDK\Http\HttpClientInterface;
use Freemius\SDK\Enums\Scope;

/**
 * Interface for Freemius API authenticators.
 *
 * This interface defines the methods that a Freemius API authenticator implementation
 * must provide.
 */
interface AuthenticatorInterface
{
    /**
     * Initialize the authenticator with Freemius API credentials.
     *
     * @param Scope $scope The API scope.
     * @param int $scopeId The ID of the entity for the current scope.
     * @param string $publicKey The Freemius public key.
     * @param string $secretKey The Freemius secret key.
     * @param HttpClientInterface $httpClient The HTTP client to use for API requests.
     */
    public function __construct(Scope $scope, int $scopeId, string $publicKey, string $secretKey, HttpClientInterface $httpClient);

    /**
     * Get the authentication headers for an API request.
     *
     * @param string $method The HTTP method of the request (e.g., 'GET', 'POST').
     * @param string $url The URL of the request.
     * @param array|string|null $body The request body (if applicable).
     *
     * @return array An array of authentication headers.
     */
    public function getAuthHeaders(string $method, string $url, array|string|null $body = null): array;

    /**
     * Find clock diff between current server to API server.
     *
     * @return int Clock diff in seconds.
     * @throws \Freemius\SDK\Exceptions\ApiException If the API request fails.
     */
    public function findClockDiff(): int;

    /**
     * Set clock diff for all API calls.
     *
     * @param int $seconds
     */
    public static function setClockDiff(int $seconds): void;

    /**
     * Generate a signed URL for an API request.
     *
     * @param string $url The request URL.
     * @param array $params Optional query parameters.
     *
     * @return string The signed URL.
     */
    public function getSignedUrl(string $url, array $params = []): string;
}