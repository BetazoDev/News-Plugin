<?php
/*
    Plugin Name: News Plugin
    Plugin URI:
    Description: Plugin for a technical challenge
    Version: 0.01
*/


// NewsPlugin.php

// Plugin activation hook
register_activation_hook(__FILE__, 'news_api_integration_activate');

function news_api_integration_activate() {
    // Perform activation tasks
}

// Plugin deactivation hook
register_deactivation_hook(__FILE__, 'news_api_integration_deactivate');

function news_api_integration_deactivate() {
    // Perform deactivation tasks
}

// Add menu item to the admin menu
add_action('admin_menu', 'news_api_integration_menu');

function news_api_integration_menu() {
    add_options_page('News API Integration Settings', 'News API Integration', 'manage_options', 'news-api-settings', 'news_api_integration_settings_page');
}

// Function to display the plugin settings page
function news_api_integration_settings_page() {
    ?>
    <div class="wrap">
        <h1>News API Integration Settings</h1>

        <form method="post" action="options.php">
            <?php settings_fields('news_api_integration_settings_group'); ?>
            <?php do_settings_sections('news_api_integration_settings_page'); ?>
            <?php submit_button(); ?>
        </form>

        <?php
        // Display the saved API key
        $saved_api_key = get_option('news_api_key');
        if (!empty($saved_api_key)) {
            echo '<p>Saved API Key: ' . esc_html($saved_api_key) . '</p>';

            // Fetch and display paginated news articles
            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $filter = get_option('news_api_filter', 'top-headlines');
            fetch_and_display_paginated_news($page, $filter);

            // Display shortcode usage information
            echo '<p>Use the following shortcode to display news articles on any post or page:</p>';
            echo '<code>[news_api_integration]</code>';
        }
        ?>
    </div>
    <?php
}

// Function to fetch and display paginated news articles with caching and filters
function fetch_and_display_paginated_news($page, $filter = 'top-headlines') {
    $api_key = get_option('news_api_key');
    $articles_per_page = 10; // Adjusted to display 10 articles per page
    $offset = max(0, ($page - 1) * $articles_per_page); // Ensure offset is not negative
    $page = max(1, $page); // Ensure page is not less than 1
    $cache_key = "news_api_articles_${filter}_${page}";

    // Check if cached data exists
    $cached_data = get_transient($cache_key);

    if ($cached_data) {
        // Display cached data
        echo $cached_data;
    } else {
        // Fetch data from the API based on the filter
        switch ($filter) {
            case 'top-headlines':
                $api_url = "https://newsapi.org/v2/top-headlines?country=us&apiKey=$api_key&page=$page&pageSize=$articles_per_page";
                break;
            case 'everything':
                $api_url = "https://newsapi.org/v2/everything?q=bitcoin&apiKey=$api_key&page=$page&pageSize=$articles_per_page";
                break;
            case 'techcrunch':
                $api_url = "https://newsapi.org/v2/top-headlines?sources=techcrunch&apiKey=$api_key&page=$page&pageSize=$articles_per_page";
                break;
            case 'business':
                $api_url = "https://newsapi.org/v2/top-headlines?country=us&category=business&apiKey=$api_key&page=$page&pageSize=$articles_per_page";
                break;
            case 'tesla':
                $api_url = "https://newsapi.org/v2/everything?q=tesla&from=2023-11-30&sortBy=publishedAt&apiKey=$api_key&page=$page&pageSize=$articles_per_page";
                break;
            case 'apple':
                $api_url = "https://newsapi.org/v2/everything?q=apple&from=2023-12-29&to=2023-12-29&sortBy=popularity&apiKey=$api_key&page=$page&pageSize=$articles_per_page";
                break;
            default:
                $api_url = "https://newsapi.org/v2/top-headlines?country=us&apiKey=$api_key&page=$page&pageSize=$articles_per_page";
        }

        // Add User-Agent header to API request
        $api_headers = array('headers' => array('User-Agent' => 'Your-App-Name'));
        $response = wp_remote_get($api_url, $api_headers);

        if (!is_wp_error($response)) {
            $data = json_decode(wp_remote_retrieve_body($response), true);

            // Check if 'status' key is present and has value 'ok'
            if (isset($data['status']) && $data['status'] === 'ok') {
                // Delete old transient cache
                delete_transient($cache_key);

                // Display news articles
                ob_start();
                echo '<h2>News Articles (Page ' . esc_html($page) . ')</h2>';
                echo '<ul>';
                foreach ($data['articles'] as $article) {
                    // Check if 'url' and 'title' keys are present
                    if (isset($article['url']) && isset($article['title'])) {
                        echo '<li>';
                        echo '<h3><a href="' . esc_url($article['url']) . '" target="_blank">' . esc_html($article['title']) . '</a></h3>';
                        echo '<p>Source: ' . esc_html($article['source']['name']) . '</p>';
                        echo '<p>Published on: ' . esc_html($article['publishedAt']) . '</p>';
                        echo '<p>' . esc_html($article['description']) . '</p>';
                        echo '</li>';
                    }
                }
                echo '</ul>';

                // Pagination links
                $total_pages = ceil($data['totalResults'] / $articles_per_page);
                echo '<div class="pagination">';
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo '<a href="?page=' . esc_attr($i) . '">' . esc_html($i) . '</a>';
                }
                echo '</div>';

                // Cache the output for 1 hour (adjust as needed)
                set_transient($cache_key, ob_get_flush(), 1 * HOUR_IN_SECONDS);
            } else {
                echo '<p>Error: ' . esc_html($data['message']) . '</p>';
            }
        } else {
            echo '<p>Error fetching news articles. ' . esc_html($response->get_error_message()) . '</p>';
        }
    }
}

// Function to register plugin settings
function news_api_integration_register_settings() {
    register_setting('news_api_integration_settings_group', 'news_api_key');
    register_setting('news_api_integration_settings_group', 'news_api_filter');
}

// Function to add sections and fields to the settings page
function news_api_integration_settings_init() {
    add_settings_section('news_api_integration_main_section', 'Main Settings', '__return_empty_string', 'news_api_integration_settings_page');

    add_settings_field('news_api_key', 'News API Key', 'news_api_integration_render_api_key_field', 'news_api_integration_settings_page', 'news_api_integration_main_section');
    add_settings_field('news_api_filter', 'Filter', 'news_api_integration_render_filter_field', 'news_api_integration_settings_page', 'news_api_integration_main_section');
}

// Functions to render individual fields
function news_api_integration_render_api_key_field() {
    $api_key = get_option('news_api_key');
    echo "<input type='text' name='news_api_key' value='$api_key' />";
}

function news_api_integration_render_filter_field() {
    $filter = get_option('news_api_filter', 'top-headlines');
    echo "<select name='news_api_filter'>
            <option value='top-headlines' " . selected($filter, 'top-headlines', false) . ">Top Headlines</option>
            <option value='everything' " . selected($filter, 'everything', false) . ">Everything (WSJ)</option>
            <option value='techcrunch' " . selected($filter, 'techcrunch', false) . ">TechCrunch</option>
            <option value='business' " . selected($filter, 'business', false) . ">Business (US)</option>
            <option value='tesla' " . selected($filter, 'tesla', false) . ">Tesla News</option>
            <option value='apple' " . selected($filter, 'apple', false) . ">Apple News</option>
          </select>";
}

// Hook into the settings API
add_action('admin_init', 'news_api_integration_register_settings');
add_action('admin_init', 'news_api_integration_settings_init');

// Shortcode to display news articles
function news_api_integration_shortcode() {
    $api_key = get_option('news_api_key');
    $api_url = "https://newsapi.org/v2/top-headlines?country=us&apiKey=$api_key&page=1&pageSize=1";

    // Add the "User-Agent" header
    $headers = array(
        'User-Agent' => 'news-plugin',
    );

    $response = wp_remote_get($api_url, array('headers' => $headers));

    if (!is_wp_error($response)) {
        $data = json_decode(wp_remote_retrieve_body($response), true);

        if (isset($data['articles']) && is_array($data['articles'])) {
            if (!empty($data['articles'])) {
                $first_article = reset($data['articles']); // Get the first article
                $content = isset($first_article['content']) ? $first_article['content'] : '';

                return $content;
            } else {
                return '<p>No articles found in the response.</p>';
            }
        } else {
            return '<p>Error: The "articles" key is missing or not an array.</p>';
        }
    } else {
        return '<p>Error fetching news articles. Please check your API key and try again.</p>';
    }
}

add_shortcode('news_api_integration', 'news_api_integration_shortcode');
