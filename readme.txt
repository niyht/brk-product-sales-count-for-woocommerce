=== BRK Product Sales Count for WooCommerce ===
Contributors: brksoft
Donate link: https://brksoft.com
Tags: woocommerce, sales count, product sales, custom sales, product statistics
Requires at least: 4.0
Tested up to: 6.7
Requires PHP: 7.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

BRK Product Sales Count for WooCommerce displays actual or custom sales counts on product pages to build trust and boost customer engagement.

== Description ==

**BRK Product Sales Count for WooCommerce** is a powerful plugin that allows you to show either real-time or manually configured sales counts on WooCommerce product pages. It helps build trust and urgency among your customers, ultimately increasing conversions.

### Features:
- **Real-Time Sales Count**: Automatically fetch the actual number of products sold based on WooCommerce order data.
- **Custom Sales Count**: Display manually configured random sales counts within a specified range.
- **Visibility Control**: Choose whether the sales count is visible or hidden on the product page.
- **User-Friendly Settings**: Easily configure the settings via a metabox on the product edit page.
- **Transients for Efficiency**: Reduce server load with transient caching for custom sales counts.
- **Localization Ready**: Fully translatable into any language.

### Use Cases:
- Highlight popular products to encourage customers to buy.
- Create a sense of urgency with custom sales counts.
- Build trust by displaying real-time sales data.

= Live demo =

Visit our [live demo](https://sales-count.brksoft.com/product/variation-grey-t-shirt/ "live demo") here to see how this plugin works.

== Installation ==

1. Download the plugin as a `.zip` file.
2. Go to your WordPress dashboard and navigate to `Plugins` > `Add New`.
3. Click the `Upload Plugin` button and select the `.zip` file.
4. Install and activate the plugin.
5. Ensure that WooCommerce is installed and activated.

== Usage ==

1. Navigate to the product edit page in WooCommerce.
2. Locate the **Sales Count Manager** metabox.
3. Configure the following options:
   - **Set Type**: Choose between `Real` (real-time sales) or `Custom` (manually configured sales).
   - **Set Visibility**: Select `Show` or `Hide` to control visibility on the product page.
   - **Minimum and Maximum Numbers**: For custom sales counts, set the range for random generation.
4. Save the product, and the configured sales count will appear on the product page.

== Screenshots ==

1. sales-count-screenshot-1
2. sales-count-screenshot-2
3. sales-count-screenshot-3
4. sales-count-screenshot-4
5. sales-count-screenshot-5
6. sales-count-screenshot-6
7. sales-count-screenshot-7
8. sales-count-screenshot-8
9. sales-count-screenshot-9

== Changelog ==

= 1.0.0 =
* Initial release with core functionality.

== Frequently Asked Questions ==

= Do I need WooCommerce for this plugin to work? =
Yes, this plugin requires WooCommerce to be installed and activated.

= How does the custom sales count work? =
When `Custom` is selected, the plugin generates a random sales count within the specified minimum and maximum range using WordPress transients for efficient caching.

= Can I hide the sales count for specific products? =
Yes, you can choose `Hide` under the **Set Visibility** option in the metabox.

= Does the real-time sales count consider all orders? =
The real-time sales count fetches completed and processing orders for the current date only.

== Upgrade Notice ==

= 1.0.0 =
Upgrade to the latest version for enhanced security and compatibility with the latest WordPress and WooCommerce versions.

== License ==

This plugin is licensed under the GPLv2 or later. For more details, visit [http://www.gnu.org/licenses/gpl-2.0.html](http://www.gnu.org/licenses/gpl-2.0.html).
