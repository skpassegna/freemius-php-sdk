<?php

namespace Freemius\SDK\Authentication;

use Freemius\SDK\Http\HttpClientInterface;

/**
 * Signature-based authenticator for the Freemius API.
 *
 * This class implements the signature-based authentication method for the Freemius API.
 */
class SignatureAuthenticator implements AuthenticatorInterface
{
    private const API_VERSION = '1';
    private const DATE_FORMAT = 'r';

    private string $scope;
    private int $developerId;
    private string $publicKey;
    private string $secretKey;
    private HttpClientInterface $httpClient;

    /**
     * @var int Clock diff in seconds between current server to API server.
     */
    private static int $_clock_diff = 0;

    /**
     * @inheritDoc
     */
    public function __construct(string $scope, int $developerId, string $publicKey, string $secretKey, HttpClientInterface $httpClient)
    {
        $this->scope        = $scope;
        $this->developerId  = $developerId;
        $this->publicKey    = $publicKey;
        $this->secretKey    = $secretKey;
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
        $pong = $this->httpClient->get('/v' . self::API_VERSION . '/ping.json');

        return ($time - strtotime($pong['timestamp']));
    }

    /**
     * @inheritDoc
     */
    public function getAuthHeaders(string $method, string $url, array|string|null $body = null): array
    {
        $date = date(self::DATE_FORMAT, time() - self::$_clock_diff);
        $canonicalizedResource = $this->canonicalizeResource($url);
        $contentType = 'application/json';
        $stringToSign = $this->buildStringToSign($method, $canonicalizedResource, $date, $body ?? [], $contentType);
        $signature = $this->calculateSignature($stringToSign);

        // If secret and public keys are identical, it means that
        // the signature uses public key hash encoding.
        $authType = ($this->secretKey !== $this->publicKey) ? 'FS' : 'FSP';

        return [
            'Date'          => $date,
            'Authorization' => sprintf(
                '%s %d:%s:%s',
                $authType,
                $this->developerId,
                $this->publicKey,
                $signature
            ),
        ];
    }

    /**
     * Canonicalize the request resource.
     *
     * @param string $url The request URL.
     *
     * @return string The canonicalized resource.
     */
    private function canonicalizeResource(string $url): string
    {
        $parsedUrl = parse_url($url);

        return $parsedUrl['path'] ?? '';
    }

    /**
     * Build the string to sign for the request.
     *
     * @param string $method The HTTP method.
     * @param string $canonicalizedResource The canonicalized resource.
     * @param string $date The request date.
     * @param array|string $body The request body.
     * @param string $contentType The Content-Type header.
     *
     * @return string The string to sign.
     */
    private function buildStringToSign(
        string $method,
        string $canonicalizedResource,
        string $date,
        array|string $body,
        string $contentType
    ): string {
        // Only calculate content MD5 hash for requests with a body
        $contentMd5 = in_array($method, ['POST', 'PUT']) && ! empty($body)
            ? md5(is_array($body) ? json_encode($body) : $body)
            : '';

        return implode("\n", [
            strtoupper($method),
            $contentMd5,
            $contentType,
            $date,
            '/v' . self::API_VERSION . $canonicalizedResource,
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
}