<?php

namespace Freemius\SDK\Authentication;

/**
 * Interface for API authenticators.
 *
 * This interface defines the method that an API authenticator implementation must provide.
 */
interface AuthenticatorInterface
{
    /**
     * Get the authentication headers for an API request.
     *
     * @return array An array of authentication headers.
     */
    public function getAuthHeaders(): array;
}