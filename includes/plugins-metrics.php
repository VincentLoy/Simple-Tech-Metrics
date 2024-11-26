<?php

function wp_tech_metrics_get_plugins_data() {
    // Obtenir les plugins actifs et inactifs
    $all_plugins = get_plugins();
    $active_plugins = get_option('active_plugins', []);

    $plugins_data = [];
    foreach ($all_plugins as $plugin_file => $plugin_info) {
        $plugin_dir = WP_PLUGIN_DIR . '/' . dirname($plugin_file);
        $plugin_size = wp_tech_metrics_calculate_folder_size($plugin_dir);

        $plugins_data[] = [
            'name'        => $plugin_info['Name'],
            'version'     => $plugin_info['Version'],
            'status'      => in_array($plugin_file, $active_plugins) ? 'active' : 'inactive',
            'size'        => $plugin_size,
            'update'      => is_plugin_update_available($plugin_file),
        ];
    }

    return $plugins_data;
}

function is_plugin_update_available($plugin_file) {
    // Vérifier si une mise à jour est disponible pour un plugin
    $update_plugins = get_site_transient('update_plugins');
    if (!empty($update_plugins->response[$plugin_file])) {
        return true;
    }
    return false;
}

function wp_tech_metrics_display_plugins_table() {
    $plugins_data = wp_tech_metrics_get_plugins_data();

    echo '<h2>' . __('Plugins', WP_TECH_METRICS_TEXT_DOMAIN) . '</h2>';
    echo '<table class="widefat fixed striped">';
    echo '<thead>
            <tr>
                <th>' . __('Name', WP_TECH_METRICS_TEXT_DOMAIN) . '</th>
                <th>' . __('Version', WP_TECH_METRICS_TEXT_DOMAIN) . '</th>
                <th>' . __('Status', WP_TECH_METRICS_TEXT_DOMAIN) . '</th>
                <th>' . __('Size', WP_TECH_METRICS_TEXT_DOMAIN) . '</th>
                <th>' . __('Update Available', WP_TECH_METRICS_TEXT_DOMAIN) . '</th>
            </tr>
          </thead>';
    echo '<tbody>';
    foreach ($plugins_data as $plugin) {
        $tr_class = $plugin['status'] === 'active' ? 'active' : '';
        $plug_updt = $plugin['update'] ? 'wpmetrics-green' : '';

        if ($plug_updt) {
            $tr_class = 'warning';
        }

        echo "<tr class='{$tr_class}'>";
        echo '<td>' . esc_html($plugin['name']) . '</td>';
        echo '<td>' . esc_html($plugin['version']) . '</td>';
        echo '<td>' . esc_html($plugin['status']) . '</td>';
        echo '<td>' . esc_html($plugin['size']) . '</td>';
        echo "<td class='{$plug_updt}'>" . ($plugin['update'] ? __('Yes', WP_TECH_METRICS_TEXT_DOMAIN) : __('No', WP_TECH_METRICS_TEXT_DOMAIN)) . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}
