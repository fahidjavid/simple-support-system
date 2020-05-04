=== Simple Support System ===
Contributors: fahidjavid
Tags: support, envato, api, register, login, shortcode
Requires at least: 3.0
Tested up to: 5.4.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


== Description ==

Simple Support System plugin interacts with Envato API to allow (verified) users registration and get support only with their item purchase codes. So that way, only real buyers will get support in an easy way of your Envato items.

Simple Support System plugin is a very simple and quick plugin to configure and use. It needs you to take a very few setups to setup a fully working support system for your Envato items.

Its main features are as follows:

* Allows your users to be registered on your site with one of their item purchase code.
* Login form, you may add registration and login forms on the same page.
* Interacts with Envato API to display your users (real time) support and purchase information.
* Displays your users purchased items list with appropriate information, e.g Buyer Name, Purchase Date, Purchase Code, Supported or Expired.
* Allows your already registered users to add their new item purchase codes.
* Validation and Verification of your users purchases, so there is no duplicity of purchase codes or users to make support system more efficient.
* Create Ticket form support and it can be configured with any of your email address to receive users requests at.
* Allows your users to create ticket for the only those purchased items which are with valid support period (not expired yet).
* Simple Support System was built to be compatible with 99% of all existing themes, both free and commercial.
* Simple Support System is fully responsive to be viewed and used on any devices.
* It has built with flexibility in mind; the code is clean, easy to understand, WordPress standard approaches, and well documented.


== Installation ==

1. In your WordPress admin go to 'Plugins -> Add' New.
2. Enter Simple Support System in the text box and click Search Plugins.
3. In the list of Plugins click 'Install Now' next to the Simple Support System.
4. Once installed click to 'Activate'.
5. Once plugin is activate, navigate to the 'Dashboard -> SSS Settings' page and add your Envato Token. If you don't have already created it then you can create your Envato Token here: https://build.envato.com/create-token/
   Your token must have permission to "Verify purchases of your items". You can set these permissions while creating your token or you may update those permissions preferences by editing a token permissions.
6. Also provide the MailBox address on the Dashboard -> SSS Settings page to receive tickets (question emails) from the users through create ticket form.
7. Now go to the 'Pages -> Add' New, and create support pages with the following shortcodes.
8. Go and manage your forum :)

Register Verified User form shortcode: <strong>[sss_register_verified_user]</strong> <br>
Login form shortcode: <strong>[sss_login_user]</strong> <br>
List Purchases & Add New Item Purchase Code shortcode: <strong>[sss_list_purchases]</strong> <br>
Create Ticket form Shortcode: <strong>[sss_create_ticket]</strong> <br>


== Screenshots ==

1. After plugin activation go to 'SSS Settings' page and provide Envato API Token & MailBox Address.
2. Use '[sss_register_verified_user]' shortcode to display the 'Verified User Registration' form on your site.
3. Once user get its purchase code verified through verify purchase code form then a registration form will be displayed.
4. To display User Login form use the '[sss_login_user]' shortcode.
5. Use '[sss_list_purchases]' shortcode to display user purchase history and 'Add New Purchase Code' form (user login required).
6. For the Create Ticket form use '[sss_create_ticket]' shortcode. User will be able to create ticket for only items with valid support period (login required). Items with expired support period will not be select until user renew that item support period.

== Frequently Asked Questions ==

= Does this plugin works with all major PHP versions? =

Yes, it works with all major PHP versions and I have tested it with PHP version 5.2, 5.3, 5.4, 5.5, 5.6 and 7.0.


== Changelog ==

= 1.1.0 =

* Tested with WordPress 5.4.1

= 1.0.0 =

* Initial Release