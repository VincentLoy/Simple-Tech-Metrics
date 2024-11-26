<?php

function simple_tech_metrics_get_system_data() {
    global $wpdb;

    return [
        'php_version' => PHP_VERSION,
        'memory_limit' => ini_get('memory_limit'),
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
        'max_execution_time' => ini_get('max_execution_time') . ' seconds',
        'server_type' => $_SERVER['SERVER_SOFTWARE'],
        'mysql_version' => $wpdb->db_version(),
        'wordpress_version' => get_bloginfo('version'),
        'site_url' => get_site_url(),
        'home_url' => get_home_url(),
        'disk_space_free' => size_format(disk_free_space('/')),
        'disk_space_total' => size_format(disk_total_space('/')),
    ];
}

function simple_tech_metrics_display_system_table() {
    $system_data = simple_tech_metrics_get_system_data();

    echo '<h2>' . __('System Information', SIMPLE_TECH_METRICS_TEXT_DOMAIN) . '</h2>';
    echo '<table class="widefat fixed striped">';
    echo '<thead>
            <tr>
                <th>' . __('Metric', SIMPLE_TECH_METRICS_TEXT_DOMAIN) . '</th>
                <th>' . __('Value', SIMPLE_TECH_METRICS_TEXT_DOMAIN) . '</th>
            </tr>
          </thead>';
    echo '<tbody>';
    foreach ($system_data as $metric => $value) {
        echo '<tr>';
        echo '<td>' . esc_html(ucwords(str_replace('_', ' ', $metric))) . '</td>';
        echo '<td>' . esc_html($value) . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}

