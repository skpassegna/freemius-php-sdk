<?php

namespace Freemius\SDK\Exceptions;

use Exception;

/**
 * Exception class for API-related errors.
 *
 * This exception is thrown when the Freemius API returns an error response.
 */
class ApiException extends FreemiusException
{
    /**
     * @var string The API error type.
     */
    private string $type;

    /**
     * @var string The API error code.
     */
    private string $code;

    /**
     * @var int The HTTP status code of the API response.
     */
    private int $httpStatusCode;

    /**
     * @var string The timestamp of the API error.
     */
    private string $timestamp;

    /**
     * ApiException constructor.
     *
     * @param string $type The API error type.
     * @param string $message The exception message.
     * @param string $code The API error code.
     * @param int $httpStatusCode The HTTP status code of the API response.
     * @param string $timestamp The timestamp of the API error.
     * @param int $sdkCode The SDK error code.
     * @param Exception|null $previous The previous exception (if any).
     */
    public function __construct(
        string $type,
        string $message,
        string $code,
        int $httpStatusCode,
        string $timestamp,
        int $sdkCode = 0,
        ?Exception $previous = null
    ) {
        $this->type = $type;
        $this->code = $code;
        $this->httpStatusCode = $httpStatusCode;
        $this->timestamp = $timestamp;

        parent::__construct($message, $sdkCode, $previous);
    }

    /**
     * Get the API error type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get the API error code.
     *
     * @return string
     */
    public function getApiCode(): string
    {
        return $this->code;
    }

    /**
     * Get the HTTP status code of the API response.
     *
     * @return int
     */
    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    /**
     * Get the timestamp of the API error.
     *
     * @return string
     */
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }
}