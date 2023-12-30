News Plugin Documentation
Introduction
The News Plugin is a WordPress plugin developed for a technical challenge. It integrates with the News API to fetch and display news articles on your WordPress site.

Features
Settings Page:

Accessible from the WordPress admin menu under "Settings > News API Integration."
Allows users to enter and save the News API key.
Provides a dropdown to select a news filter option.
Shortcode:

Includes a shortcode [news_api_integration] to display news articles.
Retrieves the latest article's content from the News API and displays it.
Caching:

Implements caching to reduce the number of API requests.
Cached data is stored for a limited time to ensure freshness.
Paginated Display:

Fetches and displays paginated news articles with a specified number of articles per page.
Installation
Download the plugin ZIP file from the GitHub repository or the provided source.
Upload the ZIP file to your WordPress site through the admin dashboard (Plugins > Add New > Upload Plugin).
Activate the plugin.
Usage
Activate the Plugin:

Once activated, the plugin will add a menu item under "Settings" in the WordPress admin.
Configure API Key:

Visit the "News API Integration" settings page.
Enter your News API key and select a filter option.
Save the settings.
Display News:

Use the [news_api_integration] shortcode in any post or page.
This shortcode will display the content of the latest news article.
Shortcode Parameters
The [news_api_integration] shortcode does not accept any parameters.
Customization
The plugin can be customized by modifying the source code based on specific requirements.
Troubleshooting
If there are issues with fetching news articles, check the News API key and ensure it is valid.
Review error messages displayed on the settings page for guidance.
Credits
Developed by [Your Name]
Powered by News API
