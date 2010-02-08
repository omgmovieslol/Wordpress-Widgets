=== Plugin Name ===
Contributors: sk33t
Donate link: http://ja.meswilson.com
Tags: widget, compete, statistics, stats
Requires at least: 2.0.2
Tested up to: 2.7
Stable tag: trunk

This widget adds a link, your current rank, the amount of visitors, and your rank image to the sidebar.

== Description ==

= Requirements =

* WordPress 2.2 or 2.0.x/2.1.x with WordPress Widgets
* PHP 4.3.0 or greater (needed for function file\_get\_contents)
* <a href="http://developer.compete.com/">API Key from Compete.com</a> (You can use the one supplied, but that is limited to 1000 requests a day)

= Features =

* Displays data about your site or any site you choose
* Edit nearly all aspects of it via widget admin
* Caching for large traffic sites


== Installation ==

1. Download compete-widget.zip
1. Extract and upload compete.php to the plugins/ directory
1. Enable Compete Rankings in the Plugin admin panel
1. In widget admin panel, place Compete in the sidebar, and edit it to enter your API key (optional)

== Frequently Asked Questions ==

= Nothing shows up when I try it or I get an error that file_get_contents doesn't exist =

This script relies on the function file\_get\_contents to fetch the RSS feed. If your host doesn't allow the use of it, but allows the use of curl, you can replace
<pre>$stories = file_get_contents($uri);</pre>
with
<pre>$ch = curl_init();
$timeout = 5; // set to zero for no timeout
curl_setopt ($ch, CURLOPT_URL, $uri);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$stories = curl_exec($ch);
curl_close($ch);</pre>

= What does cache time mean? = 

It's the number of seconds before the content will be requested again. You can set this to be 0 or -1 to not use the caching system. If you edit any of the widgets in the widget admin panel, the cache is cleared.

== Screenshots ==

1. Default Display
2. Admin Panel

== Formatting ==

You can edit ‘Widget Formatting’ to change how the info is displayed. To access the variables, use this formatting:

* %rank% - Rank of the site
* %icon% - The icon relating to your traffic rank (large or small depending on options)
* %count% - People count
* %host% - Hostname used in lookup
* %link% - Link to the compete page for the host
* %compete% - The compete link - Compete.com

The default formatting is:

&lt;span style="float:right;"&gt;&lt;img src="%icon%" alt="%rank%" /&gt;&lt;/span&gt;
&lt;a href="%link%"&gt;%host%&lt;/a&gt;&lt;br /&gt;

Ranking: %rank%&lt;br /&gt;
People: %count%&lt;br /&gt;
%compete%

