<?php
/*
Plugin Name: Simple Tech Metrics
Plugin URI: https://github.com/VincentLoy/Simple-Tech-Metrics
Description: Basic insights to better understand your WordPress site.
Version: 1.0.0
Author: Vincent Loy
Author URI: https://github.com/VincentLoy
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: simple-tech-metrics
Domain Path: /languages
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('SIMPLE_TECH_METRICS_VERSION', '1.0.0');
define('SIMPLE_TECH_METRICS_TEXT_DOMAIN', 'simple-tech-metrics');
define('SIMPLE_TECH_METRICS_DIR', plugin_dir_path(__FILE__));
define('SIMPLE_TECH_METRICS_URL', plugin_dir_url(__FILE__));

function load_stm_files() {
    require_once SIMPLE_TECH_METRICS_DIR . 'includes/helpers.php';
    require_once SIMPLE_TECH_METRICS_DIR . 'includes/database-metrics.php';
    require_once SIMPLE_TECH_METRICS_DIR . 'includes/media-metrics.php';
    require_once SIMPLE_TECH_METRICS_DIR . 'includes/plugins-metrics.php';
    require_once SIMPLE_TECH_METRICS_DIR . 'includes/system-metrics.php';
    require_once SIMPLE_TECH_METRICS_DIR . 'includes/themes-metrics.php';
    require_once SIMPLE_TECH_METRICS_DIR . 'includes/stm-exports.php';
}

if (defined('DOING_AJAX') && DOING_AJAX) {
    load_stm_files();
}

// Render admin page
function stm_render_admin_page() {
    load_stm_files();
    
    // Display admin page
    require_once SIMPLE_TECH_METRICS_DIR . 'includes/stm-admin-page.php';
}

// Enqueue admin scripts and styles
add_action('admin_enqueue_scripts', 'simple_tech_metrics_enqueue_assets');
function simple_tech_metrics_enqueue_assets($hook_suffix) {
    if ($hook_suffix === 'toplevel_page_simple-tech-metrics') {
        wp_enqueue_style('simple-tech-metrics-styles', SIMPLE_TECH_METRICS_URL . 'assets/css/styles.css', [], SIMPLE_TECH_METRICS_VERSION);
        wp_enqueue_script('simple-tech-metrics-scripts', SIMPLE_TECH_METRICS_URL . 'assets/js/scripts.js', ['jquery'], SIMPLE_TECH_METRICS_VERSION, true);
    }
}

// lang stuff
add_action('plugins_loaded', 'simple_tech_metrics_load_textdomain');
function simple_tech_metrics_load_textdomain() {
    load_plugin_textdomain(SIMPLE_TECH_METRICS_TEXT_DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages/');
}


// Add admin menu
add_action('admin_menu', function () {
    add_menu_page(
        __('Simple Tech Metrics', SIMPLE_TECH_METRICS_TEXT_DOMAIN),
        __('Tech Metrics', SIMPLE_TECH_METRICS_TEXT_DOMAIN),
        'manage_options',
        'simple-tech-metrics',
        'stm_render_admin_page',
        'dashicons-chart-area',
        80
    );
});
