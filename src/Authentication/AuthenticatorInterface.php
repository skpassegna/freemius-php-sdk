<?php

namespace Freemius\SDK\Authentication;

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
     * @param string $scope        The API scope (e.g., 'developer', 'app', 'user', 'install').
     * @param int    $developerId  The Freemius developer ID.
     * @param string $publicKey    The Freemius public key.
     * @param string $secretKey    The Freemius secret key.
     */
    public function __construct(string $scope, int $developerId, string $publicKey, string $secretKey);

    /**
     * Get the authentication headers for an API request.
     *
     * @param string $method The HTTP method of the request (e.g., 'GET', 'POST').
     * @param string $url    The URL of the request.
     * @param array  $body   The request body (if applicable).
     *
     * @return array An array of authentication headers.
     */
    public function getAuthHeaders(string $method, string $url, array $body = []): array;
}
