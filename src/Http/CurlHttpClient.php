<?php

namespace Freemius\SDK\Http;

use Freemius\SDK\Exceptions\ApiException;

/**
 * HTTP client implementation using cURL.
 */
class CurlHttpClient implements HttpClientInterface
{
    /**
     * @inheritDoc
     */
    public function get(string $url, array $params = [], array $headers = []): array
    {
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        return $this->makeRequest($url, 'GET', [], $headers);
    }

    /**
     * @inheritDoc
     */
    public function post(string $url, array $data = [], array $headers = []): array
    {
        return $this->makeRequest($url, 'POST', $data, $headers);
    }

    /**
     * @inheritDoc
     */
    public function put(string $url, array $data = [], array $headers = []): array
    {
        return $this->makeRequest($url, 'PUT', $data, $headers);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $url, array $headers = []): array
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
     * @return array The API response as an associative array.
     * @throws ApiException If the API request fails.
     */
    private function makeRequest(string $url, string $method, array $data = [], array $headers = []): array
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new ApiException(
                [],
                curl_error($ch),
                curl_errno($ch)
            );
        }

        curl_close($ch);

        $decodedResponse = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ApiException(
                [],
                'Invalid JSON response from API: ' . json_last_error_msg(),
                json_last_error()
            );
        }

        return $decodedResponse;
    }
}