<?php

namespace Freemius\SDK\Exceptions;

use Exception;

/**
 * Base exception class for the Freemius SDK.
 *
 * All exceptions thrown by the SDK will extend this class.
 */
class FreemiusException extends Exception
{
    /**
     * FreemiusException constructor.
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