<?php

namespace ApApi\DataSync\AdminPages;

use ApApi\Logger\Logger;
use ApApi\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

/**
 * Debug Page for viewing log files
 *
 * Provides an admin interface to select and view log files created by the Logger class
 * without requiring FTP access. Uses simple PHP form submission instead of AJAX.
 */
class DebugPage
{
    use SingletonTrait;

    /**
     * Logger instance for accessing log directory and file information
     */
    private Logger $logger;

    /**
     * Debug page slug
     */
    private const string PAGE_SLUG = 'ap-api-debug';

    /**
     * Parent page slug
     */
    private const string PARENT_SLUG = SettingsPage::PAGE_SLUG;

    /**
     * Constructor
     */
    private function __construct()
    {
        $this->logger = Logger::getInstance();
    }

    /**
     * Initialize WordPress hooks
     *
     * @return void
     */
    public function initHooks(): void
    {
        add_action('admin_menu', [$this, 'addAdminMenu']);
    }

    /**
     * Add the debug page to admin menu
     *
     * @return void
     */
    public function addAdminMenu(): void
    {
        add_submenu_page(
            self::PARENT_SLUG,
            esc_html__('Debug Logs', 'ap-api-integration'),
            esc_html__('Debug', 'ap-api-integration'),
            'manage_options',
            self::PAGE_SLUG,
            [$this, 'renderDebugPage']
        );
    }

    /**
     * Render the debug page content
     *
     * @return void
     */
    public function renderDebugPage(): void
    {
        // Check user permissions
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'ap-api-integration'));
        }

        // Handle form submission
        $selectedLogFile = '';
        $logContent = '';
        $errorMessage = '';

        if (isset($_POST['ap_debug_nonce']) && wp_verify_nonce($_POST['ap_debug_nonce'], 'ap_debug_action')) {
            if (!empty($_POST['selected_log_file'])) {
                $selectedLogFile = sanitize_text_field($_POST['selected_log_file']);
                $logContent = $this->getLogFileContent($selectedLogFile);

                if ($logContent === false) {
                    $errorMessage = 'Failed to read the selected log file. File may not exist or may not be readable.';
                }
            }
        }

        // Get available log files
        $logFiles = $this->getAvailableLogFiles();

        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('Debug Logs', 'ap-api-integration'); ?></h1>
            <p><?php echo esc_html__('Select a log file to view its contents. Log files are created daily and contain sync operations, errors, and other debug information.', 'ap-api-integration'); ?></p>

            <form method="post" action="">
                <?php wp_nonce_field('ap_debug_action', 'ap_debug_nonce'); ?>

                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="selected_log_file"><?php echo esc_html__('Select Log File', 'ap-api-integration'); ?></label>
                            </th>
                            <td>
                                <?php if (empty($logFiles)): ?>
                                    <p><?php echo esc_html__('No log files found. Log files are created when the plugin performs sync operations.', 'ap-api-integration'); ?></p>
                                <?php else: ?>
                                    <select name="selected_log_file" id="selected_log_file" class="regular-text">
                                        <option value=""><?php echo esc_html__('-- Select a log file --', 'ap-api-integration'); ?></option>
                                        <?php foreach ($logFiles as $logFile): ?>
                                            <option value="<?php echo esc_attr($logFile['filename']); ?>" <?php selected($selectedLogFile, $logFile['filename']); ?>>
                                                <?php echo esc_html($logFile['display_name']); ?> (<?php echo esc_html($logFile['size']); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <p class="description">
                                        <?php echo esc_html__('Files are listed with the most recent first. File size is shown in parentheses.', 'ap-api-integration'); ?>
                                    </p>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <?php if (!empty($logFiles)): ?>
                    <?php submit_button(__('View Log File', 'ap-api-integration'), 'primary', 'submit', false); ?>
                <?php endif; ?>
            </form>

            <?php if (!empty($errorMessage)): ?>
                <div class="notice notice-error">
                    <p><?php echo esc_html($errorMessage); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($logContent)): ?>
                <h2><?php echo esc_html__('Log File Content', 'ap-api-integration'); ?> - <?php echo esc_html($selectedLogFile); ?></h2>
                <div style="background: #f9f9f9; border: 1px solid #ddd; padding: 15px; margin-top: 20px; border-radius: 4px;">
                    <pre style="white-space: pre-wrap; word-wrap: break-word; font-family: 'Courier New', Courier, monospace; font-size: 14px; line-height: 1.4; max-height: 600px; overflow-y: auto; margin: 0; background: #fff; padding: 10px; border: 1px solid #ccc; border-radius: 2px;"><?php echo esc_html($logContent); ?></pre>
                </div>
                <p class="description" style="margin-top: 10px;">
                    <?php echo esc_html__('Log entries are displayed in chronological order. Each entry includes a timestamp and relevant context information.', 'ap-api-integration'); ?>
                </p>
            <?php endif; ?>

            <?php if (empty($logFiles)): ?>
                <div class="notice notice-info">
                    <p>
                        <strong><?php echo esc_html__('No log files available yet.', 'ap-api-integration'); ?></strong><br>
                        <?php echo esc_html__('Log files are created when the plugin performs sync operations. Try running a manual sync from the', 'ap-api-integration'); ?>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=ap-api-actions')); ?>"><?php echo esc_html__('Api Actions page', 'ap-api-integration'); ?></a>
                        <?php echo esc_html__('to generate log entries.', 'ap-api-integration'); ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <style>
            .form-table select {
                min-width: 300px;
            }
            .notice {
                margin: 15px 0;
            }
        </style>
        <?php
    }

    /**
     * Get list of available log files
     *
     * @return array Array of log files with metadata
     */
    private function getAvailableLogFiles(): array
    {
        $logDir = $this->logger->getLogDirectoryPath();
        $logPrefix = $this->logger->getLogFilePrefix();

        if (!is_dir($logDir)) {
            return [];
        }

        // Get all log files matching the pattern
        $pattern = $logDir . $logPrefix . '*.log';
        $files = glob($pattern);

        if (empty($files)) {
            return [];
        }

        $logFiles = [];

        foreach ($files as $filePath) {
            $filename = basename($filePath);

            // Extract date from filename (format: ap-api-sync-logs-DD-MM-YYYY.log)
            $dateMatch = [];
            if (preg_match('/ap-api-sync-logs-(\d{2})-(\d{2})-(\d{4})\.log$/', $filename, $dateMatch)) {
                $day = $dateMatch[1];
                $month = $dateMatch[2];
                $year = $dateMatch[3];
                $displayDate = "$day-$month-$year";
                $sortDate = "$year$month$day"; // For sorting
            } else {
                $displayDate = $filename;
                $sortDate = $filename;
            }

            // Get file size
            $size = filesize($filePath);
            $sizeFormatted = $this->formatFileSize($size);

            $logFiles[] = [
                'filename' => $filename,
                'filepath' => $filePath,
                'display_name' => $displayDate,
                'sort_date' => $sortDate,
                'size' => $sizeFormatted,
                'raw_size' => $size
            ];
        }

        // Sort by date (most recent first)
        usort($logFiles, function($a, $b) {
            return strcmp($b['sort_date'], $a['sort_date']);
        });

        return $logFiles;
    }

    /**
     * Get content of a specific log file
     *
     * @param string $filename Log file name
     * @return string|false File content or false on error
     */
    private function getLogFileContent(string $filename): string|false
    {
        $logDir = $this->logger->getLogDirectoryPath();
        $filePath = $logDir . $filename;

        // Ensure file exists and is readable
        if (!file_exists($filePath) || !is_readable($filePath)) {
            return false;
        }

        // Read file content
        return file_get_contents($filePath);
    }

    /**
     * Format file size in human readable format
     *
     * @param int $size File size in bytes
     * @return string Formatted file size
     */
    private function formatFileSize(int $size): string
    {
        if ($size === 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return round($size, 2) . ' ' . $units[$unitIndex];
    }
}
