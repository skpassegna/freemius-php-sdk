<?php

namespace Freemius\SDK\Exceptions;

use Exception;

/**
 * Class ApiException
 *
 * Generic exception for API errors.
 *
 * @package Freemius\SDK\Exceptions
 */
class ApiException extends Exception
{
    /**
     * @var array API error response data.
     */
    private array $_result;

    /**
     * ApiException constructor.
     *
     * @param string         $message  Error message.
     * @param int            $code     Error code.
     * @param Exception|null $previous Previous exception.
     * @param array          $result   API error response data.
     */
    public function __construct(string $message = "", int $code = 0, ?Exception $previous = null, array $result = [])
    {
        parent::__construct($message, $code, $previous);
        $this->_result = $result;
    }

    /**
     * Get the API error response data.
     *
     * @return array
     */
    public function getResult(): array
    {
        return $this->_result;
    }
}