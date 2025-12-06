<?php

namespace ApApi\DataSync\Cron;

use ApApi\DataSync\Client;
use ApApi\DataSync\Endpoints\Authenticate;
use ApApi\DataSync\Endpoints\GetAllStoresDetails;
use ApApi\DataSync\ApApiException;
use ApApi\DataSync\Traits\ApiDataStorageTrait;
use ApApi\DataSync\Transformers\ApiStoreTransformer;
use ApApi\Logger\Logger;
use ApApi\Repositories\ApiStoreRepository;
use ApApi\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

/**
 * Class ApiSyncCron
 * Manages daily cron job for syncing stores data from AP API
 */
class ApiSyncCron
{
    use SingletonTrait;
    use ApiDataStorageTrait;

    /**
     * Cron hook name
     */
    private const string CRON_HOOK = 'ap_api_daily_sync';

    /**
     * Logger instance
     *
     * @var Logger
     */
    private Logger $logger;

    /**
     * Private constructor for Singleton pattern
     */
    private function __construct()
    {
        $this->logger = Logger::getInstance();
    }

    /**
     * Initialize all hooks for cron functionality
     *
     * @return void
     */
    public function initHooks(): void
    {
        // Ensure cron is scheduled on every init
        add_action('init', [$this, 'ensureCronScheduled']);

        // Hook the sync method to our custom cron event
        add_action(self::CRON_HOOK, [$this, 'syncStoresData']);
    }

    /**
     * Ensure the daily cron job is scheduled
     * If not scheduled, schedule it
     *
     * @return void
     */
    public function ensureCronScheduled(): void
    {
        // Check if the cron job is already scheduled
        if (!wp_next_scheduled(self::CRON_HOOK)) {
            // Schedule daily cron job using UTC timestamp
            wp_schedule_event(time(), 'daily', self::CRON_HOOK);
        }
    }

    /**
     * Sync stores data from AP API and save to option
     * This method is executed by the cron job
     *
     * @return void
     * @throws ApApiException When API authentication or data fetching fails
     * @throws \Exception When data transformation fails
     * @throws \Throwable When fatal errors occur during processing
     */
    public function syncStoresData(): void
    {
        // First get the raw data from the API
        try {
            // Authenticate and get token
            $token = (new Authenticate(
                new Client(null)
            ))->execute();

            // Fetch all stores details using the token
            $storesDetails = (new GetAllStoresDetails(
                new Client($token['token'])
            ))->execute();

            // Save the raw data to options table (autoload false for performance)
            $this->storeApiData($storesDetails, false);

            $this->logger->log(
                '[FETCH] [SUCCESS] Raw data from API fetched successfully',
            );
        } catch (ApApiException $exception) {
            // Log the error using logger instance
            $this->logger->log(
                '[FETCH] [ERROR] ' . $exception->getMessage(),
                $exception->getData()
            );

            // Rethrow the exception so UI can catch it
            throw $exception;
        }

        // Second, transform and validate the data
        try {
            $this->logger->log(
                '[FETCH] [TRANSFORMATION] data started',
            );

            // Transform the data and save it separately
            $transformer = new ApiStoreTransformer();
            $transformedData = $transformer->transform($storesDetails);

            // Clear repository cache to ensure fresh data on next request
            $repository = new ApiStoreRepository();
            $repository->clearCache();

            // We will create new cache while generating all stores.
            // Run validation if we can create all VOs
            $repository->getAllStores();

            // Save transformed data with branchId as keys using trait method
            $this->storeTransformedApiData($transformedData, false);

            $this->logger->log(
                '[FETCH] [TRANSFORMATION] data validated and transformed successfully',
            );
        } catch (\Exception $exception) {
            $this->logger->log(
                '[FETCH] [TRANSFORMATION] [ERROR] ' . $exception->getMessage(),
                [
                    'exception_type' => get_class($exception),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => $exception->getTraceAsString()
                ]
            );

            // Rethrow the exception so UI can catch it
            throw $exception;
        } catch (\Throwable $throwable) {
            // Catch fatal errors, memory issues, autoloading failures, etc.
            $this->logger->log(
                '[FETCH] [TRANSFORMATION] [FATAL ERROR] ' . $throwable->getMessage(),
                [
                    'throwable_type' => get_class($throwable),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                    'trace' => $throwable->getTraceAsString()
                ]
            );

            // Rethrow the throwable so UI can catch it
            throw $throwable;
        }
    }
}
