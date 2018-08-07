<?php
function sgpb_mailchimp_style($hook) {
	
	if ('popup-builder_page_create-popup' == $hook || 'popup-builder_page_mailchimp' == $hook || $hook == 'popup-builder_page_edit-popup') {

		wp_register_style('sgpg_mailchimp_style', SGPB_MAILCHIMP_STYLE_URL.'style.css', false, '1.0.0');
		wp_enqueue_style('sgpg_mailchimp_style');
		wp_register_style('sgpg_mailchimp_default_form_style', SGPB_MAILCHIMP_STYLE_URL.'sgMailchimpDefaults.css', false, '1.0.0');
		wp_enqueue_style('sgpg_mailchimp_default_form_style');	
	}

	
}

add_action('admin_enqueue_scripts', 'sgpb_mailchimp_style');