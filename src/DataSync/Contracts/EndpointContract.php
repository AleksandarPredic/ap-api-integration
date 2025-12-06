<?php

namespace ApApi\DataSync\Contracts;

use ApApi\DataSync\ApApiException;

interface EndpointContract
{
    /**
     * Constructor
     *
     * @param ClientContract $client Client for making API requests
     */
    public function __construct(ClientContract $client);

    /**
     * @return array
     * @throws ApApiException If the request fails
     */
    public function execute(): array;
}
