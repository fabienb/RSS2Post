<?php
/**
 * Plugin Name: RSS2Post
 * Plugin URI: https://fabienb.blog
 * Description: This plugin automatically creates and publishes posts on WordPress from multiple RSS feeds.
 * Version: 1.4
 * Author: Fabien Butazzi, improved and validated by Qwen 2.5 Coder 32B and Gemma 3 27B
 * Author URI: https://fabienb.blog
 * Text Domain: fabienb
 */

declare(strict_types=1);

// Define constants for the RSS feed URLs, post type, status, and import limit
const POST_TYPE     = 'post';
const POST_STATUS   = 'draft';
const IMPORT_LIMIT  = 4;
const RSS_FEED_URLS = [
    'https://example.com/rss1.xml', // Replace with your first RSS feed URL
		'https://example.com/rss2.xml', // Add more URLs as needed
		'https://example.com/rss3.xml',
];

/**
 * Class FabRssImporter - Encapsulates the plugin's functionality.
 */
class FabRssImporter {

	public function __construct() {
		add_action('fab_fetch_rss_feed', [$this, 'importFromAllFeeds']); // Changed action to handle multiple feeds
		register_deactivation_hook(__FILE__, [$this, 'deactivatePlugin']);
		$this->scheduleEvent();
	}

    /**
     * Imports posts from all RSS feed URLs.
     *
     * @return void
     */
    public function importFromAllFeeds(): void {
        foreach (RSS_FEED_URLS as $feedUrl) {
            try {
                $this->importFeed($feedUrl); // Call a separate method to handle each feed.
            } catch (\Exception $e) {
                error_log('RSS Feed to WP Post: Error importing from ' . $feedUrl . ' - ' . $e->getMessage());
            }
        }
    }

	/**
	 * Fetches and imports items from a single RSS feed as WordPress posts.
	 *
	 * @param string $feedUrl The URL of the RSS feed to import from.
	 * @return void
	 */
	private function importFeed(string $feedUrl): void {
		$feed = fetch_feed($feedUrl);

		if (is_wp_error($feed)) {
			throw new \Exception('Error fetching feed: ' . $feed->get_error_message());
		}

		$items = $feed->get_items();
		$importCount = 0;

		foreach ($items as $item) {
            // Check import limit per feed.  Reset count for each new feed.
			if (IMPORT_LIMIT > 0 && $importCount >= IMPORT_LIMIT) {
				break;
			}

			$postId = post_exists(wp_strip_all_tags($item->get_title()));

			if (!$postId) {
				$postData = [
					'post_title'   => wp_strip_all_tags($item->get_title()),
					'post_content' => wp_kses_post($item->get_content()),
					'post_status'  => POST_STATUS,
					'post_type'    => POST_TYPE,
				];

				$newPostId = wp_insert_post($postData);

				if (is_wp_error($newPostId)) {
					throw new \Exception('Error inserting post: ' . $newPostId->get_error_message());
				} else {
					$importCount++;
					error_log('RSS Feed to WP Post: Imported post ID ' . $newPostId . ' from ' . $feedUrl);
				}
			}
		}

		error_log('RSS Feed to WP Post: Total posts imported from ' . $feedUrl . ': ' . $importCount);
	}


	/**
	 * Schedule the RSS feed import event if it's not already scheduled.
	 */
	private function scheduleEvent(): void {
		if (!wp_next_scheduled('fab_fetch_rss_feed')) {
			wp_schedule_event(time(), 'hourly', 'fab_fetch_rss_feed');
		}
	}

	/**
	 * Deactivate the plugin and clear the scheduled event.
	 */
	public function deactivatePlugin(): void {
		wp_clear_scheduled_hook('fab_fetch_rss_feed');
	}
}


// Instantiate the class to start the plugin
new FabRssImporter();
