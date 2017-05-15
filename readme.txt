=== Admin Color Bar ===
Contributors: serverpress, spectromtech, greggfranklin, davejesch, Steveorevo
Donate link: http://serverpress.com
Tags: admin, admin bar, dashboard, customize, color, local, staging, live
Requires at least: 4.6
Tested up to: 4.7.3
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Configure the color of the admin bar in the WordPress admin area.

== Description ==

This utility plugin provides the ability to configure the color of the admin bar and allows setting a message that will appear within the admin bar. This allows you to more easily identify when you're working on Local, Staging and Live WordPress installations.

<strong>Usage Scenarios:</strong>

The Local Admin Color Bar plugin is useful when working with multiple installs of WordPress, such as when you have a local development environment, a staging site as well as a live site.

By setting the admin bar's background color to Orange for a staging site, and Red for a local site, you can more easily identify which site you're working on. This reduces the possibility that you'll make errors by performing actions on the wrong site.

If you are using DesktopServer for your local development on Windows and would like to use the Local Admin Color Bar plugin as a Design Time plugin, you can install this in your /Applications/XAMPP/ds-plugins directory on Macintosh or the C:\xampplite\ds-plugins\ directory on Windows and it can then be configured to automatically start for all of your local development web sites. This allows you to use the plugin in your local environment, where it's needed and not to deploy it to your live environment where it may not be needed. For more information on DesktopServer and local development tools, please visit our web site at: https://serverpress.com/get-desktopserver/

<strong>How it Works:</strong>

The plugin provides configuration options that allows you to select a background color and text color for the admin bar. You can also select a message that will be displayed within the admin bar to provide additional information to the user.

The configuration settings will be seen by all users on the site. If you are using the plugin in a MultiSite environment, the settings are not shared among all the sites. You will need to configure each site on the network.

<strong>Support:</strong>

><strong>Support Details:</strong> We are happy to provide support and help troubleshoot issues. Visit our Contact page at <a href="http://serverpress.com/contact/">http://serverpress.com/contact/</a>. Users should know however, that we check the WordPress.org support forums once a week on Wednesdays from 6pm to 8pm PST (UTC -8).

ServerPress, LLC is not responsible for any loss of data that may occur as a result of using this tool. We strongly recommend performing a site and database backup before testing and using this tool. However, should you experience such an issue, we want to know about it right away.

We welcome feedback and Pull Requests for this plugin via our public GitHub repository located at: https://github.com/ServerPress/admin-color-bar

== Installation ==

Installation instructions: To install, do the following:

1. From the dashboard of your site, navigate to Plugins --&gt; Add New.
2. Select the "Upload Plugin" button.
3. Click on the "Choose File" button to upload your file.
3. When the Open dialog appears select the admin-color-bar.zip file from your desktop.
4. Follow the on-screen instructions and wait until the upload is complete.
5. When finished, activate the plugin via the prompt. A confirmation message will be displayed.

or, you can upload the files directly to your server.

1. Upload all of the files in `admin-color-bar.zip` to your  `/wp-content/plugins/admin-color-bar` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Screenshots ==

1. Plugin page.

== Changelog ==
= 1.2 - May 15, 2017 =
* Change plugin name. Previous repository: https://github.com/ServerPress/local-admin-color-bar
* Initial release to WordPress repository.

= 1.0.1 - May 10, 2016 =
* Fix plugin name.

= 1.0 - May 15, 2015 =
* Initial Release

== Upgrade Notice ==

= 1.2 =
First release.
