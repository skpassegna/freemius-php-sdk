<?php

namespace Freemius\SDK\Exceptions;

/**
 * Class Context
 *
 * Holds contextual information related to an exception.
 *
 * @package Freemius\SDK\Exceptions
 */
class Context
{
    /**
     * @var array An associative array to store arbitrary context data.
     */
    private array $data = [];

    /**
     * Add data to the context.
     *
     * @param string $key The key for the context data.
     * @param mixed $value The value for the context data.
     */
    public function add(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * Get data from the context.
     *
     * @param string $key The key for the context data.
     *
     * @return mixed|null The value for the context data, or null if the key doesn't exist.
     */
    public function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    /**
     * Check if the context has data for a specific key.
     *
     * @param string $key The key to check.
     *
     * @return bool True if the key exists, false otherwise.
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * Get all context data.
     *
     * @return array An associative array of all context data.
     */
    public function all(): array
    {
        return $this->data;
    }
}