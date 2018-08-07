<?php
class SGPBMain {
	
	public function __construct() {
		
		$this->init();		
	}

	public function init() {

		$this->actions();
		$this->includeFiles();
	}

	public function includeFiles() {

		require_once(SGPB_MAILCHIMP_CLASSES."/sgpbMailchimpInstall.php");
		require_once(SGPB_MAILCHIMP_FILES_PATH."/mailchimpAdminPost.php");
		require_once(SGPB_MAILCHIMP_STYLE."style.php");
		require_once(SGPB_MAILCHIMP_JAVASCRIPT."javascript.php");
		require_once(SGPB_MAILCHIMP_CLASSES.'MailChimp.php');
		require_once(SGPB_MAILCHIMP_CLASSES."MailchimpSingleton.php");
		require_once(SGPB_MAILCHIMP_CLASSES.'SGPBMailChimp.php');
		require_once(SGPB_MAILCHIMP_FILES_PATH."/mailchimpAjax.php");
		require_once(SGPB_MAILCHIMP_HELPER."/GetData.php");
		require_once(SGPB_MAILCHIMP_HELPER."/PBMFunctions.php");
	}

	public function sgMailchimpMenu() {

		require_once(SGPB_MAILCHIMP_FILES_PATH."/mailchimpSettings.php");
	}

	public function addMenuMailchimp() {

		add_submenu_page(
	        'PopupBuilder',  
	        'Mailchimp popup', 
	        'Mailchimp popup', 
	        'delete_plugins', 
	        'mailchimp', 
	        array($this, 'sgMailchimpMenu')
	    );
	}

	public function actions() {
		
		register_activation_hook(SGPB_MAILCHIMP_PATH."/popup-builder-mailchimp.php", array($this, 'SGPBMailchimpActivate'));
		register_deactivation_hook(SGPB_MAILCHIMP_PATH."/popup-builder-mailchimp.php", array($this, 'SGPBMailchimpDeactivate'));
		register_uninstall_hook(SGPB_MAILCHIMP_PATH."/popup-builder-mailchimp.php", array("SGPBMain", 'SGPBMailchimpUninstall'));
		add_action('admin_menu', array($this, 'addMenuMailchimp'), 11);
	}

	public function SGPBMailchimpActivate() {

		MailchimpPopupInstaller::install();
	}

	public function SGPBMailchimpDeactivate() {
		
		MailchimpPopupInstaller::deactivate();
	}

	public static function SGPBMailchimpUninstall() {
		
	//	MailchimpPopupInstaller::uninstall();
	}
}