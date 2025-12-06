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

/**
 * Class Authorize
 * Handles authentication with the AP API
 */
class Authenticate implements EndpointContract
{
    use ApApiCredentials;

    /**
     * API authentication endpoint
     */
    private const string ENDPOINT = 'auth/authenticate';

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
     * Execute the authentication request
     *
     * @return array{token: string, obtained_at: int} Authentication response with token and timestamp
     * @throws ApApiException If authentication fails or credentials are missing
     */
    public function execute(): array
    {
        $username = $this->getApApiUsername();
        $password = $this->getApApiPassword();

        if (empty($username) || empty($password)) {
            throw new ApApiException(
                esc_html__(
                    'AP API credentials are not configured. Please configure them in Settings > AP API Integration.',
                    'ap-api-integration'
                ), []
            );
        }

        $authBody = [
            'username' => $username,
            'password' => $password,
        ];

        try {
            // Make the authentication request to get a token
            $response = $this->client->call($authBody, self::ENDPOINT, HttpMethod::POST);

            if (empty($response['token'] ?? null)) {
                throw new ApApiException(
                    esc_html__(
                        'Invalid response from AP API authentication endpoint. Token not found.',
                        'ap-api-integration'
                    ),
                    [
                        'response' => $response
                    ]
                );
            }

            // Return the token and timestamp
            return [
                'token' => $response['token'],
                'obtained_at' => current_time('timestamp'),
            ];
        } catch (ApApiException $exception) {
            // Re-throw MBApiException as-is to preserve context
            throw $exception;
        } catch (\Exception $exception) {
            // Wrap other exceptions in MBApiException
            throw new ApApiException(
                esc_html__('Authentication with AP API failed.', 'ap-api-integration'),
                [
                    'error' => $exception->getMessage(),
                    'code'  => $exception->getCode(),
                ]
            );
        }
    }
}
