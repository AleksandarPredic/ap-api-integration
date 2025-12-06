<?php

namespace ApApi\Logger;

use ApApi\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

/**
 * Class Logger
 * Handles logging to custom log files with daily rotation
 */
class Logger
{
    use SingletonTrait;

    /**
     * Log directory path
     */
    private const string LOG_DIR = 'logs/ap-api-sync/';

    /**
     * Log file prefix
     */
    private const string LOG_FILE_PREFIX = 'ap-api-sync-logs-';

    /**
     * Private constructor for Singleton pattern
     */
    private function __construct()
    {
    }

    /**
     * Log a message to the daily log file
     *
     * @param string $message Message to log
     * @param array $data Additional context data
     * @return void
     */
    public function log(string $message, array $data = []): void
    {
        $logFile = $this->getDailyLogFilePath();

        // Ensure log directory exists
        $this->ensureLogDirectoryExists();

        // Format the log entry
        $logEntry = sprintf(
            '[%s] %s',
            current_time('mysql'),
            $message
        );

        // Add context if provided
        if (!empty($data)) {
            $logEntry .= ' | Data: ' . wp_json_encode($data);
        }

        // Add line break
        $logEntry .= PHP_EOL;

        // Write to log file (3 = append to file)
        error_log($logEntry, 3, $logFile);
    }

    /**
     * Get the path to today's log file
     *
     * @return string Full path to log file
     */
    private function getDailyLogFilePath(): string
    {
        $uploadDir = wp_upload_dir();
        $logFileName = self::LOG_FILE_PREFIX . current_time('d-m-Y') . '.log';

        return $uploadDir['basedir'] . '/' . self::LOG_DIR . $logFileName;
    }

    /**
     * Ensure the log directory exists and is writable
     *
     * @return void
     */
    private function ensureLogDirectoryExists(): void
    {
        $uploadDir = wp_upload_dir();
        $logDirPath = $uploadDir['basedir'] . '/' . self::LOG_DIR;

        if (!file_exists($logDirPath)) {
            wp_mkdir_p($logDirPath);
        }
    }

    /**
     * Get the log directory path
     *
     * @return string Full path to log directory
     */
    public function getLogDirectoryPath(): string
    {
        $uploadDir = wp_upload_dir();
        return $uploadDir['basedir'] . '/' . self::LOG_DIR;
    }

    /**
     * Get the log file prefix
     *
     * @return string Log file prefix
     */
    public function getLogFilePrefix(): string
    {
        return self::LOG_FILE_PREFIX;
    }
}
