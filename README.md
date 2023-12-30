# News Plugin Documentation
## Introduction
The News Plugin is a WordPress plugin developed for a technical challenge. It integrates with the News API to fetch and display news articles on your WordPress site.

## Features
#### 1. Settings Page:

- Accessible from the WordPress admin menu under "Settings > News API Integration."
- Allows users to enter and save the News API key.
- Provides a dropdown to select a news filter option.

#### 2. Shortcode:
- Includes a shortcode [news_api_integration] to display news articles.
- Retrieves the latest article's content from the News API and displays it.

#### 3. Caching:
- Implements caching to reduce the number of API requests.
- Cached data is stored for a limited time to ensure freshness.

#### 4. Paginated Display:
- Fetches and displays paginated news articles with a specified number of articles per page.

## Installation
1. Download the plugin ZIP file from the [GitHub repository](https://github.com/BetazoDev/News-Plugin.git "GitHub repository") or the provided source.
2. Upload the ZIP file to your WordPress site through the admin dashboard (Plugins > Add New > Upload Plugin).
3. Activate the plugin.

## Usage
#### 1. Activate the Plugin:
- Once activated, the plugin will add a menu item under "Settings" in the WordPress admin.

#### 2. Configure API Key:
- Visit the "News API Integration" settings page.
- Enter your News API key and select a filter option.
- Save the settings.

#### 3. Display News:
- Use the [news_api_integration] shortcode in any post or page.
- This shortcode will display the content of the latest news article.

## Shortcode Parameters
- The [news_api_integration] shortcode does not accept any parameters.

## Customization
- The plugin can be customized by modifying the source code based on specific requirements.

## Troubleshooting
- If there are issues with fetching news articles, check the News API key and ensure it is valid.
- Review error messages displayed on the settings page for guidance.

## Credits
Developed by Humberto Alonso
Powered by News API

## License
This plugin is licensed under the GNU General Public License v2.0.


