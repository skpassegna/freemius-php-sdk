<?php

namespace Freemius\SDK\Entities;

/**
 * Represents a Freemius feature.
 */
class Feature
{
    public int $id;
    public int $pluginId;
    public string $title;
    public ?string $description;
    public bool $isFeatured;
    public string $created;
    public ?string $updated;

    /**
     * Feature constructor.
     *
     * @param int         $id          The feature ID.
     * @param int         $pluginId    The plugin ID.
     * @param string      $title       The feature title.
     * @param string|null $description The feature description (optional).
     * @param bool        $isFeatured  Whether the feature is featured.
     * @param string      $created     The creation timestamp.
     * @param string|null $updated     The last update timestamp (optional).
     */
    public function __construct(
        int $id,
        int $pluginId,
        string $title,
        ?string $description = null,
        bool $isFeatured = false,
        string $created,
        ?string $updated = null
    ) {
        $this->id          = $id;
        $this->pluginId    = $pluginId;
        $this->title       = $title;
        $this->description = $description;
        $this->isFeatured  = $isFeatured;
        $this->created     = $created;
        $this->updated     = $updated;
    }
}