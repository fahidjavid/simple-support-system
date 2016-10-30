=== Simple Support System ===
Contributors: fahidjavid
Tags: support, envato, api, register, login, shortcode
Requires at least: 3.0
Tested up to: 4.6.1
Stable tag: 4.6.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Simple Support System plugin interacts with Envato API to allow (verified) users registration only with their item purchase codes. So, only real buyers will get support for the purchased items from the items author.

== Installation ==

Instructions for installing the Simple Support System Plugin.

1. In your WordPress admin go to Plugins -> Add New.
2. Enter Simple Support System in the text box and click Search Plugins.
3. In the list of Plugins click Install Now next to the Simple Support System.
4. Once installed click to Activate.
5. Once plugin is activate, navigate to the Dashboard -> SSS Settings page and add your Envato Token. If you don't have already created it then you can create your Envato Token here: https://build.envato.com/create-token/
   Your token must have permission to "Verify purchases of your items". You can set these permissions while creating your token or you may update those permissions preferences by editing a token permissions.
6. Also provide the MailBox address on the Dashboard -> SSS Settings page to receive tickets (question emails) from the users through create ticket form.
6. Now go to the Pages -> Add New, and create support pages with the following shortcodes.

Register Verified User form shortcode: [sss_register_verified_user]
Login form Shortcode: [sss_login_user]
List Purchases & Add New Item Purchase code shortcode: [sss_list_purchases]
Create Ticket form Shortcode: [sss_create_ticket]

== Frequently Asked Questions ==

= Does this plugin works with all major PHP versions? =

Yes, it works with all major PHP versions and I have tested it with PHP version 5.2, 5.3, 5.4, 5.5, 5.6 and 7.0.

== Changelog ==

= 1.0.0 =

* Initial Release