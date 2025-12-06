<?php

namespace ApApi\DataSync\Traits;

// Do not allow directly accessing this file.
if ( ! defined('ABSPATH')) {
    exit('Direct script access denied.');
}

/**
 * Trait ApApiCredentials
 * Provides easy access to AP API username and password
 */
trait ApApiCredentials
{
    /**
     * Option name for username
     */
    private const string OPTION_USERNAME = 'ap_api_settings_username';

    /**
     * Option name for password
     */
    private const string OPTION_PASSWORD = 'ap_api_settings_password';

    /**
     * Option name for API base URL
     */
    private const string OPTION_API_BASE_URL = 'ap_api_settings_base_url';

    /**
     * Get AP API username from options table
     *
     * @return string
     */
    protected function getApApiUsername(): string
    {
        return get_option(self::OPTION_USERNAME, '');
    }

    /**
     * Get AP API password from options table
     *
     * @return string
     */
    protected function getApApiPassword(): string
    {
        return get_option(self::OPTION_PASSWORD, '');
    }

    /**
     * Get AP API base URL from options table
     *
     * @return string
     */
    protected function getApApiBaseUrl(): string
    {
        return get_option(self::OPTION_API_BASE_URL, '');
    }
}
