<?php

namespace Freemius\SDK;

use Exception;
use Freemius\SDK\Exceptions\ApiException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Freemius
 *
 * Main class for interacting with the Freemius API.
 *
 * @package Freemius\SDK
 */
class Freemius extends FreemiusBase
{
    // Define API constants
    const API_ADDRESS = 'https://api.freemius.com';
    const SANDBOX_API_ADDRESS = 'https://sandbox-api.freemius.com';

    /**
     * @var Client Guzzle HTTP client.
     */
    private Client $_client;

    /**
     * @var int Clock diff in seconds between current server to API server.
     */
    private static int $_clockDiff = 0;

    /**
     * Initialize the Freemius API class.
     *
     * @param string $scope   API scope ('app', 'developer', 'user', 'install', 'plugin', 'store').
     * @param int    $id      Element's ID.
     * @param string $public  Public key.
     * @param string $secret  Element's secret key.
     * @param bool   $sandbox Whether or not to run API in sandbox mode.
     *
     * @throws InvalidArgumentException If an invalid scope is provided.
     */
    public function __construct(string $scope, int $id, string $public, string $secret, bool $sandbox = false)
    {
        parent::__construct($scope, $id, $public, $secret, $sandbox);

        $this->_client = new Client([
            'base_uri' => $this->getApiBaseUrl(),
            'timeout'  => 60.0,
            'headers'  => [
                'User-Agent' => 'fs-php-' . self::VERSION,
            ],
        ]);
    }

    /**
     * Set the Guzzle HTTP client (for testing purposes).
     *
     * @param Client $client
     */
    public function setClient(Client $client): void
    {
        $this->_client = $client;
    }

    /**
     * Get the base URL for the Freemius API.
     *
     * @return string API base URL.
     */
    private function getApiBaseUrl(): string
    {
        return $this->isSandbox() ? self::SANDBOX_API_ADDRESS : self::API_ADDRESS;
    }

    /**
     * Get the full API URL for a given path.
     *
     * @param string $canonizedPath Canonized API request path.
     *
     * @return string Full API URL.
     */
    public function getUrl(string $canonizedPath = ''): string
    {
        return $this->getApiBaseUrl() . $canonizedPath;
    }

    /**
     * Set clock diff for all API calls.
     *
     * @param int $seconds Clock diff in seconds.
     */
    public static function setClockDiff(int $seconds): void
    {
        self::$_clockDiff = $seconds;
    }

    /**
     * Generate signature for an API request.
     *
     * @param string $resourceUrl    The URL to make the request to.
     * @param string $method         HTTP method (GET, POST, PUT, DELETE).
     * @param string $jsonEncodedParams JSON encoded request parameters.
     * @param string $contentType    Request content type.
     *
     * @return array Authorization parameters.
     */
    private function generateAuthorizationParams(
        string $resourceUrl,
        string $method = 'GET',
        string $jsonEncodedParams = '',
        string $contentType = 'application/json'
    ): array {
        $method = strtoupper($method);

        $eol = "\n";
        $contentMd5 = '';
        $now = (time() - self::$_clockDiff);
        $date = date('r', $now);

        if (in_array($method, ['POST', 'PUT']) && !empty($jsonEncodedParams)) {
            $contentMd5 = md5($jsonEncodedParams);
        }

        $stringToSign = implode($eol, [
            $method,
            $contentMd5,
            $contentType,
            $date,
            $resourceUrl,
        ]);

        // If secret and public keys are identical, it means that
        // the signature uses public key hash encoding.
        $authType = ($this->_secret !== $this->_public) ? 'FS' : 'FSP';

        $auth = [
            'date'          => $date,
            'authorization' => $authType . ' ' . $this->_id . ':' .
                $this->_public . ':' .
                self::base64UrlEncode(hash_hmac(
                    'sha256',
                    $stringToSign,
                    $this->_secret
                )),
        ];

        if (!empty($contentMd5)) {
            $auth['content_md5'] = $contentMd5;
        }

        return $auth;
    }

    /**
     * Get a signed URL for an API request.
     *
     * @param string $path        API request path.
     * @param bool   $isPremium  Whether to retrieve a premium version (for downloads).
     *
     * @return string Signed URL.
     */
    public function getSignedUrl(string $path, bool $isPremium = false): string
    {
        $resource = explode('?', $this->canonizePath($path));
        $resourceUrl = $resource[0];

        $auth = $this->generateAuthorizationParams($resourceUrl);

        return $this->getUrl(
            $resourceUrl . '?' .
                (1 < count($resource) && !empty($resource[1]) ? $resource[1] . '&' : '') .
                http_build_query([
                    'auth_date'    => $auth['date'],
                    'authorization' => $auth['authorization'],
                    'is_premium'   => $isPremium, // Add is_premium parameter
                ])
        );
    }

    /**
     * Make an HTTP request.
     *
     * @param string $canonizedPath Canonized API request path.
     * @param string $method        HTTP method (GET, POST, PUT, DELETE).
     * @param array  $params        Request parameters.
     * @param array  $fileParams    File parameters.
     *
     * @return string API response body.
     * @throws ApiException If an API error occurs.
     */
    public function makeRequest(
        string $canonizedPath,
        string $method = 'GET',
        array $params = [],
        array $fileParams = []
    ): string {
        $method = strtoupper($method);
        $options = [];
        $jsonEncodedParams = json_encode($params);

        // Handle PUT requests as POST with method=PUT
        if ('PUT' === $method) {
            $query = parse_url($canonizedPath, PHP_URL_QUERY);
            $canonizedPath .= (is_string($query) ? '&' : '?') . 'method=PUT';
            $method = 'POST';
        }

        if (in_array($method, ['POST', 'PUT'])) {
            if (!empty($fileParams)) {
                // Handle multipart/form-data requests
                $boundary = '----' . uniqid();
                $multipartItems = [];

                if (!empty($jsonEncodedParams)) {
                    $multipartItems[] = [
                        'name'     => 'data',
                        'contents' => $jsonEncodedParams,
                        'headers'  => ['Content-Type' => 'application/json'],
                    ];
                }

                foreach ($fileParams as $name => $filePath) {
                    $multipartItems[] = [
                        'name'     => $name,
                        'contents' => fopen($filePath, 'r'),
                        'filename' => basename($filePath),
                        'headers'  => ['Content-Type' => $this->getMimeContentType($filePath)],
                    ];
                }

                $options['body'] = new MultipartStream($multipartItems, $boundary);
                $options['headers']['Content-Type'] = "multipart/form-data; boundary={$boundary}";
            } else {
                // Handle JSON requests
                $options['json'] = $params;
            }
        }

        $resource = explode('?', $canonizedPath);
        $this->signRequest(
            $resource[0],
            $method,
            $options,
            !empty($fileParams) ? '' : $jsonEncodedParams // Content-MD5 should be empty for multipart requests
        );

        try {
            $response = $this->_client->request($method, $canonizedPath, $options);
            return $this->handleResponse($response);
        } catch (GuzzleException $e) {
            throw new ApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Sign an API request.
     *
     * @param string $resourceUrl    The URL to make the request to.
     * @param string $method         HTTP method (GET, POST, PUT, DELETE).
     * @param array  $options        Guzzle request options.
     * @param string $jsonEncodedParams JSON encoded request parameters.
     */
    private function signRequest(
        string $resourceUrl,
        string $method,
        array &$options,
        string $jsonEncodedParams = ''
    ): void {
        $auth = $this->generateAuthorizationParams($resourceUrl, $method, $jsonEncodedParams);

        // Use array_merge to avoid duplicate headers
        $options['headers'] = array_merge($options['headers'] ?? [], [
            'Date'          => $auth['date'],
            'Authorization' => $auth['authorization'],
        ]);

        if (!empty($auth['content_md5'])) {
            // Use array_merge to avoid duplicate headers
            $options['headers'] = array_merge($options['headers'] ?? [], [
                'Content-MD5' => $auth['content_md5'],
            ]);
        }
    }

    /**
     * Handle the API response.
     *
     * @param ResponseInterface $response
     *
     * @return string Response body.
     *
     * @throws ApiException If the API returns an error.
     */
    private function handleResponse(ResponseInterface $response): string
    {
        $statusCode = $response->getStatusCode();
        $body = (string)$response->getBody();

        if ($statusCode >= 400) {
            $errorData = json_decode($body, true);
            $errorMessage = $errorData['error']['message'] ?? 'API request failed.';
            $errorCode = $errorData['error']['code'] ?? $statusCode;

            throw new ApiException($errorMessage, $errorCode);
        }

        return $body;
    }

    /**
     * Get the MIME content type for a file.
     *
     * @param string $filename File path.
     *
     * @return string MIME content type.
     * @throws InvalidArgumentException If the file type is unknown.
     */
    protected function getMimeContentType(string $filename): string // Changed to protected
    {
        $mimeTypes = [
            'zip'  => 'application/zip',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'gif'  => 'image/gif',
        ];

        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if (!isset($mimeTypes[$ext])) {
            throw new InvalidArgumentException('Unknown file type: ' . $filename);
        }

        return $mimeTypes[$ext];
    }
}
