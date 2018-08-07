<?php
class SgmailchimpJavascrip {

	public function __construct() {

		$this->actions();
	}

	public function actions() {

		add_action('admin_enqueue_scripts', array($this, "sgMailchimpEnqueueScripts"));
	}

	public function sgMailchimpEnqueueScripts($hook) {
		
		wp_register_script('mailchimpjs', SGPB_MAILCHIMP_URL . '/javascript/sgpbMailchimp.js');
		wp_register_script('sgMailchimpBackend', SGPB_MAILCHIMP_URL . '/javascript/sgMailchimpBackend.js');

		if($hook == 'popup-builder_page_mailchimp' ||
			$hook == 'popup-builder_page_edit-popup') {

			wp_enqueue_script('sgMailchimpBackend');
		}
		
	}
}

$javascriptObj = new SgmailchimpJavascrip();
