<?php
/*
Plugin Name: Google Reader widget
Plugin URI: http://ja.meswilson.com/blog/2007/05/26/wordpress-google-reader-widget/
Description: Adds a sidebar widget to show your latest shared stories.
Author: James Wilson
Version: 1.9
Author URI: http://ja.meswilson.com/blog/
*/

/*
Changes:
1.9 - Updated my info
1.8 - Added support for user comments. By underlog@underlog.org. If you 'Share with a note', they will be displayed. Use %comment%
1.7 - Fixed errors created by tagging support. 
1.6 - Added tagging support
1.5 - It's still $after_widget. Thanks Ryan
1.4 - Force $userid to be numeric, and it's %site% not %site
1.3 - It's $after_widget, not $afterwidget. Thanks Eric
1.2 - Fixed &amp; problem. 
1.1 - Added %site% and %sitelink% options
1.0 - Original
*/

// _Very_ largely based on the gsearch widget by Automattic

// Put functions into one big function we'll call at the plugins_loaded
// action. This ensures that all required plugin functions are defined.
function widget_googlereader_init() {

	// Check for the required plugin functions. This will prevent fatal
	// errors occurring when you deactivate the dynamic-sidebar plugin.
	if ( !function_exists('register_sidebar_widget') )
		return;

	// This is the function that outputs our little Google search form.
	function widget_googlereader($args) {
		
		// $args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys. Default tags: li and h2.
		extract($args);

		// Each widget can store its own options. We keep strings here.
		$options = get_option('widget_googlereader');
		$userid = preg_replace("/[^0-9]+/ix", "", $options['userid']);
		$lastcheck = $options['lastcheck'];
		$length = $options['length'];
		$tag=$options['tag'];
		$cachetime = ($options['cachetime'] != "" AND is_int($options['cachetime'])) ? $options['cachetime'] : 3600;
		$widgettitle = ($options['title'] != "") ? $before_title.$options['title'].$after_title : "";  // ($options['title'] != "") ? $options['title'] : "googlereader Rankings";
		if(time() - $cachetime < $lastcheck AND $options['cache'] != "") {
			echo $before_widget.$widgettitle.$options['cache'].$after_widget;
		}
		elseif( $userid != "") {
			// These lines generate our output. Widgets can be very complex
			// but as you can see here, they can also be very, very simple.
			//echo $before_widget . $before_title . $title . $after_title;
			$uri = "http://www.google.com/reader/public/atom/user/$userid".( ($tag != "") ? "/label/$tag" : "/state/com.google/broadcast")."?n=$length";
			$googlereader = "http://www.google.com/reader/shared".( ($tag != "") ? "/user" : "")."/$userid".( ($tag != "") ? "/label/$tag": "");
			//echo $uri;
			$stories = file_get_contents($uri);
			
			// I'm not using any XML parsing thing, because there's no guarantee of them being installed, and string parsing is just as easy in this case
			
			if($stories != "") {
				$stories = str_replace("&amp;", "&", $stories);
				$stories = preg_replace("/&(?!(amp|[#0-9]+|lt|gt|quot|copy|nbsp);)/ix","&amp;",$stories);
				$itemformatting = $options['item'];
				$items = "";
				$count = 0;
				// Go through all of the stories
				while(strpos($stories,"<entry") !== FALSE AND $count < $length) {
					$start = strpos($stories,"<entry");
					$stop = strpos($stories,"</entry>");
					$entry = substr($stories, $start, $stop-$start);
					
					//echo $entry;
					//echo "<br /><br /><br />";
					
					// Title
					$start = strpos($entry,"<title");
					$start = strpos($entry,">",$start) + 1;
					$title = substr($entry, $start, strpos($entry,"</title>")-$start);
					
					// Link
					$start = strpos($entry, "<link");
					$start = strpos($entry, "href=\"",$start) + 6;
					$link = substr($entry, $start, strpos($entry, '"', $start)-$start);
					
					// Comment hack, underlog@underlog.org 
					preg_match('#\<gr:annotation\>\<content type="html"\>(.+)\</content\>.*\</gr:annotation\>#', $entry, $matches);
					$comment = $matches[1];
					// echo $comment;

					// Site title
					$start = strpos($entry, "<title", $start);
					$start = strpos($entry, ">", $start) + 1;
					$site = substr($entry, $start, strpos($entry, "</title>", $start)- $start);
					
					// Site link
					$start = strpos($entry, "<link", $start);
					$start = strpos($entry, "href=\"",$start) + 6;
					$sitelink = substr($entry, $start, strpos($entry, '"', $start)-$start);

					$stop += 7;
					$stories = substr($stories,$stop);
					
					$items .= str_replace(array ( "%title%", "%link%", "%number%", "%site%", "%sitelink%", "%comment%" ), 
					array( $title, $link, $count+1, $site, $sitelink, $comment ), $itemformatting);
					$count ++;
				}
				
			}
			
			$startformatting = $options['start'];
			$start = str_replace( array( "%count%", "%googlereader%") , array( $count, $googlereader), $startformatting);
			
			$endformatting = $options['end'];
			$end = str_replace( array( "%count%", "%googlereader%") , array( $count, $googlereader), $endformatting);
			
			$content = $start.$items.$end;
			
			$options['lastcheck'] = time();
			$options['cache'] = $content;
			update_option('widget_googlereader',$options);
			
			echo $before_widget . $widgettitle;
			echo $content;
			echo $after_widget;
		}
	}

	// This is the function that outputs the form to let the users edit
	// the widget's title. It's an optional feature that users cry for.
	function widget_googlereader_control() {

		// Get our options and see if we're handling a form submission.
		$options = get_option('widget_googlereader');
		if ( !is_array($options) )
			$options = array('title'=>'Google Reader Shared Items', 
				'cache'=> '',
				'lastcheck' => 0,
				'userid' => '02774557510273097991',
				'start' => '<ul>',
				'end' => '</ul>'."\n".'<a href="%googlereader%" style="float:right;">Shared Items</a>',
				'item' => '<li style="list-style-type: none;"><a href="%link%">%title%</a> (<a href="%sitelink%">%site%</a>)</li>',
				'length' => '5',
				'tag' => '',
				'cachetime' => '3600'
			);
		if ( $_POST['googlereader-submit'] ) {

			// Remember to sanitize and format use input appropriately.
			$options['title'] = strip_tags(stripslashes($_POST['googlereader-title']));
			$options['userid'] = strip_tags(stripslashes($_POST['googlereader-userid']));
			//$options['cache'] = strip_tags(stripslashes($_POST['googlereader-cache']));
			$options['cachetime'] = strip_tags(stripslashes($_POST['googlereader-cachetime']));
			$options['start'] = stripslashes($_POST['googlereader-start']);
			$options['end'] = stripslashes($_POST['googlereader-end']);
			$options['item'] = stripslashes($_POST['googlereader-item']);
			$options['length'] =  strip_tags(stripslashes($_POST['googlereader-length']));
			$options['tag'] = strip_tags(stripslashes($_POST['googlereader-tag']));
			$options['lastcheck'] = 0;
			update_option('widget_googlereader', $options);
		}

		// Be sure you format your options to be valid HTML attributes.
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$userid = htmlspecialchars($options['userid'], ENT_QUOTES);
		$start = htmlspecialchars($options['start'], ENT_QUOTES);
		$end = htmlspecialchars($options['end'], ENT_QUOTES);
		$item = htmlspecialchars($options['item'], ENT_QUOTES);
		$length = htmlspecialchars($options['length'], ENT_QUOTES);
		$cachetime = htmlspecialchars($options['cachetime'], ENT_QUOTES);
		$tag = htmlspecialchars($options['tag'], ENT_QUOTES);
		
		// Here is our little form segment. Notice that we don't need a
		// complete form. This will be embedded into the existing form.
		echo '<p style="text-align:right;"><label for="googlereader-title">' . __('Title:') . ' <input style="width: 200px;" id="googlereader-title" name="googlereader-title" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="googlereader-userid">' . __('User id <a href="http://nothingoutoftheordinary.com/2007/05/26/wordpress-google-reader-widget/#user-id" target="_blank">?</a>:', 'widgets') . ' <input style="width: 200px;" id="googlereader-userid" name="googlereader-userid" type="text" value="'.$userid.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="googlereader-length">' . __('Items:', 'widgets') . ' <input style="width: 200px;" id="googlereader-length" name="googlereader-length" type="text" value="'.$length.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="googlereader-tag">' . __('Tag (optional):', 'widgets') . ' <input style="width: 200px;" id="googlereader-tag" name="googlereader-tag" type="text" value="'.$tag.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="googlereader-cachetime">' . __('Cache time:', 'widgets') . ' <input style="width: 200px;" id="googlereader-cachetime" name="googlereader-cachetime" type="text" value="'.$cachetime.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="googlereader-start">' . __('Items start:', 'widgets') . ' <textarea style="width: 200px;" id="googlereader-start" name="googlereader-start" row="5" cols="20">'.$start.'</textarea></label></p>';
		echo '<p style="text-align:right;"><label for="googlereader-end">' . __('Items end:', 'widgets') . ' <textarea style="width: 200px;" id="googlereader-end" name="googlereader-end" row="5" cols="20">'.$end.'</textarea></label></p>';
		echo '<p style="text-align:right;"><label for="googlereader-item">' . __('Item:', 'widgets') . ' <textarea style="width: 200px;" id="googlereader-item" name="googlereader-item" row="5" cols="20">'.$item.'</textarea></label></p>';
		echo '<input type="hidden" id="googlereader-submit" name="googlereader-submit" value="1" />';
	}
	
	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget(array('Google Reader', 'widgets'), 'widget_googlereader');

	// This registers our optional widget control form. Because of this
	// our widget will have a button that reveals a 300x100 pixel form.
	register_widget_control(array('Google Reader', 'widgets'), 'widget_googlereader_control', 300, 400);
}

// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'widget_googlereader_init');
?>
