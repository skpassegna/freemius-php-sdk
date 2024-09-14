<?php

namespace Freemius\SDK\Api\Endpoints;

use Freemius\SDK\Api\Authentication\AuthenticatorInterface;
use Freemius\SDK\Api\Http\HttpClientInterface;
use Freemius\SDK\Entities\Event;
use Freemius\SDK\Exceptions\ApiException;

/**
 * Endpoint for interacting with Freemius events.
 */
class EventsEndpoint
{
    private HttpClientInterface $httpClient;
    private AuthenticatorInterface $authenticator;
    private int $developerId;

    /**
     * EventsEndpoint constructor.
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
     * Retrieve a list of events for a plugin.
     *
     * @param int   $pluginId The plugin ID.
     * @param array $params   Optional query parameters (e.g., 'type', 'install_id', 'user_id', 'license_id', 'fields', 'count').
     *
     * @return Event[] An array of Event entities.
     * @throws ApiException If the API request fails.
     */
    public function getEvents(int $pluginId, array $params = []): array
    {
        $url = sprintf(
            '/developers/%d/plugins/%d/events.json',
            $this->developerId,
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
     * @param int   $pluginId The plugin ID.
     * @param int   $eventId  The event ID.
     * @param array $params   Optional query parameters (e.g., 'fields').
     *
     * @return Event The Event entity.
     * @throws ApiException If the API request fails.
     */
    public function getEvent(int $pluginId, int $eventId, array $params = []): Event
    {
        $url = sprintf(
            '/developers/%d/plugins/%d/events/%d.json',
            $this->developerId,
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
}