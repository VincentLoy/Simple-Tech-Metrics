<?php

// Display admin page
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