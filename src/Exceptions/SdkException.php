<?php

namespace Freemius\SDK\Exceptions;

use Exception;
use Throwable;

/**
 * Exception class for SDK-related errors.
 *
 * This exception is thrown when an error occurs within the SDK itself.
 */
class SDKException extends FreemiusException
{
    /**
     * @var array Contextual information related to the exception.
     */
    private array $context;

    /**
     * SDKException constructor.
     *
     * @param string $message The exception message.
     * @param array $context Contextual information related to the exception.
     * @param int $code The exception code.
     * @param Throwable|null $previous The previous exception (if any).
     */
    public function __construct(string $message = '', array $context = [], int $code = 0, ?Throwable $previous = null)
    {
        $this->context = $context;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get contextual information related to the exception.
     *
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }
}