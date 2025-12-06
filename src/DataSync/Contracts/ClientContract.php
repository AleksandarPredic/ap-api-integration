<?php

namespace ApApi\DataSync\Contracts;

use ApApi\DataSync\HttpMethod;
use ApApi\DataSync\ApApiException;

interface ClientContract
{
    /**
     * @param null|string $token
     */
    public function __construct(string $token);

    /**
     * @param array $body
     * @param string $endpoint
     * @param HttpMethod $method HTTP method (GET or POST)
     *
     * @return array Response Data as Array
     * @throws ApApiException
     */
    public function call(array $body, string $endpoint, HttpMethod $method): array;
}
