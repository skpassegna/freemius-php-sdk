<?php

namespace Freemius\SDK\Entities;

/**
 * Represents a Freemius feature.
 */
class Feature
{
    public readonly int $id;
    public readonly int $plugin_id;
    public readonly string $title;
    public readonly ?string $description;
    public readonly bool $is_featured;
    public readonly string $created;
    public readonly ?string $updated;

    /**
     * Feature constructor.
     *
     * @param int $id The feature ID.
     * @param int $plugin_id The plugin ID.
     * @param string $title The feature title.
     * @param string|null $description The feature description (optional).
     * @param bool $is_featured Whether the feature is featured.
     * @param string $created The creation timestamp.
     * @param string|null $updated The last update timestamp (optional).
     */
    public function __construct(
        int $id,
        int $plugin_id,
        string $title,
        ?string $description,
        bool $is_featured,
        string $created,
        ?string $updated
    ) {
        $this->id = $id;
        $this->plugin_id = $plugin_id;
        $this->title = $title;
        $this->description = $description;
        $this->is_featured = $is_featured;
        $this->created = $created;
        $this->updated = $updated;
    }
}