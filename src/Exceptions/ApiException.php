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
     * @var array The API response that caused the exception.
     */
    private array $response;

    /**
     * ApiException constructor.
     *
     * @param array          $response The API response that caused the exception.
     * @param string         $message  Optional exception message.
     * @param int            $code     Optional exception code.
     * @param Exception|null $previous Optional previous exception (if any).
     */
    public function __construct(
        array $response,
        string $message = "",
        int $code = 0,
        ?Exception $previous = null
    ) {
        $this->response = $response;

        parent::__construct($message, $code, $previous);
    }

    /**
     * Get the API response that caused the exception.
     *
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * Get the HTTP status code of the API response.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->response['statusCode'] ?? 0;
    }
}