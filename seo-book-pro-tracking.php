<?php
/*
Plugin Name: SEO Book Pro Tracking
Description: Add Google Analytics tracking code and Google Search Console meta tag from the WordPress admin page.
Version: 1.5
Author: SEO Book Pro
Author URL: https://seobook.pro
License: MIT License
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin URL and directory constants
define('SEO_BOOK_PRO_TRACKING_URL', plugin_dir_url(__FILE__));
define('SEO_BOOK_PRO_TRACKING_DIR', plugin_dir_path(__FILE__));

// Add a settings menu to the admin panel
function seo_book_pro_tracking_menu() {
    add_menu_page(
        'SEO Tracking Settings',
        'SEO Tracking',
        'manage_options',
        'seo-book-pro-tracking',
        'seo_book_pro_tracking_settings_page',
        'dashicons-analytics',
        1
    );
}
add_action('admin_menu', 'seo_book_pro_tracking_menu');

// Register settings
function seo_book_pro_tracking_settings() {
    register_setting('seo_book_pro_tracking_reg_settings', 'google_analytics_code');
    register_setting('seo_book_pro_tracking_reg_settings', 'google_search_console_meta');
}
add_action('admin_init', 'seo_book_pro_tracking_settings');

// Enqueue plugin CSS and JS
function seo_book_pro_tracking_enqueue_assets() {
    // Enqueue CSS
    wp_enqueue_style(
        'seo-book-pro-tracking-css',
        plugins_url('assets/css/tracking.css', __FILE__), // Correct URL to the CSS file
        [],
        '1.0',
        'all'
    );

    // Enqueue JavaScript (if needed)
    // wp_enqueue_script(
    //     'seo-book-pro-tracking-js',
    //     plugins_url('assets/js/tracking.js', __FILE__), // Correct URL to the JS file
    //     ['jquery'], // Dependency
    //     '1.0',
    //     true // Load in the footer
    // );
}
add_action('admin_enqueue_scripts', 'seo_book_pro_tracking_enqueue_assets');


// Create the settings page
function seo_book_pro_tracking_settings_page() {
    ?>

    <div class="wrap">
      <div class="left">
        <h2 class="settings-title">SEO Book Pro Tracking Help</h2>
        <p class="plugin-text">Add Google Analytics tracking code and Google Search Console meta tag from the WordPress admin page</p>

        <h2 class="settings-subtitle">SEO Book Pro Tracking Allows you to Quickly and Easily Add:</h2>

        <p class="plugin-text">Google Analytics tracking code (script) to the footer of your site. Google Search Console meta tag to the header of your site. No need to manually edit your theme files — this plugin makes it easy to configure these important SEO settings from your WordPress admin dashboard.</p>

        <h3 class="settings-sub-subtitle">SEO Book Pro Tracking Features</h3>

        <ul class="features">
        <li>Add Google Analytics tracking code via the admin interface.</li>
        <li>Add Google Search Console verification meta tag via the admin interface.</li>
        <li>Automatically injects the Google Analytics code into the footer.</li>
        <li>Automatically injects the Search Console meta tag into the header.</li>
        </ul>
        <h3 class="faqs-subtitle">Frequently Asked Questions</h3>
        <div class="faqs">
          <details class="faq-details">
            <summary class="faq-summary">Can I use this plugin with any WordPress theme?</summary>
            <p class="faq-answer">Yes, the plugin works with any theme and injects the necessary code into the head sections of your site.</p>
          </details>

          <details class="faq-details">
            <summary class="faq-summary">What happens if I deactivate or uninstall the plugin?</summary>
            <p class="faq-answer">If you deactivate or uninstall the plugin, the Google Analytics and Google Search Console meta tags will no longer appear on your site..</p>
          </details>
        </div>
        <div class="all-docs">
        <a class="all-faqs" href="https://seobook.pro/wordpress-development/wordpress-plugins/seo-book-pro-tracking-plugin/#faqs" title="SEO Book Pro Tracking Plugin Frequently Asked Questions | SEO Book Pro Tracking WordPress Plugin" target="_blank">
          Read all FAQs
        </a>
        <a class="documentation" href="https://seobook.pro/wordpress-development/wordpress-plugins/seo-book-pro-tracking-plugin/" title="Introducing SEO Book Pro Tracking Plugin | SEO Book Pro Tracking WordPress Plugin" target="_blank">
          Read the Full Documentation
        </a>
                </div>
      </div>
          <div class="right">
            <h1 class="settings-title">SEO Book Pro Tracking Settings</h1>
        <form method="post" action="options.php">
            <?php
            // Display settings fields and save button
            settings_fields('seo_book_pro_tracking_reg_settings');
            do_settings_sections('seo_book_pro_tracking_reg_settings');
            ?>
            <div class="submit-button"><?php submit_button(); ?></div>
            <div class="fields">

              <div class="google-analytics">
                <label id="googleAnalytics" for="google_analytics_code" class="ga4-label">Google Analytics Code</label>
                <p class="google-analytics-link">
                  <a href="https://analytics.google.com/analytics/web/" title="Get your Google Analytics Tracking Code | SEO Book Pro Tracking WordPress Plugin" target="_blank" class="ga4-link">
                    Get your Google Analytics (GA4) Tracking JavaScript Code
                  </a>
                </p>
                <textarea id="google_analytics_code" name="google_analytics_code" rows="10" cols="30" class="ga4-textarea">
                  <?php echo esc_textarea(get_option('google_analytics_code')); ?>
                </textarea>
                <p class="description">Paste your Google Analytics tracking script here. Example:
                  <code class="javascript-code">&lt;script&gt;...&lt;/script&gt;</code>
                </p>
              </div>

              <div class="google-search-console">
                <label id="googleSearchConsole" for="google_search_console_meta" class="gsc-label">Google Search Console Meta Tag</label>
                  <p class="google-search-console-link">
                    <a href="https://search.google.com/search-console/" title="Get your Google Search Console Meta Tag Code | SEO Book Pro Tracking WordPress Plugin" target="_blank" class="gsc-link">
                      Get your Google Search Console Meta Tag Code
                    </a>
                  </p>
                <input type="text" id="google_search_console_meta" name="google_search_console_meta" value="<?php echo esc_attr(get_option('google_search_console_meta')); ?>" class="gsc-input-field-text">
                <p class="description">Paste the Google Search Console meta tag here. Example: <code class="meta-code">&lt;meta name="google-site-verification" content="your_code" /&gt;</code>
                </p>
              </div>

            </div>
        </form>
          </div>
    </div>
    <?php
}

// Output the Google Analytics code in the site header
function seo_book_pro_tracking_add_analytics_code() {
    $google_analytics_code = get_option('google_analytics_code', '');

    if (!empty($google_analytics_code)) {
        // Allow only specific HTML tags and attributes
        $allowed_tags = [
            'script' => ['src' => true, 'type' => true, 'async' => true, 'defer' => true],
        ];

        echo wp_kses($google_analytics_code, $allowed_tags) . "\n";
    }
}
add_action('wp_head', 'seo_book_pro_tracking_add_analytics_code');

// Output the Google Search Console meta tag in the site header
function seo_book_pro_tracking_add_meta_tag() {
    $google_search_console_meta = get_option('google_search_console_meta', '');

    if (!empty($google_search_console_meta)) {
        // Allow specific meta tag attributes
        $allowed_tags = [
            'meta' => ['name' => true, 'content' => true],
        ];

        echo wp_kses($google_search_console_meta, $allowed_tags) . "\n";
    }
}
add_action('wp_head', 'seo_book_pro_tracking_add_meta_tag');
