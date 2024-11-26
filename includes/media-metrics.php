<?php

function wp_tech_metrics_get_media_data() {
    $uploads_dir = wp_upload_dir()['basedir']; // Chemin absolu vers le dossier uploads
    $media_data = [
        'total_size' => wp_tech_metrics_calculate_folder_size($uploads_dir), // Taille totale
        'total_files' => 0,
        'largest_files' => [],
        'unused_files' => [], // Placeholder pour des fichiers non référencés
    ];

    $all_files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($uploads_dir));
    $largest_files = [];

    foreach ($all_files as $file) {
        if ($file->isFile()) {
            $media_data['total_files']++;
            $largest_files[] = [
                'name' => $file->getFilename(),
                'size' => $file->getSize(),
                'path' => $file->getPathname(),
            ];
        }
    }

    // 5 biggest files
    usort($largest_files, function ($a, $b) {
        return $b['size'] - $a['size'];
    });
    $media_data['largest_files'] = array_slice($largest_files, 0, 5);

    return $media_data;
}

function wp_tech_metrics_display_media_table() {
    $media_data = wp_tech_metrics_get_media_data();

    echo '<h2>' . __('Media', WP_TECH_METRICS_TEXT_DOMAIN) . '</h2>';
    echo '<p>' . sprintf(
        __('Total uploads folder size: <strong>%s</strong>', WP_TECH_METRICS_TEXT_DOMAIN),
        esc_html($media_data['total_size'])
    ) . '</p>';
    echo '<p>' . sprintf(
        __('Total number of files: <strong>%d</strong>', WP_TECH_METRICS_TEXT_DOMAIN),
        esc_html($media_data['total_files'])
    ) . '</p>';

    // Table for largest files
    echo '<h3>' . __('Largest Files', WP_TECH_METRICS_TEXT_DOMAIN) . '</h3>';
    echo '<table class="widefat fixed striped">';
    echo '<thead>
            <tr>
                <th>' . __('Name', WP_TECH_METRICS_TEXT_DOMAIN) . '</th>
                <th>' . __('Size', WP_TECH_METRICS_TEXT_DOMAIN) . '</th>
                <th>' . __('Path', WP_TECH_METRICS_TEXT_DOMAIN) . '</th>
            </tr>
          </thead>';
    echo '<tbody>';
    foreach ($media_data['largest_files'] as $file) {
        echo '<tr>';
        echo '<td>' . esc_html($file['name']) . '</td>';
        echo '<td style="font-weight: bold;">' . size_format($file['size'], 2) . '</td>';
        echo '<td>' . esc_html($file['path']) . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}

