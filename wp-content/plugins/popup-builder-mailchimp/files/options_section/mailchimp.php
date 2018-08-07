<?php
	$apiKey = get_option("SG_MAILCHIMP_API_KEY");

	$status = SGPBMailChimp::apiKeyStatus($apiKey);
	/* if  not connected to app default set */
	$total = 0;

	if($status) {
		$mailchimpObj = MailchimpSingleton::getInstance($apiKey);
		$sgMialchimObj = new SGPBMailChimp($mailchimpObj);
		$total = $sgMialchimObj->getTotalCount();
	}

	$customOptions = '';

	$fromAlignmentOptions = GetOptionsData::formAlignmentOptions();
	$labelAlignmentOptions = GetOptionsData::labelAlignmentOptions();
	$successBehavior = GetOptionsData::getSuccessBehavior();
	$defaultValues = GetOptionsData::getDefaultValues();

	if (isset($_GET['id'])) {
		/* if edit mode */
		$options = $result->getMailchimpOptions();
		$customOptions = GetOptionsData::getOptionValue($options);
	}
	else {
		$customOptions = $defaultValues;
	}

	$mailchimpListId = $customOptions['mailchimp-list-id'];
	$mailchimpRequiredErrorMessage = $customOptions['mailchimp-required-error-message'];
	$mailchimpEmailValidateMessage = $customOptions['mailchimp-email-validate-message'];
	$mailchimpFormAligment = $customOptions['mailchimp-form-aligment'];
	$mailchimpLabelAligment = $customOptions['mailchimp-label-aligment'];
	$mailchimpSuccessMessage = $customOptions['mailchimp-success-message'];
	$mailchimpErrorMessage = $customOptions['mailchimp-error-message'];
	$mailchimpOnlyRequired = GetOptionsData::sgSetChecked($customOptions['mailchimp-only-required']);
	$mailchimpSuccessRedirectNewTab = GetOptionsData::sgSetChecked($customOptions['mailchimp-success-redirect-new-tab']);
	$mailchimpShowFormToTop = GetOptionsData::sgSetChecked($customOptions['mailchimp-show-form-to-top']);
	$mailchimpLabelColor = $customOptions['mailchimp-label-color'];
	$mailchimpInputWidth = $customOptions['mailchimp-input-width'];
	$mailchimpInputHeight = $customOptions['mailchimp-input-height'];
	$mailchimpInputBorderRadius = $customOptions['mailchimp-input-border-radius'];
	$mailchimpInputBorderWidth = $customOptions['mailchimp-input-border-width'];
	$mailchimpInputBorderColor = $customOptions['mailchimp-input-border-color'];
	$mailchimpInputBgColor = $customOptions['mailchimp-input-bg-color'];
	$mailchimpInputTextColor = $customOptions['mailchimp-input-text-color'];
	$mailchimpSubmitWidth = $customOptions['mailchimp-submit-width'];
	$mailchimpSubmitHeight = $customOptions['mailchimp-submit-height'];
	$mailchimpSubmitBorderWidth = $customOptions['mailchimp-submit-border-width'];
	$mailchimpSubmitBorderRadius = $customOptions['mailchimp-submit-border-radius'];
	$mailchimpSubmitBorderColor = $customOptions['mailchimp-submit-border-color'];
	$mailchimpSubmitColor = $customOptions['mailchimp-submit-color'];
	$mailchimpSubmitButtonBgcolor = $customOptions['mailchimp-submit-button-bgcolor'];
	$mailchimpSubmitTitle = $customOptions['mailchimp-submit-title'];
	$mailchimpEmailLabel = $customOptions['mailchimp-email-label'];
	$mailchimpIndicatesRequiredFields = @GetOptionsData::sgSetChecked($customOptions['mailchimp-indicates-required-fields']);

	$mailchimpClosePopupAlreadySubscribed = @GetOptionsData::sgSetChecked($customOptions['mailchimp-close-popup-already-subscribed']);
	$mailchimpDisableDoubleOptin = GetOptionsData::sgSetChecked($customOptions['mailchimp-disable-double-optin']);
	$mailchimpAsteriskLabel = (isset($customOptions['mailchimp-asterisk-label'])) ? $customOptions['mailchimp-asterisk-label']: $defaultValues['mailchimp-asterisk-label'];
	$mailchimpSuccessBehavior = (isset($customOptions['mailchimp-success-behavior'])) ? $customOptions['mailchimp-success-behavior']: $defaultValues['mailchimp-success-behavior'];
	$mailchimpSuccessRedirectUrl = (isset($customOptions['mailchimp-success-redirect-url'])) ? $customOptions['mailchimp-success-redirect-url']: $defaultValues['mailchimp-success-redirect-url'];
	$mailchimpSuccessPopupsList = (isset($customOptions['mailchimp-success-popups-list'])) ? $customOptions['mailchimp-success-popups-list']: $defaultValues['mailchimp-success-popups-list'];
?>
<div id="special-options">
	<div id="post-body" class="metabox-holder columns-2">
		<div id="postbox-container-2" class="postbox-container">
			<div id="normal-sortables" class="meta-box-sortables ui-sortable">
				<div class="postbox popup-builder-special-postbox">
					<div class="handlediv js-special-title" title="Click to toggle"><br></div>
					<h3 class="hndle ui-sortable-handle js-special-title">
						<span>Mailchimp options</span>
					</h3>
					<div class="special-options-content">
						<div class="sgpb-alert sgpb-alert-info fade in">
						  <span>If you have changed your Mailchimp list ID or the API key, please, update the popup to see the modified version of embedded form.</span>
						</div>
						<span class="liquid-width">Status:</span>
						<?php if(!$status): ?>
							<span class="sg-mailchimp-connect-status sg-mailchimp-not-connected">NOT CONNECTED</span><br>
						<?php else : ?>
							<span class="sg-mailchimp-connect-status sg-mailchimp-connected">CONNECTED</span><br>
						<?php endif;?>
						<span class="liquid-width">Your lists:</span>
						<?php if(!$status) { ?>
							<a href="<?php echo SG_APP_POPUP_ADMIN_URL?>admin.php?page=mailchimp">Setup Api key</a>
						<?php }
							else {
								/* When list is not empty */
								if($total != 0) {
									$data = $sgMialchimObj->getAllLists();
									echo $sgMialchimObj->allListSelectBox($data, $mailchimpListId, array("name"=>"mailchimp-list-id", "id"=>"sg-mailchimp-selectbox", "class"=>""));

								}
								else {
									echo "You don't have any lists yet. Please create a list first.";
								}
						 	}?>
						 	<img src="<?php echo plugins_url('images/wpAjax.gif', dirname(__FILE__).'../../../../'); ?>" alt="gif" class="spinner-mailchimp-list js-sg-spinner sg-hide-element js-sg-import-gif">
						<?php
							if($total != 0) {
								echo '<h1 class="sg-live-preview">Live Preview</h1>';
							}
						?>
						<!-- Here will be append form from ajax -->
						<div class="sg-mailchimp-form-wrapper" id="mc_embed_signup"></div>
						<textarea class="sg-hide-element" id="hidden-mailchimp-form-content" name="sg-mailchimp-form"></textarea>

						<span class="liquid-width"><b>General settings</b></span><br>
						<div class="sgmp-option-div">
							<span class="liquid-width">Disable double opt-in:</span>
							<input type="checkbox" class="mailchimp-double-optin" name="mailchimp-disable-double-optin" <?php echo $mailchimpDisableDoubleOptin;?>>
							<span class="dashicons dashicons-info  same-image-style"></span><span class="infoForMobile samefontStyle">With single opt-in, new subscribers fill out a signup form and are immediately added to a mailing list, even if their address is invalid or contains a typo. Single opt-in can clog your list with bad addresses, and possibly generate spam complaints from subscribers who don’t remember signing up.</span>
						</div>
						<div class="sg-mailchimp-options-wrapper">
							<span class="liquid-width">Show only required fields:</span>
							<input class="sg-show-only-required-fields" type="checkbox" name="mailchimp-only-required" <?php echo $mailchimpOnlyRequired;?>><br>
						</div>
						<div class="sg-mailchimp-options-wrapper">
							<span class="liquid-width">Enable asterisk label:</span>
							<input class="sg-indicates-required-fields" type="checkbox" name="mailchimp-indicates-required-fields" <?php echo $mailchimpIndicatesRequiredFields; ?>><br>
						</div>
						<div class="sg-mailchimp-options-wrapper">
							<span class="liquid-width">Form alignment:</span>
							<?php echo PBMFunctions::createSelectBox($fromAlignmentOptions, $mailchimpFormAligment, array("name"=>"mailchimp-form-aligment", "id"=>"sg-mailchimp-form-aligment", "class"=>"sg-mailchimp-selectbox")); ?><br>
						</div>
						<div class="sg-mailchimp-options-wrapper">
							<span class="liquid-width">Label alignment:</span>
							<?php echo PBMFunctions::createSelectBox($labelAlignmentOptions, $mailchimpLabelAligment, array("name"=>"mailchimp-label-aligment", "id"=>"sg-mailchimp-label-aligment", "class"=>"sg-mailchimp-selectbox")); ?><br>
						</div>
						<div class="sg-mailchimp-options-wrapper">
							<span class="liquid-width">Asterisk label:</span>
							<input type="text" class="mailchimp-asterisk-label sg-mailchimp-general-options" name="mailchimp-asterisk-label" value="<?php echo esc_attr($mailchimpAsteriskLabel);?>">
						</div>
						<div class="sg-mailchimp-options-wrapper">
							<span class="liquid-width">Required message:</span>
							<input type="text" name="mailchimp-required-error-message" class="sg-mailchimp-general-options" value="<?php echo esc_attr($mailchimpRequiredErrorMessage);?>">
						</div>
						<div class="sg-mailchimp-options-wrapper">
							<span class="liquid-width">Email message:</span>
							<input type="text" name="mailchimp-email-validate-message" class="sg-mailchimp-general-options" value="<?php echo esc_attr($mailchimpEmailValidateMessage);?>">
						</div>
						<div class="sg-mailchimp-options-wrapper">
							<span class="liquid-width">Email label:</span>
							<input type="text" name="mailchimp-email-label" class="mailchimp-email-label sg-mailchimp-general-options" value="<?php echo $mailchimpEmailLabel;?>">
						</div>
						<div class="error-message-content">
							<span class="liquid-width">Error message:</span>
							<input type="text" name="mailchimp-error-message" class="sg-mailchimp-general-options" value="<?php echo esc_attr($mailchimpErrorMessage);?>">
						</div>
						<span class="liquid-width">Show form before content:</span>
						<input type="checkbox" name="mailchimp-show-form-to-top" <?php echo $mailchimpShowFormToTop; ?>>
						<span class="dashicons dashicons-info  same-image-style"></span><span class="infoForMobile samefontStyle">If checked, the form will be displayed before the text.</span><br>

						<span class="liquid-width"><b>General style</b></span><br>
						<div class="sg-mailchimp-options-wrapper">
							<span class="liquid-width">Label color</span>
							<div class="color-picker"><input class="mailchimp-label-color" id="sgOverlayColor" type="text" name="mailchimp-label-color" value="<?php echo esc_attr(@$mailchimpLabelColor); ?>"></div><br>
						</div>

						<span class="liquid-width"><b>Inputs style</b></span><br>
						<div class="sg-mailchimp-options-wrapper">
							<span class="liquid-width">Input width:</span>
							<input type="text" class="mailchimp-input-width" name="mailchimp-input-width" value="<?php echo esc_attr($mailchimpInputWidth);?>">
						</div>
						<div class="sg-mailchimp-options-wrapper">
							<span class="liquid-width">Input height:</span>
							<input type="text" class="mailchimp-input-height" name="mailchimp-input-height" value="<?php echo esc_attr($mailchimpInputHeight);?>">
						</div>
						<div class="sg-mailchimp-options-wrapper">
							<span class="liquid-width">Border radius:</span>
							<input type="text" class="mailchimp-input-border-radius" name="mailchimp-input-border-radius" value="<?php echo esc_attr($mailchimpInputBorderRadius);?>">
							<span class="dashicons dashicons-info  same-image-style"></span><span class="infoForMobile samefontStyle">It must be number + px or %.</span><br>
						</div>
						<div class="sg-mailchimp-options-wrapper">
							<span class="liquid-width">Border width:</span>
							<input type="text" class="mailchimp-input-border-width" name="mailchimp-input-border-width" value="<?php echo esc_attr($mailchimpInputBorderWidth);?>">
						</div>
						<div class="sg-mailchimp-options-wrapper">
							<span class="liquid-width">Border color:</span>
							<div class="color-picker"><input class="mailchimp-input-border-color" id="sgOverlayColor" type="text" name="mailchimp-input-border-color" value="<?php echo esc_attr(@$mailchimpInputBorderColor); ?>"></div><br>
						</div>
						<div class="sg-mailchimp-options-wrapper">
							<span class="liquid-width">Background color:</span>
							<div class="color-picker"><input class="mailchimp-input-bg-color" id="sgOverlayColor" type="text" name="mailchimp-input-bg-color" value="<?php echo esc_attr(@$mailchimpInputBgColor); ?>"></div><br>
						</div>
						<div class="sg-mailchimp-options-wrapper">
							<span class="liquid-width">Text color:</span>
							<div class="color-picker"><input class="mailchimp-input-text-color" id="sgOverlayColor" type="text" name="mailchimp-input-text-color" value="<?php echo esc_attr(@$mailchimpInputTextColor); ?>"></div><br>
						</div>

						<span class="liquid-width"><b>Submit button style</b></span><br>
						<div class="sg-mailchimp-options-wrapper">
							<span class="liquid-width">Button title</span>
							<input type="text" name="mailchimp-submit-title" value="<?php echo esc_attr($mailchimpSubmitTitle);?>" class="mailchimp-submit-title sg-mailchimp-general-options">
						</div>
						<div class="sg-mailchimp-options-wrapper">
							<span class="liquid-width">Button width</span>
							<input type="text" name="mailchimp-submit-width" value="<?php echo esc_attr($mailchimpSubmitWidth);?>" class="mailchimp-submit-width">
						</div>
						<div class="sg-mailchimp-options-wrapper">
							<span class="liquid-width">Button height</span>
							<input type="text" name="mailchimp-submit-height" value="<?php echo esc_attr($mailchimpSubmitHeight);?>" class="mailchimp-submit-height">
						</div>
						<div class="sg-mailchimp-options-wrapper">
							<span class="liquid-width">Button border width</span>
							<input type="text" class="mailchimp-btn-border-width" name="mailchimp-submit-border-width" value="<?php echo esc_attr($mailchimpSubmitBorderWidth);?>">
							<span class="dashicons dashicons-info  same-image-style"></span><span class="infoForMobile samefontStyle">It must be number + px.</span><br>
						</div>
						<div class="sg-mailchimp-options-wrapper">
							<span class="liquid-width">Button border radius</span>
							<input type="text" class="mailchimp-btn-border-radius" name="mailchimp-submit-border-radius" value="<?php echo esc_attr($mailchimpSubmitBorderRadius);?>">
							<span class="dashicons dashicons-info  same-image-style"></span><span class="infoForMobile samefontStyle">It must be number + px or %.</span><br>
						</div>
						<div class="sg-mailchimp-options-wrapper">
							<span class="liquid-width">Button border color</span>
							<div class="color-picker"><input class="mailchimp-btn-border-color" id="sgOverlayColor" type="text" name="mailchimp-submit-border-color" value="<?php echo esc_attr(@$mailchimpSubmitBorderColor); ?>"></div><br>
						</div>
						<div class="sg-mailchimp-options-wrapper">
							<span class="liquid-width">Button background color:</span>
							<div class="color-picker"><input class="mailchimp-btn-background-color" id="sgOverlayColor" type="text" name="mailchimp-submit-button-bgcolor" value="<?php echo esc_attr(@$mailchimpSubmitButtonBgcolor); ?>"></div><br>
						</div>
						<div class="sg-mailchimp-options-wrapper">
							<span class="liquid-width">Button color:</span>
							<div class="color-picker"><input class="mailchimp-btn-color" id="sgOverlayColor" type="text" name="mailchimp-submit-color" value="<?php echo esc_attr(@$mailchimpSubmitColor); ?>"></div><br>
						</div>

						<div class="sg-radio-option-behavior">
						<span class="liquid-width"><b>After successful subscription</b></span><br>
							<?php createRadiobuttons($successBehavior, 'mailchimp-success-behavior', true, esc_html($mailchimpSuccessBehavior), "liquid-width");?>
							<div class="js-accordion-showMessage js-radio-accordion sg-accordion-content">
								<span class="liquid-width">Success message</span>
								<input type="text" name="mailchimp-success-message" value="<?php echo esc_attr($mailchimpSuccessMessage);?>"><br>
							</div>
							<div class="js-accordion-redirectToUrl js-radio-accordion sg-accordion-content">
								<span class="liquid-width">Redirect URL</span>
								<input class="input-width-static" type="text" name="mailchimp-success-redirect-url" value="<?php echo $mailchimpSuccessRedirectUrl;?>">
								<span class="liquid-width">Redirect to new tab</span>
								<input type="checkbox" name="mailchimp-success-redirect-new-tab" <?php echo $mailchimpSuccessRedirectNewTab; ?>>
							</div>
							<div class="js-accordion-openPopup js-radio-accordion sg-accordion-content">
								<span class="liquid-width">Select popup</span>
								<?php $popupsData =  SGFunctions::getPopupsDataList(array('type' => 'mailchimp'));
								echo sgCreateSelect($popupsData,'mailchimp-success-popups-list',esc_html($mailchimpSuccessPopupsList));
								?>
							</div>
						</div>

						<div class="sg-mailchimp-options-wrapper">
							<span class="liquid-width">Close popup if user already subscribed:</span>
							<input type="checkbox" name="mailchimp-close-popup-already-subscribed" <?php echo $mailchimpClosePopupAlreadySubscribed; ?>><br>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
$mailchimpParams = array(
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
	'mailchimpFormAligment' => $mailchimpFormAligment
);

wp_localize_script('mailchimpjs', 'SgMailchimpParams', $mailchimpParams);
wp_enqueue_script('mailchimpjs');
?>
<script type="text/javascript">
jQuery(".sg-mailchimp-form-wrapper").bind("sgMailchimpFormReady", function() {
	var mailchimpObj = new SgpbMailchimp();
	mailchimpObj.binding();
	mailchimpObj.build();
	mailchimpObj.formStyles();
});

</script>
