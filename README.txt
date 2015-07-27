=== Plugin Name ===
Contributors: thomasB5
Donate link: #
Requires at least: PHP 5.3
Tested up to: 3.96
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


== Description ==

This is the core of the "Sponsorship" software. This allows blog users to send E-mails to a list of contacts of their choice.

The content of the E-mails are controlled by the admin. They can contain links, html, photos etc. This is meant to be a base to build on! So be creative!

If you are interested, we have some extensions we built ourselves at http://www.wp-inbound.com/wpsponsorship/.


== Installation ==
(If you are unfamiliar with downloading plugins, consult this : https://codex.wordpress.org/Managing_Plugins#Installing_Plugins.)

To get started, the admin must configure the settings on the pages "Mail Format" and "Mail Settings". Once that is done, create

a new page in any menu you like and put the following shortcode:

[Sponsorship sender_submit="Submit info" contact_submit="Submit Contact" email_submit="Send Invitations"].

Each parameter is the text displayed in the submit buttons. Launch as is to get a feel for it, then change the text to your taste.


All emails sent out are saved in the database under *prefix*email_contacts (the emails receivers) and *prefix*email_sender (the email senders). The emails are being sent using 'SwiftMailer', an open source software simplifying the E-mail sending process.


For more information on extensions and documentation go to our website: http://www.wp-inbound.com/.

A full installation and set up guide can be found at http://www.wp-inbound.com/documentation-wpsponsorship/.
