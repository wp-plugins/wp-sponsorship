=== Plugin Name ===
Contributors: thomasB5, boluge, xbenx
Donate link: #
Requires at least: PHP 5.3, WP 3.9.6
Tested up to: 4.2.3
Tags: sponsorship, spons, or , ship, emails, rewards, program, inboundmarketing, inbound, marketing, traffic, users, codes, pairrainage,b5prod, super, good, plugin, potd, sponsorship, attract, wpponsorship, wsponsorship, wps, inbound marketing,sponsor, sponsors, filleul, wp-inbound.com, wp-inbound, b5, productions, marketing, online, tags, mailing, clients, plugin, money, income, customers, people, spons, wpgmail, wpscode, wpswoocode, woo
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


== Description ==

This is the core of the "Sponsorship" software. This allows blog users to send E-mails to a list of contacts of their choice.

The content of the E-mails are controlled by the admin. They can contain links, html, photos etc. For example, by adding a link to an article in the E-mail body you can create a powerful article sharing page where a visitor can share the link with his E-mail contacts. Another example could be to create a small invitations page where the visitor lists the people to invite and the admin customizes the content.

This is meant to be a base to build on! So be creative!

The content in the E-mail follows a pre-built template (Photo in Screenshots tab). The admin has control over the colors, links and text. 

If you are interested, we have some extensions we built ourselves at http://www.wp-inbound.com/wpsponsorship/.

Before you get started, you will need an SMTP server. Alternatively, you can use your Gmail account as an SMTP server if you wish. For more information check out this link: https://www.digitalocean.com/community/tutorials/how-to-use-google-s-smtp-server.

Other great SMTP hosts are turboSMTP (http://www.serversmtp.com/) and MANDRILL (https://www.mandrill.com/). Set up is quick, easy and free.


== Installation ==
(If you are unfamiliar with downloading plugins, consult this : https://codex.wordpress.org/Managing_Plugins#Installing_Plugins.)

To get started, the admin must configure the settings on the pages "Mail Format" and "Mail Settings". Once that is done, create

a new page in any menu you like and put the following shortcode:

[Sponsorship sender_submit="Submit info" contact_submit="Submit Contact" email_submit="Send Invitations"].

Each parameter is the text displayed in the submit buttons. Launch as is to get a feel for it, then change the text to your taste.


All emails sent out are saved in the database under *prefix*email_contacts (the emails receivers) and *prefix*email_sender (the email senders). The emails are being sent using 'SwiftMailer', an open source software simplifying the E-mail sending process.


For more information on extensions and documentation go to our website: http://www.wp-inbound.com/.

A full installation and set up guide can be found at http://www.wp-inbound.com/documentation-wpsponsorship/.

If there are any bugs please report them on www.wp-inbound.com on the contact page and we will fix it ASAP!

== Changelog ==

= 1.0.0 =
* A version without E-mail templates. Use this if you wish to create your own templates


= 1.0.1 =
* This uses a static E-mail template where you modify the colors and text. Use this version for beautiful E-mails off the bat.
*bug fixes


== Frequently Asked Questions ==

= Which SMTP Server Should I use? =

Bottom line is that it really doesn’t matter. It is personal preference. (I used Turbo SMTP when I tested the plugin.)

= How do I change the CSS for the shortcode page? =

All the styling is in the following path “plug-in-folder/public/css/sponsorship-public.css”. Add any CSS in that file. (Same format for all other extensions as well)

= What if I already have my meta tags configured? =

Simply leave it Blank! But make sure you configure the share URL properly.

= How do I make a project With Google? =

First you have to have a Google account (Gmail, youtube etc). Then search “google developers” and click the first link which should be to Google Developers Console. From there, simply follow the instructions and you are good to go!

= I keep Getting a “URL Mismatch” when using the Import Gmail button. What is wrong? =

This error happens when the url on the Google project page does not match the link in the “Google Config” page. I got this error when testing and it seemed that the “/” at the end of the URL had an effect. Make sure either both do not have it, or both have it.

== Screenshots ==

1. This is what the pre-built template looks like. If you prefer to build your own template, download version 1.0.0.
