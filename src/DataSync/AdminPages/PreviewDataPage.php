<?php

namespace ApApi\DataSync\AdminPages;

use ApApi\Repositories\ApiStoreRepository;
use ApApi\Traits\SingletonTrait;
use ApApi\Logger\Logger;

// Do not allow directly accessing this file.
if ( ! defined('ABSPATH')) {
    exit('Direct script access denied.');
}

/**
 * Class PreviewDataPage
 * Handles the AP API Preview Data page for displaying store data in HTML format
 */
class PreviewDataPage
{
    use SingletonTrait;

    /**
     * Preview Data page slug
     */
    private const string PAGE_SLUG = 'ap-api-preview-data';

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
        add_action('admin_menu', [$this, 'addPreviewDataPage']);
    }

    /**
     * Add preview data page as submenu under AP API menu
     *
     * @return void
     */
    public function addPreviewDataPage(): void
    {
        add_submenu_page(
            self::PARENT_SLUG, // Parent slug
            esc_html__('AP Api Preview Data', 'ap-api-integration'), // Page title
            esc_html__('Preview Data', 'ap-api-integration'), // Menu title
            'manage_options', // Capability
            self::PAGE_SLUG, // Menu slug
            [$this, 'render'] // Callback
        );
    }

    /**
     * Render preview data page
     *
     * @return void
     */
    public function render(): void
    {
        if ( ! current_user_can('manage_options')) {
            return;
        }

        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <div class="card">
                <h2 class="title"><?php echo esc_html__('Store Data Preview', 'ap-api-integration'); ?></h2>
                <p><?php echo esc_html__('Below is the current store data retrieved from the API:', 'ap-api-integration'); ?></p>

                <div class="ap-preview-container">
                    <?php $this->renderStoreData(); ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render store data in HTML format
     *
     * @return void
     */
    private function renderStoreData(): void
    {
        // Add CSS styles for the preview data page
        echo '<style>
            .ap-preview-container {
                background: #f9f9f9;
                padding: 20px;
                border: 1px solid #ddd;
                margin-top: 20px;
            }
            .ap-store-details {
                border: 1px solid #ccc;
                margin-bottom: 20px;
                padding: 10px;
                border-radius: 5px;
            }
            .ap-store-summary {
                cursor: pointer;
                font-weight: bold;
                color: #2c3e50;
                font-size: 16px;
                padding: 5px 0;
            }
            .ap-store-content {
                margin-top: 15px;
            }
            .ap-section-details {
                margin-bottom: 15px;
            }
            .ap-section-summary {
                cursor: pointer;
                font-weight: bold;
                font-size: 14px;
            }
            .ap-section-content {
                margin-top: 10px;
            }
            .ap-department-details {
                margin-bottom: 10px;
                padding-left: 15px;
            }
            .ap-department-summary {
                cursor: pointer;
                font-weight: bold;
                font-size: 13px;
                color: #0073aa;
            }
            .ap-department-content {
                margin-top: 8px;
            }
            .ap-price {
                color: #e67e22;
                font-weight: bold;
                background-color: #fef9e7;
                padding: 2px 6px;
                border-radius: 3px;
                border: 1px solid #f39c12;
            }
            .ap-error-message {
                color: #d54e21;
                font-weight: bold;
            }
            .ap-store-separator {
                margin: 20px 0;
                border: 1px solid #ccc;
            }
            /* Table-like styling for lists */
            .ap-table-list {
                list-style: none;
                padding: 0;
                margin: 10px 0;
                border: 1px solid #ddd;
                border-radius: 4px;
                background: white;
            }
            .ap-table-header {
                background: #f1f1f1;
                border-bottom: 2px solid #ddd;
                padding: 8px;
                font-weight: bold;
                display: grid;
                grid-template-columns: 1fr auto;
                gap: 10px;
                border-radius: 4px 4px 0 0;
            }
            .ap-table-row {
                padding: 8px;
                border-bottom: 1px solid #eee;
                display: grid;
                grid-template-columns: 1fr auto;
                gap: 10px;
                align-items: center;
            }
            .ap-table-row:last-child {
                border-bottom: none;
            }
            .ap-table-row:nth-child(even) {
                background: #f9f9f9;
            }
            .ap-table-row:hover {
                background: #f0f8ff;
            }
            .ap-table-name {
                font-weight: 500;
            }
            .ap-table-value {
                text-align: right;
                white-space: nowrap;
            }
            /* Special styling for hours table */
            .ap-hours-table .ap-table-row {
                grid-template-columns: 120px 1fr;
            }
            .ap-hours-table .ap-table-header {
                grid-template-columns: 120px 1fr;
            }
        </style>';

        try {
            $repository = new ApiStoreRepository();
            $allStores = $repository->getAllStores();

            if (empty($allStores)) {
                echo '<p class="ap-error-message">' . esc_html__('No store data available. Please run a sync first.', 'ap-api-integration') . '</p>';
                return;
            }

            foreach ($allStores as $store) {
                // Wrap each store in a details element
                echo '<details class="ap-store-details">';
                echo "<summary class='ap-store-summary'>{$store->getStoreName()}</summary>";

                echo '<div class="ap-store-content">';

                // Store Information section with details
                echo '<details class="ap-section-details">';
                echo '<summary class="ap-section-summary">Store Information</summary>';
                echo '<div class="ap-section-content">';
                echo '<div class="ap-table-header">';
                echo '<span>Detail</span><span>Value</span>';
                echo '</div>';
                echo '<ul class="ap-table-list">';
                echo '<li class="ap-table-row"><span class="ap-table-name">Branch ID</span><span class="ap-table-value">' . esc_html($store->getBranchId()) . '</span></li>';
                echo '<li class="ap-table-row"><span class="ap-table-name">Branch Number</span><span class="ap-table-value">' . esc_html($store->getBranchNo()) . '</span></li>';
                echo '<li class="ap-table-row"><span class="ap-table-name">Street Address</span><span class="ap-table-value">' . esc_html($store->getStreetAddress()) . '</span></li>';
                echo '<li class="ap-table-row"><span class="ap-table-name">City</span><span class="ap-table-value">' . esc_html($store->getCity()) . '</span></li>';
                echo '<li class="ap-table-row"><span class="ap-table-name">State</span><span class="ap-table-value">' . esc_html($store->getState()) . '</span></li>';
                echo '<li class="ap-table-row"><span class="ap-table-name">ZIP Code</span><span class="ap-table-value">' . esc_html($store->getZip()) . '</span></li>';
                echo '<li class="ap-table-row"><span class="ap-table-name">Phone</span><span class="ap-table-value">' . esc_html($store->getPhone()) . '</span></li>';
                echo '<li class="ap-table-row"><span class="ap-table-name">Email</span><span class="ap-table-value">' . esc_html($store->getEmail()) . '</span></li>';
                echo '</ul>';
                echo '</div>';
                echo '</details>';

                $storeHours = $store->getStoreHours();

                // Store hours section with details
                echo '<details class="ap-section-details">';
                echo '<summary class="ap-section-summary">Store hours</summary>';
                echo '<div class="ap-section-content">';
                echo '<div class="ap-table-header ap-hours-table">';
                echo '<span>Day</span><span>Hours</span>';
                echo '</div>';
                echo '<ul class="ap-table-list ap-hours-table">';
                echo '<li class="ap-table-row"><span class="ap-table-name">Mon - Fri</span><span class="ap-table-value">' . $storeHours->getMonFriHours() . '</span></li>';
                echo '<li class="ap-table-row"><span class="ap-table-name">Saturday</span><span class="ap-table-value">' . $storeHours->getSaturdayHours() . '</span></li>';
                echo '<li class="ap-table-row"><span class="ap-table-name">Sunday</span><span class="ap-table-value">' . $storeHours->getSundayHours() . '</span></li>';
                echo '</ul>';
                echo '</div>';
                echo '</details>';

                // Tailor prices section with details
                echo '<details class="ap-section-details">';
                echo '<summary class="ap-section-summary">Tailor prices</summary>';
                echo '<div class="ap-section-content">';
                echo '<div class="ap-table-header">';
                echo '<span>Service</span><span>Price</span>';
                echo '</div>';
                echo '<ul class="ap-table-list">';
                foreach ($store->getTailerPrices() as $tailerPrice) {
                    echo '<li class="ap-table-row"><span class="ap-table-name">' . $tailerPrice->getName() . '</span><span class="ap-table-value"><span class="ap-price">' . $tailerPrice->getPrice() . '</span></span></li>';
                }
                echo '</ul>';
                echo '</div>';
                echo '</details>';

                // Prices per department section with details
                echo '<details class="ap-section-details">';
                echo '<summary class="ap-section-summary">Prices per department</summary>';
                echo '<div class="ap-section-content">';
                foreach ($store->getPricesPerDepartments() as $deptPrices) {
                    // Wrap each department in its own details element
                    echo '<details class="ap-department-details">';
                    echo "<summary class='ap-department-summary'>{$deptPrices->getDepartmentName()}</summary>";
                    echo '<div class="ap-department-content">';
                    echo '<div class="ap-table-header">';
                    echo '<span>Item</span><span>Price</span>';
                    echo '</div>';
                    echo '<ul class="ap-table-list">';
                    foreach ($deptPrices->getItems() as $item) {
                        echo '<li class="ap-table-row"><span class="ap-table-name">' . $item->getName() . '</span><span class="ap-table-value"><span class="ap-price">' . $item->getPrice() . '</span></span></li>';
                    }
                    echo '</ul>';
                    echo '</div>';
                    echo '</details>';
                }
                echo '</div>';
                echo '</details>';

                echo '</div>'; // Close store content div
                echo '</details>'; // Close store details

                // Add separator between stores if there are multiple (outside of details)
                if (count($allStores) > 1) {
                    echo '<hr class="ap-store-separator">';
                }
            }

        } catch (\InvalidArgumentException $e) {
            $logger = Logger::getInstance();
            $logger->log(
                '[PREVIEW] [ERROR] Invalid store data format detected',
                [
                    'exception_message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]
            );
            echo '<p class="ap-error-message">' .
                 esc_html__('Invalid store data format detected. Please check data source: ', 'ap-api-integration') .
                 esc_html($e->getMessage()) . '</p>';
        } catch (\Exception $e) {
            echo '<p class="ap-error-message">' .
                 esc_html__('Error loading store data: ', 'ap-api-integration') .
                 esc_html($e->getMessage()) . '</p>';
        }
    }
}
