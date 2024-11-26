<?php
/*
Plugin Name: Simple Tech Metrics
Plugin URI: https://github.com/VincentLoy/WP-Tech-Metrics
Description: Basic insights to better understand your WordPress site.
Version: 1.0.0
Author: Vincent Loy
Author URI: https://github.com/VincentLoy
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: simple-tech-metrics
Domain Path: /languages
*/

define('SIMPLE_TECH_METRICS_VERSION', '1.0.0');
define('SIMPLE_TECH_METRICS_TEXT_DOMAIN', 'simple-tech-metrics');
define('SIMPLE_TECH_METRICS_DIR', plugin_dir_path(__FILE__));
define('SIMPLE_TECH_METRICS_URL', plugin_dir_url(__FILE__));

// Includes
require_once SIMPLE_TECH_METRICS_DIR . 'includes/helpers.php';
require_once SIMPLE_TECH_METRICS_DIR . 'includes/themes-metrics.php';
require_once SIMPLE_TECH_METRICS_DIR . 'includes/plugins-metrics.php';
require_once SIMPLE_TECH_METRICS_DIR . 'includes/media-metrics.php';
require_once SIMPLE_TECH_METRICS_DIR . 'includes/database-metrics.php';
require_once SIMPLE_TECH_METRICS_DIR . 'includes/system-metrics.php';

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
add_action('admin_menu', 'simple_tech_metrics_register_menu');
function simple_tech_metrics_register_menu() {
    add_menu_page(
        __('Simple Tech Metrics', SIMPLE_TECH_METRICS_TEXT_DOMAIN),
        __('Tech Metrics', SIMPLE_TECH_METRICS_TEXT_DOMAIN),
        'manage_options',
        'simple-tech-metrics',
        'simple_tech_metrics_display_page',
        'dashicons-chart-area',
        80
    );
}

// Display admin page
function simple_tech_metrics_display_page() {
    $tabs = [
        'themes' => __('Themes', SIMPLE_TECH_METRICS_TEXT_DOMAIN),
        'plugins' => __('Plugins', SIMPLE_TECH_METRICS_TEXT_DOMAIN),
        'media' => __('Media', SIMPLE_TECH_METRICS_TEXT_DOMAIN),
        'database' => __('Database', SIMPLE_TECH_METRICS_TEXT_DOMAIN),
        'system' => __('System', SIMPLE_TECH_METRICS_TEXT_DOMAIN),
        'tools' => __('Tools', SIMPLE_TECH_METRICS_TEXT_DOMAIN)
    ];

    echo '<div class="wrap">';
    echo '<h1>' . __('Simple Tech Metrics', SIMPLE_TECH_METRICS_TEXT_DOMAIN) . '</h1>';
    echo '<p>' . __('Technical metrics for your WordPress site are displayed below:', SIMPLE_TECH_METRICS_TEXT_DOMAIN) . '</p>';

    // Tab navigation
    echo '<h2 class="nav-tab-wrapper">';
    foreach ($tabs as $id => $label) {
        echo '<a href="#' . esc_attr($id) . '" class="nav-tab' . ($id === 'themes' ? ' nav-tab-active' : '') . '">' . esc_html($label) . '</a>';
    }
    echo '</h2>';

    // Tab content
    foreach ($tabs as $id => $label) {
        echo '<div id="' . esc_attr($id) . '" class="tab-content" style="' . ($id !== 'themes' ? 'display:none;' : '') . '">';
        if ($id === 'tools') {
            echo '<button class="button export-csv" data-export="all">' . __('Export All Metrics as CSV', SIMPLE_TECH_METRICS_TEXT_DOMAIN) . '</button>';
            echo '<p>' . __('Use this tool to export all collected metrics into a single CSV file.', SIMPLE_TECH_METRICS_TEXT_DOMAIN) . '</p>';
        } else {
            echo '<button class="button export-csv" data-export="' . esc_attr($id) . '">' . sprintf(__('Export %s as CSV', SIMPLE_TECH_METRICS_TEXT_DOMAIN), esc_html($label)) . '</button>';
            $display_function = 'simple_tech_metrics_display_' . $id . '_table';
            if (function_exists($display_function)) {
                $display_function();
            }
        }
        echo '</div>';
    }

    echo '</div>';
}

// AJAX CSV Export
add_action('wp_ajax_simple_tech_metrics_export_csv', 'simple_tech_metrics_export_csv');
function simple_tech_metrics_export_csv() {
    if (!current_user_can('manage_options')) {
        wp_die(__('Unauthorized user', SIMPLE_TECH_METRICS_TEXT_DOMAIN));
    }

    $export_type = sanitize_text_field($_POST['export_type']);
    $exports = simple_tech_metrics_get_exports();

    if (!isset($exports[$export_type])) {
        wp_die(__('Invalid export type', SIMPLE_TECH_METRICS_TEXT_DOMAIN));
    }

    $data = $exports[$export_type]['data']();
    $headers = $exports[$export_type]['headers'];

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=simple-tech-metrics-' . $export_type . '-' . date('Y-m-d') . '.csv');
    $output = fopen('php://output', 'w');

    // Write headers and data
    fputcsv($output, $headers);
    foreach ($data as $row) {
        fputcsv($output, is_array($row) ? $row : array_values($row));
    }
    fclose($output);
    exit;
}

// Export configurations
function simple_tech_metrics_get_exports() {
    return [
        'themes' => [
            'headers' => ['Name', 'Version', 'Status', 'Size', 'Last Update'],
            'data' => 'simple_tech_metrics_get_themes_data'
        ],
        'plugins' => [
            'headers' => ['Name', 'Version', 'Status', 'Size', 'Update Available'],
            'data' => 'simple_tech_metrics_get_plugins_data'
        ],
        'media' => [
            'headers' => ['Name', 'Size', 'Path'],
            'data' => function () {
                return simple_tech_metrics_get_media_data()['largest_files'];
            }
        ],
        'database' => [
            'headers' => ['Table Name', 'Rows', 'Size', 'Last Update'],
            'data' => function () {
                return simple_tech_metrics_get_database_data()['tables'];
            }
        ],
        'system' => [
            'headers' => ['Metric', 'Value'],
            'data' => function () {
                $system_data = simple_tech_metrics_get_system_data();
                return array_map(function ($key, $value) {
                    return ['Metric' => ucwords(str_replace('_', ' ', $key)), 'Value' => $value];
                }, array_keys($system_data), $system_data);
            }
        ],
        'all' => [
            'headers' => [],
            'data' => 'simple_tech_metrics_export_all_sections'
        ]
    ];
}

// All sections export
function simple_tech_metrics_export_all_sections() {
    $exports = simple_tech_metrics_get_exports();
    $output = [];

    foreach ($exports as $section => $config) {
        if ($section === 'all') continue;

        $output[] = [$section];
        $output[] = $config['headers'];
        $data = $config['data']();
        foreach ($data as $row) {
            $output[] = is_array($row) ? $row : array_values($row);
        }
        $output[] = []; // Blank line for separation
    }
    return $output;
}
