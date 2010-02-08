<?php
/*
Plugin Name: Reddit Widget
Plugin URI: http://ja.meswilson.com/blog/2007/06/01/wordpress-reddit-widget/
Description: Adds a sidebar widget to show your current liked items (on reddit.com)
Author: James Wilson
Version: 1.2
Author URI: http://ja.meswilson.com/blog/
*/

/*
Changes:
1.2 - Updated my info
1.1 - It's $after_widget not $afterwidget. Thanks Eric.
1.0 - Original
*/

// _Very_ largely based on the gsearch widget by Automattic

// Put functions into one big function we'll call at the plugins_loaded
// action. This ensures that all required plugin functions are defined.
function widget_reddit_init() {

	// Check for the required plugin functions. This will prevent fatal
	// errors occurring when you deactivate the dynamic-sidebar plugin.
	if ( !function_exists('register_sidebar_widget') )
		return;

	// This is the function that outputs our little Google search form.
	function widget_reddit($args) {
		
		// $args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys. Default tags: li and h2.
		extract($args);

		// Each widget can store its own options. We keep strings here.
		$options = get_option('widget_reddit');
		$username = urlencode($options['userid']);
		$widgettitle = ($options['title'] != "") ? $before_title.$options['title'].$after_title : "";  // ($options['title'] != "") ? $options['title'] : "reddit Rankings";
		$cachetime = $options['cachetime'];
		$lastcheck = $options['lastcheck'];
		$length = $options['length'];
		
		if(time() - $cachetime < $lastcheck AND $options['cache'] != "") {
			echo $before_widget.$widgettitle.$options['cache'].$after_widget;
		}
		else {
			$uri = 'http://reddit.com/user/' . $username . '/liked.rss';
			$profilelink = 'http://reddit.com/user/' . $username;
			ini_set('user_agent','WordPress Reddit Widget - http://nothingoutoftheordinary.com/wordpress-reddit-widget/');
			$data = file_get_contents($uri);
			
			
			if($data != "") {
				$itemformatting = $options['item'];	
				$items = "";
				$count = 0;
				// Go through all of the stories
				while(strpos($data,"<item>") != FALSE AND $count < $length) {
					$start = strpos($data,"<item>");
					$stop = strpos($data,"</item>");
					$entry = substr($data, $start, $stop-$start);
					
					
					// Title
					$start = strpos($entry,"<title>") + 8; // The titles have a space at the start for some reason. Just remove it now so we don't have to later
					$title = substr($entry, $start, strpos($entry,"</title>")-$start);
					
					// Link
					$start = strpos($entry, "<link>") + 6;
					$link = substr($entry, $start, strpos($entry, '</link>')-$start);
					
					// Description
					$start = strpos($entry, "<description>") + 13;
					$description = substr($entry, $start, strpos($entry, "</description>") - $start);
					
					// Time submitted
					$start = strpos($entry, "<dc:date>") + 9;
					$date = substr($entry, $start, strpos($entry, "</dc:date>") - $start);
					
					// more link
					$start = strpos($description, "&lt;/a&gt;&lt;a href=\"") + 22;
					$more = substr($description, $start, strpos($description, "\"&gt;[more]&lt;/a&gt;")-$start);
					
					
					$stop += 6;
					$data = substr($data,$stop);
					
					$items .= str_replace(
						array ( 
							"%title%", 
							"%link%", 
							"%desc%",
							"%date%",
							"%more%",
							"%number%"
						), 
						array( 
							$title, 
							$link, 
							$description,
							$date,
							$more,
							$count+1 ), 
						$itemformatting
					);
					$count ++;
				}
			}
			
			
			$startformatting = $options['start'];
			$start = str_replace( array( "%count%", "%username%", "%rss%", "%profile%") , array( $count, $username, $uri, $profilelink), $startformatting);
			
			$endformatting = $options['end'];
			$end = str_replace( array( "%count%", "%username%", "%rss%", "%profile%") , array( $count, $username, $uri, $profilelink), $endformatting);
			
			$content = $start.$items.$end;
			
			
			$options['cache'] = $content;
			$options['lastcheck'] = time();
			update_option('widget_reddit',$options);
			
			
			echo $before_widget;
			echo $widgettitle;
			echo $content;
			echo $after_widget;
		}
	}

	// This is the function that outputs the form to let the users edit
	// the widget's title. It's an optional feature that users cry for.
	function widget_reddit_control() {

		// Get our options and see if we're handling a form submission.
		$options = get_option('widget_reddit');
		if ( !is_array($options) )
			$options = array('title'=>'Latest Liked Items (on reddit.com)', 
				'cache'=> '',
				'lastcheck' => 0,
				'userid' => 'sk33t',
				'start' => '<ul style="list-style-type: none;">',
				'end' => '</ul>'."\n".'<a href="%profile%" style="float:right;">%username%</a>',
				'item' => '<li style="list-style-type: none;"><a href="%link%">%title%</a> (<a href="%more%">more</a>)</a></li>',
				'length' => '5',
				'cachetime' => '3600'
			);
		if ( $_POST['reddit-submit'] ) {
			// Remember to sanitize and format use input appropriately.
			$options['title'] = strip_tags(stripslashes($_POST['reddit-title']));
			$options['userid'] = strip_tags(stripslashes($_POST['reddit-userid']));
			//$options['cache'] = strip_tags(stripslashes($_POST['reddit-cache']));
			$options['cachetime'] = strip_tags(stripslashes($_POST['reddit-cachetime']));
			$options['start'] = stripslashes($_POST['reddit-start']);
			$options['end'] = stripslashes($_POST['reddit-end']);
			$options['item'] = stripslashes($_POST['reddit-item']);
			$options['length'] =  strip_tags(stripslashes($_POST['reddit-length']));
			$options['lastcheck'] = 0;
			
			update_option('widget_reddit', $options);
		}

		// Be sure you format your options to be valid HTML attributes.
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$userid = htmlspecialchars($options['userid'], ENT_QUOTES);
		$start = htmlspecialchars($options['start'], ENT_QUOTES);
		$end = htmlspecialchars($options['end'], ENT_QUOTES);
		$item = htmlspecialchars($options['item'], ENT_QUOTES);
		$length = htmlspecialchars($options['length'], ENT_QUOTES);
		$cachetime = htmlspecialchars($options['cachetime'], ENT_QUOTES);
		
		// Here is our little form segment. Notice that we don't need a
		// complete form. This will be embedded into the existing form.
		echo '<p style="text-align:right;"><label for="reddit-title">' . __('Title:') . ' <input style="width: 200px;" id="reddit-title" name="reddit-title" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="reddit-userid">' . __('Username:', 'widgets') . ' <input style="width: 200px;" id="reddit-userid" name="reddit-userid" type="text" value="'.$userid.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="reddit-length">' . __('Items:', 'widgets') . ' <input style="width: 200px;" id="reddit-length" name="reddit-length" type="text" value="'.$length.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="reddit-cachetime">' . __('Cache time:', 'widgets') . ' <input style="width: 200px;" id="reddit-cachetime" name="reddit-cachetime" type="text" value="'.$cachetime.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="reddit-start">' . __('Items start <a href="http://nothingoutoftheordinary.com/2007/06/01/wordpress-reddit-widget/#formatting">?</a>:', 'widgets') . ' <textarea style="width: 200px;" id="reddit-start" name="reddit-start" row="5" cols="20">'.$start.'</textarea></label></p>';
		echo '<p style="text-align:right;"><label for="reddit-end">' . __('Items end:', 'widgets') . ' <textarea style="width: 200px;" id="reddit-end" name="reddit-end" row="5" cols="20">'.$end.'</textarea></label></p>';
		echo '<p style="text-align:right;"><label for="reddit-item">' . __('Item:', 'widgets') . ' <textarea style="width: 200px;" id="reddit-item" name="reddit-item" row="5" cols="20">'.$item.'</textarea></label></p>';
		echo '<input type="hidden" id="reddit-submit" name="reddit-submit" value="1" />';
	}
	
	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget(array('reddit', 'widgets'), 'widget_reddit');

	// This registers our optional widget control form. Because of this
	// our widget will have a button that reveals a 300x100 pixel form.
	register_widget_control(array('reddit', 'widgets'), 'widget_reddit_control', 350, 375);
}

// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'widget_reddit_init');

?>
