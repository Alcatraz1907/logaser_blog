function SgpbMailchimp() {

}

SgpbMailchimp.cookieExpirationDate = 365;

SgpbMailchimp.prototype = {
	getMailchimpList: function() {
		var that = this;

		var data = {
		    action: 'get_mailchimp_html',
			beforeSend: function() {
				jQuery(".spinner-mailchimp-list").removeClass("sg-hide-element");
				SgpbMailchimp.changeElementDisableStatus(jQuery("#sg-save-button"), true);
				SgpbMailchimp.changeElementDisableStatus(jQuery(".sg-popup-general-option"), true);
				SgpbMailchimp.changeElementDisableStatus(jQuery(".mailchimp-double-optin"), true);
				SgpbMailchimp.changeElementDisableStatus(jQuery('.sg-mailchimp-general-options'), true);
				SgpbMailchimp.changeElementDisableStatus(jQuery(".sg-show-only-required-fields"), true);
			}
		};

		if(jQuery("#sg-mailchimp-selectbox").length != 0) {
			this.prepareToAjax(data);
		}

		jQuery(".mailchimp-double-optin").bind('change', function () {
			that.prepareToAjax(data);
		});

		jQuery('.sg-mailchimp-general-options').each(function () {
			jQuery(this).bind('change', function () {
				that.prepareToAjax(data);
			});
		});

		jQuery(".sg-show-only-required-fields").bind('change', function () {
			that.prepareToAjax(data);
		});

		jQuery("#sg-mailchimp-selectbox").bind("change", function() {
			var listId = jQuery(this, "option:selected").val();
			that.addValuesToData(data);

			/* add to data object for ajax */
			data.listId = listId;
			that.getMailchimpHtmlViaAjax(data);
			
		});
	},
	prepareToAjax: function (data) {
		var listId = jQuery("#sg-mailchimp-selectbox option:selected").val();
		this.addValuesToData(data);

		/* add to data object for ajax */
		data.listId = listId;
		this.getMailchimpHtmlViaAjax(data);
	},
	getMailchimpHtmlViaAjax: function(data) {

		jQuery.post(ajaxurl, data, function(response) {

			jQuery(".spinner-mailchimp-list").addClass("sg-hide-element");
			jQuery(".sg-mailchimp-form-wrapper").empty();
			jQuery("#hidden-mailchimp-form-content").empty();
			jQuery("#hidden-mailchimp-form-content").val(response);
			jQuery(".sg-mailchimp-form-wrapper").append(response);
			jQuery(".sg-mailchimp-form-wrapper").trigger("sgMailchimpFormReady");
			SgpbMailchimp.changeElementDisableStatus(jQuery("#sg-save-button"), false);
			SgpbMailchimp.changeElementDisableStatus(jQuery(".sg-popup-general-option"), false);
			SgpbMailchimp.changeElementDisableStatus(jQuery(".mailchimp-double-optin"), false);
			SgpbMailchimp.changeElementDisableStatus(jQuery('.sg-mailchimp-general-options'), false);
			SgpbMailchimp.changeElementDisableStatus(jQuery(".sg-show-only-required-fields"), false);
		});
	}
};

SgpbMailchimp.changeElementDisableStatus = function (element, status) {

	if(!element.length) {
		return;
	}

	element.each(function () {
		jQuery(this).prop('disabled', status);
	});
};

SgpbMailchimp.prototype.addValuesToData = function(data) {

	var requiredFieldMessage = jQuery("[name='mailchimp-required-error-message']").val();
	var emailValidateMessage = jQuery("[name='mailchimp-email-validate-message']").val();
	var asteriskLabel = jQuery('.mailchimp-asterisk-label').val();
	var emailLabel = jQuery('.mailchimp-email-label').val();
	var submitTitle = jQuery('.mailchimp-submit-title').val();

	data.requiredFieldMessage = requiredFieldMessage;
	data.emailValidateMessage = emailValidateMessage;
	data.emailLabel = emailLabel;
	data.asteriskLabel = asteriskLabel;
	data.submitTitle = submitTitle;
	data.doubleOptin = jQuery(".mailchimp-double-optin").is(':checked');
	data.welcomeMessage = jQuery(".mailchimp-welcome-message").is(':checked');
	data.showRequiredFields = jQuery(".sg-show-only-required-fields").is(':checked');
};

SgpbMailchimp.prototype.changeDimensionMode = function(dimension) {

	var size;

	if(dimension == '') {
		return '';
	}
	size =  parseInt(dimension)+"px";
	if(dimension.indexOf("%") != -1 || dimension.indexOf("px") != -1) {
		size = dimension;
	}
	return size;
};

SgpbMailchimp.prototype.addElementHeight = function(element, height) {

	var height = this.changeDimensionMode(height);

	if(height != '') {
		element.css({"height": height});
	}
};

SgpbMailchimp.prototype.addElementWidth = function(element, width) {

	var width = this.changeDimensionMode(width);

	if(width != '') {
		element.css({"width": width});
	}
};

SgpbMailchimp.prototype.addElementBgColor = function(element, color) {

	element.css({"background-color": color});
};

SgpbMailchimp.prototype.addElementColor = function(element, color) {

	element.css({"color": color});
};

SgpbMailchimp.prototype.addInputBorderRadius = function(radius) {

	var radius = this.changeDimensionMode(radius);
	jQuery('.sgpb-input').css({'border-radius': radius});
};

SgpbMailchimp.prototype.addBorderWidth = function(element, width) {

	var width = parseInt(width)+"px";
	element.css({'border-width': width})
};

SgpbMailchimp.prototype.addElementBorderColor = function(element, color) {

	element.css({'border-color': color})
};

SgpbMailchimp.prototype.addSubmitBorderWidth = function(width) {

	var width = this.changeDimensionMode(width);
	jQuery(".sgpb-embedded-subscribe").css({'border-width': width});	
};

SgpbMailchimp.prototype.addSubmitBorderRadius = function(radius) {

	var radius = this.changeDimensionMode(radius);
	jQuery(".sgpb-embedded-subscribe").css({'border-radius': radius});
};

SgpbMailchimp.prototype.addButtonAlignment = function (alignMent) {
	jQuery('.sg-submit-wrapper').css({'text-align': alignMent});
};

SgpbMailchimp.prototype.triggerChangesElements = function(isCheckedElement) {
		if(isCheckedElement) {
			jQuery(".indicates-required").each(function() {
				jQuery(this).css({"display": "block"});
			});
			jQuery(".sgpb-asterisk").each(function() {
				jQuery(this).css({"display": "inline-block"});
			});
		}
		else {
			jQuery(".indicates-required").each(function() {
				jQuery(this).css({"display": "none"});
			});
			jQuery(".sgpb-asterisk").each(function() {
				jQuery(this).css({"display": "none"});
			});
		}	
};

SgpbMailchimp.prototype.build = function() {

	this.triggerChangesElements(SgMailchimpParams.mailchimpIndicatesRequiredFields);
};

SgpbMailchimp.prototype.formStyles = function () {

	var btnHeight =  SgMailchimpParams.mailchimpSubmitHeight;
	var btnWidth = SgMailchimpParams.mailchimpSubmitWidth;
	var inputWidth = SgMailchimpParams.mailchimpInputWidth;
	var inputHeight = SgMailchimpParams.mailchimpInputHeight;

	this.addElementColor(jQuery('.sgpb-label'), SgMailchimpParams.mailchimpLabelColor);
	this.addElementHeight(jQuery(".sgpb-embedded-subscribe"), btnHeight);
	this.addElementWidth(jQuery(".sgpb-embedded-subscribe"), btnWidth);
	this.addElementBgColor(jQuery(".sgpb-embedded-subscribe"), SgMailchimpParams.mailchimpSubmitButtonBgcolor);
	this.addElementColor(jQuery(".sgpb-embedded-subscribe"), SgMailchimpParams.mailchimpSubmitColor);
	this.addElementWidth(jQuery('.sgpb-input'), inputWidth);
	this.addElementHeight(jQuery('.sgpb-input'),inputHeight);
	this.addInputBorderRadius(SgMailchimpParams.mailchimpInputBorderRadius);
	this.addBorderWidth(jQuery('.sgpb-input'), SgMailchimpParams.mailchimpInputBorderWidth);
	this.addBorderWidth(jQuery('.sgpb-input-style'), SgMailchimpParams.mailchimpInputBorderWidth);
	this.addElementBorderColor(jQuery('.sgpb-input'), SgMailchimpParams.mailchimpInputBorderColor);
	this.addElementBorderColor(jQuery('.sgpb-input-style'), SgMailchimpParams.mailchimpInputBorderColor);
	this.addElementBgColor(jQuery('.sgpb-input'), SgMailchimpParams.mailchimpInputBgColor);
	this.addElementBgColor(jQuery('.sgpb-input-style'), SgMailchimpParams.mailchimpInputBgColor);
	this.addElementColor(jQuery('.sgpb-input'), SgMailchimpParams.mailchimpInputTextColor);
	this.addElementColor(jQuery('.sgpb-input-style'), SgMailchimpParams.mailchimpInputTextColor);
	this.addSubmitBorderRadius(SgMailchimpParams.mailchimpSubmitBorderRadius);
	this.addSubmitBorderWidth(SgMailchimpParams.mailchimpSubmitBorderWidth);
	this.addButtonAlignment(SgMailchimpParams.mailchimpFormAligment);
	this.addElementBorderColor(jQuery(".sgpb-embedded-subscribe"), SgMailchimpParams.mailchimpSubmitBorderColor);
};

SgpbMailchimp.prototype.binding = function() {

	this.changeSubmitWidth();
	this.changeSubmitHeight();
	this.changeSubmitBgColor();
	this.changeSubmitColor();
	this.changeLabelColor();
	this.changeInputWidth();
	this.changeInputHeight();
	this.changeBorderRadius();
	this.changeBorderWidth();
	this.changeInputBorderColor();
	this.chaneInputBgColor();
	this.changeInputColor();
	this.changeSubmitBorderColor();
	this.changeSubmitBorderWidth();
	this.changeSubmitBorderRadius();
	this.subOptionContents();
	this.disableAdminMailchimpForm();
	this.changeEmailLabel();
	this.changeIndicatesRequiredFields();
	this.changeButtonAlignMent();
};

SgpbMailchimp.prototype.changeButtonAlignMent = function () {

	var that = this;

	jQuery('#sg-mailchimp-form-aligment').change(function () {
		that.addButtonAlignment(jQuery(this).val());
	});
};

SgpbMailchimp.prototype.changeIndicatesRequiredFields = function() {

	jQuery(".sg-indicates-required-fields").bind("change", function() {
		var isCheckedElement = jQuery(this).is(':checked');

		if(isCheckedElement) {
			jQuery(".indicates-required").each(function() {
				jQuery(this).css({"display": "block"});
			});
			jQuery(".sgpb-asterisk").each(function() {
				jQuery(this).css({"display": "inline-block"});
			});
		}
		else {
			jQuery(".indicates-required").each(function() {
				jQuery(this).css({"display": "none"});
			});
			jQuery(".sgpb-asterisk").each(function() {
				jQuery(this).css({"display": "none"});
			});
		}
	});
};

SgpbMailchimp.prototype.changeEmailLabel = function() {
	jQuery('.mailchimp-email-label').bind('input', function() {
		var val = jQuery(this).val();
		jQuery('.sgrb-label-text').text(val+"  ");
	});
};

SgpbMailchimp.prototype.disableAdminMailchimpForm = function() {

	jQuery("#sgMailchimpForm").submit(function(e) {
		e.preventDefault();
	});
};

SgpbMailchimp.prototype.changeSubmitBorderRadius = function() {

	var that = this;

	jQuery(".mailchimp-btn-border-radius").bind("change", function() {
		var val = jQuery(this).val();
		that.addSubmitBorderRadius(val);
	});
};

SgpbMailchimp.prototype.changeSubmitBorderWidth = function() {

	var that = this;

	jQuery(".mailchimp-btn-border-width").bind("blur", function () {
		var val = jQuery(this).val();
		that.addSubmitBorderWidth(val);
	});
};

SgpbMailchimp.prototype.changeSubmitBorderColor = function() {

	var that = this;
	var element = jQuery(".sgpb-embedded-subscribe");

	jQuery('.mailchimp-btn-border-color').wpColorPicker({
		change: function() {
			that.addElementBorderColor(element,jQuery(this).val());
		}
	});
	jQuery(".wp-picker-holder").bind('click',function() {
		var selectedInput = jQuery(this).prev().find('.mailchimp-btn-border-color').val();
		that.addElementBorderColor(element,selectedInput);
	});
};

SgpbMailchimp.prototype.changeInputColor = function() {

	var that = this;
	var element = jQuery('.sgpb-input');
	var styleElement = jQuery('.sgpb-input-style');

	jQuery('.mailchimp-input-text-color').wpColorPicker({
		change: function() {
			that.addElementColor(element,jQuery(this).val());
			that.addElementColor(styleElement,jQuery(this).val());
		}
	});
	jQuery(".wp-picker-holder").bind('click',function() {
		var selectedInput = jQuery(this).prev().find('.mailchimp-input-text-color').val();
		that.addElementColor(element,selectedInput);
		that.addElementColor(styleElement,selectedInput);
	});
};

SgpbMailchimp.prototype.chaneInputBgColor = function() {

	var that = this;
	var element = jQuery('.sgpb-input');
	var styleElement = jQuery('.sgpb-input-style');

	jQuery('.mailchimp-input-bg-color').wpColorPicker({
		change: function() {
			that.addElementBgColor(element,jQuery(this).val());
			that.addElementBgColor(styleElement,jQuery(this).val());
		}
	});
	jQuery(".wp-picker-holder").bind('click',function() {
		var selectedInput = jQuery(this).prev().find('.mailchimp-input-bg-color').val();
		that.addElementBgColor(element,selectedInput);
		that.addElementBgColor(styleElement,selectedInput);
	});
};

SgpbMailchimp.prototype.changeInputBorderColor = function() {

	var that = this;
	var element = jQuery('.sgpb-input');
	var styleElement = jQuery('.sgpb-input-style');

	jQuery('.mailchimp-input-border-color').wpColorPicker({
		change: function() {
			that.addElementBorderColor(element,jQuery(this).val());
			that.addElementBorderColor(styleElement,jQuery(this).val());
		}
	});
	jQuery(".wp-picker-holder").bind('click',function() {
		var selectedInput = jQuery(this).prev().find('.mailchimp-input-border-color').val();
		that.addElementBorderColor(element,selectedInput);
		that.addElementBorderColor(styleElement,selectedInput);
	});
};
 
SgpbMailchimp.prototype.changeBorderWidth = function() {

	var that = this;
	var element = jQuery('.sgpb-input');
	var styleElement = jQuery('.sgpb-input-style');

	jQuery(".mailchimp-input-border-width").bind("blur", function() {
		that.addBorderWidth(element,jQuery(this).val());
		that.addBorderWidth(styleElement,jQuery(this).val());
	});
};

SgpbMailchimp.prototype.changeBorderRadius = function() {

	var that = this;

	jQuery(".mailchimp-input-border-radius").bind("blur", function() {
		that.addInputBorderRadius(jQuery(this).val());
	});
};

SgpbMailchimp.prototype.changeInputHeight = function() {

	var that = this;
	var element = jQuery('.sgpb-input');

	jQuery(".mailchimp-input-height").bind("blur", function() {
		that.addElementHeight(element,jQuery(this).val());
	});
};

SgpbMailchimp.prototype.changeInputWidth = function() {

	var that = this;
	var element = jQuery('.sgpb-input');

	jQuery(".mailchimp-input-width").bind("blur", function() {
		that.addElementWidth(element,jQuery(this).val());
	});
};

SgpbMailchimp.prototype.changeLabelColor = function() {

	var that = this;
	var element = jQuery('.sgpb-label');

	jQuery(".mailchimp-label-color").wpColorPicker({
		change: function() {
			that.addElementColor(element, jQuery(this).val());
		}
	});
	jQuery(".wp-picker-holder").bind('click',function() {
		var selectedInput = jQuery(this).prev().find('.mailchimp-label-color').val();
		that.addElementColor(element,selectedInput);
	});
};

SgpbMailchimp.prototype.changeSubmitWidth = function() {

	var that = this;
	var element = jQuery(".sgpb-embedded-subscribe");

	jQuery(".mailchimp-submit-width").bind("blur", function() {
		that.addElementWidth(element,jQuery(this).val());
	});
};

SgpbMailchimp.prototype.changeSubmitHeight = function() {

	var that = this;
	var element = jQuery(".sgpb-embedded-subscribe");

	jQuery(".mailchimp-submit-height").bind("blur", function() {
		that.addElementHeight(element,jQuery(this).val());
	});
};

SgpbMailchimp.prototype.changeSubmitBgColor = function() {

	var that = this;
	var element = jQuery(".sgpb-embedded-subscribe");

	jQuery(".mailchimp-btn-background-color").wpColorPicker({
		change: function() {
			that.addElementBgColor(element,jQuery(this).val());
		}
	});
	jQuery(".wp-picker-holder").bind('click',function() {
		var selectedInput = jQuery(this).prev().find('.mailchimp-btn-background-color').val();
		that.addElementBgColor(element,selectedInput);
	});
};

SgpbMailchimp.prototype.changeSubmitColor = function() {

	var that = this;
	var element = jQuery(".sgpb-embedded-subscribe");

	jQuery(".mailchimp-btn-color").wpColorPicker({
		change: function() {
			that.addElementColor(element,jQuery(this).val());
		}
	});
	jQuery(".wp-picker-holder").bind('click',function() {
		var selectedInput = jQuery(this).prev().find('.mailchimp-btn-color').val();
		that.addElementColor(element,selectedInput);
	});
};

/*This function is used inside ajax.php*/
SgpbMailchimp.prototype.sgpbMailchimpResponse = function(data, popupId) {

	var data = JSON.parse(data);
	var status = data.status;
	data.popupId = popupId;

	if (status == 200) {
		this.sgpbMailchimpSuccess(data);            
	}
	else if(status == 401) {
		this.sgpbMailchimpMemberExists(data);
	}
	else {
		this.sgpbMailchimpError(data);
	}
};

SgpbMailchimp.prototype.sgpbMailchimpMemberExists = function (data) {

	var mustClosePopup = SgMailchimpParams.mailchimpClosePopupAlreadySubscribed;
	this.dontShowSubscribedUsers();
	
	if(!mustClosePopup) {
		this.sgpbMailchimpError(data);
		return;
	}

	jQuery.sgcolorbox.close(); 
};

SgpbMailchimp.prototype.sgpbMailchimpSuccess = function(data) {

    var behavior = SgMailchimpParams.mailchimpSuccessBehavior;
    var redirectToNewTab = SgMailchimpParams.mailchimpSuccessRedirectNewTab;
    var openPopupStatus = false;
    var that = this;

    switch(behavior) {
		case 'showMessage':
			jQuery("#sg-popup-content-wrapper-"+data.popupId+" .sgpb-alert-success").removeClass('sg-hide-element');
			jQuery("#sg-popup-content-wrapper-"+data.popupId+" form").css({'display': 'none'});
			jQuery("#sg-popup-content-wrapper-"+data.popupId+" .sgpb-alert-danger").addClass('sg-hide-element');
			that.dontShowSubscribedUsers();
			break;
		case 'redirectToUrl':
			that.dontShowSubscribedUsers();
			if(redirectToNewTab) {
				window.open(SgMailchimpParams.mailchimpSuccessRedirectUrl);
				jQuery.sgcolorbox.close();
			}
			else {
				window.location = SgMailchimpParams.mailchimpSuccessRedirectUrl;
			}

			break;
		case 'openPopup':
			jQuery.sgcolorbox.close();

			jQuery('#sgcolorbox').bind("sgPopupClose", function() {
			    var sgPopupID = SgMailchimpParams.mailchimpSuccessPopupsList;
			    var parentPopupId = SgMailchimpParams.popupId;

			    if(sgPopupID == '' || openPopupStatus || parentPopupId == sgPopupID) {
			        return;
			    }
			    openPopupStatus = true;
			    sgPoupFrontendObj = new SGPopup();
			    sgPoupFrontendObj.showPopup(sgPopupID,false);
			});
			that.dontShowSubscribedUsers();
			break;
		case 'hidePopup':
			jQuery.sgcolorbox.close();
			that.dontShowSubscribedUsers();
			break;
    }

};

SgpbMailchimp.prototype.dontShowSubscribedUsers = function() {

	SGPopup.setToPopupsCookiesList('SGMailchimpPopup');
	var id = SgMailchimpParams.popupId;
	var expirationDate = SgpbMailchimp.cookieExpirationDate;
	SGPopup.setCookie('SGMailchimpPopup'+id,id, expirationDate, true);
};

SgpbMailchimp.prototype.sgpbMailchimpError = function(data) {

	jQuery('#sgcboxLoadedContent').scrollTop(5);
	jQuery("#sg-popup-content-wrapper-"+data.popupId+" .sgpb-alert-danger").removeClass('sg-hide-element');
};

SgpbMailchimp.prototype.subOptionContents = function() {

};

SgpbMailchimp.prototype.showOptionsInfo = function(checkboxSelector, param2) {
	
	if(jQuery(""+checkboxSelector+":checked").length == 0) {
		jQuery("."+param2+"").css({'display': 'none'});
	}
	else {
		jQuery("."+param2+"").css({'display':'block'});
	}

	jQuery(""+checkboxSelector+"").bind("click",function() {

		if(jQuery(""+checkboxSelector+":checked").length == 0) {
			jQuery("."+param2+"").css({'display':'none'});
		}
		else {
			jQuery("."+param2+"").css({'display':'block'});
		}
	});
};

jQuery(document).ready(function() {
	var sgMailchimpObj = new SgpbMailchimp();
	sgMailchimpObj.getMailchimpList();
});