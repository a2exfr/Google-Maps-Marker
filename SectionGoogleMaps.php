<?php
defined('is_running') or die('Not an entry point...');

class SectionGoogleMaps{

	static function SectionTypes($section_types){
		$section_types['GoogleMaps_section'] = [];
		$section_types['GoogleMaps_section']['label'] = 'Google Maps';
		return $section_types;
	}

	static function NewSections($links){
		global $addonRelativeCode;
		foreach ($links as $key => $link) {
			$match = is_array($link[0]) ? implode('-', $link[0]) : $link[0];
			if ($match == 'GoogleMaps_section'){
				$links[$key][1] = $addonRelativeCode . '/img/gm.png';
			}
		}
		return $links;
	}

	static function InlineEdit_Scripts($scripts, $type){
		if ($type !== 'GoogleMaps_section'){
			return $scripts;
		}
		global $addonRelativeCode;
		$scripts[] = $addonRelativeCode . '/js/GM_edit.js';
		return $scripts;
	}

	static function SaveSection($return, $section, $type){
		if ($type != 'GoogleMaps_section'){
			return $return;
		}
		global $page;
		$page->file_sections[$section]['zoom'] = &$_POST['zoom'];
		$page->file_sections[$section]['markers'] = &$_POST['markers'];
		$page->file_sections[$section]['Bouncemarker'] = &$_POST['Bouncemarker'];
		$page->file_sections[$section]['CustomIcon'] = &$_POST['CustomIcon'];
		$page->file_sections[$section]['dragheight'] = &$_POST['dragheight'];
		$page->file_sections[$section]['fullscreen'] = &$_POST['fullscreen'];
		$page->file_sections[$section]['sizeW'] = &$_POST['sizeW'];
		$page->file_sections[$section]['sizeH'] = &$_POST['sizeH'];
		$page->file_sections[$section]['responsive'] = &$_POST['responsive'];
		$page->file_sections[$section]['ratio'] = &$_POST['ratio'];
		$page->file_sections[$section]['map_type'] = &$_POST['map_type'];
		$page->file_sections[$section]['mc'] = &$_POST['mc'];

		return true;
	}

	static function DefaultContent($default_content, $type){
		if ($type != 'GoogleMaps_section'){
			return $default_content;
		}
		global $addonRelativeCode;
		$section = [];
		$section['gp_label'] = "Google Maps";
		$section['content'] = '<div class="mapCanvas" style=" width: 500px; height: 500px;"></div>
		<input type="hidden" name="zoom" class="zoom" value="1"/>';
		$section['sizeW'] = '500';
		$section['sizeH'] = '500';
		$section['responsive'] = '';
		$section['fullscreen'] = '';
		$section['dragheight'] = '480';
		$section['CustomIcon'] = '';
		$section['Bouncemarker'] = '';
		$section['markers'] = [];
		$section['values'] = [];
		$section['zoom'] = 0;
		$section['ratio'] = "56.25";
		$section['GMStyle'] = "";
		$section['mc'] = "true";
		$section['map_type'] = "0";//0-roadmap 1 -sat 2-hybrid 3-terrain
		$section['addonRelativeCode'] = $addonRelativeCode;

		return $section;
	}

	static function SectionToContent($section_data){
		if ($section_data['type'] != 'GoogleMaps_section'){
			return $section_data;
		}
		global $addonRelativeCode;

		$data = self::GetData();
		if ($data['apikey'] == "" or $data['apikey'] == 'YOUR_API_KEY'){
			ob_start();
			echo '<h4 class="text-center">Usage of the Google Maps APIs requires API key</h4>';
			echo '<p class="text-center">Insert API key on <a href="' . common::GetUrl('Admin_GoogleMapsMarker') . '">Plugin Admin page</a></p>';
			echo '<img class="img-responsive center-block" src="' . $addonRelativeCode . '/img/burn.png"/>';
			$section_data['content'] = ob_get_clean();
			return $section_data;
		}

		$sizeW = $section_data['sizeW'] ? $section_data['sizeW'] : 500;
		$sizeH = $section_data['sizeH'] ? $section_data['sizeH'] : 500;
		if ($section_data['mc'] == 'false'){
			return;
		}
		$markers = $section_data['markers'];
		$zoom = $section_data['zoom'];
		$dragheight = $section_data['dragheight'];

		ob_start();

		echo '<div style="display:none;">';
		echo '<div class="map_data">';
		if ($markers and $markers <> ""){
			foreach ($markers as $key => $value) {
				echo '<input type="hidden" id="' . $key . '" name="coords" value="' . $value['info'] . '"/>';
			}
		}
		echo '<input type="hidden" name="zoom" class="zoom" value="' . $zoom . '"/>';
		echo '<input type="hidden" name="dragheight" class="dragheight" value="' . $dragheight . '"  />';

		if ($section_data['Bouncemarker']){
			echo '<input type="checkbox" name="Bouncemarker" value="Bouncemarker" class="Bouncemarker" checked="checked" />';
		} else {
			echo '<input type="checkbox" name="Bouncemarker" value="Bouncemarker" class="Bouncemarker"/>';
		}

		echo '<input type="hidden" name="CustomIcon" class="CustomIcon" value="' . $section_data['CustomIcon'] . '"  />';
		echo '<input type="hidden" name="map_type" class="map_type" value="' . $section_data['map_type'] . '"  />';

		echo '<textarea rows="5" cols="45" name="GMStyle" class="GMStyle">' . $data['GMStyle'] . '</textarea>';

		echo '</div>';
		echo '</div>';//disp none

		if ($section_data['responsive']){
			$cl = 'r_map';
			$sizeW = "";
			$sizeH = "";
			$ratio = 'padding-bottom: ' . $section_data['ratio'] . '%;';;
		} else {
			$cl = "";
			$ratio = "";
		}

		if ($section_data['fullscreen']){
			$style = $section_data['responsive'] ? 'style="width:100%;"' : 'style=" width: ' . $sizeW . 'px;"';

			echo ' <div class="map-container" ' . $style . '>';
			echo ' <div class="btn-full-screen">';
			echo ' <a id="btn-enter-full-screen"><img alt="enter fullscreen" src="' . $addonRelativeCode . '/img/fullscreen_enter.png"/></a>';
			echo '<a id="btn-exit-full-screen"><img alt="exit fullscreen" src="' . $addonRelativeCode . '/img/fullscreen_exit.png"/></a>';
			echo '  </div>';
			echo '<div class="mapCanvas ' . $cl . '" style=" width: ' . $sizeW . 'px; height: ' . $sizeH . 'px;' . $ratio . '"></div>';
			echo ' </div>';

		} else {
			echo '<div class="mapCanvas ' . $cl . '" style=" width: ' . $sizeW . 'px; height: ' . $sizeH . 'px;' . $ratio . '"></div>';
		}

		$section_data['content'] = ob_get_clean();

		return $section_data;
	}

	static function GetData(){
		global $addonRelativeCode, $page, $addonPathData;

		$configFile = $addonPathData . '/config.php';
		$data = [];
		if (file_exists($configFile)){
			include $configFile;
			$data['apikey'] = $config['apikey'];
			$data['GMStyle'] = $config['GMStyle'];

		} else {
			$data['apikey'] = '';
			$data['GMStyle'] = '';
		}
		return $data;
	}

}

?>
