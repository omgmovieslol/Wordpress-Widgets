=== Plugin Name ===
Contributors: sk33t
Donate link: http://ja.meswilson.com/donate
Tags: widget, google, google reader, reader
Requires at least: 2.0.2
Tested up to: 2.7.1
Stable tag: trunk

This widget will display your latest shared stories. You can change the amount of stories to display and how to display them.

== Description ==

= Requirements =

* WordPress 2.2+ or 2.0.x/2.1.x with WordPress Widgets
* PHP 4.3.0 or greater (needed for function file\_get\_contents)
* Your Google Reader User ID

= Features =

* Displays your latest shared items from Google Reader
* Completely customizable display
* Caching for large traffic sites


= User ID =

In order to get your shares, you need to know your user id according to Google Reader. This can be found by going to the ‘Shared Items’ link, and copying the 20 digit number at the end of your shared items link.

So something like:
http://www.google.com/reader/shared/02774557510273097991
Where 02774557510273097991 would be my user id.



== Installation ==

1. Download googlereader.zip
1. Extract and upload googlereader.php to the plugins/ directory
1. Enable Google Reader in the Plugin admin panel
1. In widget admin panel, place Google Reader in the sidebar, and edit it to enter your user id

== Frequently Asked Questions ==

= What does cache time mean? = 

It's the number of seconds before the content will be requested again. You can set this to be 0 or -1 to not use the caching system. If you edit any of the widgets in the widget admin panel, the cache is cleared.

= My widget doesn't display any stories = 

This could be for many reasons. First possibilty, you incorrectly entered your <a href="http://ja.meswilson.com/blog/2007/05/26/wordpress-google-reader-widget/#user-id">Google Reader ID</a>. Next, your host might not support file_get_contents or curl. Try changing the use option to curl in the widget admin panel. You could try using some sort of fsockopen if neither curl nor file_get_contents work. If your host doesn't support outside requests, this widget will not work for you.

== Screenshots ==

1. Default Display
2. Admin Panel

== Formatting ==

There are 3 parts needed to format the output.

The first part, called items start in the admin panel, is the first part of the widget after the title. For the default formatting, this is just &lt;ul&gt;.

The second part, called items end, is the ending of the widget. By default, this is:
&lt;/ul&gt;
&lt;a href="%googlereader%" style="float:right;"&gt;Shared Items&lt;/a&gt;

The third part is what is called for each item. By default, this is:
&lt;li style="list-style-type: none;"&gt;&lt;a href="%link%"&gt;%title%&lt;/a&gt;&lt;/li&gt;

The premise of calling each value is this:

1. start
1. item
1. item
1. ...
1. item
1. end

The formatting for items is:

* %link% - Link to the item
* %title% - Title of the item
* %site% - The title of the site the item is from
* %sitelink% - The link to the site the item is from
* %number% - The number of the current item
* %comment% - The comment (note) made when sharing an item

The formatting for start and end is:

* %googlereader% - Link to your Google Reader shared page
* %count% - Number of items shown

