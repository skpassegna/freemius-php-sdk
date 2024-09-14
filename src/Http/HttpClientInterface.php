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
     * Send a GET request.
     *
     * @param string $url     The URL to send the request to.
     * @param array  $params  Optional query parameters.
     * @param array  $headers Optional headers.
     *
     * @return array The API response as an associative array.
     * @throws \Freemius\SDK\Exceptions\ApiException If the API request fails.
     */
    public function get(string $url, array $params = [], array $headers = []): array;

    /**
     * Send a POST request.
     *
     * @param string $url     The URL to send the request to.
     * @param array  $data    Optional request body data.
     * @param array  $headers Optional headers.
     *
     * @return array The API response as an associative array.
     * @throws \Freemius\SDK\Exceptions\ApiException If the API request fails.
     */
    public function post(string $url, array $data = [], array $headers = []): array;

    /**
     * Send a PUT request.
     *
     * @param string $url     The URL to send the request to.
     * @param array  $data    Optional request body data.
     * @param array  $headers Optional headers.
     *
     * @return array The API response as an associative array.
     * @throws \Freemius\SDK\Exceptions\ApiException If the API request fails.
     */
    public function put(string $url, array $data = [], array $headers = []): array;

    /**
     * Send a DELETE request.
     *
     * @param string $url     The URL to send the request to.
     * @param array  $headers Optional headers.
     *
     * @return array The API response as an associative array.
     * @throws \Freemius\SDK\Exceptions\ApiException If the API request fails.
     */
    public function delete(string $url, array $headers = []): array;
}