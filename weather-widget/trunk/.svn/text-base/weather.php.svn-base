<?php
/*
Plugin Name: Weather Widget
Plugin URI: http://ja.meswilson.com/blog/2007/05/29/wordpress-weather-widget/
Description: Adds a sidebar widget to show your current weather
Author: James Wilson
Version: 1.6
Author URI: http://ja.meswilson.com/blog/
*/

/*
Changes:
1.6 - weather.com changed their api yet again. it's prod again. so now it send both prod and link parameters.
1.5 - Fixed unclosed <span>. Add </span> to the end of the formatting to fix this.
1.4 - Updated my info
1.3 - weather.com updated their api. instead of prod, it's now link. that's it.
1.2 - Added celsius support
1.1 - It's $after_widget, not $afterwidget. Thanks Eric.
1.0 - Original
*/

// _Very_ largely based on the gsearch widget by Automattic

// Put functions into one big function we'll call at the plugins_loaded
// action. This ensures that all required plugin functions are defined.
function widget_weather_init() {

	// Check for the required plugin functions. This will prevent fatal
	// errors occurring when you deactivate the dynamic-sidebar plugin.
	if ( !function_exists('register_sidebar_widget') )
		return;

	// This is the function that outputs our little Google search form.
	function widget_weather($args) {
		
		// $args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys. Default tags: li and h2.
		extract($args);

		// Each widget can store its own options. We keep strings here.
		$options = get_option('widget_weather');
		$location = urlencode($options['location']);
		$partnerid = urlencode($options['partnerid']);
		$licensekey = urlencode($options['licensekey']);
		$imageloc = $options['imagelocation'];
		$widgettitle = ($options['title'] != "") ? $before_title.$options['title'].$after_title : "";  // ($options['title'] != "") ? $options['title'] : "weather Rankings";
		$cachetime = $options['cachetime'];
		$lastcheck = $options['lastcheck'];
		$unit = $options['unit'];
		$unit = ($unit == "s" || $unit == "m") ? $unit : "s";
		
		if(time() - $cachetime < $lastcheck AND $options['cache'] != "") {
			echo $before_widget.$widgettitle.$options['cache'].$after_widget;
		}
		else {
			$uri = 'http://xoap.weather.com/weather/local/' . $location . '?cc=*&link=xoap&prod=xoap&unit=' . $unit . '&par=' . $partnerid . '&key=' . $licensekey;
			$data = file_get_contents($uri);
			
			if($data != "") {
				$start = strpos($data, "<cc>");
				$cc = substr($data, $start, strpos($data,"</cc>") - $start);
				if($cc != "") {
					// Location
					$start = strpos($cc,"<obst>") + 6;
					$loc = substr($cc, $start, strpos($cc, "</obst>") - $start);
					
					// Temperature
					$start = strpos($cc, "<tmp>") + 5;
					$temp = substr($cc, $start, strpos($cc, "</tmp>") - $start);
					
					// Feels like
					$start = strpos($cc, "<flik>") + 6;
					$feels = substr($cc, $start, strpos($cc, "</flik>") - $start);
					
					// Conditions, called <t> for some reason
					$start = strpos($cc, "<t>") + 3;
					$cond = substr($cc, $start, strpos($cc, "</t>") - $start);
					
					// Icon number
					$start = strpos($cc, "<icon>") + 6;
					$icon = substr($cc, $start, strpos($cc, "</icon>") - $start);
					
				}
			}
			
			$attribution = '<a href="http://www.weather.com/?prod=xoap&par=' . $partnerid . '">Weather data provided by weather.com&reg;</a>';
			$iconhref = $imageloc . $icon . '.png';
			
			$format = $options['formatting'];
			
			$content = str_replace(
				array (
					"%loc%",
					"%temp%",
					"%feels%",
					"%cond%",
					"%icon%",
					"%iconhref%",
					"%attribution%"
				) ,
				array (
					$loc,
					$temp,
					$feels,
					$cond,
					$icon,
					$iconhref,
					$attribution
				) ,
				$format
			);
			
			$options['cache'] = $content;
			$options['lastcheck'] = time();
			update_option('widget_weather',$options);
			
			
			echo $before_widget;
			echo $widgettitle;
			echo $content;
			echo $after_widget;
		}
	}

	// This is the function that outputs the form to let the users edit
	// the widget's title. It's an optional feature that users cry for.
	function widget_weather_control() {

		// Get our options and see if we're handling a form submission.
		$options = get_option('widget_weather');
		if ( !is_array($options) )
			$options = array('title'=>'', 
				'partnerid' => '1036672568', // My partner id
				'licensekey' => '561e1a02298548de', // License key. Not sure what the limits are.
				'cache' => '',
				'cachetime' => '3600',
				'location' => '77450',
				'lastcheck' => 0,
				'imagelocation' => 'http://ja.meswilson.com/blog/weatherimages/',
				'unit' => 's',
				'formatting' => '<img src="%iconhref%" alt="%cond%" style="float:right;" />%loc%<br /><span style="font-size: 150%;">%temp% &deg;F</span> (%feels% &deg;F)<br /><span style="font-size: 65%; float: right;">%attribution%</a></span>'
			);
		if ( $_POST['weather-submit'] ) {

			// Remember to sanitize and format use input appropriately.
			$options['title'] = strip_tags(stripslashes($_POST['weather-title']));
			$options['location'] = strip_tags(stripslashes($_POST['weather-location']));
			$options['partnerid'] = strip_tags(stripslashes($_POST['weather-partnerid']));
			$options['licensekey'] = strip_tags(stripslashes($_POST['weather-licensekey']));
			$options['cachetime'] = strip_tags(stripslashes($_POST['weather-cachetime']));
			$options['imagelocation'] = strip_tags(stripslashes($_POST['weather-imagelocation']));
			$options['formatting'] = stripslashes($_POST['weather-formatting']);
			$options['unit'] = $_POST['weather-unit'];
			$options['lastcheck'] = -1;
			
			update_option('widget_weather', $options);
		}

		// Be sure you format your options to be valid HTML attributes.
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$location = htmlspecialchars($options['location'], ENT_QUOTES);
		$imagelocation = htmlspecialchars($options['imagelocation'], ENT_QUOTES);
		$partnerid = htmlspecialchars($options['partnerid'], ENT_QUOTES);
		$licensekey = htmlspecialchars($options['licensekey'], ENT_QUOTES);
		$cachetime = htmlspecialchars($options['cachetime'], ENT_QUOTES);
		$formatting = htmlspecialchars($options['formatting'], ENT_QUOTES);
		$unit = $options['unit'];
		
		// Here is our little form segment. Notice that we don't need a
		// complete form. This will be embedded into the existing form.
		echo '<p style="text-align:right;"><label for="weather-title">' . __('Title:') . ' <input style="width: 200px;" id="weather-title" name="weather-title" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="weather-location">' . __('Location <a href="http://ja.meswilson.com/blog/2007/05/29/wordpress-weather-widget/#location">?</a>:', 'widgets') . ' <input style="width: 200px;" id="weather-location" name="weather-location" type="text" value="'.$location.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="weather-imagelocation">' . __('Images location <a href="http://nothingoutoftheordinary.com/2007/05/29/wordpress-weather-widget/#images">?</a>:', 'widgets') . ' <input style="width: 200px;" id="weather-imagelocation" name="weather-imagelocation" type="text" value="'.$imagelocation.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="weather-partnerid">' . __('Partner ID:', 'widgets') . ' <input style="width: 200px;" id="weather-partnerid" name="weather-partnerid" type="text" value="'.$partnerid.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="weather-licensekey">' . __('License Key:', 'widgets') . ' <input style="width: 200px;" id="weather-licensekey" name="weather-licensekey" type="text" value="'.$licensekey.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="weather-cachetime">' . __('Cache time:', 'widgets') . ' <input style="width: 200px;" id="weather-cachetime" name="weather-cachetime" type="text" value="'.$cachetime.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="weather-unit">' . __('Units:', 'widgets') . ' <select style="width: 200px;" id="weather-unit" name="weather-unit"><option value="s" '.( ($unit == "s") ? "selected=\"selected\" " : "" ).'>Fahrenheit</option><option value="m" '.( ($unit == "m") ? "selected=\"selected\" " : "" ).'>Celsius</option></label></p>';
		echo '<p style="text-align:right;"><label for="weather-formatting">' . __('Widget Format:', 'widgets') . ' <textarea style="width: 200px;" id="weather-formatting" name="weather-formatting" row="5" cols="20">'.$formatting.'</textarea></label></p>';
		echo '<input type="hidden" id="weather-submit" name="weather-submit" value="1" />';
	}
	
	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget(array('Weather', 'widgets'), 'widget_weather');

	// This registers our optional widget control form. Because of this
	// our widget will have a button that reveals a 300x100 pixel form.
	register_widget_control(array('Weather', 'widgets'), 'widget_weather_control', 350, 325);
}

// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'widget_weather_init');

?>
