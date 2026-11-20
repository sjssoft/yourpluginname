# yourpluginname

A clean, lightweight WordPress plugin starter boilerplate built for university workshops.

## Features Included
- **Security Guards:** `ABSPATH` checks, nonces, capability checks, and output escaping.
- **Custom Post Type:** `student_project` (`Dashboard -> Projects`) with Gutenberg support.
- **Shortcode:** `[student_portfolio count="3"]` to display projects anywhere.
- **Settings API:** Admin options page under `Settings -> YourPluginName`.
- **Asset Enqueuing & AJAX:** Proper `wp_enqueue_style`, `wp_enqueue_script`, `wp_localize_script`, and Vanilla JS `fetch` AJAX like counter.
- **Clean Uninstall:** Removes saved plugin options via `uninstall.php`.

## Quick Setup
1. Copy or clone the `yourpluginname` folder into `wp-content/plugins/`.
2. Activate **YourPluginName** in WordPress Admin.
3. Add a few items under **Projects -> Add New**.
4. Create a Page and insert the `[student_portfolio]` shortcode.