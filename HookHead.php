<?php
/*
plugin for Google Maps Marker
Author: a2exfr
http://my-sitelab.com/
Date: 03-11-2016
Version 1.0.8 */

defined('is_running') or die('Not an entry point...');

class GM_head{
	
	function __construct(){

		global $addonRelativeCode, $page,$addonPathData;
		
		$configFile       = $addonPathData.'/config.php';
			if (file_exists($configFile)) {
				include $configFile;
				$this->apikey		= $config['apikey'];
				$this->GMStyle 		= $config['GMStyle'];
					
			} else {
					  $this->apikey			= '';
					  $this->GMStyle 		= '';
			}
			
		$page->head .= '<script type="text/javascript" src="//maps.google.com/maps/api/js?key='.$this->apikey.'"></script>';
		$page->head .= '<script type="text/javascript" src="'.$addonRelativeCode . '/js/GM_page.js"></script>';
		$page->css_user[] = $addonRelativeCode.'/css/maps_page.css';
	
	}
}
	