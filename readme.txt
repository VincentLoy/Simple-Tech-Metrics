=== WP Tech Metrics ===
Contributors: Vincent Loy  
Tags: WordPress, metrics, technical, plugins, themes, media, database, system  
Requires at least: 6.7.1  
Tested up to: 6.7.1  
Requires PHP: 7.4  
Stable tag: 1.0.0  
License: GPLv2 or later  
License URI: https://www.gnu.org/licenses/gpl-2.0.html  

A WordPress plugin to display detailed technical metrics about your site, including plugins, themes, media, database, and system information.

== Description ==

**WP Tech Metrics** provides a centralized dashboard with technical insights about your WordPress site. Designed for developers and administrators, this plugin helps you understand your site’s structure and optimize its performance.

This plugin is **open-source** and welcomes contributions! Whether you’d like to propose new features, submit translations, or report issues, visit our [GitHub repository](https://github.com/VincentLoy/WP-Tech-Metrics) to get involved.

== Features ==

### Plugins
- Lists all plugins with details such as name, version, size, status (active/inactive), and whether updates are available.

### Themes
- Displays all installed themes with details such as name, version, status (active/inactive), size, and last update date.

### Media
- Summarizes the uploads folder with total size and file count.
- Highlights the largest files with their sizes and file paths.

### Database
- Shows detailed database metrics, including the size of each table, number of rows, and total database size.
- Includes information on the last update time for each table.

### System Information
- Provides system metrics such as:
  - PHP version
  - Memory limits
  - Maximum upload size
  - Server type (e.g., Apache, Nginx)
  - WordPress version
  - Disk space usage (total and available)

### Export Functionality
- Export metrics for each section (plugins, themes, media, database, system) as individual CSV files.
- Export all collected metrics into a single comprehensive CSV file.

### Multilingual Ready
- Fully compatible with WordPress translation standards, allowing you to translate all plugin strings using `.po` and `.mo` files.

== Installation ==

1. Download the plugin zip file.
2. Upload it to your WordPress site's `/wp-content/plugins/` directory.
3. Activate the plugin through the "Plugins" menu in WordPress.
4. Access the "Tech Metrics" menu in your WordPress admin dashboard to view your site's metrics.

== Frequently Asked Questions ==

= What versions of WordPress and PHP does this plugin support? =
This plugin requires at least WordPress version 6.7.1 and PHP version 7.4.

= Can I export the data? =
Yes! Each section has a dedicated export button to download metrics as a CSV file. There's also an option to export all metrics together.

= How do I translate the plugin? =
Place your `.mo` and `.po` files in the `languages` folder of the plugin. Follow WordPress localization standards for translations.

== Managing and Compiling Translations ==

Refer to the "Managing and Compiling Translations" section in the documentation for detailed instructions on adding, editing, and compiling translations.

== Contribute ==

We welcome contributions to WP Tech Metrics! Here's how you can help:
- **Suggest Features:** Share your ideas for improving the plugin.
- **Submit Translations:** Help us expand language support by contributing `.po` and `.mo` files.
- **Report Issues:** Encountered a bug? Let us know!

Visit our [GitHub repository](https://github.com/VincentLoy/WP-Tech-Metrics) to contribute, collaborate, or report issues.

== Changelog ==

= 1.0.0 =
* Initial release.
* Provides metrics for plugins, themes, media, database, and system.
* Export functionality for individual or all metrics as CSV.
* Multilingual support.

== Upgrade Notice ==

= 1.0.0 =
Initial release. Compatible with WordPress 6.7.1 and above.
