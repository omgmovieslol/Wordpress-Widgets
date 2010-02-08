=== Plugin Name ===
Contributors: sk33t
Donate link: http://ja.meswilson.com/blog/
Tags: widget, weather, weather.com
Requires at least: 2.0.2
Tested up to: 2.8.1
Stable tag: trunk

This widget displays the current condition, temperature, and the feels like temperature. It uses weather.com’s xoap api to retrieve the information.

== Description ==

= Requirements =

* WordPress 2.2 or 2.0.x/2.1.x with WordPress Widgets
* PHP 4.3.0 or greater (needed for function file\_get\_contents)


= Location =

The location can either be a zip code for US users or the locid. To find the locid, you can do a search using http://xoap.weather.com/search/search?where=<b>[search parameters]</b>.This will return an XML file with search results and their corresponding locid’s.

You can also find it using the Weather.com website. After searching for and selecting your city, you will be sent to a url that will look something like, http://www.weather.com/outlook/driving/interstate/local/<b>USTX0617</b>?from=search_city, where <b>USTX0617</b> is the locid.

= Image Hosting =

I’ve provided the images used and provided by weather.com <a href="http://nothingoutoftheordinary.com/weatherimages.tar.gz">here</a>. If you want to host your own images, extract and upload the files, then, in the admin panel, edit the ‘Image Location’ field to point to your directory of images. Make sure to have the trailing slash.

If you don’t use your own images, you are free to use the ones that I’ve hosted, which are located at http://nothingoutoftheordinary.com/weatherimages/


== Installation ==

1. Download weather-widget.zip
1. Extract and upload weather.php to the plugins/ directory
1. Enable Weather Widget in the Plugin admin panel
1. In widget admin panel, place Weather in the sidebar, and edit it to enter your location

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

* %loc% - Location
* %temp% - Current temperature
* %feels% - Feels like temperature
* %icon% - The icon referring to the current weather. This is just a number
* %iconhref% - The location of the image with your set image location at the front
* %attribution% - The link supposedly required to be displayed. Weather data provided by weather.com®

The default formatting is:

&lt;img src="%iconhref%" alt="%cond%" style="float:right;" /&gt;
%loc%&lt;br /&gt;
&lt;span style="font-size: 150%;"&gt;%temp% °F&lt;/span&gt;
(%feels% °F)&lt;br /&gt;
&lt;span style="font-size: 65%; float: right;"&gt;%attribution%&lt;/a&gt;
