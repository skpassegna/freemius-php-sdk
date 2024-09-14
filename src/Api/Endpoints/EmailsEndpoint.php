<?php

namespace Freemius\SDK\Api\Endpoints;

use Freemius\SDK\Authentication\AuthenticatorInterface;
use Freemius\SDK\Http\HttpClientInterface;
use Freemius\SDK\Exceptions\ApiException;

/**
 * Endpoint for interacting with Freemius emails.
 */
class EmailsEndpoint
{
    private HttpClientInterface $httpClient;
    private AuthenticatorInterface $authenticator;
    private int $developerId;

    /**
     * EmailsEndpoint constructor.
     *
     * @param HttpClientInterface   $httpClient   The HTTP client to use for API requests.
     * @param AuthenticatorInterface $authenticator The authenticator to use for API requests.
     * @param int                    $developerId  The Freemius developer ID.
     */
    public function __construct(
        HttpClientInterface $httpClient,
        AuthenticatorInterface $authenticator,
        int $developerId
    ) {
        $this->httpClient   = $httpClient;
        $this->authenticator = $authenticator;
        $this->developerId  = $developerId;
    }

    /**
     * Retrieve a list of email templates for a plugin.
     *
     * @param int   $pluginId The plugin ID.
     * @param array $params   Optional query parameters (e.g., 'trigger', 'fields', 'count').
     *
     * @return array An array of email template data.
     * @throws ApiException If the API request fails.
     */
    public function getEmailTemplates(int $pluginId, array $params = []): array
    {
        $url = sprintf(
            '/developers/%d/plugins/%d/emails.json',
            $this->developerId,
            $pluginId
        );

        $response = $this->httpClient->get(
            $url,
            $params,
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        if (!isset($response['emails']) || !is_array($response['emails'])) {
            throw new ApiException($response, 'Invalid API response: missing emails data.');
        }

        return $response['emails'];
    }

    /**
     * Retrieve a specific email template.
     *
     * @param int   $pluginId   The plugin ID.
     * @param string $trigger The email trigger (e.g., 'after_purchase').
     * @param array $params     Optional query parameters (e.g., 'fields').
     *
     * @return array An array containing the email template data.
     * @throws ApiException If the API request fails.
     */
    public function getEmailTemplate(int $pluginId, string $trigger, array $params = []): array
    {
        $url = sprintf(
            '/developers/%d/plugins/%d/emails/%s.json',
            $this->developerId,
            $pluginId,
            $trigger
        );

        $response = $this->httpClient->get(
            $url,
            $params,
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        return $response;
    }

    /**
     * Update an existing email template.
     *
     * @param int   $pluginId   The plugin ID.
     * @param string $trigger The email trigger (e.g., 'after_purchase').
     * @param array $data       The email template data to update.
     *
     * @return array An array containing the updated email template data.
     * @throws ApiException If the API request fails.
     */
    public function updateEmailTemplate(int $pluginId, string $trigger, array $data): array
    {
        $url = sprintf(
            '/developers/%d/plugins/%d/emails/%s.json',
            $this->developerId,
            $pluginId,
            $trigger
        );

        $response = $this->httpClient->put(
            $url,
            $data,
            $this->authenticator->getAuthHeaders('PUT', $url, $data)
        );

        return $response;
    }

    /**
     * Send a test email.
     *
     * @param int   $pluginId   The plugin ID.
     * @param string $trigger The email trigger (e.g., 'after_purchase').
     * @param string $email     The email address to send the test email to.
     *
     * @throws ApiException If the API request fails.
     */
    public function sendTestEmail(int $pluginId, string $trigger, string $email): void
    {
        $url = sprintf(
            '/developers/%d/plugins/%d/emails/%s/test.json',
            $this->developerId,
            $pluginId,
            $trigger
        );

        $this->httpClient->post(
            $url,
            ['email' => $email],
            $this->authenticator->getAuthHeaders('POST', $url, ['email' => $email])
        );
    }
}