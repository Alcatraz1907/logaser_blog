<?php
require_once(SG_APP_POPUP_CLASSES.'/SGPopup.php');

class SGMailchimpPopup extends SGPopup {

	public $content;
	public $mailchimpOptions;

	public function __construct() {

		wp_register_script('sgValidate', SGPB_MAILCHIMP_URL . '/javascript/sgValidate.js', array(), SG_MAILCHIMP_VERSION);
		wp_enqueue_script('sgValidate');
		wp_register_style('sgpbMailchimp', SGPB_MAILCHIMP_STYLE_URL.'sgpbMailchimp.css', false, '1.0.0');
		wp_enqueue_style('sgpbMailchimp');
		wp_register_style('sgpb_mailchimp_default_form_style', SGPB_MAILCHIMP_STYLE_URL.'sgMailchimpDefaults.css', false, '1.0.0');
		wp_enqueue_style('sgpb_mailchimp_default_form_style');
	}

	public function setContent($content) {

		$this->content = $content;
	}

	public function getContent() {

		return $this->content;
	}

	public function setMailchimpOptions($options) {

		$this->mailchimpOptions = $options;
	}

	public function getMailchimpOptions() {

		return $this->mailchimpOptions;
	}

	public static function create($data, $obj = null) {

		$obj = new self();
		$options = json_decode($data['options'], true);

		$mailchimpOptions = $options['mailchimpOptions'];

		$obj->setMailchimpOptions($mailchimpOptions);
		$obj->setContent($data['mailchimp']);

		return parent::create($data, $obj);
	}

	public function save($data = array()) {

		$editMode = $this->getId()?true:false;

		$res = parent::save($data);
		if ($res===false) return false;

		$sgMailchimpContent = $this->getContent();
		$mailchimpOptions = $this->getMailchimpOptions();

		global $wpdb;
		if ($editMode) {
			$sgMailchimpContent = stripslashes($sgMailchimpContent);
			$sql = $wpdb->prepare("UPDATE ".$wpdb->prefix."sg_popup_mailchimp SET content=%s, options=%s WHERE id=%d", $sgMailchimpContent, $mailchimpOptions, $this->getId());
			$res = $wpdb->query($sql);
		}
		else {

			$sql = $wpdb->prepare("INSERT INTO ".$wpdb->prefix."sg_popup_mailchimp (id, content, options) VALUES (%d, %s, %s)",$this->getId(),$sgMailchimpContent, $mailchimpOptions);
			$res = $wpdb->query($sql);
		}

		return $res;
	}

	protected function setCustomOptions($id) {

		global $wpdb;
		$st = $wpdb->prepare("SELECT content, options FROM ".$wpdb->prefix."sg_popup_mailchimp WHERE id = %d", $id);
		$arr = $wpdb->get_row($st,ARRAY_A);
		$this->setContent($arr['content']);
		$this->setMailchimpOptions($arr['options']);
	}

	private function changeDimensionMode($dimension) {

		$size = (int)$dimension.'px';

		if(strpos($dimension, '%') || strpos($dimension, 'px')) {
			$size = $dimension;
		}

		return $size;
	}

	public function getMailchimpObject() {

		$apiKey = get_option("SG_MAILCHIMP_API_KEY");
		$mailchimpObj = MailchimpSingleton::getInstance($apiKey);
		$sgMailchimpObj = new SGPBMailChimp($mailchimpObj);

		return $sgMailchimpObj;
	}


	protected function getExtraRenderOptions() {

		$popupContent = '';
		$currentPopupId = $this->getId();
		$options = json_decode($this->getMailchimpOptions(), true);
		$mailchimpFormAlignment = $options['mailchimp-form-aligment'];
		$mailchimpLabelAlignment = $options['mailchimp-label-aligment'];
		$mailchimpSuccessMessage = $options['mailchimp-success-message'];
		$mailchimpErrorMessage = $options['mailchimp-error-message'];

		$mailchimpOnlyRequired = GetOptionsData::sgSetChecked(@$options['mailchimp-only-required']);
		$mailchimpShowFormToTop = GetOptionsData::sgSetChecked(@$options['mailchimp-show-form-to-top']);
		$mailchimpClosePopupAlreadySubscribed = GetOptionsData::sgSetChecked(@$options['mailchimp-close-popup-already-subscribed']);
		$mailchimpLabelColor = $options['mailchimp-label-color'];
		$mailchimpInputWidth = $options['mailchimp-input-width'];
		$mailchimpInputHeight = $options['mailchimp-input-height'];
		$mailchimpInputBorderRadius = $options['mailchimp-input-border-radius'];
		$mailchimpInputBorderWidth = $options['mailchimp-input-border-width'];
		$mailchimpInputBorderColor = $options['mailchimp-input-border-color'];
		$mailchimpInputBgColor = $options['mailchimp-input-bg-color'];
		$mailchimpInputTextColor = $options['mailchimp-input-text-color'];
		$mailchimpSubmitWidth = $options['mailchimp-submit-width'];
		$mailchimpSubmitHeight = $options['mailchimp-submit-height'];
		$mailchimpSubmitColor = $options['mailchimp-submit-color'];
		$mailchimpSubmitButtonBgcolor = $options['mailchimp-submit-button-bgcolor'];
		$mailchimpSubmitTitle = $options['mailchimp-submit-title'];
		$mailchimpSubmitBorderWidth = $options['mailchimp-submit-border-width'];
		$mailchimpSubmitBorderRadius = $options['mailchimp-submit-border-radius'];
		$mailchimpSubmitBorderColor = $options['mailchimp-submit-border-color'];
		$mailchimpIndicatesRequiredFields = GetOptionsData::sgSetChecked($options['mailchimp-indicates-required-fields']);
		if (empty($mailchimpIndicatesRequiredFields)) {
			$popupContent .= '<style type=\"text/css\">';
			$popupContent .= '.sgpb-asterisk,';
			$popupContent .= '.sgpb-indicates-required {';
			$popupContent .= 'display: none;';
			$popupContent .= '}';
			$popupContent .= '</style>';
		}
		$mailchimpSuccessBehavior = $options['mailchimp-success-behavior'];
		$mailchimpSuccessRedirectUrl = $options['mailchimp-success-redirect-url'];
		$mailchimpSuccessPopupsList = $options['mailchimp-success-popups-list'];
		$mailchimpListId  = $options['mailchimp-list-id'];
		$validateRequiredMessage  = $options['mailchimp-required-error-message'];
		$emailValidateMessage  = $options['mailchimp-email-validate-message'];
		$mailchimpSuccessRedirectNewTab  = GetOptionsData::sgSetChecked(@$options['mailchimp-success-redirect-new-tab']);

		$mailchimparams = array(
			'popupId' => $currentPopupId,
			'mailchimpInputWidth' => $mailchimpInputWidth,
			'mailchimpInputHeight' => $mailchimpInputHeight,
			'mailchimpInputBorderRadius' => $mailchimpInputBorderRadius,
			'mailchimpInputBorderWidth' => $mailchimpInputBorderWidth,
			'mailchimpInputBorderColor' => $mailchimpInputBorderColor,
			'mailchimpInputBgColor' => $mailchimpInputBgColor,
			'mailchimpInputTextColor' => $mailchimpInputTextColor,
			'mailchimpLabelColor' => $mailchimpLabelColor,
			'mailchimpSubmitWidth' => $mailchimpSubmitWidth,
			'mailchimpSubmitHeight' => $mailchimpSubmitHeight,
			'mailchimpSubmitButtonBgcolor' => $mailchimpSubmitButtonBgcolor,
			'mailchimpSubmitColor' => $mailchimpSubmitColor,
			'mailchimpSubmitTitle' => $mailchimpSubmitTitle,
			'mailchimpOnlyRequired' => $mailchimpOnlyRequired,
			'mailchimpSubmitBorderRadius' => $mailchimpSubmitBorderRadius,
			'mailchimpSubmitBorderWidth' => $mailchimpSubmitBorderWidth,
			'mailchimpSubmitBorderColor' => $mailchimpSubmitBorderColor,
			'mailchimpIndicatesRequiredFields' => $mailchimpIndicatesRequiredFields,
			'mailchimpSuccessBehavior' => $mailchimpSuccessBehavior,
			'mailchimpSuccessRedirectUrl' => $mailchimpSuccessRedirectUrl,
			'mailchimpSuccessPopupsList' => $mailchimpSuccessPopupsList,
			'mailchimpSuccessRedirectNewTab' => $mailchimpSuccessRedirectNewTab,
			'mailchimpClosePopupAlreadySubscribed' => $mailchimpClosePopupAlreadySubscribed
		);
		wp_register_script('mailchimpjs', SGPB_MAILCHIMP_URL . '/javascript/sgpbMailchimp.js', array(), SG_MAILCHIMP_VERSION);
		wp_localize_script('mailchimpjs', 'SgMailchimpParams', $mailchimparams);
		wp_enqueue_script('mailchimpjs');

		$popupContent .= "<div class=\"sgpb-alert sgpb-alert-success sg-hide-element js-sgpb-visibility\" >";
		$popupContent .= "<p id=\"mailchimp-default-success-message\">$mailchimpSuccessMessage</p>";
		$popupContent .= "</div>";

		$popupContent .= "<div class=\"sgpb-alert sgpb-alert-danger sg-hide-element js-sgpb-visibility\">";
		$popupContent .= "<p id=\"mailchimp-default-error-message\">$mailchimpErrorMessage</p>";
		$popupContent .= "</div>";


		$mailchimpForm = '<div class="sg-mailchimp-form-wrapper" id="mc_embed_signup">'.$options['sg-mailchimp-form'].'</div>';
		$content = $this->getContent();

		if($mailchimpSuccessBehavior == 'openPopup' && $mailchimpSuccessPopupsList != $currentPopupId) {
			$shortcodeContent = do_shortcode('[sg_popup id="'.$mailchimpSuccessPopupsList.'" event="click"] [/sg_popup]');
			add_action('wp_footer', function() use ($shortcodeContent) { echo $shortcodeContent; }, 1);
		}

		if(empty($mailchimpShowFormToTop)) {
			$popupContent .= $content.$mailchimpForm;
		}
		else {
			$popupContent .= $mailchimpForm.$content;
		}

		$mailchimpInputBorderRadius = $this->changeDimensionMode($mailchimpInputBorderRadius);
		$mailchimpSubmitBorderRadius = $this->changeDimensionMode($mailchimpSubmitBorderRadius);

		$popupContent .= "<style type=\"text/css\">
			.sgpb-embedded-subscribe {
				background-color: $mailchimpSubmitButtonBgcolor !important;
				color: $mailchimpSubmitColor !important;
				border-radius: $mailchimpSubmitBorderRadius !important;
				border-width: $mailchimpSubmitBorderWidth !important;
				border-color: $mailchimpSubmitBorderColor !important;
			}

			.sgpb-input {
				color: $mailchimpInputTextColor !important;
				border-radius: $mailchimpInputBorderRadius !important;
				border-width: $mailchimpInputBorderWidth !important;
				background-color: $mailchimpInputBgColor !important;
				border-color: $mailchimpInputBorderColor !important;
			}
			.sgpb-label {
				color: $mailchimpLabelColor !important;
			}
		</style>";

		if(!empty($mailchimpSubmitWidth)) {
			$mailchimpSubmitWidth = $this->changeDimensionMode($mailchimpSubmitWidth);
			$popupContent .= "<style type=\"text/css\">
				.sgpb-embedded-subscribe {
					width: $mailchimpSubmitWidth !important;
				}
			</style>";
		}

		if(!empty($mailchimpSubmitHeight)) {
			$mailchimpSubmitHeight = $this->changeDimensionMode($mailchimpSubmitHeight);
			$popupContent .= "<style type=\"text/css\">
				.sgpb-embedded-subscribe {
					height: $mailchimpSubmitHeight !important;
				}
			</style>";
		}

		if(!empty($mailchimpInputWidth)) {
			$mailchimpInputWidth = $this->changeDimensionMode($mailchimpInputWidth);
			$popupContent .= "<style type=\"text/css\">
				.sgpb-input  {
					width: $mailchimpInputWidth !important;
				}
			</style>";
		}

		if(!empty($mailchimpInputHeight)) {
			$mailchimpInputHeight = $this->changeDimensionMode($mailchimpInputHeight);
			$popupContent .= "<style type=\"text/css\">
				.sgpb-input {
					height: $mailchimpInputHeight !important;
				}
			</style>";
		}

		$popupContent .= "<style type=\"text/css\">
			#sgMailchimpForm {
				display: inline-block;
			}

		</style>";

		$popupContent .= "<style type=\"text/css\">
			.sgpb-label {
				display: block !important;
				text-align: ".$mailchimpLabelAlignment."
			}
		</style>";

		$popupContent .= "<style type=\"text/css\">
			.sg-mailchimp-form-wrapper {
				text-align: ".$mailchimpFormAlignment."
			}

			.sg-submit-wrapper {
				text-align: ".$mailchimpFormAlignment." !important;
			}

			.sgpb-validate-message {
				color: red !important;
			}
		</style>";

		$popupContent .= '<script type="text/javascript">
		jQuery(document).ready(function() {
		    jQuery("#sgcolorbox").ready(function() {
				jQuery("#sgcolorbox").bind("sgColorboxOnCompleate", function() {
					var mailchimpObj = new SgpbMailchimp();
					mailchimpObj.build();
				});

			});
		});

		</script>';

		$sgMailchimpObj = $this->getMailchimpObject();

		$requiredMessages = array(
			"required" => $validateRequiredMessage,
			"email" => $emailValidateMessage
		);
		$validateScript = $sgMailchimpObj->getValidateScript($mailchimpListId, $requiredMessages);

		$popupContent .= '<script type="text/javascript">
			jQuery(document).ready(function() {

				'.$validateScript.'
				var currentMailchimpForm = jQuery("#sg-popup-content-wrapper-'.$currentPopupId.' form");

				validateObj.submitHandler = function(){

					var formData = currentMailchimpForm.serialize();

					var doubleOptin = currentMailchimpForm.attr("data-double-optin");
					var dataWelcomeMessage = currentMailchimpForm.attr("data-welcome-message");
					var sgMailchimpSubmitButtonTitle = \'\';

					var data = {
					    action: "mailchimp_subscribe",
					    listId: "'.$mailchimpListId.'",
					    formData: formData,
					    doubleOptin: doubleOptin,
					    dataWelcomeMessage: dataWelcomeMessage,
					    beforeSend: function() {
					    	sgMailchimpSubmitButtonTitle = jQuery(".sgpb-embedded-subscribe").val();
					    	jQuery(".sgpb-embedded-subscribe").val("Please wait");
					    	jQuery(".sgpb-embedded-subscribe").attr("disabled","disabled");
					    }
					};

					jQuery.post("'.admin_url('admin-ajax.php').'", data, function(response) {

						jQuery(".sgpb-embedded-subscribe").val(sgMailchimpSubmitButtonTitle);
						jQuery(".sgpb-embedded-subscribe").removeAttr("disabled");
						var mailchimpObj = new SgpbMailchimp();
						mailchimpObj.sgpbMailchimpResponse(response, '.$currentPopupId.');
					});
				};
				validateObj.invalidHandler = function(form, validator) {
				    jQuery(document).on(\'DOMNodeInserted\', function(e) {
				        var popupId = SgMailchimpParams.popupId;
				        var popupData = SG_POPUP_DATA[popupId];
				        var popupDimensionMode = popupData[\'popup-dimension-mode\'];
				        if(popupDimensionMode == "responsiveMode") {
				            jQuery.sgcolorbox.resize();
				        }
				    });
				};
				currentMailchimpForm.validate(validateObj);

			});

		</script>';
		$content = $this->improveContent($popupContent);
		$this->sgAddPopupContentToFooter($content, $currentPopupId);

		return array('html'=>$content);
	}

	public function render() {

		return parent::render();
	}
}
