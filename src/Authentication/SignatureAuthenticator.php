<?php

namespace Freemius\SDK\Authentication;

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

    /**
     * @inheritDoc
     */
    public function __construct(string $scope, int $developerId, string $publicKey, string $secretKey)
    {
        $this->scope        = $scope;
        $this->developerId  = $developerId;
        $this->publicKey    = $publicKey;
        $this->secretKey    = $secretKey;
    }

    /**
     * @inheritDoc
     */
    public function getAuthHeaders(string $method, string $url, array|string $body = []): array
    {
        $date = date(self::DATE_FORMAT);
        $canonicalizedResource = $this->canonicalizeResource($url);
        $stringToSign = $this->buildStringToSign($method, $canonicalizedResource, $date, $body);
        $signature = $this->calculateSignature($stringToSign);

        return [
            'Date'          => $date,
            'Authorization' => sprintf(
                'FS %d:%s:%s',
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
     * @param string       $method              The HTTP method.
     * @param string       $canonicalizedResource The canonicalized resource.
     * @param string       $date                 The request date.
     * @param array|string $body                The request body.
     *
     * @return string The string to sign.
     */
    private function buildStringToSign(string $method, string $canonicalizedResource, string $date, array|string $body = []): string
    {
        // Only calculate content MD5 hash for requests with a body
        $contentMd5 = in_array($method, ['POST', 'PUT']) && !empty($body)
            ? md5(is_array($body) ? json_encode($body) : $body)
            : '';

        return implode("\n", [
            strtoupper($method),
            $contentMd5,
            'application/json',
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
        return base64_encode(hash_hmac('sha256', $stringToSign, $this->secretKey, true));
    }
}