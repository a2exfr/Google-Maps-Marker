<?php
defined('is_running') or die('Not an entry point...');

class GM_head{

	private $apikey;
	private $GMStyle;

	function __construct(){

		global $addonRelativeCode, $page, $addonPathData;

		$configFile = $addonPathData . '/config.php';
		if (file_exists($configFile)){
			include $configFile;
			$this->apikey = $config['apikey'];
			$this->GMStyle = $config['GMStyle'];

		} else {
			$this->apikey = '';
			$this->GMStyle = '';
		}

		$is_map_on_page = false;
		if (isset($page->file_sections) && is_array($page->file_sections) && count($page->file_sections)){
			foreach ($page->file_sections as $section) {
				if ($section['type'] == 'GoogleMaps_section'){
					$is_map_on_page = true;
					break;
				}
			}
		}
		if ($is_map_on_page){
			$page->head .= '<script type="text/javascript" src="' . $addonRelativeCode . '/js/GM_page.js"></script>';
			$page->head .= '<script type="text/javascript" src="//maps.google.com/maps/api/js?key=' . $this->apikey . '&callback=startGoogleMaps"></script>';
			$page->css_user[] = $addonRelativeCode . '/css/maps_page.css';
		}

	}
}
