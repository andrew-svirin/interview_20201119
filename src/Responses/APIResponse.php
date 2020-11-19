<?php

namespace AndrewSvirin\Interview\Responses;

/**
 * Class Response
 * Common response data.
 */
abstract class APIResponse
{

    /**
     * Content.
     * @var string|null
     */
    private $content;

    public function __construct(string $content = null)
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
