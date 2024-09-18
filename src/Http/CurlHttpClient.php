<?php

namespace Freemius\SDK\Http;

use Freemius\SDK\Exceptions\ApiException;

/**
 * HTTP client implementation using cURL.
 */
class CurlHttpClient implements HttpClientInterface
{
    private string $baseUrl;
    private string $scope;

    private const MAX_RETRIES = 3;
    private const RETRY_DELAY = 1; // in seconds

    /**
     * CurlHttpClient constructor.
     *
     * @param string $baseUrl The base URL for API requests.
     * @param string $scope The API scope.
     */
    public function __construct(string $baseUrl, string $scope)
    {
        $this->baseUrl = $baseUrl;
        $this->scope   = $scope;
    }

    /**
     * @inheritDoc
     */
    public function get(string $url, array $params = [], array $headers = []): array|string
    {
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        return $this->makeRequest($url, 'GET', [], $headers);
    }

    /**
     * @inheritDoc
     */
    public function post(string $url, array $data = [], array $headers = []): array|string
    {
        return $this->makeRequest($url, 'POST', $data, $headers);
    }

    /**
     * @inheritDoc
     */
    public function put(string $url, array $data = [], array $headers = []): array|string
    {
        return $this->makeRequest($url, 'PUT', $data, $headers);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $url, array $headers = []): array|string
    {
        return $this->makeRequest($url, 'DELETE', [], $headers);
    }

    /**
     * Make an HTTP request using cURL.
     *
     * @param string $url     The URL to send the request to.
     * @param string $method  The HTTP method (GET, POST, PUT, DELETE).
     * @param array  $data    Optional request body data.
     * @param array  $headers Optional headers.
     *
     * @return array|string The API response as an associative array or a string for non-JSON responses.
     * @throws ApiException If the API request fails.
     */
    private function makeRequest(string $url, string $method, array $data = [], array $headers = []): array|string
    {
        $retries = 0;

        while ($retries < self::MAX_RETRIES) {
            $ch = curl_init();

            // Prepend the base URL and scope to the endpoint path
            curl_setopt($ch, CURLOPT_URL, $this->baseUrl . '/v1/' . $this->scope . $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

            if (!empty($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            }

            if (!empty($headers)) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }

            $response = curl_exec($ch);

            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if (curl_errno($ch)) {
                throw new ApiException(
                    [],
                    curl_error($ch),
                    curl_errno($ch)
                );
            }

            curl_close($ch);

            // Check for rate limiting errors (assuming HTTP status code 429)
            if ($statusCode === 429) {
                $retries++;
                sleep(self::RETRY_DELAY);
                continue;
            }

            // Check if the response is JSON
            $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            if (strpos($contentType, 'application/json') !== false) {
                $decodedResponse = json_decode($response, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new ApiException(
                        [],
                        'Invalid JSON response from API: ' . json_last_error_msg(),
                        json_last_error()
                    );
                }

                $decodedResponse['statusCode'] = $statusCode;

                return $decodedResponse;
            } else {
                // Return raw response content for non-JSON responses
                return $response;
            }
        }

        // If we reach here, all retries have failed
        throw new ApiException([], 'API request failed after multiple retries.');
    }
}