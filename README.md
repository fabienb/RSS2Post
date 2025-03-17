# RSS Feed to WP Post

![Latest Version](https://img.shields.io/badge/release-v1.4-orange)
[![WordPress Version](https://img.shields.io/badge/wordpress-%3E%3D6.5-00749c)](https://wordpress.org/)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.0-8892BF.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0.html)

## Description

This WordPress plugin automatically creates and publishes posts from one or more RSS feeds. It's ideal for aggregating content from external sources, such as blogs, small news sites, or YouTube channels. The plugin is designed to be lightweight and easy to configure.

**I am happy to release this plugin for FREE. But if this is helpful to you in any way, please consider donating via [Paypal](https://paypal.me/fabienbutazzi) or use the Sponsor links in the sidebar to support this work and future enhancements.**

## Features

*   **Multiple Feed Support:** Import posts from multiple RSS/Atom feeds simultaneously.
*   **Post Type & Status Control:** Define the WordPress post type (e.g., `post`, `page`) and status (`publish`, `draft`, `pending`) for imported posts.
*   **Import Limit:**  Control the number of posts imported from each feed per scheduled run to prevent overwhelming your site.
*   **Error Logging:** Logs errors to the WordPress debug log for easy troubleshooting.
*   **Scheduled Imports:** Automatically imports new content on a configurable schedule (hourly by default).
*   **Duplicate Post Prevention:** Checks if a post with the same title already exists before creating a new one.

## Installation

Copy the raw PHP code you find in snippet.php and add it to your WordPress. This can be done in 2 different ways: 
- editing the functions.php file in your theme, which I would never recommend unless you are using a child theme; 
- adding this code into a snippets manager plugin.

My favourite snippets manager is Fluent Snippets. I do not have any affiliation with them, it's simply the plugin I use because it's free and does not bloat the database but keeps all snippets in separate external files (easier to manage, backup and transfer and also much better performance). Whatever your preference, this snippet works in whatever plugin you are using.

Of course, *always make a backup copy of your current WordPress before making any changes*.

## Requirements

There are no particular requirements, but this has been tested in the following conditions:
- WordPress 5.8 and higher
- PHP 8.1 and higher

## Dependencies

- WordPress, obviously
- A WordPress plugin to manage snippets (I use FluentSnippets, see above) or using a child theme where you can modify the file `functions.php`.

## Configuration

The plugin is configured using constants defined within the `rss-to-post.php` file. You'll need to edit this file directly to adjust the settings:

*   **`RSS_FEED_URLS`**:  An array of RSS feed URLs. Add or remove URLs as needed.
    ```php
    const RSS_FEED_URLS = [
        'https://example.com/rss1.xml', // Example: YouTube channel feed
        'https://example.com/rss2.xml',
        // Add more feeds here...
    ];
    ```

*   **`POST_TYPE`**: The WordPress post type to use for imported posts (default: `'post'`).
*   **`POST_STATUS`**:  The initial status of the imported posts (default: `'draft'`). Options include `'publish'`, `'pending'`, etc.
*   **`IMPORT_LIMIT`**: The maximum number of posts to import from each feed per scheduled run (default: `4`). Set to `0` for unlimited imports (not recommended for high-volume feeds).

## Scheduling

The plugin uses WordPress's built-in scheduling system (`wp_schedule_event`) to automatically fetch and import content. By default, it runs hourly.  You can adjust the schedule by modifying the arguments in the `wp_schedule_event` function within the plugin code (advanced users only).

## Troubleshooting

*   **Check the WordPress debug log:**  Enable `WP_DEBUG` in your `wp-config.php` file to see detailed error messages.
*   **Verify Feed URLs:** Ensure that the RSS feed URLs are correct and accessible.
*   **Permissions:** Make sure the web server has permission to access external resources (RSS feeds).

## Important Considerations

This is a light self-contained plugin presented as a snippet. I coded this to grab the content from my other feeds, but they are updated only a handful of times per week. 
As such this plugin has its limitations and is not intended for labour-intensive tasks.

*   **Performance:** Fetching and processing multiple RSS feeds can be resource-intensive. Consider adding caching mechanisms or optimizing your code if you're dealing with many feeds or large feeds. 
*   **Scheduling:** The `wp_schedule_event` is scheduled hourly, so it will iterate through all the feeds on each schedule run. If you have a very large number of feeds, this could become problematic. You might need to explore more advanced scheduling options (e.g., using a custom cron job) or stagger the feed imports. 

## Contributing

Contributions are welcome! Please fork this repository and submit a pull request with your changes.

## License

This plugin is licensed under the [GNU General Public License v2 or later](LICENSE).

## Support

For support, please open an issue on [GitHub](https://github.com/fabienb/RSS2Post/issues).
