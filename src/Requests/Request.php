<?php

namespace AndrewSvirin\Interview\Requests;

/**
 * Class Request
 * Common request data.
 */
abstract class Request
{

    /**
     * Method.
     * @var string
     */
    private $method;

    /**
     * Body.
     * @var string|null
     */
    private $body;

    public function __construct(string $method, string $body = null)
    {
        $this->method = $method;
        $this->body = $body;
    }

    /**
     * Read data from body.
     * @return string|null
     */
    public function getBody(): ?string
    {
        return $this->body;
    }
}
