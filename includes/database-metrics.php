<?php

function wp_tech_metrics_get_database_data() {
    global $wpdb;

    $tables = $wpdb->get_results('SHOW TABLE STATUS');
    $database_data = [
        'total_size' => 0,
        'tables' => [],
    ];

    foreach ($tables as $table) {
        $table_size = ($table->Data_length + $table->Index_length) / 1024 / 1024; // Convert to MB
        $database_data['total_size'] += $table_size;

        $database_data['tables'][] = [
            'name' => $table->Name,
            'rows' => $table->Rows,
            'size' => size_format($table_size * 1024 * 1024, 2), // Format to readable size
            'update_time' => $table->Update_time,
        ];
    }

    return $database_data;
}

function wp_tech_metrics_display_database_table() {
    $database_data = wp_tech_metrics_get_database_data();

    echo '<h2>' . __('Database', WP_TECH_METRICS_TEXT_DOMAIN) . '</h2>';
    echo '<p>' . sprintf(
        __('Total database size: <strong>%s</strong>', WP_TECH_METRICS_TEXT_DOMAIN),
        esc_html(size_format($database_data['total_size'] * 1024 * 1024, 2))
    ) . '</p>';

    echo '<table class="widefat fixed striped">';
    echo '<thead>
            <tr>
                <th>' . __('Table Name', WP_TECH_METRICS_TEXT_DOMAIN) . '</th>
                <th>' . __('Rows', WP_TECH_METRICS_TEXT_DOMAIN) . '</th>
                <th>' . __('Size', WP_TECH_METRICS_TEXT_DOMAIN) . '</th>
                <th>' . __('Last Update', WP_TECH_METRICS_TEXT_DOMAIN) . '</th>
            </tr>
          </thead>';
    echo '<tbody>';

    foreach ($database_data['tables'] as $table) {
        echo '<tr>';
        echo '<td>' . esc_html($table['name']) . '</td>';
        echo '<td>' . esc_html($table['rows']) . '</td>';
        echo '<td>' . esc_html($table['size']) . '</td>';
        echo '<td>' . esc_html($table['update_time'] ?? __('N/A', WP_TECH_METRICS_TEXT_DOMAIN)) . '</td>';
        echo '</tr>';
    }
    
    echo '</tbody>';
    echo '</table>';
}

