<?php

function wp_tech_metrics_get_themes_data() {
    // Récupérer les données de tous les thèmes installés
    $themes = wp_get_themes();

    $themes_data = [];
    foreach ($themes as $theme_slug => $theme) {
        $theme_path = $theme->get_theme_root() . '/' . $theme_slug;
        $theme_size = wp_tech_metrics_calculate_folder_size($theme_path);

        $themes_data[] = [
            'name'        => $theme->get('Name'),
            'version'     => $theme->get('Version'),
            'status'      => $theme_slug === get_option('stylesheet') ? 'active' : 'inactive',
            'size'        => $theme_size,
            'last_update' => date("Y-m-d H:i:s", filemtime($theme_path)),
        ];
    }

    return $themes_data;
}

function wp_tech_metrics_display_themes_table() {
    $themes_data = wp_tech_metrics_get_themes_data();
    
    echo '<h2>' . __('Themes', WP_TECH_METRICS_TEXT_DOMAIN) . '</h2>';
    echo '<table class="widefat fixed striped">';
    echo '<thead>
    <tr>
    <th>' . __('Name', WP_TECH_METRICS_TEXT_DOMAIN) . '</th>
    <th>' . __('Version', WP_TECH_METRICS_TEXT_DOMAIN) . '</th>
    <th>' . __('Status', WP_TECH_METRICS_TEXT_DOMAIN) . '</th>
    <th>' . __('Size', WP_TECH_METRICS_TEXT_DOMAIN) . '</th>
    <th>' . __('Last Update', WP_TECH_METRICS_TEXT_DOMAIN) . '</th>
    </tr>
    </thead>';
    echo '<tbody>';
    foreach ($themes_data as $theme) {
        $green_class = $theme['status'] == 'active' ? 'active' : '';
        echo "<tr class='{$green_class}'>";
        echo '<td>' . esc_html($theme['name']) . '</td>';
        echo '<td>' . esc_html($theme['version']) . '</td>';
        echo "<td class='{$green_class}'>" . esc_html($theme['status']) . '</td>';
        echo '<td>' . esc_html($theme['size']) . '</td>';
        echo '<td>' . esc_html($theme['last_update']) . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}

