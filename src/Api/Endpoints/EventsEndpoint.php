<?php

namespace Freemius\SDK\Api\Endpoints;

use Freemius\SDK\Authentication\AuthenticatorInterface;
use Freemius\SDK\Http\HttpClientInterface;
use Freemius\SDK\Entities\Event;
use Freemius\SDK\Exceptions\ApiException;
use Freemius\SDK\Enums\Scope;

/**
 * Endpoint for interacting with Freemius events.
 */
class EventsEndpoint
{
    private HttpClientInterface $httpClient;
    private AuthenticatorInterface $authenticator;
    private Scope $scope;
    private int $scopeId;
    private string $apiVersion;

    /**
     * EventsEndpoint constructor.
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
     * Retrieve a list of events for a plugin.
     *
     * @param int $pluginId The plugin ID.
     * @param array $params Optional query parameters (e.g., 'type', 'install_id', 'user_id', 'license_id', 'fields', 'count').
     *
     * @return Event[] An array of Event entities.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getEvents(int $pluginId, array $params = []): array
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/events.json',
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

        if (!isset($response['events']) || !is_array($response['events'])) {
            throw new ApiException($response, 'Invalid API response: missing events data.');
        }

        $events = [];
        foreach ($response['events'] as $eventData) {
            $events[] = new Event(
                $eventData['id'],
                $eventData['date'],
                $eventData['type'],
                $eventData['install_id'] ?? null,
                $eventData['user_id'] ?? null,
                $eventData['license_id'] ?? null,
                $eventData['data'] ?? null
            );
        }

        return $events;
    }

    /**
     * Retrieve a specific event.
     *
     * @param int $pluginId The plugin ID.
     * @param int $eventId The event ID.
     * @param array $params Optional query parameters (e.g., 'fields').
     *
     * @return Event The Event entity.
     * @throws ApiException If the API request fails or the scope is invalid.
     */
    public function getEvent(int $pluginId, int $eventId, array $params = []): Event
    {
        $this->validateScope([Scope::DEVELOPER, Scope::PLUGIN]);

        $url = sprintf(
            '/%s/%s/%d/plugins/%d/events/%d.json',
            $this->apiVersion,
            $this->scope->value,
            $this->scopeId,
            $pluginId,
            $eventId
        );

        $response = $this->httpClient->get(
            $url,
            $params,
            $this->authenticator->getAuthHeaders('GET', $url)
        );

        return new Event(
            $response['id'],
            $response['date'],
            $response['type'],
            $response['install_id'] ?? null,
            $response['user_id'] ?? null,
            $response['license_id'] ?? null,
            $response['data'] ?? null
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