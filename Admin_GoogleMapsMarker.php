<?php
/*
plugin for Google Maps Marker
Author: a2exfr
http://my-sitelab.com/
Date: 03-11-2016
Version 1.0.8 */

defined('is_running') or die('Not an entry point...');

class Admin{

	private $apikey;
	private $GMStyle;
	private $addon_vers;

	function __construct(){
		global $page, $addonRelativeCode, $config;
		$this->loadConfig();
		$page->css_admin[] = $addonRelativeCode . '/css/maps_admin.css';
		foreach ($config['addons'] as $addon_key => $addon_info) {
			if ($addon_info['name'] == 'Google Maps Marker'){
				$this->addon_vers = $addon_info['version'];
			}
		}
		echo '<h2>Google Maps Marker ' . $this->addon_vers . ' &raquo; Admin</h2>';
		$cmd = common::GetCommand();
		switch ($cmd) {
			case 'saveConfig':
				$this->saveConfig();
				break;
		}
		$this->loadConfig();
		$this->showForm();
	}

	function showForm(){
		global $langmessage, $addonRelativeCode, $config;

		echo '<form action="' . \gp\tool::GetUrl('Admin_GoogleMapsMarker') . '" method="post">';
		echo '<table class="bordered" style="width:100%;">';

		echo '<tr>';
		echo '<th style="width:30%;">' . $langmessage['options'] . '</th>';
		echo '<th style="width:70%;">' . $langmessage['Current_Value'] . '</th>';
		echo '</tr>';

		echo '<tr>';
		echo '<td>Google Maps API key &ndash; <strong>required,</strong> <a href="#read_me">read more&hellip;</a></td>';
		echo '<td><input name="apikey" type="text" value="' . $this->apikey . '" class="gpinput" style="width:264px;" /></td>';
		echo '</tr>';

		echo '<tr>';
		echo '<td>Google Map Style (optional)<br/><a target="_blank" ';
		echo 'href="https://www.google.com/search?q=google+maps+styles&ie=utf-8&oe=utf-8&gws_rd=cr&ei=_iw3VqLULaSAzAO_27eQBw#q=free+google+maps+styles">find styles</a></td>';
		echo '<td><textarea rows="5" style="width:90%;" name="GMStyle" id="GMStyle" placeholder="[your style]">' . $this->GMStyle . '</textarea></td>';
		echo '</tr>';

		echo '<tr>';
		echo '<td colspan="2"><input type="hidden" name="cmd" value="saveConfig" />';
		echo '<input type="submit" value="' . $langmessage['save_changes'] . '" class="gpsubmit GMsave" /></td>';
		echo '</tr>';

		echo '</table>';

		echo '<div class="info_panel">';

		echo '<h5 id="read_me"><strong>Important</strong></h5>';
		echo '<p>Before you can make use of the plugin you first need to insert an API key!<br/>';
		echo '1. Sign in to your Google account (or sign up for one if you do not have one yet)<br/>';
		echo '2. Follow <a href="https://cloud.google.com/maps-platform/#get-started" target="_blank">this link</a> to get your Google Maps JavaScript API key.<br/>';
		echo '3. Insert the key in the &quot;Google Maps API key&quot; field above and hit the save button.';
		echo '</p>';

		echo '<h5><strong>Usage</strong></h5>';
		echo '<p>';
		echo '1. Add a new Google Maps section to your page content. <br/>';
		echo '2. Left click on the map to place a new marker. Zoom and pan the map to refine placement. Markers are draggable. <br/>';
		echo '3. Right click on a marker to remove it. <br/>';
		echo '4. Left click on a marker to edit it&raquo;s info popup bubble. Info popup bubbles accept HTML markup.';
		echo '</p>';

		echo '<h5><strong>Note</strong></h5>';
		echo '<p>When using multiple markers in a single map, the zoom will be set automatically. With only one marker, your current map zoom will be saved.</p>';
		echo '<hr/>';

		echo '<p>Google Maps Marker plugin for Typesetter CMS, version ' . $this->addon_vers . '</p>';
		echo '<p style="float:right; text-align:center;"><a href="http://my-sitelab.com/" target="_blank"><img alt="Sitelab" src="' . $addonRelativeCode . '/img/st_logo.jpg" /><br/><em>Made by Sitelab</em></a></p>';
		echo '<ul>';
		echo '<li><a href="http://ts-addons.my-sitelab.com/Marker_Google_Maps" target="_blank">Plugin page </a>(demo, documentation)</li>';
		echo '<li><a href="http://www.typesettercms.com/Forum?show=f1303" target="_blank">Support Forum </a>(questions, bugs, issues, suggestions for improvements are welcome.)</li>';
		echo '<li><a href="http://www.typesettercms.com/User/2617/Plugins" target="_blank">More of my plugins</a></li>';
		echo '</ul>';

		echo '</div>';
	}

	function saveConfig(){
		global $addonPathData;
		global $langmessage;
		$saveMsg = "";
		$configFile = $addonPathData . '/config.php';
		$config = [];
		$config['apikey'] = $_POST['apikey'];
		$config['GMStyle'] = $_POST['GMStyle'];
		$this->apikey = $config['apikey'];
		$this->GMStyle = $config['GMStyle'];
		if (!gpFiles::SaveArray($configFile, 'config', $config)){
			message($langmessage['OOPS'] . $saveMsg);
			return false;
		}
		message($langmessage['SAVED'] . $saveMsg);
		return true;
	}

	function loadConfig(){
		global $addonPathData;
		$configFile = $addonPathData . '/config.php';
		if (file_exists($configFile)){
			include $configFile;
		}
		if (isset($config)){
			$this->apikey = $config['apikey'];
			$this->GMStyle = $config['GMStyle'];
		} else {
			$this->apikey = 'YOUR_API_KEY';
			$this->GMStyle = '';
		}
	}
}


?>