function SgMailchimpBackend() {

}

SgMailchimpBackend.prototype.init = function() {
	this.apiKeyToggleShow();
	this.initAccordions();
};

SgMailchimpBackend.prototype.apiKeyToggleShow = function() {
	jQuery("#sg-show-maichimp-apikey").bind("click", function() {
		if(jQuery(this).prop("checked")) {
			jQuery("#mailchimp_api_key").attr('type', 'text');
		}
		else {
			jQuery("#mailchimp_api_key").attr('type', 'password');
		}

	})
};

SgMailchimpBackend.prototype.initAccordions = function() {

	var radioButtonsList = [
        jQuery("[name='mailchimp-success-behavior']")
	];

	for(var radioButtonIndex in radioButtonsList) {

        var radioButton = radioButtonsList[radioButtonIndex];

        var that = this;
        radioButton.each(function () {
            that.buildAccordionActions(jQuery(this));
        });
        radioButton.on("change", function () {
            that.buildAccordionActions(jQuery(this), 'change');
        });
	}
};

SgMailchimpBackend.prototype.buildAccordionActions = function (currentRadioButton, event) {

    if(event == 'change') {
	    currentRadioButton.parents('.sg-radio-option-behavior').first().find('.js-radio-accordion').css({'display': 'none'});
    }

    var value = currentRadioButton.val();
    var toggleContent = jQuery('.js-accordion-'+value);

    if(currentRadioButton.is(':checked')) {
        currentRadioButton.after(toggleContent.css({'display':'inline-block'}));
    }
    else {
        toggleContent.css({'display': 'none'});
    }
};

jQuery(document).ready(function() {
	var obj = new SgMailchimpBackend();
	obj.init();
});