<?php

namespace Freemius\SDK\Http;

/**
 * Interface for HTTP clients.
 *
 * This interface defines the methods that an HTTP client implementation must provide.
 */
interface HttpClientInterface
{
    /**
     * HttpClientInterface constructor.
     *
     * @param string $baseUrl The base URL for API requests.
     */
    public function __construct(string $baseUrl);

    /**
     * Send a GET request.
     *
     * @param string $url     The URL to send the request to.
     * @param array  $params  Optional query parameters.
     * @param array  $headers Optional headers.
     *
     * @return array|string The API response as an associative array or a string.
     * @throws \Freemius\SDK\Exceptions\ApiException If the API request fails.
     */
    public function get(string $url, array $params = [], array $headers = []): array|string;

    /**
     * Send a POST request.
     *
     * @param string $url     The URL to send the request to.
     * @param array  $data    Optional request body data.
     * @param array  $headers Optional headers.
     *
     * @return array|string The API response as an associative array or a string.
     * @throws \Freemius\SDK\Exceptions\ApiException If the API request fails.
     */
    public function post(string $url, array $data = [], array $headers = []): array|string;

    /**
     * Send a PUT request.
     *
     * @param string $url     The URL to send the request to.
     * @param array  $data    Optional request body data.
     * @param array  $headers Optional headers.
     *
     * @return array|string The API response as an associative array or a string.
     * @throws \Freemius\SDK\Exceptions\ApiException If the API request fails.
     */
    public function put(string $url, array $data = [], array $headers = []): array|string;

    /**
     * Send a DELETE request.
     *
     * @param string $url     The URL to send the request to.
     * @param array  $headers Optional headers.
     *
     * @return array|string The API response as an associative array or a string.
     * @throws \Freemius\SDK\Exceptions\ApiException If the API request fails.
     */
    public function delete(string $url, array $headers = []): array|string;
}