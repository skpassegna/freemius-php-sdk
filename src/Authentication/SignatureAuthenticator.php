<?php

namespace Freemius\SDK\Authentication;

use Freemius\SDK\Api\FreemiusApi;
use Freemius\SDK\Http\HttpClientInterface;
use Freemius\SDK\Enums\Scope;

/**
 * Signature-based authenticator for the Freemius API.
 */
class SignatureAuthenticator implements AuthenticatorInterface
{
    private const DATE_FORMAT = 'r';

    private Scope $scope;
    private int $scopeId;
    private string $publicKey;
    private string $secretKey;
    private HttpClientInterface $httpClient;

    /**
     * @var int Clock diff in seconds between current server to API server.
     */
    private static int $_clock_diff = 0;

    /**
     * SignatureAuthenticator constructor.
     *
     * @param Scope $scope The API scope.
     * @param int $scopeId The ID of the entity for the current scope.
     * @param string $publicKey The Freemius public key.
     * @param string $secretKey The Freemius secret key.
     * @param HttpClientInterface $httpClient The HTTP client to use for API requests.
     */
    public function __construct(Scope $scope, int $scopeId, string $publicKey, string $secretKey, HttpClientInterface $httpClient)
    {
        $this->scope = $scope;
        $this->scopeId = $scopeId;
        $this->publicKey = $publicKey;
        $this->secretKey = $secretKey;
        $this->httpClient = $httpClient;
    }

    /**
     * Set clock diff for all API calls.
     *
     * @param int $seconds
     */
    public static function setClockDiff(int $seconds): void
    {
        self::$_clock_diff = $seconds;
    }

    /**
     * Find clock diff between current server to API server.
     *
     * @return int Clock diff in seconds.
     * @throws \Freemius\SDK\Exceptions\ApiException If the API request fails.
     */
    public function findClockDiff(): int
    {
        $time = time();
        $pong = $this->httpClient->get('/' . FreemiusApi::API_VERSION . '/ping.json');
        return ($time - strtotime($pong['timestamp']));
    }

    /**
     * @inheritDoc
     */
    public function getAuthHeaders(string $method, string $url, array|string|null $body = null): array
    {
        $date = date(self::DATE_FORMAT, time() - self::$_clock_diff);
        $contentType = 'application/json';
        $stringToSign = $this->buildStringToSign($method, $url, $date, $body ?? [], $contentType);
        $signature = $this->calculateSignature($stringToSign);

        // If secret and public keys are identical, it means that
        // the signature uses public key hash encoding.
        $authType = ($this->secretKey !== $this->publicKey) ? 'FS' : 'FSP';

        $headers = [
            'Date' => $date,
            'Authorization' => sprintf(
                '%s %d:%s:%s',
                $authType,
                $this->scopeId, // Use scopeId instead of developerId
                $this->publicKey,
                $signature
            ),
        ];

        // Add Content-MD5 header if the request has a body
        if (!empty($body)) {
            $headers['Content-MD5'] = md5(is_array($body) ? json_encode($body) : $body);
        }

        return $headers;
    }

    /**
     * Build the string to sign for the request.
     *
     * @param string $method The HTTP method.
     * @param string $url The request URL.
     * @param string $date The request date.
     * @param array|string $body The request body.
     * @param string $contentType The Content-Type header.
     *
     * @return string The string to sign.
     */
    private function buildStringToSign(string $method, string $url, string $date, array|string $body, string $contentType): string
    {
        // Only calculate content MD5 hash for requests with a body
        $contentMd5 = (!empty($body)) ? md5(is_array($body) ? json_encode($body) : $body) : '';

        return implode("\n", [
            strtoupper($method),
            $contentMd5,
            $contentType,
            $date,
            '/' . FreemiusApi::API_VERSION . $url,
        ]);
    }

    /**
     * Calculate the signature for the request.
     *
     * @param string $stringToSign The string to sign.
     *
     * @return string The base64-encoded signature.
     */
    private function calculateSignature(string $stringToSign): string
    {
        return self::base64UrlEncode(hash_hmac('sha256', $stringToSign, $this->secretKey, true));
    }

    /**
     * Base64 encoding that does not need to be urlencode()ed.
     * Exactly the same as base64_encode except it uses
     *   - instead of +
     *   _ instead of /
     *   No padded =
     *
     * @param string $input string
     * @return string base64Url encoded string
     */
    protected static function base64UrlEncode($input)
    {
        $str = strtr(base64_encode($input), '+/', '-_');
        $str = str_replace('=', '', $str);

        return $str;
    }

    /**
     * Generate a signed URL for an API request.
     *
     * @param string $url The request URL.
     * @param array $params Optional query parameters.
     *
     * @return string The signed URL.
     */
    public function getSignedUrl(string $url, array $params = []): string
    {
        $auth = $this->getAuthHeaders('GET', $url);

        return $this->httpClient->baseUrl .
            '/' . FreemiusApi::API_VERSION .
            $url . '?' .
            http_build_query(array_merge($params, [
                'auth_date' => $auth['Date'],
                'authorization' => $auth['Authorization']
            ]));
    }
}