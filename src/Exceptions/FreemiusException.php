<?php

namespace Freemius\SDK\Exceptions;

use Exception;
use Throwable;

/**
 * Base exception class for the Freemius SDK.
 *
 * All exceptions thrown by the SDK will extend this class.
 */
class FreemiusException extends Exception
{
    /**
     * @var Context Contextual information related to the exception.
     */
    protected Context $context;

    /**
     * FreemiusException constructor.
     *
     * @param string $message Exception message.
     * @param int $code Exception code.
     * @param Context|null $context Contextual information related to the exception.
     * @param Throwable|null $previous Previous exception (if any).
     */
    public function __construct(string $message = "", int $code = 0, ?Context $context = null, ?Throwable $previous = null)
    {
        $this->context = $context ?? new Context();
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get contextual information related to the exception.
     *
     * @return Context
     */
    public function getContext(): Context
    {
        return $this->context;
    }

    /**
     * Set contextual information for the exception.
     *
     * @param Context $context
     */
    public function setContext(Context $context): void
    {
        $this->context = $context;
    }
}