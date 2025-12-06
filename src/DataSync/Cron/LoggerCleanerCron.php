<?php

namespace ApApi\DataSync\Cron;

use ApApi\Logger\Logger;
use ApApi\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

/**
 * Class LoggerCleanerCron
 * Manages weekly cron job for cleaning up old log files
 */
class LoggerCleanerCron
{
    use SingletonTrait;

    /**
     * Cron hook name
     */
    private const string CRON_HOOK = 'ap_api_logger_cleanup';

    /**
     * Maximum number of log files to keep
     */
    private const int MAX_LOG_FILES = 100;

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

        // Hook the cleanup method to our custom cron event
        add_action(self::CRON_HOOK, [$this, 'cleanupOldLogs']);
    }

    /**
     * Ensure the weekly cron job is scheduled
     * If not scheduled, schedule it
     *
     * @return void
     */
    public function ensureCronScheduled(): void
    {
        // Check if the cron job is already scheduled
        if (!wp_next_scheduled(self::CRON_HOOK)) {
            // Schedule weekly cron job
            wp_schedule_event(time(), 'weekly', self::CRON_HOOK);
        }
    }

    /**
     * Clean up old log files, keeping only the newest 100
     * This method is executed by the cron job
     *
     * @return void
     */
    public function cleanupOldLogs(): void
    {
        $logDir = $this->logger->getLogDirectoryPath();
        $prefix = $this->logger->getLogFilePrefix();
        $pattern = $logDir . $prefix . '*.log';

        // Get all log files matching the pattern
        $files = glob($pattern);

        // If no files found, log and return early
        if (empty($files)) {
            $this->logger->log("[Log cleanup] No log files found to clean");
            return;
        }

        $fileCount = count($files);

        // If within limit, log and return early
        if ($fileCount <= self::MAX_LOG_FILES) {
            $this->logger->log("[Log cleanup] {$fileCount} log file(s) found, no cleanup needed (limit: " . self::MAX_LOG_FILES . ")");
            return;
        }

        // Create array with file => modification timestamp
        $filesWithTime = [];
        foreach ($files as $file) {
            $filesWithTime[$file] = filemtime($file);
        }

        // Sort by timestamp descending (newest first)
        arsort($filesWithTime);

        // Get sorted filenames
        $sortedFiles = array_keys($filesWithTime);

        // Files to delete (everything after the first 100)
        $filesToDelete = array_slice($sortedFiles, self::MAX_LOG_FILES);

        // Delete old files
        $deletedCount = 0;
        $failedCount = 0;

        foreach ($filesToDelete as $file) {
            if (unlink($file)) {
                $deletedCount++;
            } else {
                $failedCount++;
            }
        }

        // Log the cleanup results
        if ($deletedCount > 0) {
            $this->logger->log("[Log cleanup] Completed: deleted {$deletedCount} old log file(s)");
        }

        if ($failedCount > 0) {
            $this->logger->log("[Log cleanup] Warning: failed to delete {$failedCount} file(s)");
        }
    }
}
