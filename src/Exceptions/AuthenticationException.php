<?php

namespace Freemius\SDK\Exceptions;

use Exception;

/**
 * Exception class for authentication failures.
 *
 * This exception is thrown when authentication with the Freemius API fails.
 */
class AuthenticationException extends FreemiusException
{
    /**
     * AuthenticationException constructor.
     *
     * @param string         $message  Exception message.
     * @param int            $code     Exception code.
     * @param Exception|null $previous Previous exception (if any).
     */
    public function __construct(string $message = "", int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}