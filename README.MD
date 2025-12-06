# AP API Integration

A WordPress plugin that integrates with Zips or BerrySoft API to synchronize stores data on a daily schedule with comprehensive logging, automatic log rotation, and manual administration tools.

---

## Overview

The AP API Integration plugin provides seamless integration between your WordPress site and the API. It automatically fetches and stores store details on a daily basis, maintains detailed logs of all operations, includes intelligent log management to keep your server clean, and provides manual administration tools for cache management and on-demand synchronization.

---

## Features

- ✅ **Automated Daily Sync** - Fetches store details from AP API once per day
- ✅ **Manual Sync Control** - Trigger data synchronization on demand via admin interface
- ✅ **Cache Management** - Clear cached data manually when needed
- ✅ **Secure Authentication** - Token-based authentication with the API
- ✅ **Admin Settings Page** - Easy configuration of API credentials
- ✅ **Admin Actions Page** - Manual cache clearing and sync operations
- ✅ **Preview Data Page** - View synced store data with collapsible sections and table formatting
- ✅ **Debug Page** - Debug page in WordPress admin
- ✅ **Comprehensive Logging** - Daily log files with complete operation history
- ✅ **Automatic Log Rotation** - Creates new log file each day
- ✅ **Intelligent Log Cleanup** - Keeps only the newest 100 log files
- ✅ **Self-Healing Cron Jobs** - Automatically reschedules if deleted
- ✅ **Type-Safe Architecture** - Built with PHP 8.3 features for reliability
- ✅ **Advanced Error Handling** - Graceful error handling with detailed logging and user feedback
- ✅ **Consistent Price Formatting** - All prices display with 2-decimal formatting using centralized trait

---

## Widgets

The plugin provides the following Elementor widgets for displaying store information:

- **AP Store Details Widget** - Displays complete store information (name, address, phone, hours)
- **AP Store Address Widget** - Displays store address only
- **AP Store Hours Widget** - Displays store operating hours only
- **AP Store Phone Widget** - Displays clickable store phone number only
- **AP Store Tailor Prices Widget** - Displays tailor prices
- **AP Store Prices Per Department Widget** - Displays department-organized pricing with section headers, showing items and prices grouped by department

All widgets include a store selection dropdown.

---

## Requirements

- **WordPress:** 5.8 or higher
- **PHP:** 8.3 or higher
- **PHP Extensions:** JSON, cURL (typically included with WordPress)

---

## Installation

### Automatic Installation

1. Download the plugin zip file
2. Go to **WordPress Admin → Plugins → Add New**
3. Click **Upload Plugin** button
4. Choose the zip file and click **Install Now**
5. Click **Activate Plugin**

### Manual Installation

1. Download and extract the plugin zip file
2. Upload the `ap-api-integration` folder to `/wp-content/plugins/` directory
3. Go to **WordPress Admin → Plugins**
4. Find **AP API Integration** and click **Activate**

---

## Configuration

### Setting Up API Credentials

1. Go to **WordPress Admin → AP API**
2. Enter your API credentials:
   - **Username** - Your AP API username
   - **Password** - Your AP API password
   - **API Base URL** - The AP API base URL (e.g., `https://api.example.com/`)
3. Click **Save Settings**

**Note:** The API Base URL will automatically have a trailing slash added if you forget it.

---

## Admin Actions Page

The plugin includes a dedicated **Api Actions** page for manual administration tasks, accessible via **WordPress Admin → AP API → Api Actions**.

### Available Actions

#### 1. Clear Cache
- **Purpose:** Clears all cached store data to force fresh data retrieval
- **When to use:** When you need to ensure the latest data is fetched immediately
- **Security:** Requires confirmation dialog before execution
- **Feedback:** Shows success/failure messages with specific details

#### 2. Manual Sync
- **Purpose:** Triggers the data synchronization process immediately instead of waiting for the daily cron
- **When to use:**
  - When setting up the plugin for the first time
  - When you need fresh data immediately
  - For testing API connectivity
  - After changing API credentials
- **Process:** Runs the same sync process as the daily cron job
- **Security:** Requires confirmation dialog before execution
- **Error Handling:** Displays specific error messages for different failure types:
  - **API Errors:** Authentication or network connectivity issues
  - **Processing Errors:** Data transformation or validation failures
  - **Fatal Errors:** System-level issues requiring technical support

### Action Results

The Actions page provides detailed feedback for all operations:

**Success Messages:**
- ✅ Cache cleared successfully
- ✅ Manual sync completed successfully

**Error Messages:**
- ❌ Failed to clear cache
- ❌ Manual sync failed due to API error (check settings and logs)
- ❌ Manual sync failed due to data processing error (check logs)
- ❌ Manual sync failed due to fatal system error (check server logs)

### Security Features

- **User Permission Checks:** Only administrators can access the page
- **Nonce Security:** All actions use WordPress nonces to prevent CSRF attacks
- **Confirmation Dialogs:** Both actions require user confirmation before execution
- **Secure Redirects:** All actions redirect back to the Actions page with status messages

### Accessing the Actions Page

1. Log in to WordPress Admin
2. Navigate to **AP API** in the main menu
3. Click **Api Actions** in the submenu

---

## Preview Data Page

The plugin includes a **Preview Data** page for viewing synced store information, accessible via **WordPress Admin → AP API → Preview Data**.

### Features

- **Collapsible Interface** - Each store and data section can be expanded/collapsed using HTML `<details>` elements
- **Table Format** - Data is presented in clean, organized tables with proper headers
- **Hierarchical Structure** - Stores contain sections (hours, tailor prices, departments), departments contain items

### Accessing the Preview Data Page

1. Log in to WordPress Admin
2. Navigate to **AP API** in the main menu
3. Click **Preview Data** in the submenu

---

## How It Works

### Data Synchronization Process

1. **Authentication** - The plugin authenticates with the API using your credentials
2. **Data Fetch** - Retrieves all stores details from the API
3. **Raw Data Storage** - Saves the original API response to WordPress options table
4. **Data Transformation** - Converts raw API data into a clean, optimized structure using `ApiStoreTransformer`
5. **Data Validation** - Validates transformed data by attempting to create value objects via `ApiStoreRepository`
6. **Transformed Data Storage** - If validation passes, saves the transformed data keyed by `branchId` for fast lookups
7. **Cache Management** - Automatically manages transient cache for optimal performance
8. **Logging** - Records the operation result (success or error) to the log file

### Accessing Synced Data

The synced data is stored in two formats in the WordPress options table:

1. **Raw Data** (`ap_api_cron_stores__api_data`) - Original API response for backup/debugging
2. **Transformed Data** (`ap_api_stores_transformed_data`) - Optimized structure keyed by `branchId`

#### Using the ApiStoreRepository

```php
use ApApi\Repositories\ApiStoreRepository;

$repository = new ApiStoreRepository();

// Get a single store by branch ID (O(1) lookup)
$store = $repository->findStoreByBranchId(902);

// Get multiple stores by branch IDs
$stores = $repository->findStoresByBranchIds([902, 903, 904]);

// Get all stores
$allStores = $repository->getAllStores();

// Clear cache if needed
$repository->clearCache();
```

#### Performance Benefits

- **Fast Lookups** - Direct key-based access by `branchId` (O(1) vs O(n))
- **Transient Caching** - Repository automatically caches data for 12 hours
- **Automatic Cache Management** - Cache is cleared and refreshed during daily sync
- **Graceful Degradation** - If new API data is invalid, old transformed data remains usable

---

## Cron Jobs

The plugin uses two automated cron jobs to handle synchronization and maintenance:

### 1. Daily Data Sync

- **Hook Name:** `ap_api_daily_sync`
- **Schedule:** Once per day
- **Purpose:** Fetches store details from AP API
- **Self-Healing:** Automatically reschedules if deleted

### 2. Weekly Log Cleanup

- **Hook Name:** `ap_api_logger_cleanup`
- **Schedule:** Once per week
- **Purpose:** Deletes old log files, keeping only the newest 100
- **Self-Healing:** Automatically reschedules if deleted

---

## Logging System

### Log File Location

Log files are stored in your WordPress uploads directory:

```
wp-content/uploads/logs/ap-api-sync/
```

### Log File Naming

Log files use daily rotation with the format:

```
ap-api-sync-logs-d-m-Y.log
```

**Examples:**
- `ap-api-sync-logs-23-10-2025.log`
- `ap-api-sync-logs-24-10-2025.log`

### Log File Content

Each log entry includes:
- **Timestamp** - When the event occurred
- **Message** - Description of what happened
- **Context** - Additional data (errors, details, etc.)

**Example log entry:**
```
[2025-10-23 14:30:45] AP API Cron Sync Error: Authentication failed | Context: {"error":"Invalid credentials"}
```

### Log Types

The logging system records:

1. **Sync Operations:**
   - Successful data fetches
   - Authentication errors
   - API connection errors
   - Data processing errors

2. **Cleanup Operations:**
   - Number of files cleaned
   - Cleanup status
   - Files within limit notification

### Accessing Logs

To view your logs:

1. Connect to your server via FTP/SSH
2. Navigate to `wp-content/uploads/logs/ap-api-sync/`
3. Download or view the log file for the date you're interested in

### Log Retention

- **Maximum Files:** 100 (approximately 3 months of history)
- **Cleanup Frequency:** Weekly
- **Automatic Rotation:** New file created daily
- **Oldest Files Deleted:** When total exceeds 100 files

---

## Recommended Plugins

### WP Crontrol

We highly recommend installing **WP Crontrol** to monitor and manage the plugin's cron jobs.

**Plugin Link:** [https://wordpress.org/plugins/wp-crontrol/](https://wordpress.org/plugins/wp-crontrol/)

#### Why WP Crontrol is Useful:

- ✅ **View Scheduled Cron Jobs** - See when the next sync will run
- ✅ **Run Jobs Manually** - Trigger sync or cleanup on demand
- ✅ **Debug Issues** - Check if cron jobs are properly scheduled
- ✅ **Monitor Execution** - View cron job history and status
- ✅ **Manage Schedules** - Modify or delete cron events if needed

#### How to Use WP Crontrol with This Plugin:

1. Install and activate WP Crontrol
2. Go to **Tools → Cron Events**
3. Look for these hooks:
   - `ap_api_daily_sync` - Daily data synchronization
   - `ap_api_logger_cleanup` - Weekly log cleanup
4. Click **Run Now** to manually trigger a sync
5. Monitor the "Next Run" time to see when it will execute next

---

## Troubleshooting

### Data Not Syncing

**Symptoms:** No data in option table

**Solutions:**
1. Check API credentials in AP API (main menu)
2. Verify API Base URL is correct and includes trailing slash
3. Check log files for error messages
4. Use WP Crontrol to manually trigger `ap_api_daily_sync`
5. Verify WordPress cron is working (not disabled in wp-config.php)

### Cron Jobs Not Running

**Symptoms:** Sync never happens, logs show no activity

**Solutions:**
1. Check your WordPress cron configuration:
  - If on managed hosting (Cloudways, WP Engine, Kinsta, etc.), `DISABLE_WP_CRON` may be intentionally set to `true`
  - This is NORMAL - the host runs cron via system cron instead of page loads
  - Verify the system cron is configured: Check your hosting control panel for cron job settings
2. Install WP Crontrol plugin to verify cron schedules exist

### Authentication Errors

**Symptoms:** Logs show "Authentication failed" or similar messages

**Solutions:**
1. Verify username and password in plugin settings
2. Check with AP API provider that credentials are correct
3. Ensure API Base URL is accessible from your server
4. Check if API requires IP whitelisting
5. Verify SSL certificates are valid (if using HTTPS)

### Log Files Not Appearing

**Symptoms:** No log files in uploads directory

**Solutions:**
1. Check directory permissions on `wp-content/uploads/`
2. Ensure WordPress can write to uploads directory
3. Verify plugin is activated
4. Check if any errors are occurring (PHP error log)
5. Manually trigger a sync to generate a log entry

### Too Many Log Files

**Symptoms:** More than 100 log files accumulating

**Solutions:**
1. Check if cleanup cron is scheduled using WP Crontrol
2. Manually trigger cleanup
3. Verify weekly cleanup cron is not disabled or stuck
4. Check log files for cleanup operation messages

### Timeout Errors

**Symptoms:** Logs show timeout or connection timeout errors

**Solutions:**
1. Plugin already uses 30-second timeout (increased from WP default 5 seconds)
2. Check with YOUR API provider about API response times
3. Verify server's outgoing connection settings
4. Check if firewall is blocking outgoing requests
5. Contact your hosting provider about timeout limits

## Developer Information

### Plugin Architecture

The plugin uses modern PHP 8.3 features and follows WordPress best practices:

- **Namespace:** `ApApi\`
- **Singleton Pattern:** Used for main plugin components
- **Dependency Injection:** Logger injected via constructor
- **Type Safety:** Full type declarations (properties, parameters, returns)
- **HTTP Methods Enum:** Type-safe HTTP method handling
- **Exception Handling:** Custom `MBApiException` for API errors
- **Improved Error Handling:** Try-catch blocks for test functionality

### Key Components

#### 1. Client (`src/DataSync/Client.php`)
- Handles HTTP communication with API
- Supports GET and POST methods via `HttpMethod` enum
- 30-second timeout for API requests
- Automatic error handling and validation

#### 2. Endpoints
- **Authenticate** (`src/DataSync/Endpoints/Authenticate.php`) - API authentication
- **GetAllStoresDetails** (`src/DataSync/Endpoints/GetAllStoresDetails.php`) - Fetch stores

#### 3. Value Objects
- **ApiStore** (`src/ValueObjects/ApiStore.php`) - Complete store data with pricing
- **ApiPricesPerDepartment** (`src/ValueObjects/ApiPricesPerDepartment.php`) - Department pricing data
- **ApiDepartmentItemPrices** (`src/ValueObjects/ApiDepartmentItemPrices.php`) - Individual item pricing
- **ApiStoreHours** (`src/ValueObjects/ApiStoreHours.php`) - Store operating hours
- **ApiTailorPrice** (`src/ValueObjects/ApiTailorPrice.php`) - Tailoring service pricing

#### 4. Data Processing Components
- **ApiStoreTransformer** (`src/DataSync/Transformers/ApiStoreTransformer.php`) - Transforms raw API data into optimized structure
- **ApiStoreRepository** (`src/DataSync/Repositories/ApiStoreRepository.php`) - Repository pattern for data access with transient caching

#### 5. Data Storage & Caching
- **ApiDataStorageTrait** (`src/DataSync/Traits/ApiDataStorageTrait.php`) - Centralized data storage methods
- **Transient Caching** - 12-hour cache in repository for optimal performance
- **Dual Storage** - Raw API data + transformed data for reliability and speed

#### 6. Cron Jobs
- **ApiSyncCron** (`src/DataSync/Cron/ApiSyncCron.php`) - Daily data sync
- **LoggerCleanerCron** (`src/DataSync/Cron/LoggerCleanerCron.php`) - Weekly log cleanup

#### 7. Logger (`src/Logger/Logger.php`)
- Daily rotating log files
- Automatic directory creation
- Structured log entries with timestamps

#### 8. Admin Pages
- **SettingsPage** (`src/DataSync/AdminPages/SettingsPage.php`) - Settings page in WordPress admin
- **ActionsPage** (`src/DataSync/AdminPages/ActionsPage.php`) - Manual cache clearing and sync operations
- **PreviewDataPage** (`src/DataSync/AdminPages/PreviewDataPage.php`) - Collapsible data preview with table formatting

#### 9. Traits
- **SingletonTrait** (`src/Traits/SingletonTrait.php`) - Singleton pattern implementation
- **PriceFormattingTrait** (`src/Traits/PriceFormattingTrait.php`) - Centralized price formatting with 2-decimal places

### Hooks and Filters

The plugin currently doesn't expose public hooks, but this may be added in future versions.

### Extending the Plugin

To add additional API endpoints:

1. Create a new class in `src/DataSync/Endpoints/`
2. Implement `EndpointContract` interface
3. Add `execute()` method with your logic
4. Use the `Client` class to make API calls

---

## Support

### Getting Help

If you encounter issues with the plugin:

1. **Check the logs** - Review log files in `wp-content/uploads/logs/ap-api-sync/` or use the Debug page in WordPress admin
2. **Check this README** - Most common issues are covered in Troubleshooting
3. **Check WordPress debug log** - Enable `WP_DEBUG` and check `wp-content/debug.log`
4. **Use WP Crontrol** - Verify cron jobs are scheduled and running

### Reporting Issues

When reporting an issue, please include:
- WordPress version
- PHP version
- Plugin version
- Relevant log entries
- Steps to reproduce the issue
- Screenshots (if applicable)

---

## Changelog

### Version 1.0.0

- Initial release
- Daily data synchronization
- Automatic log rotation
- Weekly log cleanup
- Admin settings page
- Comprehensive error logging
- Self-healing cron jobs
- Elementor widgets

---

## Credits

Developed by: Aleksandar Predic, AlmaCore, alex@almacore.io
