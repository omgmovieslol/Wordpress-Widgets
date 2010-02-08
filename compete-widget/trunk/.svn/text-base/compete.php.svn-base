<?php
/*
Plugin Name: Compete widget
Plugin URI: http://ja.meswilson.com/blog/2007/05/24/compete-wordpress-widget/
Description: Adds a sidebar widget to show your compete rank.
Author: James Wilson
Version: 1.3
Author URI: http://ja.meswilson.com/blog/
*/

/*
Changes:
1.3 - Updated my info
1.2 - Added my own Compete API key.
1.1 - It's $after_widget, not $afterwidget. Thanks Eric.
1.0 - Original
*/

// _Very_ largely based on the gsearch widget by Automattic

// Put functions into one big function we'll call at the plugins_loaded
// action. This ensures that all required plugin functions are defined.
function widget_compete_init() {

	// Check for the required plugin functions. This will prevent fatal
	// errors occurring when you deactivate the dynamic-sidebar plugin.
	if ( !function_exists('register_sidebar_widget') )
		return;

	// This is the function that outputs our little Google search form.
	function widget_compete($args) {
		
		// $args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys. Default tags: li and h2.
		extract($args);

		// Each widget can store its own options. We keep strings here.
		$options = get_option('widget_compete');
		$apikey = urlencode($options['apikey']);
		$lastcheck = $options['lastcheck'];
		$imagesize = $options['imagesize'];
		$cachetime = ($options['cachetime'] != "" AND is_int($options['cachetime'])) ? $options['cachetime'] : 3600;
		$title = ($options['title'] != "") ? $before_title.$options['title'].$after_title : "";  // ($options['title'] != "") ? $options['title'] : "Compete Rankings";
		if(time() - $cachetime < $lastcheck AND $options['cache'] != "") {
			echo $before_widget.$title.$options['cache'].$after_widget;
		}
		elseif( $apikey != "") {
			// These lines generate our output. Widgets can be very complex
			// but as you can see here, they can also be very, very simple.
			//echo $before_widget . $before_title . $title . $after_title;
			$host = parse_url(get_bloginfo('home'));
			$host = $host['host'];
			$url_parts = ($options['url'] != "") ? urlencode($options['url']) : urlencode($host);
			$uri = "http://api.compete.com/fast-cgi/MI?d=$url_parts&ver=3&apikey=$apikey&size=$imagesize";
			$compete = "http://snapshot.compete.com/$url_parts";
			//echo $uri;
			$api = file_get_contents($uri);
			
			
			// I'm not using any XML parsing thing, because there's no guarantee of them being installed, and string parsing is just as easy in this case
			
			if($api != "") {
				$start = strpos($api,"<rank");
				$rank = substr($api, $start, strpos($api,"</rank>")-$start);
				
				if($rank != "") {
					$start = strpos($rank, "<val>");
					if($start = $start + 5) {
						$ranking = substr($rank, $start,strpos($rank,"</val>")-$start);
					}
					
					$start = strpos($rank, "<icon>");
					if($start = $start + 6 ) {
						$icon = substr($rank, $start, strpos($rank,"</icon>")-$start);
					}
				}
				
				$start = strpos($api, "<metrics");
				$metrics = substr($api,$start,strpos($api, "</metrics>")-$start);
				
				if($metrics != "") {
					$start = strpos($metrics,"<count>");
					if($start = $start + 7) {
						$count = substr($metrics, $start, strpos($metrics,"</count>")-$start);
					}
				}
			}
			
			
			if($options['content'] != "") {
				$content = str_replace(
					array(
						"%host%",
						"%rank%",
						"%icon%",
						"%count%",
						"%compete%",
						"%link%"
					), 
					array(
						urldecode($url_parts),
						$rank, 
						$icon, 
						$count,
						"<span style=\"font-size: 75%\"><a href=\"http://snapshot.compete.com\">Compete.com</a></span>",
						$compete
					), 
					$options['content']
				);
			}
			else {
				$content = "";
				$content .= "<span style=\"float:right;\"><img src=\"$icon\" alt=\"$ranking\" /></span>";
				$content .= "<a href=\"$compete\">$url_parts</a><br />Ranking: $ranking<br />People: $count<br />";
				//echo '<div style="margin-top:5px;text-align:center;"><form id="gsearch" action="http://www.google.com/search" method="get" onsubmit="this.q.value=\'site:'.$url_parts['host'].' \'+this.rawq.value"><input name="rawq" size="20" /><input type="hidden" name="q" value="" /><input value="'.$buttontext.'" name="submit" type="submit" /></form></div>';
				$content .= "<span style=\"font-size: 75%\"><a href=\"http://snapshot.compete.com\">Compete.com</a></span>";
			}
			
			$options['lastcheck'] = time();
			$options['cache'] = $content;
			update_option('widget_compete',$options);
			
			echo $before_widget . $title;
			echo $content;
			echo $after_widget;
		}
	}

	// This is the function that outputs the form to let the users edit
	// the widget's title. It's an optional feature that users cry for.
	function widget_compete_control() {

		// Get our options and see if we're handling a form submission.
		$options = get_option('widget_compete');
		if ( !is_array($options) )
			$options = array('title'=>'Compete Rankings', 
				'cache'=> '',
				'lastcheck' => 0,
				'apikey' => '9vmtth9uar6ykr8qa5cjx46n',
				'url' => '',
				'cachetime' => '3600',
				'imagesize' => 'large',
				'content' => ''
			);
		if ( $_POST['compete-submit'] ) {

			// Remember to sanitize and format use input appropriately.
			$options['title'] = strip_tags(stripslashes($_POST['compete-title']));
			$options['apikey'] = strip_tags(stripslashes($_POST['compete-apikey']));
			//$options['cache'] = strip_tags(stripslashes($_POST['compete-cache']));
			$options['url'] = strip_tags(stripslashes($_POST['compete-url']));
			$options['cachetime'] = strip_tags(stripslashes($_POST['compete-cachetime']));
			$options['imagesize'] = strip_tags(stripslashes($_POST['compete-imagesize']));
			$options['content'] = stripslashes($_POST['compete-content']);
			$options['lastcheck'] = 0;
			update_option('widget_compete', $options);
		}

		// Be sure you format your options to be valid HTML attributes.
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$apikey = htmlspecialchars($options['apikey'], ENT_QUOTES);
		$url = htmlspecialchars($options['url'], ENT_QUOTES);
		$cachetime = htmlspecialchars($options['cachetime'], ENT_QUOTES);
		$imagesize = htmlspecialchars($options['imagesize'], ENT_QUOTES);
		$content = htmlspecialchars($options['content'], ENT_QUOTES);
		
		// Here is our little form segment. Notice that we don't need a
		// complete form. This will be embedded into the existing form.
		echo '<p style="text-align:right;"><label for="compete-title">' . __('Title:') . ' <input style="width: 200px;" id="compete-title" name="compete-title" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="compete-apikey">' . __('API Key:', 'widgets') . ' <input style="width: 200px;" id="compete-apikey" name="compete-apikey" type="text" value="'.$apikey.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="compete-url">' . __('URL (blank for current site):', 'widgets') . ' <input style="width: 200px;" id="compete-url" name="compete-url" type="text" value="'.$url.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="compete-cachetime">' . __('Cache time:', 'widgets') . ' <input style="width: 200px;" id="compete-cachetime" name="compete-cachetime" type="text" value="'.$cachetime.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="compete-imagesize">' . __('Image Size:','widgets') . ' <select style="width: 200px;" id="compete-imagesize" name="compete-imagesize"><option value="large" ' . ( ($imagesize == "large") ? "selected=\"selected\"" : "" ) . '>Large</option><option value="small" ' . ( ($iamgesize == "small") ? "selected=\"selected\"" : "") . '>Small</option></select></label></p>';
		echo '<p style="text-align:right;"><label for="compete-content">' . __('Widget Format:', 'widgets') . ' <textarea style="width: 200px;" id="compete-content" name="compete-content" row="5" cols="20">'.$content.'</textarea></label></p>';
		echo '<input type="hidden" id="compete-submit" name="compete-submit" value="1" />';
	}
	
	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget(array('Compete', 'widgets'), 'widget_compete');

	// This registers our optional widget control form. Because of this
	// our widget will have a button that reveals a 300x100 pixel form.
	register_widget_control(array('Compete', 'widgets'), 'widget_compete_control', 400, 300);
}

// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'widget_compete_init');

?>
