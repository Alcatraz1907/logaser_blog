<?php
use \DrewM\MailChimp\MailChimp;

class SGPBMailChimp {

	public static $mailchimpObj;
	private $showRequiredFields;

	public function __construct($obj) {

		self::$mailchimpObj = $obj;
	}

	public function setShowRequiredFields($showRequiredFields) {

		$this->showRequiredFields = false;

		if($showRequiredFields == 'true') {
			$this->showRequiredFields = true;
		}
	}

	public function getShowRequiredFields() {

		return $this->showRequiredFields;
	}

	/* return bool value check is valid Api key */
	public static function apiKeyStatus($apiKey) {

		$dashPosition = strpos( $apiKey, '-' );
		/*when the structure of the User inserted api key is different from the structure of the mailchimp Api key*/
		if( $dashPosition !== false ) {
			$apiUrl = 'https://' . substr( $apiKey, $dashPosition + 1 ) . '.api.mailchimp.com/3.0/?count=50&apikey='.$apiKey;
			$data = wp_remote_get($apiUrl);
			/* when has error for example inserted vl9573bf5f9b9e2a00457ba419c8afa8-us25*/
			if(is_array($data)) {
				if($data['body']) {
					$body = json_decode($data['body'], true);
					if(isset($body['account_id'])) {
						return true;
					}
					else {
						return false;
					}
				}
				else {
					return false;
				}
			}else {
				return false;
			}

		}
		else {
			return false;
		}
	}

	public function getAllLists() {

		/* get all list obj */
		$lists = self::$mailchimpObj->get('/lists/', array('count'=>SG_MAILCHIMP_LIST_LIMIT));

		return $lists;
	}

	public function getTotalCount() {

		$lists = $this->getAllLists();
		if(isset($lists) && is_array($lists)) {
			$total = $lists['total_items'];
		}
		else {
			$total = 0;
		}

		return $total;
	}

	public function getListMergeFields($listId) {

		$getMergeFields = self::$mailchimpObj->get('/lists/'.$listId.'/merge-fields');

		return $getMergeFields;
	}

	/**
	 * get Merge fields Names
	 *
	 * @since 1.1.5
	 *
	 * @param string $listId mailchimp form list id
	 *
	 * @return array
	 *
	 */

	public function getListMergeFieldsNames($listId) {

		$listFields = $this->getListMergeFields($listId);
		/*Default email input name which always exist*/
		$names = array('EMAIL');

		if(!empty($listFields['merge_fields'])) {
			foreach ($listFields['merge_fields'] as $field) {
				array_push($names, $field['tag']);
			}
		}

		return $names;
	}

	/**
	 * get Merge fields data
	 *
	 * @since 1.1.5
	 *
	 * @param string $listId mailchimp form list id
	 * @param array $formData mailchimp form all data
	 *
	 * @return assoc array
	 *
	 */

	public function getMergeFiledsValuesFromListForm($listId, $formData) {

		$margeNames = $this->getListMergeFieldsNames($listId);
		$margeData = array();

		if(!empty($formData)) {
			foreach ($formData as $key => $value) {

				if(in_array($key, $margeNames)) {
					$fieldTpe = $this->getMargeFieldTypeFormListByName($listId, $key);

					switch ($fieldTpe) {
						case 'date':
						case 'birthday':
							$value = $this->implodeArrayValues($value);
							$margeData[$key] = $value;
							break;
						case 'phone':
							if(!is_array($value)) {
								$margeData[$key] = $value;
								break;
							}
							$assocArrayValues = array_values($value);
							$implodeStirng = implode(" - ", $assocArrayValues);
							$margeData[$key] = $implodeStirng;
							break;
						default:
							$margeData[$key] = $value;
					}
				}
			}
		}

		return $margeData;
	}

	/**
	 * Get Merge field type
	 *
	 * @since 1.1.7
	 *
	 * @param int $listId
	 * @param string $elementName
	 *
	 * @return string $fieldType
	 *
	 */

	public function getMargeFieldTypeFormListByName($listId, $elementName) {

		$margeFields = $this->getListMergeFields($listId);
		$fieldType = '';

		/*When marge fields exists*/
		if(!empty($margeFields['merge_fields']) && is_array($margeFields['merge_fields'])) {
			$margeFields = $margeFields['merge_fields'];

			foreach ($margeFields as $field) {

				if($field['tag'] == $elementName) {
					$fieldType = $field['type'];
					return $fieldType;
				}
			}
		}

		return $fieldType;
	}

	/**
	 * Assoc array to string
	 *
	 * @since 1.1.7
	 *
	 * @param assoc array $dataArray
	 *
	 * @return string $implodeStirng
	 *
	 */
	public function implodeArrayValues($dataArray) {

		$implodeString = '';

		if(!is_array($dataArray)) {
			return $implodeString;
		}

		if(!empty($dataArray['month'])) {
			$implodeString .= $dataArray['month'].'/';
		}
		if(!empty($dataArray['day'])) {
			$implodeString .= $dataArray['day'];
		}
		if(!empty($dataArray['year'])) {
			$implodeString .= '/'.$dataArray['year'];
		}

		return $implodeString;
	}

	/**
	 * get Interest fields data
	 *
	 * @since 1.1.5
	 *
	 * @param string $listId mailchimp form list id
	 * @param array $formData mailchimp form all data
	 *
	 * @return assoc array
	 *
	 */

	public function getInterestFiledsValuesFromListForm($listId, $formData) {

		$margeNames = $this->getListMergeFieldsNames($listId);
		array_push($margeNames, 'subscribe');
		$interestData = array();

		if(!empty($formData)) {

			foreach ($formData as $key => $value) {

				if(is_array($value) && !in_array($key, $margeNames)) {
					foreach ($value as $elementValue) {
						$interestData[$elementValue] = true;
					}
				}
				if(!in_array($key, $margeNames)) {
					/*For Select box default empty value case*/
					if($value != ' ') {
						$interestData[$value] = true;
					}
				}
			}
		}

		return $interestData;
	}

	/**
	 * get Interest fields names
	 *
	 * @since 1.1.5
	 *
	 * @param string $listId mailchimp form list id
	 *
	 * @return  array
	 *
	 */
	public function getInterestNames($listId) {
		$container = self::$mailchimpObj->get('/lists/'.$listId.'/interest-categories/');
		$interestNames = array();

		foreach ($container['categories'] as $category) {

			$categoriesElements = self::$mailchimpObj->get('/lists/'.$category['list_id'].'/interest-categories/'.$category['id'].'/interests', array('count'=>SG_MAILCHIMP_LIST_LIMIT));
			if(isset($categoriesElements['interests'])) {
				foreach($categoriesElements['interests'] as $interests) {
					array_push($interestNames, $interests['id']);
				}
			}
		}

		return $interestNames;
	}

	public function isSecureUrl() {

		$isSecure = false;

		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'){
			$isSecure = true;
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
			$isSecure = true;
		}

		return $isSecure;
	}

	public function getCurrentFormAction($listId) {

		$lists = $this->getAllLists();
		$currentList = '';

		foreach ($lists['lists'] as $list) {

			if($list['id'] == $listId){
				$currentList = $list;
			}
		}

		$isSecure = $this->isSecureUrl();

		$REQUEST_PROTOCOL = $isSecure ? 'https:' : 'http:';

		$url = $REQUEST_PROTOCOL.preg_replace('#^[^:/.]*[:/]+#i', '//', $currentList['subscribe_url_long']);

		$comPosition = strpos($url, ".com");
		if($comPosition !== false && $isSecure){

			$deleteIndex = $comPosition-1;
			$isNumeric = is_numeric($url[$deleteIndex]);

			/*For checking is exist number next to manage*/
			if($isNumeric) {
				$url = substr_replace($url, '',$deleteIndex,1);
			}

		}

		$url = explode("?", $url);
		$url = $url['0']."/"."post-json?".$url['1']."&c=?";
		return $url;
	}

	private static function isComplexElement($field)
	{
		$isComplexElement = false;

		if($field['type'] == 'phone') {
			if($field['options']['phone_format'] == 'US') {
				$isComplexElement = true;
			}
		}

		if($field['type'] == 'date' || $field['type'] == 'birthday') {
			$isComplexElement = true;
		}
		if($field['type'] == 'address') {
			$isComplexElement = true;
		}

		return $isComplexElement;
	}

	private static function isNumberElement($field)
	{
		$isNumber = false;

		if($field['type'] == 'phone') {
			if($field['options']['phone_format'] == 'none') {
				$isNumber = true;
			}
		}
		else if($field['type'] == 'number') {
			$isNumber = true;
		}

		return $isNumber;
	}

	private static function createComplexElementValidation($field, $requiredMessages)
	{
		$validateData = '';
		if($field['type'] == 'phone') {
			if ($field['options']['phone_format'] == 'US') {
				$validateData['ruleData'][] = "'".$field['tag']."[area]' : {
					complexFieldsValidation: true,
					numberCustomCheck: true
				},";
				$validateData['message'][] = "'".$field['tag']."[area]': {complexFieldsValidation: '".$requiredMessages['required']."', numberCustomCheck: 'Please enter only digits.'},";
			}
		}
		if($field['type'] == 'date' || $field['type'] == 'birthday') {
			$validateData['ruleData'][] = "'".$field['tag']."[month]' : {
					complexFieldsValidation: true
				},";
			$validateData['message'][] = "'".$field['tag']."[month]': '".$requiredMessages['required']."',";
		}
		if($field['type'] == 'address') {
			$validateData['ruleData'][] = "'".$field['tag']."[addr1]' : {
					complexFieldsValidation: true
				},";
			$validateData['message'][] = "'".$field['tag']."[addr1]': '".$requiredMessages['required']."',";
			$validateData['ruleData'][] = "'".$field['tag']."[city]' : {
					complexFieldsValidation: true
				},";
			$validateData['message'][] = "'".$field['tag']."[city]': '".$requiredMessages['required']."',";
			$validateData['ruleData'][] = "'".$field['tag']."[state]' : {
					complexFieldsValidation: true
				},";
			$validateData['message'][] = "'".$field['tag']."[state]': '".$requiredMessages['required']."',";
			$validateData['ruleData'][] = "'".$field['tag']."[zip]' : {
					complexFieldsValidation: true
				},";
			$validateData['message'][] = "'".$field['tag']."[zip]': '".$requiredMessages['required']."',";
		}

		return $validateData;
	}

	private static function validateComplexDataCustomRule() {

		$customRule = 'jQuery.validator.addMethod("complexFieldsValidation", function(value, element) {

			var className = jQuery(element).attr("data-class-name");
			var status = true;
			var validateElements = jQuery("."+className);

			if(validateElements.length) {
				validateElements.removeClass("sgpb-validate-message");
				validateElements.each(function() {
					if(jQuery(this).val() == "") {
						status = false;
						validateElements.addClass("sgpb-validate-message");
					}
				})
			}

			return status;
		});
		jQuery.validator.addMethod("numberCustomCheck", function(value, element) {

			var className = jQuery(element).attr("data-class-name");
			var status = true;
			var validateElements = jQuery("."+className);

			if(validateElements.length) {
				validateElements.removeClass("sgpb-validate-message");
				validateElements.each(function() {

					if(!isFinite(jQuery(this).val())) {
						status = false;
						validateElements.addClass("sgpb-validate-message");
					}
				})
			}

			return status;
		});

		';

		return $customRule;
	}

	private static function showErrorMessages()
	{
		$messages = "jQuery.validator.setDefaults({
		    errorPlacement: function(error, element) {
		        var errorWrapperClassName = jQuery(element).attr('data-error-message-class');
		        jQuery('.'+errorWrapperClassName).html(error);
		    }
		});";

		return $messages;
	}

	public function getValidateScript($listId, $requiredMessages) {

		$margeFields = $this->getListMergeFields($listId);
		$margeFieldsData = array();
		$rules = 'rules: { ';
		$messages = 'messages: { ';
		$validateObj = self::validateComplexDataCustomRule();
		$validateObj .= $this->showErrorMessages();
		$validateObj .= 'validateObj = { ';

		$rules .= 'EMAIL: "required"'.',';
		$messages .= 'EMAIL: "'.$requiredMessages['email'].'",';

		if(!empty($margeFields['merge_fields'])) {
			$margeFieldsData = $margeFields['merge_fields'];
		}
		foreach ($margeFieldsData as $field) {

			if(empty($field)) {
				continue;
			}

			if($field['required']) {
				$isComplexElement = self::isComplexElement($field);
				$isNumberElement = self::isNumberElement($field);

				if($isNumberElement) {
					$rules .= "".$field['tag'].": {
						required: true,
                        number: true
                    },";
				}
				else if($isComplexElement) {
					$complexElementValidateData = self::createComplexElementValidation($field, $requiredMessages);

					if(!empty($complexElementValidateData['ruleData'])) {
						foreach ($complexElementValidateData['ruleData'] as $ruleData) {
							$rules .= $ruleData;
						}
					}
					if(!empty($complexElementValidateData['message'])) {
						foreach ($complexElementValidateData['message'] as $ruleMessage) {
							$messages .= $ruleMessage;
						}
					}
				}
				else {
					$rules .= "".$field['tag']." : 'required',";
					$messages .= "".$field['tag'].": '".$requiredMessages['required']."',";
				}

			}
		}
		$rules = rtrim($rules, ",");
		$messages = rtrim($messages, ",");

		$rules .= '},';
		$messages .= '}';
		$validateObj .= $rules;
		$validateObj .= $messages;
		$validateObj .= '};';

		return $validateObj;
	}

	public function createRadioButtons($field) {

		$reqStar = '';
		$hideField = '';
		$i = 0;
		$optionStatusClassName = 'sgpb-optional-field';
		$req = $field['required'];

		if(!$field['required'] && $this->getShowRequiredFields()) {
			return '';
		}

		if($req) {
			$optionStatusClassName = 'sgpb-required-field';
			$reqStar = ' <span class="asterisk sgpb-asterisk">*</span>';
		}

		$public = $field['public'];

		/*When does not required but hidden filed*/
		if(!$req && !$public) {
			$hideField = 'sg-hide-element';
		}

		$output = '<div class="mc-field-group input-group sgpb-field-group '.$optionStatusClassName.' '.$hideField.'">';
		$output .= '<label class="sgpb-label" for="mce-'.$field['tag'].'">'.$field['name'].$reqStar.'</label>';
		$output .= '<ul class="sgpb-ul">';

			foreach ($field['options'] as $key => $options) {

				foreach ($options as $optionValue) {
					$output .= '<li><input type="'.$field['type'].'" name="'.$field['tag'].'" value="'.$optionValue.'" id="mce-'.$field['tag'].'-'.$i.'">
					<label for="mce-'.$field['tag'].'-'.$i.'">'.$optionValue.'</label></li>';
					$i++;
				}
			}
		$output .= "</ul>";
		$output .='<label for="'.$field['tag'].'" class="error" style="display:none" ></label>';
		$output .= '</div>';

		return $output;
	}

	public function createSimpleElement($field) {

		$reqStar = '';
		$required = '';
		$hideField = '';
		$optionStatusClassName = 'sgpb-optional-field';
		$type = $field['type'];
		$name = $field['tag'];
		$label = $field['name'];
		$req = $field['required'];
		$public = $field['public'];

		if(!$req && $this->getShowRequiredFields()) {
			return '';
		}

		/*When does not required but hidden filed*/
		if(!$req && !$public) {
			$hideField = 'sg-hide-element';
		}

		if($req) {
			$required = 'required';
			$optionStatusClassName = 'sgpb-required-field';
			$reqStar = '<span class="asterisk sgpb-asterisk">*</span>';
		}
		$output = '<div class="mc-field-group sgpb-field-group '.$optionStatusClassName.' '.$hideField.'" >';
		$output .= '<label class="sgpb-label" for="mce-'.$name.'">'.$label.$reqStar."</label>";
		$output .= '<input type="'.$type.'" name="'.$name.'" id="mce-'.$name.'" class="'.$required.' sgpb-input" data-error-message-class="'.$name.'-error-message">';
		$output .= '<div id="simpleValidateMessage" class="'.$name.'-error-message sgpb-validate-message"></div>';
		$output .= '</div>';

		return $output;
	}

	public function createAddressField($field) {

		$reqStar = '';
		$required = '';
		$hideField = '';
		$optionStatusClassName = 'sgpb-optional-field';
		$name = $field['tag'];
		$label = $field['name'];
		$req = $field['required'];

		if(!$req && $this->getShowRequiredFields()) {
			return '';
		}

		$public = $field['public'];

		/*When does not required but hidden filed*/
		if(!$req && !$public) {
			$hideField = 'sg-hide-element';
		}

		if($req) {
			$required = 'required';
			$optionStatusClassName = 'sgpb-required-field';
			$reqStar = '<span class="asterisk sgpb-asterisk">*</span>';
		}

		$output = '<div class="mc-address-group sgpb-address-group '.$optionStatusClassName.' '.$hideField.'" >';
			$output .= '<div class="mc-field-group sgpb-field-group ">';
				$output .= '<label class="sgpb-label" for="mce-'.$name.'-addr1">'.$label.' '.$reqStar.'</label>';
				$output .= '<input type="text" class="sgpb-address-addr1" maxlength="70" name="'.$name.'[addr1]"id="mce-'.$name.'-addr1" class="'.$required.' sgpb-input" data-class-name="sgpb-address-addr1" data-error-message-class="address-addr1-error-message">';
				$output .= '<div class="address-addr1-error-message sgpb-validate-message"></div>';
			$output .= '</div>';
			$output .= '<div class="mc-field-group sgpb-field-group ">';
				$output .= '<label class="sgpb-label" for="mce-'.$name.'-addr2">Address Line 2</label>';
				$output .= '<input type="text"  maxlength="70" name="'.$name.'[addr2]"id="mce-'.$name.'-addr2" class=" sgpb-input">';
			$output .= '</div>';
			$output .= '<div class="mc-field-group sgpb-field-group ">';
				$output .= '<label class="sgpb-label" for="mce-'.$name.'-city">City '.$reqStar.'</label>';
				$output .= '<input type="text" class="sgpb-address-city"  maxlength="40" name="'.$name.'[city]" id="mce-'.$name.'-city" class="'.$required.' sgpb-input" data-class-name="sgpb-address-city" data-error-message-class="address-city-error-message">';
				$output .= '<div class="address-city-error-message sgpb-validate-message"></div>';
			$output .= '</div>';
			$output .= '<div class="mc-field-group sgpb-field-group ">';
				$output .= '<label class="sgpb-label" for="mce-'.$name.'-state">State/Province/Region '.$reqStar.'</label>';
				$output .= '<input type="text" class="sgpb-address-state" maxlength="20" name="'.$name.'[state]" id="mce-'.$name.'-state" class="'.$required.' sgpb-input" data-class-name="sgpb-address-state" data-error-message-class="address-state-error-message">';
				$output .= '<div class="address-state-error-message sgpb-validate-message"></div>';
			$output .= '</div>';
			$output .= '<div class="mc-field-group sgpb-field-group ">';
				$output .= '<label class="sgpb-label" for="mce-'.$name.'-zip">Postal / Zip Code '.$reqStar.'</label>';
				$output .= '<input type="text" class="sgpb-address-zip" maxlength="10" name="'.$name.'[zip]" id="mce-'.$name.'-zip" class="'.$required.' sgpb-input" data-class-name="sgpb-address-zip" data-error-message-class="address-zip-error-message">';
				$output .= '<div class="address-zip-error-message sgpb-validate-message"></div>';
			$output .= '</div>';
			$output .= '<div class="mc-field-group sgpb-field-group ">';
				$output .= '<label class="sgpb-label" for="mce-'.$name.'-country">Country '.$reqStar.'</label>';
				$output .= '<select name="'.$name.'[country]" id="mce-'.$name.'-country" class="'.$required.' sgpb-input" aria-required="true">';
				$output .= GetOptionsData::getMailchimpCountryOptions();
				$output .= '</select>';
			$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	public function createDateElement($field) {

		$optionStatusClassName = 'sgpb-optional-field';
		$hideField = '';
		$name = $field['tag'];
		$label = $field['name'];
		$req = $field['required'];
		$options = $field['options'];
		$reqStar = '';
		$output = '';
		$public = $field['public'];

		/*When does not required but hidden filed*/
		if(!$req && !$public) {
			$hideField = 'sg-hide-element';
		}

		if(!$req && $this->getShowRequiredFields()) {
			return '';
		}
		$allOptions = array_values($options);

		foreach ($allOptions as $option) {
			$optionElements = explode("/", $option);
		}

		if(!isset($optionElements)) {
			return $output;
		}

		$autofocusId = "mce-$name-month";

		if($req) {
			$optionStatusClassName = 'sgpb-required-field';
			$reqStar = '<span class="asterisk sgpb-asterisk">*</span>';
		}

		$output = '<div class="mc-field-group sgpb-field-group '.$optionStatusClassName.' '.$hideField.'">';
		$output .= "<label class='sgpb-label' for=$autofocusId>$label.$reqStar</label>";
		$dateInputs = '';

		foreach ($optionElements as $element) {

			if($element == 'DD') {
				$dateInputs .= '<span class="subfield dayfield sgpb-subfield">';
					$dateInputs .= '<input class="datepart sgpb-datepart sgpb-input" type="text" pattern="[0-9]*"  value="" placeholder="DD" size="2" maxlength="2" name="'.$name.'[day]" id="mce-'.$name.'-day">';
				$dateInputs .= '</span>';
			}

			if($element == "MM") {
				$dateInputs .= '<span class="subfield monthfield sgpb-subfield">';
					$dateInputs .= "<input class=\"datepart sgpb-input\" type=\"text\" pattern=\"[0-9]*\"  value=\"\" placeholder=\"MM\" size=\"2\" maxlength=\"2\" name=\"".$name."[month]\" id=\"mce-".$name."-month\" data-class-name=\"datepart\" data-error-message-class=\"$name-error-message\">";
				$dateInputs .= '</span>';
			}
			if($element == "YYYY") {
				$dateInputs .= '<span class="subfield yearfield sgpb-subfield">';
					$dateInputs .= "<input class=\"datepart sgpb-input\" type=\"text\" pattern=\"[0-9]*\"  value=\"\" placeholder=\"YYYY\" size=\"4\" maxlength=\"4\" name=\"".$name."[year]\" id=\"mce-".$name."-year\">";
				$dateInputs .= '</span>';
			}
			if($element == "/") {
				$dateInputs .= " / ";
			}
		}

		$output .= $dateInputs;

		$output .= '<div id="phoneValidateMessage" class="'.$name.'-error-message sgpb-validate-message"></div>';
		$output .= '</div>';

		return $output;
	}


	public function createDropDown($field) {

		$hideField = '';
		$name = $field['tag'];
		$label = $field['name'];
		$req = $field['required'];
		$options = $field['options'];
		$reqStar = '';
		$optionStatusClassName = 'sgpb-optional-field';
		$allOptions = array_values($options);
		$allOptions = $allOptions[0];
		$firstData = array('');
		$allOptions = array_merge($firstData, $allOptions);
		$public = $field['public'];

		/*When does not required but hidden filed*/
		if(!$req && !$public) {
			$hideField = 'sg-hide-element';
		}

		if(!$req && $this->getShowRequiredFields()) {
			return '';
		}
		if($req) {
			$optionStatusClassName = 'sgpb-required-field';
			$reqStar = ' <span class="asterisk sgpb-asterisk">*</span>';
		}

		$output = '<div class="mc-field-group sgpb-field-group '.$optionStatusClassName.' '.$hideField.'">';
		$output .= "<label class='sgpb-label' for=\"mce-".$name."\">$label$reqStar</label>";
		$selectBoxData = array_combine($allOptions, $allOptions);
		$output .= PBMFunctions::createSelectBox($selectBoxData, '', array('class'=>'sgpb-selectbox sgpb-input', 'name'=>$name, 'id'=>"mce-$name", 'data-error-message-class' => $name.'-error-message'));

		$output .= '<div id="selectBoxValidateMessage" class="'.$name.'-error-message sgpb-validate-message"></div>';
		$output .= "</div>";

		return  $output;
	}

	public function createPhoneElements($field) {

		$name = $field['tag'];
		$label = $field['name'];
		$req = $field['required'];
		$options = $field['options'];
		$reqStar = '';
		$hideField = '';
		$public = $field['public'];

		/*When does not required but hidden filed*/
		if(!$req && !$public) {
			$hideField = 'sg-hide-element';
		}

		$output = '<div class="mc-field-group sgpb-field-group '.$hideField.'">';

		if(!$req && $this->getShowRequiredFields()) {
			return '';
		}

		if($req) {
			$reqStar = '<span class="asterisk sgpb-asterisk">*</span>';
		}

		$output .= "<label class=\"sgpb-label\" for=\"mce-".$name."\">$label$reqStar</label>";
		if($options['phone_format'] == 'US') {
			$output .= '<div class="phonefield phonefield-us">';
			$output .= "( <span class=\"phonearea\"> <input  class=\"phonePart sgpb-input-style\" pattern=\"[0-9]*\" id=\"mce-".$name."-area\" name=\"".$name."[area]\"  maxlength=\"3\" size=\"3\" data-class-name='phonePart' data-error-message-class='$name-error-message'></span> )";
			$output .= "<span class=\"phonedetail1\"><input class=\"phonePart sgpb-input-style\" pattern=\"[0-9]*\" type=\"text\" id=\"mce-".$name."-detail1\" name=\"".$name."[detail1]\" maxlength=\"3\" size=\"3\" ></span>";
			$output .= " - ";
			$output .= '<span class="phonedetail2"><input class="phonePart sgpb-input-style" pattern="[0-9]*" id="mce-'.$name.'-detail2" name="'.$name.'[detail2]" maxlength="4" size="4" value="" type="text"></span>';
			$output .= '<span class="small-meta nowrap">(###) ###-####</span>';
			$output .= '</div>';
		}
		else {
			$output .= "<input class=\"sgpb-input\" type=\"number\" name=\"$name\" id=\"mce-".$name."\"  data-error-message-class='$name-error-message'>";
		}
		$output .= '<div id="phoneValidateMessage" class="'.$name.'-error-message sgpb-validate-message"></div>';
		$output .= '</div>';

		return $output;
	}

	public function createFormElement($field) {

		$type = $field['type'];
		$element = '';

		switch ($type) {
			case 'text':
			case 'zip':
			case 'url':
			case 'number':
				$element = $this->createSimpleElement($field);
				break;
			case 'date':
			case 'birthday':
				$element = $this->createDateElement($field);
				break;
			case 'dropdown':
				$element = $this->createDropDown($field);
				break;
			case 'imageurl':
				$element = $this->createSimpleElement($field);
				break;
			case 'phone':
				$element = $this->createPhoneElements($field);
				break;
		    case 'radio':
			    $element = $this->createRadioButtons($field);
		        break;
		    case 'address':
			    $element = $this->createAddressField($field);
		        break;
		}

		return $element;
	}

	public function interestDropdown($categoriesElements, $args) {

		$selectBoxName = $args['name'];
		$interestDropdownData = array(' '=>' ');

		$selectBox = '<div class="mc-field-group sgpb-field-group">';
		$selectBox .= '<label class="sgpb-label">'.$args['title'].'</label>';

		foreach ($categoriesElements['interests'] as $element) {
			$interestDropdownData[$element['id']] = $element['name'];
		}
		$selectBox .= PBMFunctions::createSelectBox($interestDropdownData, '', array('name'=>$selectBoxName,'class'=>'REQ_CSS sgpb-selectbox sgpb-input'));

		$selectBox .= '</div>';

		return $selectBox;
	}

	public function createInputElements($inputData, $type, $inputName) {

		$checkboxes = '<ul>';
			$checkboxesLi = '';
			foreach ($inputData as $element) {
				$checkboxesLi .= '<li>';
					$checkboxesLi .= '<input type="'.$type.'" value="'.$element['id'].'" id="" name="'.$inputName.'">';
					$checkboxesLi .= '<label>'.$element['name'].'</label>';
				$checkboxesLi .= '</li>';
			}
			$checkboxes .= $checkboxesLi;
		$checkboxes .= '</ul>';

		return $checkboxes;
	}

	public function interestCheckboxes($categoriesElements, $args) {

		$inputName = $args['name']."[]";

		$checkboxes = '<div class="mc-field-group input-group">';
			$checkboxes .= '<strong>'.$args['title'].'</strong>';
			$checkboxes .= $this->createInputElements($categoriesElements['interests'], 'checkbox', $inputName);
		$checkboxes .= '</div>';

		return $checkboxes;
	}

	public function insteresRadioButtons($categoriesElements, $args) {

		$inputName =  $args['name'];

		$checkboxes = '<div class="mc-field-group input-group">';
			$checkboxes .= '<strong>'.$args['title'].'</strong>';
			$checkboxes .= $this->createInputElements($categoriesElements['interests'], 'radio', $inputName);
		$checkboxes .= '</div>';

		return $checkboxes;
	}

	public function getInteresElements($listId) {

		$container = self::$mailchimpObj->get('/lists/'.$listId.'/interest-categories/');

		if(!isset($container) || !is_array($container['categories'])) {
			return '';
		}
		$interestFields = '';
		foreach ($container['categories'] as $category) {

			$titleId = explode(' ', $category['title']);
			if(isset($titleId) && is_array($titleId)) {
				$titleId = $titleId[1];
			}
			else {
				$titleId = '';
			}


			$subDiv = '<div id="mergeRow-100-'.$titleId.'" class="mergeRow">';

				$categoriesElements = self::$mailchimpObj->get('/lists/'.$category['list_id'].'/interest-categories/'.$category['id'].'/interests', array('count'=>SG_MAILCHIMP_LIST_LIMIT));
				$args = array(
					'title' => $category['title'],
					'name' => $category['id']
				);

				switch ($category['type']) {
					case 'dropdown':
						$subDiv .= $this->interestDropdown($categoriesElements, $args);
						break;
					case 'checkboxes':
						$subDiv .= $this->interestCheckboxes($categoriesElements, $args);
						break;
					case 'radio':
						$subDiv .= $this->insteresRadioButtons($categoriesElements, $args);
						break;
				}
			$subDiv .= '</div>';

			$interestFields .= $subDiv;
		}
		return $interestFields;
	}


	public function getListFormHtml($params) {

		$output = "";
		$listId = $params['listId'];
		$emailLabel = $params['emailLabel'];
		$doubleOptin = $params['doubleOptin'];
		$showRequiredFields = $params['showRequiredFields'];
		$welcomeMessage = $params['welcomeMessage'];
		$asteriskLabel = $params['asteriskLabel'];
		$margeFields = $this->getListMergeFields($listId);
		$action = $this->getCurrentFormAction($listId);
		$submitTitle = $params['submitTitle'];

		$this->setShowRequiredFields($showRequiredFields);

		$output .= '<form action="'.$action.'" method="post" id="sgMailchimpForm" name="mc-embedded-subscribe-form" class="sgMailchimpForm validate" target="_blank" novalidate data-double-optin="'.$doubleOptin.'" data-welcome-message="'.$welcomeMessage.'">';
		$output .= '<div class="indicates-required sgpb-indicates-required"><span class="asterisk">*</span> '.$asteriskLabel.'</div>';
		$output .= '<div class="mc-field-group sgpb-required-field sgpb-field-group sg-mailchimp-email-wrapp">
					<label class="sgpb-label" for="mce-EMAIL"><span class="sgrb-label-text">'.$emailLabel.'</span>  <span class="asterisk sgpb-asterisk">*</span></label>';
		$output .= "<input type=\"email\" value=\"\" name=\"EMAIL\" id=\"mce-EMAIL\" class=\"required email sgpb-input\" id=\"mce-EMAIL\" aria-required=\"true\" data-error-message-class='email-error-message'>
					<div id='emailValidateMessage' class='email-error-message'></div>
		</div>";

		foreach ($margeFields['merge_fields'] as $field) {
			$output .= $this->createFormElement($field);
		}
		$output .= $this->getInteresElements($listId);
		$output .= '<div class="mc-field-group sg-clear sg-submit-wrapper"><input type="submit" value="'.stripslashes(esc_html($submitTitle)).'" name="subscribe" id="mc-embedded-subscribe" class="sgpb-embedded-subscribe sg-button"></div>';
		$output .= "</form>";

		return $output;
	}

	public function allListSelectBox($data, $selectedValue, $attrs) {

		$attrString = '';
		$selected = '';

		if(!empty($attrs) && isset($attrs)) {
			foreach ($attrs as $attrName => $attrValue) {
				$attrString .= ''.$attrName.'="'.$attrValue.'" ';
			}
		}

		$selectBox = '<select '.$attrString.'>';
		foreach ($data["lists"] as $list) {
			if($selectedValue == $list['id']) {
				$selected = 'selected';
			}
			$selectBox .= '<option value="'.$list['id'].'" '.$selected.'>'.$list['name'].'</option>';
			$selected = '';
		}
		$selectBox .= '</select>';

		return $selectBox;
	}
}
