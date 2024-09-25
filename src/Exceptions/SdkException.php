<?php

namespace Freemius\SDK\Exceptions;

use Throwable;

/**
 * Exception class for SDK-related errors.
 *
 * This exception is thrown when an error occurs within the SDK itself.
 */
class SDKException extends FreemiusException
{
    /**
     * SDKException constructor.
     *
     * @param string $message The exception message.
     * @param array $context Additional context data.
     * @param int $code The exception code.
     * @param Throwable|null $previous The previous exception (if any).
     */
    public function __construct(string $message = "", array $context = [], int $code = 0, ?Throwable $previous = null)
    {
        // Initialize the Context object
        $this->context = new Context();

        // Add context data to the exception
        if (!empty($context)) {
            foreach ($context as $key => $value) {
                $this->context->add($key, $value);
            }
        }

        parent::__construct($message, $code, $this->context, $previous);
    }
}