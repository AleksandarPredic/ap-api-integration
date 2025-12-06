<?php

namespace ApApi\DataSync\AdminPages;

use ApApi\Traits\SingletonTrait;
use ApApi\DataSync\Traits\ApApiCredentials;

// Do not allow directly accessing this file.
if ( ! defined('ABSPATH')) {
    exit('Direct script access denied.');
}

/**
 * Class SettingsPage
 * Handles the AP API Integration settings page with custom form handling
 */
class SettingsPage
{
    use SingletonTrait;
    use ApApiCredentials;

    /**
     * Settings page slug
     */
    public const string PAGE_SLUG = 'ap-api-integration';

    /**
     * Constructor
     */
    private function __construct()
    {
    }

    /**
     * Initialize WordPress hooks
     *
     * @return void
     */
    public function initHooks(): void
    {
        add_action('admin_menu', [$this, 'addSettingsPage']);
        add_action('admin_post_ap_api_save_settings', [$this, 'handleFormSubmission']);
    }

    /**
     * Add settings page as top-level menu
     *
     * @return void
     */
    public function addSettingsPage(): void
    {
        add_menu_page(
            esc_html__('AP API Integration', 'ap-api-integration'), // Page title
            esc_html__('AP API', 'ap-api-integration'), // Menu title (shorter)
            'manage_options', // Capability
            self::PAGE_SLUG, // Menu slug
            [$this, 'renderSettingsPage'], // Callback
            'dashicons-cloud', // Icon
            80 // Position (after Comments)
        );
    }

    /**
     * Handle form submission for API credentials
     *
     * @return void
     */
    public function handleFormSubmission(): void
    {
        // Security checks
        if ( ! current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to access this page.', 'ap-api-integration'));
        }

        // Verify nonce
        if ( ! isset($_POST['ap_api_nonce']) || ! wp_verify_nonce($_POST['ap_api_nonce'], 'ap_api_save_settings')) {
            wp_die(esc_html__('Security check failed. Please try again.', 'ap-api-integration'));
        }

        // Get and sanitize input
        $username   = isset($_POST[self::OPTION_USERNAME]) ? sanitize_text_field($_POST[self::OPTION_USERNAME]) : '';
        // WordPress automatically adds slashes to $_POST data via wp_magic_quotes(). We need to use wp_unslash() to remove these automatic slashes before storing the password.
        $password   = isset($_POST[self::OPTION_PASSWORD]) ? wp_unslash($_POST[self::OPTION_PASSWORD]) : '';
        $apiBaseUrl = isset($_POST[self::OPTION_API_BASE_URL]) ? $_POST[self::OPTION_API_BASE_URL] : '';

        // Use our updateCredentials method which sets autoload = false
        self::updateCredentials($username, $password, $apiBaseUrl);

        // Redirect back with success message
        wp_redirect(
            add_query_arg(
                ['page' => self::PAGE_SLUG, 'settings-updated' => 'true'],
                admin_url('admin.php')
            )
        );
        exit;
    }

    /**
     * Sanitize API base URL and ensure it ends with trailing slash
     *
     * @param string $url
     *
     * @return string
     */
    public function sanitizeApiBaseUrl(string $url): string
    {
        $sanitizedUrl = esc_url_raw($url);

        if (empty($sanitizedUrl)) {
            return '';
        }

        // Ensure URL ends with trailing slash
        return rtrim($sanitizedUrl, '/') . '/';
    }

    /**
     * Render settings page
     *
     * @return void
     */
    public function renderSettingsPage(): void
    {
        if ( ! current_user_can('manage_options')) {
            return;
        }

        ?>
        <div class="wrap">
            <h1><?php
                echo esc_html(get_admin_page_title()); ?></h1>

            <?php
            if (isset($_GET['settings-updated']) && $_GET['settings-updated'] === 'true'): ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php
                        echo esc_html__('Settings saved successfully.', 'ap-api-integration'); ?></p>
                </div>
            <?php
            endif; ?>

            <form method="post" action="<?php
            echo esc_url(admin_url('admin-post.php')); ?>">
                <?php
                wp_nonce_field('ap_api_save_settings', 'ap_api_nonce'); ?>
                <input type="hidden" name="action" value="ap_api_save_settings"/>

                <table class="form-table" role="presentation">
                    <tbody>
                    <tr>
                        <th scope="row">
                            <label for="<?php
                            echo esc_attr(self::OPTION_USERNAME); ?>">
                                <?php
                                echo esc_html__('Username', 'ap-api-integration'); ?>
                            </label>
                        </th>
                        <td>
                            <input type="text"
                                   id="<?php
                                   echo esc_attr(self::OPTION_USERNAME); ?>"
                                   name="<?php
                                   echo esc_attr(self::OPTION_USERNAME); ?>"
                                   value="<?php
                                   echo esc_attr($this->getApApiUsername()); ?>"
                                   class="regular-text"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="<?php
                            echo esc_attr(self::OPTION_PASSWORD); ?>">
                                <?php
                                echo esc_html__('Password', 'ap-api-integration'); ?>
                            </label>
                        </th>
                        <td>
                            <input type="password"
                                   id="<?php
                                   echo esc_attr(self::OPTION_PASSWORD); ?>"
                                   name="<?php
                                   echo esc_attr(self::OPTION_PASSWORD); ?>"
                                   value="<?php
                                   echo esc_attr($this->getApApiPassword()); ?>"
                                   class="regular-text"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="<?php
                            echo esc_attr(self::OPTION_API_BASE_URL); ?>">
                                <?php
                                echo esc_html__('API Base URL', 'ap-api-integration'); ?>
                            </label>
                        </th>
                        <td>
                            <input type="url"
                                   id="<?php
                                   echo esc_attr(self::OPTION_API_BASE_URL); ?>"
                                   name="<?php
                                   echo esc_attr(self::OPTION_API_BASE_URL); ?>"
                                   value="<?php
                                   echo esc_attr($this->getApApiBaseUrl()); ?>"
                                   class="regular-text"
                                   placeholder="https://api.example.com/"/>
                            <p class="description">
                                <?php
                                echo esc_html__(
                                    'The API base URL will automatically have a trailing slash added.',
                                    'ap-api-integration'
                                ); ?>
                            </p>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <?php
                submit_button(esc_html__('Save Settings', 'ap-api-integration')); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Update options with autoload set to false
     *
     * @param string $username
     * @param string $password
     * @param string $apiBaseUrl
     *
     * @return void
     */
    public static function updateCredentials(string $username, string $password, string $apiBaseUrl = ''): void
    {
        update_option(self::OPTION_USERNAME, $username, false);
        update_option(self::OPTION_PASSWORD, $password, false);

        // Use the same sanitization logic as the settings form
        $instance      = self::getInstance();
        $normalizedUrl = $instance->sanitizeApiBaseUrl($apiBaseUrl);
        update_option(self::OPTION_API_BASE_URL, $normalizedUrl, false);
    }
}
