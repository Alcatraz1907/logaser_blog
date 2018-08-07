<?php
class MailchimpAdminPost {

	public function __construct() {

		$this->actions();
	}

	public function actions() {

		add_action('admin_post_save_api_key', array($this, "saveApiKey"));
		add_action('admin_post_'.SGPB_MAILCHIMP_ADDON_KEY, array($this, "sgPopupSave"));
	}

	public function saveApiKey() {

		if(isset($_POST['mailchimp-api-key']) && $_POST['mailchimp-api-key'] != '') {
			update_option("SG_MAILCHIMP_API_KEY", $_POST['mailchimp-api-key']);
		}
		wp_redirect(SG_APP_POPUP_ADMIN_URL."admin.php?page=mailchimp");
	}


	public function sgSanitize($optionsKey, $isTextField = false) {

		if (isset($_POST[$optionsKey])) {

			if ($optionsKey == "sg_popup_mailchimp" ||
				$optionsKey == "sg-mailchimp-form"||
				$isTextField == true) {
				$sgPopupData = $_POST[$optionsKey];
				return $sgPopupData;
				/*require_once(SG_APP_POPUP_FILES ."/sg_popup_pro.php");
				return SgPopupPro::sgPopupDataSanitize($sgPopupData);*/
			}
			return sanitize_text_field($_POST[$optionsKey]);
		}
		else {
			return "";
		}
	}

	private function includePopupConfig() {

		if(file_exists(WP_PLUGIN_DIR.'/popup-builder-wordpress-plugin')) {
			$pluginFolder = 'popup-builder-wordpress-plugin';
		}
		else if(file_exists(WP_PLUGIN_DIR.'/popup-builder-platinum')) {
			$pluginFolder = 'popup-builder-platinum';
		}
		else if(file_exists(WP_PLUGIN_DIR.'/popup-builder-gold')) {
			$pluginFolder = 'popup-builder-gold';
		}
		else if(file_exists(WP_PLUGIN_DIR.'/popup-builder-silver')) {
			$pluginFolder = 'popup-builder-silver';
		}
		else if(file_exists(WP_PLUGIN_DIR.'/popup-builder')) {
			$pluginFolder = 'popup-builder';
		}
		else {
			$pluginFolder = false;
		}
		if($pluginFolder) {
			$popupConfigPath = WP_PLUGIN_DIR.'/'.$pluginFolder.'/config.php';
			require_once($popupConfigPath);
		}
	}
 
	public function sgPopupSave() {

		$this->includePopupConfig();
		$_POST = stripslashes_deep($_POST);
		if(file_exists(SG_APP_POPUP_PATH ."/classes/SGPBExtensionManager.php")) {
			require_once(SG_APP_POPUP_PATH ."/classes/SGPBExtensionManager.php");
		}

		$postData = $_POST;
		$showAllPages = $this->sgSanitize('allPages');
		$showAllPosts = $this->sgSanitize('allPosts');
		$showAllCustomPosts = sgSanitize('allCustomPosts');
		$allSelectedPages = "";
		$allSelectedPosts = "";
		$allSelectedCustomPosts = "";
		$allSelectedCategories = $this->sgSanitize("posts-all-categories", true);

		/* if popup check for all pages it is not needed for save all pages all posts */
		if($showAllPages !== "all") {
			$allSelectedPages = explode(",", $this->sgSanitize('all-selected-page'));
		}
		
		if($showAllPosts !== "all") {
			$allSelectedPosts = explode(",", $this->sgSanitize('all-selected-posts'));
		}
		if($showAllCustomPosts !== "all") {
			$allSelectedCustomPosts = explode(",", sgSanitize('all-selected-custom-posts'));
		}

		$mailchimpOptions = array(
			'mailchimp-disable-double-optin' => $this->sgSanitize('mailchimp-disable-double-optin'),
			'mailchimp-list-id' => $this->sgSanitize('mailchimp-list-id'),
			'sg-mailchimp-form' => stripslashes($this->sgSanitize('sg-mailchimp-form')),
			'mailchimp-required-error-message' => $this->sgSanitize('mailchimp-required-error-message'),
			'mailchimp-email-validate-message' => $this->sgSanitize('mailchimp-email-validate-message'),
			'mailchimp-error-message' => $this->sgSanitize('mailchimp-error-message'),
			'mailchimp-submit-button-bgcolor' => $this->sgSanitize('mailchimp-submit-button-bgcolor'),
			'mailchimp-form-aligment' => $this->sgSanitize('mailchimp-form-aligment'),
			'mailchimp-label-aligment' => $this->sgSanitize('mailchimp-label-aligment'),
			'mailchimp-success-message' => $this->sgSanitize('mailchimp-success-message'),
			'mailchimp-only-required' => $this->sgSanitize('mailchimp-only-required'),
			'mailchimp-show-form-to-top' => $this->sgSanitize('mailchimp-show-form-to-top'),
			'mailchimp-label-color' => $this->sgSanitize('mailchimp-label-color'),
			'mailchimp-input-width' => $this->sgSanitize('mailchimp-input-width'),
			'mailchimp-input-height' => $this->sgSanitize('mailchimp-input-height'),
			'mailchimp-input-border-radius' => $this->sgSanitize('mailchimp-input-border-radius'),
			'mailchimp-input-border-width' => $this->sgSanitize('mailchimp-input-border-width'),
			'mailchimp-input-border-color' => $this->sgSanitize('mailchimp-input-border-color'),
			'mailchimp-input-bg-color' => $this->sgSanitize('mailchimp-input-bg-color'),
			'mailchimp-input-text-color' => $this->sgSanitize('mailchimp-input-text-color'),
			'mailchimp-submit-width' => $this->sgSanitize('mailchimp-submit-width'),
			'mailchimp-submit-height' => $this->sgSanitize('mailchimp-submit-height'),
			'mailchimp-submit-border-width' => $this->sgSanitize('mailchimp-submit-border-width'),
			'mailchimp-submit-border-radius' => $this->sgSanitize('mailchimp-submit-border-radius'),
			'mailchimp-submit-border-color' => $this->sgSanitize('mailchimp-submit-border-color'),
			'mailchimp-submit-color' => $this->sgSanitize('mailchimp-submit-color'),
			'mailchimp-submit-title' => $this->sgSanitize('mailchimp-submit-title'),
			'mailchimp-email-label' => $this->sgSanitize('mailchimp-email-label'),
			'mailchimp-indicates-required-fields' => $this->sgSanitize('mailchimp-indicates-required-fields'),
			'mailchimp-asterisk-label' => $this->sgSanitize('mailchimp-asterisk-label'),
			'mailchimp-success-behavior' => $this->sgSanitize('mailchimp-success-behavior'),
			'mailchimp-success-redirect-url' => $this->sgSanitize('mailchimp-success-redirect-url'),
			'mailchimp-success-popups-list' => $this->sgSanitize('mailchimp-success-popups-list'),
			'mailchimp-success-redirect-new-tab' => $this->sgSanitize('mailchimp-success-redirect-new-tab'),
			'mailchimp-close-popup-already-subscribed' => $this->sgSanitize('mailchimp-close-popup-already-subscribed')
		);
	
		/*setup additional options*/
		$addToGeneralOptions = array(
			'showAllPages' => $showAllPages,
			'showAllPosts' => $showAllPosts,
			'showAllCustomPosts' => $showAllCustomPosts,
			'allSelectedPages' => $allSelectedPages,
			'allSelectedPosts' => $allSelectedPosts,
			'allSelectedCustomPosts' => $allSelectedCustomPosts,
			'allSelectedCategories'=>$allSelectedCategories,
			'fblikeOptions'=> '',
			'videoOptions'=> '',
			'exitIntentOptions'=> '',
			'countdownOptions'=> '',
			'socialOptions'=> '',
			'socialButtons'=> ''
		);
		/*Get common options for mailchimp popup*/
		$options = IntegrateExternalSettings::getPopupGeneralOptions($addToGeneralOptions);

		$options['mailchimpOptions'] = json_encode($mailchimpOptions);

		$mailchimp = stripslashes($this->sgSanitize("sg_popup_mailchimp"));
		$type = $this->sgSanitize('type');
		$title = stripslashes($this->sgSanitize('title'));
		$id = $this->sgSanitize('hidden_popup_number');
		$jsonDataArray = json_encode($options);

		$data = array(
			'id' => $id,
			'title' => $title,
			'type' => $type,
			'mailchimp' => $mailchimp,
			'options' => $jsonDataArray,
		);


		/*Exclude this from current function*/
		function setPopupForAllPages($id, $data, $type) {

			SGPopup::addPopupForAllPages($id, $data, $type);
		}

		function setOptionPopupType($id, $type) {

			update_option("SG_POPUP_".strtoupper($type)."_".$id,$id);
		}

		if (empty($title)) {
			wp_redirect(SG_APP_POPUP_ADMIN_URL."admin.php?page=edit-popup&type=$type&titleError=1");
			exit();
		}

		/*changed files paths and check  paths*/
		$paths = IntegrateExternalSettings::getCurrentPopupAppPaths($_POST['type']);

		$popupAppPath = $paths['app-path'];

		$popupName = "SG".ucfirst(strtolower($_POST['type']));
		$popupClassName = $popupName."Popup";
		
		require_once($popupAppPath ."/classes/".$popupClassName.".php");

		if ($id == "") {
			global $wpdb;
			call_user_func(array($popupClassName, 'create'), $data);
			$lastId = $wpdb->get_var("SELECT LAST_INSERT_ID() FROM ".  $wpdb->prefix."sg_popup");
			$postData['saveMod'] = '';
			$postData['popupId'] = $lastId;
			if(class_exists('SGPBExtensionManager')) {
				$extensionManagerObj = new SGPBExtensionManager();
				$extensionManagerObj->setPostData($postData);
				$extensionManagerObj->save();
			}

			if(POPUP_BUILDER_PKG != POPUP_BUILDER_PKG_FREE) {
				
				SGPopup::removePopupFromPages($lastId,'page');
				SGPopup::removePopupFromPages($lastId,'categories');
				if($options['allPagesStatus']) {
					if(!empty($showAllPages) && $showAllPages != 'all') {
						setPopupForAllPages($lastId, $allSelectedPages, 'page');
					}
					else {

						$this->updatePopupOptions($lastId, array('page'), true);;
					}
				}
				
				if($options['allPostsStatus']) {
					if(!empty($showAllPosts) && $showAllPosts == "selected") {

						setPopupForAllPages($lastId, $allSelectedPosts, 'page');
					}
					else if($showAllPosts == "all") {
						$this->updatePopupOptions($lastId, array('page'), true);
					}
					if($showAllPosts == "allCategories") {
						setPopupForAllPages($lastId, $allSelectedCategories, 'categories');
					}
				}

				if($options['allCustomPostsStatus']) {
					if(!empty($showAllCustomPosts) && $showAllCustomPosts == "selected") {
						setPopupForAllPages($lastId, $allSelectedCustomPosts, 'page');
					}
					else if($showAllCustomPosts == "all") {
						$this->updatePopupOptions($lastId, $options['all-custom-posts'], true);
					}
				}
			
			}
			
			setOptionPopupType($lastId, $type);
			wp_redirect(SG_APP_POPUP_ADMIN_URL."admin.php?page=edit-popup&id=".$lastId."&type=$type&saved=1");
			exit();
		}
		else {
			$popup = SGPopup::findById($id);
			$popup->setTitle($title);
			$popup->setId($id);
			$popup->setType($type);
			$popup->setOptions($jsonDataArray);

			$popup->setContent($mailchimp);
			$popup->setMailchimpOptions(json_encode($mailchimpOptions));
			
			if(POPUP_BUILDER_PKG != POPUP_BUILDER_PKG_FREE) {
				
				SGPopup::removePopupFromPages($id, 'page');
				SGPopup::removePopupFromPages($id, 'categories');
				if(!empty($options['allPagesStatus'])) {
					if($showAllPages && $showAllPages != 'all') {
						$this->updatePopupOptions($id, array('page'), false);
						setPopupForAllPages($id, $allSelectedPages, 'page');
					}
					else {
						$this->updatePopupOptions($id, array('page'), true);
					}
				}
				else  {
					$this->updatePopupOptions($id, array('page'), false);
				}

				if(!empty($options['allPostsStatus'])) {
					if(!empty($showAllPosts) && $showAllPosts == "selected") {
						$this->updatePopupOptions($id, array('post'), false);
						setPopupForAllPages($id, $allSelectedPosts, 'page');
					}
					else if($showAllPosts == "all"){
						$this->updatePopupOptions($id, array('post'), true);
					}
					if($showAllPosts == "allCategories") {
						setPopupForAllPages($id, $allSelectedCategories, 'categories');
					}
				}
				else {
					$this->updatePopupOptions($id, array('post'), false);;
				}

				if(!empty($options['allCustomPostsStatus'])) {
					if(!empty($showAllCustomPosts) && $showAllCustomPosts == "selected") {
						$this->updatePopupOptions($id, $options['all-custom-posts'], false);
						setPopupForAllPages($id, $allSelectedCustomPosts, 'page');
					}
					else if($showAllCustomPosts == "all") {
						$this->updatePopupOptions($id, $options['all-custom-posts'], true);
					}
				}
				else {
					$this->updatePopupOptions($id, $options['all-custom-posts'], false);
				}
			}
		
			setOptionPopupType($id, $type);
			if(class_exists('SGPBExtensionManager')) {
				$postData['saveMod'] = '1';
				$postData['popupId'] = $id;
				$extensionManagerObj = new SGPBExtensionManager();
				$extensionManagerObj->setPostData($postData);
				$extensionManagerObj->save();
			}
			$popup->save();
			wp_redirect(SG_APP_POPUP_ADMIN_URL."admin.php?page=edit-popup&id=$id&type=$type&saved=1");
			exit();
		}
	}

	/**
	 * Saving data to wp options
	 *
	 * @since 3.1.5
	 * 
	 * @param int $id popup id number
	 * @param array $postTypes page post types
	 * @param bool $isInsert true for insert false for remove
	 *
	 * @return void
	 *
	 */

	public function updatePopupOptions($id, $postTypes, $isInsert) {

		/*getting wp option data*/
		$allPosts = get_option("SG_ALL_POSTS");
		$key = false;

		if(!$allPosts) {
			$allPosts = array();
		}

		if($allPosts && !empty($allPosts)) {
			/*Assoc array key value*/
			$key = $this->getCurrentPopupIdFromOptions($id);
		}
		
		/*If id is exists inside the data, insert into wp-options*/
		if($key !== false) {
			$popupPostTypes = $allPosts[$key]['popstTypes'];

			if($isInsert) {
				$popupPostTypes = array_merge($popupPostTypes, $postTypes);
				$popupPostTypes = array_unique($popupPostTypes);
			}
			else {
				if(!empty($postTypes)) {
					$popupPostTypes = array_diff($popupPostTypes, $postTypes);
				}
			}

			if(empty($popupPostTypes)) {
				unset($allPosts[$key]);
			}
			else {
				$allPosts[$key]['popstTypes'] = $popupPostTypes;
			}

		}
		else if($isInsert) {
			$data = array('id'=>$id, 'popstTypes'=>$postTypes);
			array_push($allPosts, $data);
		}

		update_option("SG_ALL_POSTS", $allPosts);
	}

	public function getCurrentPopupIdFromOptions($id) {

		$allPosts = get_option("SG_ALL_POSTS");
		
		foreach ($allPosts as $key => $post) {
			if($post['id'] == $id) {
				return $key;
			}
		}

		return false;
	}

}

$adminPostObj = new MailchimpAdminPost();
