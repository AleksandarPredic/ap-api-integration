<?php

namespace ApApi\DataSync\AdminPages;

use JetBrains\PhpStorm\NoReturn;
use ApApi\DataSync\ApApiException;
use ApApi\Repositories\ApiStoreRepository;
use ApApi\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if ( ! defined('ABSPATH')) {
    exit('Direct script access denied.');
}

/**
 * Class ActionsPage
 * Handles the AP API Actions page with cache clearing and manual sync functionality
 */
class ActionsPage
{
    use SingletonTrait;

    /**
     * Actions page slug
     */
    private const string PAGE_SLUG = 'ap-api-actions';

    /**
     * Parent page slug
     */
    private const string PARENT_SLUG = SettingsPage::PAGE_SLUG;

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
        add_action('admin_menu', [$this, 'addActionsPage']);
        add_action('admin_post_ap_api_clear_cache', [$this, 'handleClearCache']);
        add_action('admin_post_ap_api_sync_manually', [$this, 'handleManualSync']);
    }

    /**
     * Add actions page as submenu under AP API menu
     *
     * @return void
     */
    public function addActionsPage(): void
    {
        add_submenu_page(
            self::PARENT_SLUG, // Parent slug
            esc_html__('AP Api Actions', 'ap-api-integration'), // Page title
            esc_html__('Api Actions', 'ap-api-integration'), // Menu title
            'manage_options', // Capability
            self::PAGE_SLUG, // Menu slug
            [$this, 'renderActionsPage'] // Callback
        );
    }

    /**
     * Handle clear cache action
     *
     * @return void
     */
    #[NoReturn] public function handleClearCache(): void
    {
        // Security checks
        if ( ! current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to access this page.', 'ap-api-integration'));
        }

        // Verify nonce
        if ( ! isset($_POST['ap_api_nonce']) || ! wp_verify_nonce($_POST['ap_api_nonce'], 'ap_api_clear_cache')) {
            wp_die(esc_html__('Security check failed. Please try again.', 'ap-api-integration'));
        }

        // Clear cache using repository
        $repository = new ApiStoreRepository();
        $success = $repository->clearCache();

        // Redirect back with appropriate message
        $message = $success ? 'cache-cleared' : 'cache-clear-failed';
        wp_redirect(
            add_query_arg(
                ['page' => self::PAGE_SLUG, 'action-result' => $message],
                admin_url('admin.php')
            )
        );
        exit;
    }

    /**
     * Handle manual sync action
     *
     * @return void
     */
    #[NoReturn] public function handleManualSync(): void
    {
        // Security checks
        if ( ! current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to access this page.', 'ap-api-integration'));
        }

        // Verify nonce
        if ( ! isset($_POST['ap_api_nonce']) || ! wp_verify_nonce($_POST['ap_api_nonce'], 'ap_api_sync_manually')) {
            wp_die(esc_html__('Security check failed. Please try again.', 'ap-api-integration'));
        }

        // Trigger the cron job manually using the hook with exception handling
        try {
            do_action('ap_api_daily_sync');
            $message = 'sync-completed';
        } catch (ApApiException $exception) {
            // API-specific errors (authentication, network, etc.)
            $message = 'sync-failed-api';
        } catch (\Exception $exception) {
            // General exceptions (transformation, validation, etc.)
            $message = 'sync-failed-general';
        } catch (\Throwable $throwable) {
            // Fatal errors, memory issues, etc.
            $message = 'sync-failed-fatal';
        }

        // Redirect back with appropriate message
        wp_redirect(
            add_query_arg(
                ['page' => self::PAGE_SLUG, 'action-result' => $message],
                admin_url('admin.php')
            )
        );
        exit;
    }

    /**
     * Render actions page
     *
     * @return void
     */
    public function renderActionsPage(): void
    {
        if ( ! current_user_can('manage_options')) {
            return;
        }

        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <?php $this->renderNotices(); ?>

            <div class="card">
                <h2 class="title"><?php echo esc_html__('Cache Management', 'ap-api-integration'); ?></h2>
                <p><?php echo esc_html__('Clear the cached store data to force fresh data retrieval on next frontend page view.', 'ap-api-integration'); ?></p>

                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display: inline;">
                    <?php wp_nonce_field('ap_api_clear_cache', 'ap_api_nonce'); ?>
                    <input type="hidden" name="action" value="ap_api_clear_cache"/>
                    <?php submit_button(
                        esc_html__('Clear Cache', 'ap-api-integration'),
                        'secondary',
                        'clear_cache',
                        false,
                        ['onclick' => 'return confirm("' . esc_js(__('Are you sure you want to clear the cache?', 'ap-api-integration')) . '")']
                    ); ?>
                </form>
            </div>

            <div class="card">
                <h2 class="title"><?php echo esc_html__('Data Synchronization', 'ap-api-integration'); ?></h2>
                <p><?php echo esc_html__('Manually trigger data synchronization from the AP API. This will fetch the latest store data immediately.', 'ap-api-integration'); ?></p>

                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display: inline;">
                    <?php wp_nonce_field('ap_api_sync_manually', 'ap_api_nonce'); ?>
                    <input type="hidden" name="action" value="ap_api_sync_manually"/>
                    <?php submit_button(
                        esc_html__('Sync Manually', 'ap-api-integration'),
                        'primary',
                        'sync_manually',
                        false,
                        ['onclick' => 'return confirm("' . esc_js(__('Are you sure you want to run manual sync? This may take a few moments.', 'ap-api-integration')) . '")']
                    ); ?>
                </form>
            </div>

            <div class="card">
                <h3><?php echo esc_html__('Important Notes', 'ap-api-integration'); ?></h3>
                <ul>
                    <li><?php echo esc_html__('Manual sync may take several seconds to a couple of minutes to complete.', 'ap-api-integration'); ?></li>
                    <li><?php echo esc_html__('Clearing cache will force fresh data retrieval on the next frontend page view.', 'ap-api-integration'); ?></li>
                    <li><?php echo esc_html__('Check the logs for detailed sync information and any errors.', 'ap-api-integration'); ?></li>
                </ul>
            </div>
        </div>
        <?php
    }

    /**
     * Render admin notices based on action results
     *
     * @return void
     */
    private function renderNotices(): void
    {
        if (!isset($_GET['action-result'])) {
            return;
        }

        $result = sanitize_text_field($_GET['action-result']);

        switch ($result) {
            case 'cache-cleared':
                ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php echo esc_html__('Cache cleared successfully.', 'ap-api-integration'); ?></p>
                </div>
                <?php
                break;

            case 'cache-clear-failed':
                ?>
                <div class="notice notice-error is-dismissible">
                    <p><?php echo esc_html__('Failed to clear cache. Please try again.', 'ap-api-integration'); ?></p>
                </div>
                <?php
                break;

            case 'sync-completed':
                ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php echo esc_html__('Manual sync completed successfully. Check the logs for details.', 'ap-api-integration'); ?></p>
                </div>
                <?php
                break;

            case 'sync-failed-api':
                ?>
                <div class="notice notice-error is-dismissible">
                    <p><?php echo esc_html__('Manual sync failed due to API error. Please check your API settings and logs for details.', 'ap-api-integration'); ?></p>
                </div>
                <?php
                break;

            case 'sync-failed-general':
                ?>
                <div class="notice notice-error is-dismissible">
                    <p><?php echo esc_html__('Manual sync failed due to data processing error. Please check the logs and try again.', 'ap-api-integration'); ?></p>
                </div>
                <?php
                break;

            case 'sync-failed-fatal':
                ?>
                <div class="notice notice-error is-dismissible">
                    <p><?php echo esc_html__('Manual sync failed due to a fatal system error. Please check your server logs and contact support if needed.', 'ap-api-integration'); ?></p>
                </div>
                <?php
                break;
        }
    }
}
