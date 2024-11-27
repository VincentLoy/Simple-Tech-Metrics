<?php

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