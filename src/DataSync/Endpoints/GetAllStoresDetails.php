<?php

namespace ApApi\DataSync\Endpoints;

use ApApi\DataSync\Contracts\ClientContract;
use ApApi\DataSync\Contracts\EndpointContract;
use ApApi\DataSync\HttpMethod;
use ApApi\DataSync\ApApiException;
use ApApi\DataSync\Traits\ApApiCredentials;

if (!defined('ABSPATH')) {
    exit;
}

class GetAllStoresDetails implements EndpointContract
{

    use ApApiCredentials;

    /**
     * API endpoint for retrieving all stores details
     */
    private const ENDPOINT = 'store/GetAllStoresDetails';

    /**
     * Client for making API requests
     *
     * @var ClientContract
     */
    private ClientContract $client;

    /**
     * {@inheritDoc}
     */
    public function __construct(ClientContract $client)
    {
        $this->client = $client;
    }

    /**
     * Execute the request to get all stores details
     *
     * @return array Stores details response
     * @throws ApApiException If the request fails
     */
    public function execute(): array
    {
        try {
            // Make the request with an empty body since no parameters are needed
            $response = $this->client->call([], self::ENDPOINT, HttpMethod::GET);

            // Return the response data
            return $response;
        } catch (ApApiException $exception) {
            // Re-throw MBApiException as-is to preserve context
            throw $exception;
        } catch (\Exception $exception) {
            // Wrap other exceptions in MBApiException
            throw new ApApiException(
                esc_html__('Failed to retrieve stores details from AP API.', 'ap-api-integration'),
                [
                    'error' => $exception->getMessage(),
                    'code'  => $exception->getCode(),
                ]
            );
        }
    }
}
