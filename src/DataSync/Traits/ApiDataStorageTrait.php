<?php

namespace ApApi\DataSync\Traits;

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

/**
 * Trait for sharing API data storage
 *
 * Provides common constants and methods for accessing stored API data
 * between different classes that need to work with the same data source.
 *
 * @package ApApi\DataSync\Traits
 */
trait ApiDataStorageTrait
{
    /**
     * Option name for storing synced API data
     */
    private const string OPTION_NAME = 'ap_api_cron_stores__api_data';

    /**
     * Option name for storing transformed API data
     */
    private const string TRANSFORMED_OPTION_NAME = 'ap_api_stores__transformed_data';

    /**
     * Retrieve the stored API data from WordPress options
     *
     * @return array|false The stored data (should be array) or false if not found
     */
    protected function getStoredApiData(): mixed
    {
        return get_option(self::OPTION_NAME, false);
    }

    /**
     * Store API data to WordPress options
     *
     * @param string $data The data to store as JSON string fetched from the API
     * @param bool $autoload Whether to autoload the option (default: false for performance)
     * @return bool True on success, false on failure
     */
    protected function storeApiData(mixed $data, bool $autoload): bool
    {
        return update_option(self::OPTION_NAME, $data, $autoload);
    }

    /**
     * Retrieve the stored transformed API data from WordPress options
     *
     * @return array|false The stored transformed data (should be array) or false if not found
     */
    protected function getTransformedApiData(): mixed
    {
        return get_option(self::TRANSFORMED_OPTION_NAME, false);
    }

    /**
     * Store transformed API data to WordPress options
     *
     * @param array $data The transformed data to store as array
     * @param bool $autoload Whether to autoload the option (default: false for performance)
     * @return bool True on success, false on failure
     */
    protected function storeTransformedApiData(array $data, bool $autoload = false): bool
    {
        return update_option(self::TRANSFORMED_OPTION_NAME, $data, $autoload);
    }
}

