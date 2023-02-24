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
		echo '<h2>Google Maps marker admin</h2>';
		echo '<h5>version ' . $this->addon_vers . '</h5>';
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
		echo '<div id="infoPanel">';
		echo '<form action="' . common::GetUrl('Admin_GoogleMapsMarker') . '" method="post">';
		echo '<p>Google maps <b>API key</b> <a href="#read_me">[read how to get one...]</a><br/>';
		echo '<input   name="apikey" value="' . $this->apikey . '" class="gpinput" style="width:200px" />';
		echo '</p>';
		echo '<p>Google Map Style: <a target="_blank" href="https://www.google.com/search?q=google+maps+styles&ie=utf-8&oe=utf-8&gws_rd=cr&ei=_iw3VqLULaSAzAO_27eQBw#q=free+google+maps+styles">(find styles)</a></br> ';
		echo '<textarea rows="5" cols="45" name="GMStyle" id="GMStyle" placeholder="[your style]">' . $this->GMStyle . '</textarea>';
		echo '</p>';
		echo '<input type="hidden" name="cmd" value="saveConfig" />';
		echo '<input type="submit" value="' . $langmessage['save_changes'] . '" class="gpsubmit GMsave"/>';
		echo '</p>';
		echo '</form>';
		echo '</div>';
		echo '<div class="info_inner">';
		echo ' <p id="read_me"><b>Important: </b> As per Google recent announcement, usage of the Google Maps APIs now requires a key.<br/>
				Maps API applications use the Key for browser apps.<br/>
				To use plugin you need to get google api key(if you already do not have one), and insert it in Google maps API key field.<br/>

				Go to  <a class=" gpbutton" href="https://console.developers.google.com/" target="_blank">Get API key </a> <br/>

			   1. Under Google Map\'s Apis choose Google Maps JavaScript API <br/>
			   2. Enable the Api. <br/>
			   3. Go to credentials section.Choose create Credentials. <br/>
			   4. Choose API Key from the popup,and then choose browser key from the proceeding popup. <br/>
			   5. Insert in "Google maps API key" field  your own api key obtained. <br/>

		</p>
		<p>
		<b>Usage:</b>  Left click on map to set marker. Right click on marker to remove it. Markers is dragable.<br/>
		Left click on marker to edit info window. Info window acsepts html tags.</p>';
		echo '<p><b>Note:</b> When using several markers, the zoom is automatically set. When using only one marker, you can save the chosen zoom.<br/></p>';
		echo '<br>';
		echo '<h4>Google Maps Marker</h4>';
		echo '<h5>version ' . $this->addon_vers . '</h5>';
		echo '<ul>';
		echo '<li><a href="http://ts-addons.my-sitelab.com/Marker_Google_Maps" target="_blank">Plugin page </a>(Demo,documentation)</li>';
		echo '<li><a href="http://www.typesettercms.com/Forum?show=f1303" target="_blank">Support Forum </a>(Qwestions, bugs, issues, suggestions for improvements are welcome.)</li>';
		echo '<li><a href="http://www.typesettercms.com/User/2617/Plugins" target="_blank">Another my plugins</a></li>';
		echo '</ul>';
		echo '<p><i>plugin for Typesetter CMS</i></p>';
		echo '<p><i>Made by Sitelab</i></p>';
		echo '<p><a href="http://my-sitelab.com/" target="_blank"><img alt="Sitelab" src="' . $addonRelativeCode . '/img/st_logo.jpg' . '"  /></a> </p>';
		echo '</div>';
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