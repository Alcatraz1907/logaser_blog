<?php
class PBMFunctions {

 	public static function createSelectBox($data, $selectedValue, $attrs) {

 		$attrString = '';
		$selected = '';

		if(!empty($attrs) && isset($attrs)) {

			foreach ($attrs as $attrName => $attrValue) {
				$attrString .= ''.$attrName.'="'.$attrValue.'" ';
			}
		}

		$selectBox = '<select '.$attrString.'>';

		foreach ($data as $value => $label) {

			if($selectedValue == $value) {
				$selected = 'selected';
			}
			$selectBox .= '<option value="'.$value.'" '.$selected.'>'.$label.'</option>';
			$selected = '';
		}

		$selectBox .= '</select>';

		return $selectBox;
 	}

 	public static function getApiKeySeccretView($apiKey) {

 		$strLength = strlen($apiKey);
 		/*length will be change*/
 		$length = ceil($strLength/2);
 		$secureAsterisks = str_repeat("*",$length);
 		$viewApiKey = substr_replace($apiKey,$secureAsterisks,0,$length);
 	
 		return $viewApiKey;
 	}
}