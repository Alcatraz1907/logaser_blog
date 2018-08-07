<?php
class sgMailchimpAjax {

	public function __construct() {

		$this->action();
	}

	public function action() {
		
		add_action('wp_ajax_get_mailchimp_html', array($this, "sgGetMailchimpHtml"));

		add_action('wp_ajax_mailchimp_subscribe', array($this, "sgMailchimpSubscribe"));
		add_action('wp_ajax_nopriv_mailchimp_subscribe', array($this, "sgMailchimpSubscribe"));
	}

	public function sanitizeField($optionsKey, $isTextField = false) {

		if (isset($_POST[$optionsKey])) {

			if ($isTextField == true) {
				$sgPopupData = $_POST[$optionsKey];
				return $sgPopupData;
			}
			return sanitize_text_field($_POST[$optionsKey]);
		}
		else {
			return "";
		}
	}

	public function sgMailchimpSubscribe() {

		$listId = $this->sanitizeField('listId');
		
		$doubleOptionStatus = 'pending';
		$formData = $this->sanitizeField('formData', true);
		parse_str($formData, $formData);
		$email = $formData['EMAIL'];
		$apiKey = get_option("SG_MAILCHIMP_API_KEY");
		$doubleOptin = $this->sanitizeField('doubleOptin');
		
		if($doubleOptin == "true") {
			$doubleOptionStatus = "subscribed";
		}
		
		$mailchimpObj = MailchimpSingleton::getInstance($apiKey);
		$sgMialchimpObj = new SGPBMailChimp($mailchimpObj);

		$margeData = $sgMialchimpObj->getMergeFiledsValuesFromListForm($listId, $formData);
		$interestData = $sgMialchimpObj->getInterestFiledsValuesFromListForm($listId, $formData);

		$params = array(
	        'email_address' => $email,
	        'merge_fields'  => $margeData,
	        'status'        => $doubleOptionStatus,   // double opt-in
		);

		/*When exist group elememt(s)*/
		if(!empty($interestData)) {
			$params['interests'] = $interestData;
		}

		$result = $mailchimpObj->post('/lists/'.$listId.'/members', $params);

		$responseStat = $result['status'];
		$responseTitle = $result['title'];
		if($responseStat != '400') {
			$responseStat = 200;
		}
		if($responseTitle == 'Member Exists') {
			$responseStat = 401;
		}
		/*When successfully subscribed*/
		if(!isset($result['title'])) {
					
			$responseTitle = 'Almost finished... We need to confirm your email address. To complete the subscription process, please click the link in the email we just sent you.';
			/*When turn of double opt-in option */
			if($doubleOptin == "true") {
				$responseTitle = 'You have successfully subscribed to our mail list.';
			}	
		}
	
		$formData = array('status' => $responseStat, 'message' => $responseTitle);

		echo json_encode($formData);
		die();
	}
 
	public function sgGetMailchimpHtml() {

		$content = "";
		$apiKey = get_option("SG_MAILCHIMP_API_KEY");
		$listId = $_POST['listId'];
		$validateRequiredMessage = $_POST['requiredFieldMessage'];
		$emailValidateMessage = $_POST['emailValidateMessage'];
		$asteriskLabel = $_POST['asteriskLabel'];
		$emailLabel = $this->sanitizeField('emailLabel');
		$submitTitle = $this->sanitizeField('submitTitle');
		$doubleOptin = $this->sanitizeField('doubleOptin');
		$showRequiredFields = $this->sanitizeField('showRequiredFields');
		$welcomeMessage = $this->sanitizeField('welcomeMessage');

		$mailchimpObj = MailchimpSingleton::getInstance($apiKey);
		$sgMailchimpObj = new SGPBMailChimp($mailchimpObj);
		$total = $sgMailchimpObj->getTotalCount();

		$listParams = array(
			'listId' => $listId,
			'emailLabel' => $emailLabel,
			'doubleOptin' => $doubleOptin,
			'showRequiredFields' => $showRequiredFields,
			'welcomeMessage' => $welcomeMessage,
			'asteriskLabel' => $asteriskLabel,
			'submitTitle' => $submitTitle
		);

		$formElement = $sgMailchimpObj->getListFormHtml($listParams);
		
		/*If user does not have list yet*/
		if($total == 0) {
			echo "";
			die();
		}
		$content .= $formElement;

		echo $content;
		die();
	}
}

$ajaxObj = new sgMailchimpAjax();
