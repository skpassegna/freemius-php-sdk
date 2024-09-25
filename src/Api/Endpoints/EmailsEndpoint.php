<?php

namespace Freemius\SDK\Api\Endpoints;

use Freemius\SDK\Authentication\AuthenticatorInterface;
use Freemius\SDK\Http\HttpClientInterface;
use Freemius\SDK\Exceptions\ApiException;
use Freemius\SDK\Enums\Scope;

/**
 * Endpoint for interacting with Freemius emails.
 */
class EmailsEndpoint
{
    private HttpClientInterface $httpClient;
    private AuthenticatorInterface $authenticator;
    private Scope $scope;
    private int $scopeId;
    private string $apiVersion;

    /**
     * EmailsEndpoint constructor.
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
     * Retrieve a list of email templates for a plugin.
     *
     * @param int $pluginId The plugin ID.
     * @param string|null $trigger Filter email templates by trigger.
     * @param string|null $fields Comma-separated list of fields to include in the response.
     * @param int|null $count Maximum number of email templates to retrieve.
     *
     * @return array An array of email template data.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getEmailTemplates(
        int $pluginId,
        ?string $trigger = null,
        ?string $fields = null,
        ?int $count = null
    ): array {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $params = [];
        if ($trigger !== null) {
            $params['trigger'] = $trigger;
        }
        if ($fields !== null) {
            $params['fields'] = $fields;
        }
        if ($count !== null) {
            $params['count'] = $count;
        }

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/emails.json',
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

        if (!isset($response['emails']) || !is_array($response['emails'])) {
            throw new ApiException($response, 'Invalid API response: missing emails data.');
        }

        return $response['emails'];
    }

    /**
     * Retrieve a specific email template.
     *
     * @param int $pluginId The plugin ID.
     * @param string $trigger The email trigger (e.g., 'after_purchase').
     * @param string|null $fields Comma-separated list of fields to include in the response.
     *
     * @return array An array containing the email template data.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getEmailTemplate(int $pluginId, string $trigger, ?string $fields = null): array
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $params = [];
        if ($fields !== null) {
            $params['fields'] = $fields;
        }

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/emails/%s.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId,
            $trigger
        );
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $response = $this->httpClient->get(
            $url,
            [], // Parameters are now in the URL
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        return $response;
    }

    /**
     * Update an existing email template.
     *
     * @param int $pluginId The plugin ID.
     * @param string $trigger The email trigger (e.g., 'after_purchase').
     * @param array $data The email template data to update.
     *
     * @return array An array containing the updated email template data.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function updateEmailTemplate(int $pluginId, string $trigger, array $data): array
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/emails/%s.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
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
     * @param int $pluginId The plugin ID.
     * @param string $trigger The email trigger (e.g., 'after_purchase').
     * @param string $email The email address to send the test email to.
     *
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function sendTestEmail(int $pluginId, string $trigger, string $email): void
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/emails/%s/test.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId,
            $trigger
        );

        $this->httpClient->post(
            $url,
            ['email' => $email],
            $this->authenticator->getAuthHeaders('POST', $url, ['email' => $email])
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