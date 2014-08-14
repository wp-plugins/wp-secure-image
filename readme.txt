=== Secure Image Protection ===

Contributors: ArtistScope
Donate link: http://www.artistscope.com/secure_image_protection.asp
Tags: protect, secure, encrypt, image
Requires at least: 3.0.1
Tested up to: 3.9
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Copy protect images. Insert encrypted images with Domain Lock copy protected from right-click mouse-save, page-save, drag-n-drop and site grabbers.
== Description ==

Insert [Secure Image Pro]( http://www.artistscope.com/secure_image_protection.asp) encrypted images to pages and posts from your WordPress page editor that are supported across all web browsers on all operating systems, ie: Windows, Mac and Linux. Hand-held devices that can use Java will also be supported.

* Easy install.
* Upload and embed encrypted images using WordPress native media tools.
* Insert [encrypted images](http://www.artistscope.com/image-encryption.asp) into posts or pages using a media button.
* Images are displayed in a security applet supported on all computers.
* Ability to set varying levels of protection per page or post.
* Control which web browsers can access your protected pages.
* Checks for Java version and redirects for updates and install.
* Manage settings to control image display options. 
* [Domain locked images](http://www.artistscope.com/domain-lock.asp) are safe from spiders and even your Webmaster.
* Page is also protected from right-click and drag-drop save of all media.
* Supported in all web browsers.
* Supported on Windows, Mac, Linux and handheld devices that use Java.

Each page has the option of including Java version check so that if a visitor does not have Java installed, they are redirected with instructions and a download link. Because browser detection is dependent on JavaScript, if a visitor has JavaScript disabled they also will be redirected for instructions on how to correct their browser settings. These support pages are included in the plugin's folder and can be customized to suit your own messages and design.

More information and online demos can be seen at the [Secure Image Protection]( http://www.artistscope.com/secure_image_protection.asp) website. 

You can see this plugin and our other WP [copy protection plugins](http://wordpress.artistscope.com) at our WordPress demo site.


** Implementation **

Click on the [S] media button above a post to upload and embed Secure Image Pro encrypted images into your current post or page. When inserting an encrypted image obj, the necessary shortcode is automatically inserted into the post editor. 

You can upload new image class files or select from a list of already uploaded class files. After selecting an image class file you can then set the security options to apply to the page such as:

* Include Java version detection
* Enable or disable use of the keyboard

For more information visit the [Secure Image](http://www.artistscope.com/secure_image_protection_wordpress_plugin.asp) plugin page at ArtistScope.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the "wp-secure-image" folder and its contents to the "/wp-content/plugins/" directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create a new folder at "/wp-content/uploads/secure-image/"
4. Set write permissions on this new folder
5. Check and modify the default settings to suit your pages
6. You can now add Secure Image Pro images using its media button above the post editor

== Frequently Asked Questions ==

= Which web browsers are supported by this plugin? =

All popular web browsers are supported on all platforms including Windows, Mac and Linux (100% of net users).

= What can be done about Print Screen and screen capture? =

Nothing that will work on all platforms because it’s not possible to control capture without using system level plugins and that is only possible on Windows. However if you are happy to provide support for Windows computers only (92% of net users) then there are several more secure options available (safe from Print Screen and screen capture)  from ArtistScope that are also available as WordPress plugins.

== Screenshots ==

1. To add a Secure Image encrypted image at the last cursor position in the text area, click the [S] media button.
2. After uploading or selecting an existing class image, nominate the settings to apply to the page or post.
3. Here you can nominate the default settings that apply to all Secure Image pages.
4. A file list can be displayed showing all Secure Image class files that have been uploaded.

== Changelog ==

= 1.0 =
* Tested and verified on WorPress version 3.9.2
* Added alternative user check in case session logging not supported by webhost.
* Added settings option to allow uploads by admin only.
* Upload will progress only on same host IP.
* Referrer user agent must be Shockwave Flash
* Referrer url must match with the same script name.
* Save settings page options altered for show in smaller screens.
* No need to click "Insert File to editor" button after Save button clicked.

= 0.9 =
* Improved security to prevent remote execution.
* Simplified Java check. and redirection for download.

= 0.8 =
* Fixed security flaw in upload function.
* Tested and aproved for WordPress 3.9.

= 0.7 =
* Updated applet JAR file to comply with new browser security.
* Addedd new applet parameter for "permissions".

= 0.6 =
* Added detection for Windows 8.1

= 0.5 =
* Removed dependency on wp-load.php

= 0.4 =
* Updated to support JQuery 1.8.

= 0.3 =
* Added Netscape Navigator browser option in default settings.
* Fixed bug with progress bar diisplayed when uploading.

= 0.2 =
* Added parameters to shorcode for editing of existing image inserts.
* Same images can be used on multiple posts with unique settings.
* Revised default settings options.
* Revised functions to comply with CodePlex recommendations.

= 0.1 =
* First release.

== About ==

**Other modules**

Secure Image was specially designed to display encrypted images on web pages for all computers. A most versatile yet sophisticated [image protection](http://www.artistscope.com/protect-images.asp) solution that anyone can use.

* The [Secure Image converter](http://www.artistscope.com/secure_image_protection_gui.asp) is available as Windows desktop software.
* Converter can be [run by Command-line](http://www.artistscope.com/secure_image_protection_cmd.asp) on Windows computers and servers.
* Custom DLLs are available to [interface command-line with web page scripts](http://www.artistscope.com/encrypt_image_uploads.asp).

**Alternatives**

While Secure Image provides the most sophisticated image encryption with domain lock, it cannot provide [protection from Print Screen or screen capture](http://www.artistscope.com/prevent-screen-capture.asp). However other solutions are available from ArtistScope that do provide such protection from all copy including capture, such as [CopySafe Web Protection]( http://www.artistscope.com/copysafe_web_protection.asp) which is specially designed for images, [CopySafe PDF Protector](http://www.artistscope.com/copysafe_pdf_protection.asp) which is specially designed for PDF documents and [ArtistScope Secure Video](http://www.artistscope.com/secure_video_protection.asp) which is specially designed for video file. For online viewing only, the [ArtistScope Site Protection System (ASPS)](http://www.artistscope.com/artis-secure-web-reader.asp) uses a secure web browser that is properly designed to protect web media rather than expose it. Each of these solutions have WordPress plugins or widgets available for easy integration into your WordPress project.

