<?php

namespace ApApi\DataSync;

use ApApi\DataSync\Contracts\ClientContract;
use ApApi\DataSync\Traits\ApApiCredentials;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Client
 * Handles API communication with the AP API
 */
class Client implements ClientContract
{
    use ApApiCredentials;

    /**
     * Request timeout in seconds
     */
    private const int REQUEST_CURL_TIMEOUT = 40;

    /**
     * API authentication token
     *
     * @var string
     */
    private string|null $token;

    /**
     * {@inheritDoc}
     */
    public function __construct(?string $token)
    {
        $this->token = $token;
    }

    /**
     * {@inheritDoc}
     */
    public function call(array $body, string $endpoint, HttpMethod $method): array
    {
        $apiBaseUrl = $this->getApApiBaseUrl();

        // Validate API base URL is configured
        if (empty($apiBaseUrl)) {
            throw new ApApiException(
                esc_html__(
                    'AP API base URL is not configured. Please configure it in Settings > AP API Integration.',
                    'ap-api-integration'
                ),
                []
            );
        }

        $headers = [
            'Content-Type' => 'application/json',
        ];

        if ($this->token) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }

        $args = [
            'headers' => $headers,
            'timeout' => self::REQUEST_CURL_TIMEOUT,
        ];

        // Handle body based on method
        if ($method === HttpMethod::POST) {
            $args['body'] = json_encode($body);
        }

        // For GET requests, append body as query parameters to the URL if present
        $url = $apiBaseUrl . $endpoint;
        if ($method === HttpMethod::GET && !empty($body)) {
            $url = add_query_arg($body, $url);
        }

        // Use appropriate WordPress HTTP function based on method
        if ($method === HttpMethod::GET) {
            $response = wp_remote_get($url, $args);
        } else {
            $response = wp_remote_post($url, $args);
        }

        $responseBody = wp_remote_retrieve_body($response);
        $responseCode = wp_remote_retrieve_response_code($response);

        // Get the data from the $response and validate for errors
        if (is_wp_error($response)) {
            throw new ApApiException(
                $response->get_error_message(),
                [
                    'responseCode' => $responseCode,
                    'response_body' => $responseBody,
                ]
            );
        }

        if (! in_array($responseCode, [200, 201, 202, 203, 204])) {
            throw new ApApiException(
                'Request error, code ' . $responseCode,
                [
                    'responseCode' => $responseCode,
                    'response_body' => $responseBody,
                ]
            );
        }

        $responseData = json_decode($responseBody, true);

        if ($responseData['errors'] ?? null) {
            throw new ApApiException(
                $responseData['message'] ?? 'No error message!',
                [
                    'responseCode' => $responseCode,
                    'responseData' => $responseData,
                ]
            );
        }

        return $responseData;
    }
}
