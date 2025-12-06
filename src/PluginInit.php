<?php

namespace ApApi;

use ApApi\Traits\SingletonTrait;
use ApApi\Widgets\StoreAddressWidget;
use ApApi\Widgets\StoreDetailsWidget;
use ApApi\Widgets\StoreHoursWidget;
use ApApi\Widgets\StorePhoneWidget;
use ApApi\Widgets\StorePricesPerDepartmentWidget;
use ApApi\Widgets\StoreTailorPricesWidget;
use ApApi\DataSync\AdminPages\SettingsPage;
use ApApi\DataSync\AdminPages\ActionsPage;
use ApApi\DataSync\AdminPages\PreviewDataPage;
use ApApi\DataSync\AdminPages\DebugPage;
use ApApi\DataSync\Cron\ApiSyncCron;
use ApApi\DataSync\Cron\LoggerCleanerCron;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

class PluginInit
{
    use SingletonTrait;

    private function __construct()
    {
    }

    /**
     * Set plugin required functionality
     */
    public function setInstances(): void
    {
        // Initialize cron functionality
        ApiSyncCron::getInstance()->initHooks();
        LoggerCleanerCron::getInstance()->initHooks();

        // Add all custom widgets
        add_action(
            'elementor/widgets/register',
            function ($widgetsManager) {
                $widgetsManager->register(new StoreDetailsWidget());
                $widgetsManager->register(new StoreAddressWidget());
                $widgetsManager->register(new StorePhoneWidget());
                $widgetsManager->register(new StoreHoursWidget());
                $widgetsManager->register(new StoreTailorPricesWidget());
                $widgetsManager->register(new StorePricesPerDepartmentWidget());
            }
        );

        if (!is_admin()) {
            return;
        }

        // Admin only below

        // Initialize settings page
        SettingsPage::getInstance()->initHooks();

        // Initialize actions page
        ActionsPage::getInstance()->initHooks();

        // Initialize preview data page
        PreviewDataPage::getInstance()->initHooks();

        // Initialize debug page
        DebugPage::getInstance()->initHooks();
    }
}
