<?php
/*
Plugin Name: YouTube widget
Plugin URI: http://ja.meswilson.com/blog/2007/05/31/wordpress-youtube-widget/
Description: Adds a sidebar widget to show a YouTube video.
Author: James Wilson
Version: 1.1
Author URI: http://ja.meswilson.com/blog/
*/

/*
1.2 - Updated my info
1.1 - Country code support
1.0 - Original
*/

// _Very_ largely based on the gsearch widget by Automattic
// Currently weekly doesn't work


// Put functions into one big function we'll call at the plugins_loaded
// action. This ensures that all required plugin functions are defined.
function widget_youtube_init() {

	// Check for the required plugin functions. This will prevent fatal
	// errors occurring when you deactivate the dynamic-sidebar plugin.
	if ( !function_exists('register_sidebar_widget') )
		return;

	// This is the function that outputs our little Google search form.
	function widget_youtube($args) {
		
		// $args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys. Default tags: li and h2.
		extract($args);
		
		
		
		
		$widget = <<<YTB
<object width="%width%" height="%height%">
	<param name="movie" value="http://www.youtube.com/v/%videoid%%autoplay%"></param>
	<param name="wmode" value="transparent"></param>
	<embed src="http://www.youtube.com/v/%videoid%%autoplay%" type="application/x-shockwave-flash" wmode="transparent" width="%width%" height="%height%"></embed>
</object>
YTB;
		
		// Each widget can store its own options. We keep strings here.
		$options = get_option('widget_youtube');
		$videoid = $options['videoid'];
		$width = $options['width'];
		$height = $options['height'];
		$title = ($options['title'] != "") ? $before_title.$options['title'].$after_title : "";  // ($options['title'] != "") ? $options['title'] : "Compete Rankings";
		$autoplay = ($options['autoplay'] == '1') ? '&autoplay=1' : '';
		
		// These lines generate our output. Widgets can be very complex
		// but as you can see here, they can also be very, very simple.
		//echo $before_widget . $before_title . $title . $after_title;
		
		$content = str_replace(
			array(
				"%videoid%",
				"%width%",
				"%height%",
				"%autoplay%"
			), 
			array(
				$videoid,
				$width,
				$height,
				$autoplay
			), 
			$widget
		);
		
		echo $before_widget;
		echo $title;
		echo $content;
		echo $after_widget;
	
	}

	// This is the function that outputs the form to let the users edit
	// the widget's title. It's an optional feature that users cry for.
	function widget_youtube_control() {

		// Get our options and see if we're handling a form submission.
		$options = get_option('widget_youtube');
		if ( !is_array($options) )
			$options = array('title'=>'', 
				'video'=> 'http://www.youtube.com/watch?v=AYxu_MQSTTY',
				'videoid' => 'AYxu_MQSTTY',
				'width' => '200',
				'height' => '165',
				'autoplay' => '0'
			);
		if ( $_POST['youtube-submit'] ) {

			// Remember to sanitize and format use input appropriately.
			$options['title'] = strip_tags(stripslashes($_POST['youtube-title']));
			$options['video'] = strip_tags(stripslashes($_POST['youtube-video']));
			$options['width'] = strip_tags(stripslashes($_POST['youtube-width']));
			$options['height'] = strip_tags(stripslashes($_POST['youtube-height']));
			$options['autoplay'] = ($_POST['youtube-autoplay'] == "on" ) ? "1" : "0";
			
			//$loc = strpos($options['video'],"v=");
			//$videoid = substr($options['video'], $loc + 2);
			//$videoid = str_replace( array("http://www.youtube.com/watch?v=", "http:/youtube.com/watch?v="), "", $options['video']);
			//$videoid = preg_replace("http://([A-z]+.)?youtube.com/watch?v=", "", $options['video']);
			$videoid = str_ireplace( 
				array(
					"http://www.youtube.com/watch?v=", 
					"http:/youtube.com/watch?v=",
					"http://br.youtube.com/watch?v=",
					"http://uk.youtube.com/watch?v=",
					"http://fr.youtube.com/watch?v=",
					"http://ie.youtube.com/watch?v=",
					"http://it.youtube.com/watch?v=",
					"http://jp.youtube.com/watch?v=",
					"http://nl.youtube.com/watch?v=",
					"http://pl.youtube.com/watch?v=",
					"http://es.youtube.com/watch?v="
				), 
				"", 
				$options['video']
			);
			
			$loc2 = strpos($videoid, "&");
			if($loc2 != FALSE AND $loc2 > 0)
				$videoid = substr($videoid, 0, $loc2);
			
			$options['videoid'] = strip_tags(stripslashes($videoid));
			
			update_option('widget_youtube', $options);
		}

		// Be sure you format your options to be valid HTML attributes.
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$video = htmlspecialchars($options['video'], ENT_QUOTES);
		$width = htmlspecialchars($options['width'], ENT_QUOTES);
		$height = htmlspecialchars($options['height'], ENT_QUOTES);
		$autoplay = htmlspecialchars($options['autoplay'], ENT_QUOTES);
		
		// Here is our little form segment. Notice that we don't need a
		// complete form. This will be embedded into the existing form.
		echo '<p style="text-align:right;"><label for="youtube-title">' . __('Title:') . ' <input style="width: 200px;" id="youtube-title" name="youtube-title" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="youtube-video">' . __('Video:', 'widgets') . ' <input style="width: 200px;" id="youtube-video" name="youtube-video" type="text" value="'.$video.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="youtube-width">' . __('Width:', 'widgets') . ' <input style="width: 200px;" id="youtube-width" name="youtube-width" type="text" value="'.$width.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="youtube-height">' . __('Height:', 'widgets') . ' <input style="width: 200px;" id="youtube-height" name="youtube-height" type="text" value="'.$height.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="youtube-autoplay">' . __('Autoplay:', 'widgets') . ' <input id="youtube-autoplay" name="youtube-autoplay" type="checkbox" ' . (  ($autoplay == '1') ? "checked=\"checked\"" : "" ) . '</label></p>';
		//echo '<p style="text-align:right;"><label for="compete-imagesize">' . __('Image Size:','widgets') . ' <select style="width: 200px;" id="compete-imagesize" name="compete-imagesize"><option value="large" ' . ( ($imagesize == "large") ? "selected=\"selected\"" : "" ) . '>Large</option><option value="small" ' . ( ($iamgesize == "small") ? "selected=\"selected\"" : "") . '>Small</option></select></label></p>';
		//echo '<p style="text-align:right;"><label for="compete-content">' . __('Widget Format:', 'widgets') . ' <textarea style="width: 200px;" id="compete-content" name="compete-content" row="5" cols="20">'.$content.'</textarea></label></p>';
		echo '<input type="hidden" id="youtube-submit" name="youtube-submit" value="1" />';
	}
	
	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget(array('YouTube', 'widgets'), 'widget_youtube');

	// This registers our optional widget control form. Because of this
	// our widget will have a button that reveals a 300x100 pixel form.
	register_widget_control(array('YouTube', 'widgets'), 'widget_youtube_control', 300, 200);
}

// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'widget_youtube_init');

?>
