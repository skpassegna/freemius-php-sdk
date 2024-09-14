<?php

namespace Freemius\SDK;

use Exception;
use Freemius\SDK\Exceptions\ApiException;
use Freemius\SDK\Exceptions\InvalidArgumentException;
use Freemius\SDK\Exceptions\OAuthException;

/**
 * Class FreemiusBase
 *
 * Base class for Freemius API interactions.
 *
 * @package Freemius\SDK
 */
abstract class FreemiusBase
{
    const VERSION = '1.0.0'; // Updated version for the refactored SDK
    const FORMAT = 'json';

    /**
     * @var int Element's ID.
     */
    protected int $_id;

    /**
     * @var string Public key.
     */
    protected string $_public;

    /**
     * @var string Element's secret key.
     */
    protected string $_secret;

    /**
     * @var string API scope ('app', 'developer', 'user', 'install', 'plugin', 'store').
     */
    protected string $_scope;

    /**
     * @var bool Whether or not to run API in sandbox mode.
     */
    protected bool $_sandbox;

    /**
     * Initialize the API base class.
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
        $this->_id = $id;
        $this->_public = $public;
        $this->_secret = $secret;
        $this->_scope = $scope;
        $this->_sandbox = $sandbox;
    }

    /**
     * Check if running in sandbox mode.
     *
     * @return bool True if in sandbox mode, false otherwise.
     */
    public function isSandbox(): bool
    {
        return $this->_sandbox;
    }

    /**
     * Canonize API request path.
     *
     * @param string $path API request path.
     *
     * @return string Canonized path.
     * @throws InvalidArgumentException If an invalid scope is provided.
     */
    public function canonizePath(string $path): string
    {
        $path = trim($path, '/');
        $queryPos = strpos($path, '?');
        $query = '';

        if (false !== $queryPos) {
            $query = substr($path, $queryPos);
            $path = substr($path, 0, $queryPos);
        }

        // Trim '.json' suffix.
        $formatLength = strlen('.' . self::FORMAT);
        $start = $formatLength * (-1); // negative
        if (substr(strtolower($path), $start) === ('.' . self::FORMAT)) {
            $path = substr($path, 0, strlen($path) - $formatLength);
        }

        $base = match ($this->_scope) {
            'app'        => '/apps/' . $this->_id,
            'developer' => '/developers/' . $this->_id,
            'store'      => '/stores/' . $this->_id,
            'user'       => '/users/' . $this->_id,
            'plugin'    => '/developers/{developer_id}/plugins/' . $this->_id, // Added developer_id placeholder
            'install'    => '/developers/{developer_id}/plugins/{plugin_id}/installs/' . $this->_id, // Added placeholders
            default     => throw new InvalidArgumentException('Invalid scope: ' . $this->_scope)
        };

        // Removed extra '/v' . self::VERSION
        return $base .
               (!empty($path) ? '/' : '') . $path .
               ((false === strpos($path, '.')) ? '.' . self::FORMAT : '') . $query;
    }

    /**
     * Make an API request.
     *
     * @param string $canonizedPath Canonized API request path.
     * @param string $method        HTTP method (GET, POST, PUT, DELETE).
     * @param array  $params        Request parameters.
     * @param array  $fileParams    File parameters.
     *
     * @return mixed API response.
     * @throws ApiException If an API error occurs.
     */
    abstract public function makeRequest(string $canonizedPath, string $method = 'GET', array $params = [], array $fileParams = []);

    /**
     * Make an API request and handle errors.
     *
     * @param string $path       API request path.
     * @param string $method        HTTP method (GET, POST, PUT, DELETE).
     * @param array  $params        Request parameters.
     * @param array  $fileParams    File parameters.
     *
     * @return mixed Decoded API response.
     * @throws ApiException If an API error occurs.
     */
    protected function _api(string $path, string $method = 'GET', array $params = [], array $fileParams = []): mixed
    {
        $method = strtoupper($method);

        // Removed redundant PUT to POST conversion

        try {
            $result = $this->makeRequest($this->canonizePath($path), $method, $params, $fileParams);
        } catch (OAuthException $e) {
            throw new ApiException($e->getMessage(), $e->getCode(), $e);
        } catch (Exception $e) {
            // Map to a generic API exception
            throw new ApiException($e->getMessage(), $e->getCode(), $e);
        }

        $decoded = json_decode($result);

        // Return the raw result if JSON decoding fails
        return (null === $decoded) ? $result : $decoded;
    }

    /**
     * Test API connectivity.
     *
     * @return bool True if API is reachable, false otherwise.
     */
    public function test(): bool
    {
        $pong = $this->_api('/v' . self::VERSION . '/ping.json');

        return (is_object($pong) && isset($pong->api) && 'pong' === $pong->api);
    }

    /**
     * Find clock diff between current server to API server.
     *
     * @return int Clock diff in seconds.
     * @throws ApiException If an API error occurs.
     */
    public function findClockDiff(): int
    {
        $time = time();
        $pong = $this->_api('/v' . self::VERSION . '/ping.json');

        return ($time - strtotime($pong->timestamp));
    }

    /**
     * Make an API request using the canonized path.
     *
     * @param string $path       API request path.
     * @param string $method        HTTP method (GET, POST, PUT, DELETE).
     * @param array  $params        Request parameters.
     * @param array  $fileParams    File parameters.
     *
     * @return mixed Decoded API response.
     * @throws ApiException If an API error occurs.
     */
    public function api(string $path, string $method = 'GET', array $params = [], array $fileParams = []): mixed
    {
        return $this->_api($this->canonizePath($path), $method, $params, $fileParams);
    }

    /**
     * Base64 encoding that does not need to be urlencode()ed.
     * Exactly the same as base64_encode except it uses
     *   - instead of +
     *   _ instead of /
     *   No padded =
     *
     * @param string $input base64UrlEncoded string
     * @return string
     */
    protected static function base64UrlDecode(string $input): string
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    /**
     * Base64 encoding that does not need to be urlencode()ed.
     * Exactly the same as base64_encode except it uses
     *   - instead of +
     *   _ instead of /
     *
     * @param string $input string
     * @return string base64Url encoded string
     */
    protected static function base64UrlEncode(string $input): string
    {
        $str = strtr(base64_encode($input), '+/', '-_');
        $str = str_replace('=', '', $str);

        return $str;
    }
}