<?php
class MailchimpPopupInstaller {

	public static function createTables($blogId = '') {

		global $wpdb;

		$sgMailchimpPopupBase = "CREATE TABLE IF NOT EXISTS ". $wpdb->prefix.$blogId."sg_popup_mailchimp (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`content` TEXT NOT NULL,
			`options` TEXT NOT NULL,
			PRIMARY KEY (id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8; ";

		$sgPopupAddon = "CREATE TABLE IF NOT EXISTS ". $wpdb->prefix.$blogId."sg_popup_addons (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`name` varchar(255) NOT NULL UNIQUE,
			`paths` TEXT NOT NULL,
			`type` varchar(255) NOT NULL,
			`options` TEXT NOT NULL,
			PRIMARY KEY (id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8; ";

		$paths = self::getPaths();
		$sgInsertCurrentPaths = $wpdb->prepare("INSERT IGNORE ". $wpdb->prefix.$blogId."sg_popup_addons (name, paths, type,options) VALUES(%s,%s,%s,%s) ", SGPB_MAILCHIMP_ADDON_KEY, $paths, 'plugin','');

		$wpdb->query($sgMailchimpPopupBase);
		$wpdb->query($sgPopupAddon);
		$wpdb->query($sgInsertCurrentPaths);
	}

	public static function getPaths() {

		$pathsArray = array();
		$pathsArray['app-path'] = SGPB_MAILCHIMP_PATH;
		$pathsArray['files-path'] = SGPB_MAILCHIMP_FILES_PATH;

		return json_encode($pathsArray);
	}

	public static function install() {

		$obj = new self();
		$obj->createTables();

		/*get_current_blog_id() == 1 When plugin activated inside the child of multisite instance*/
		if(is_multisite() && get_current_blog_id() == 1) {
			global $wp_version;
			if($wp_version > '4.6.0') {
				$sites = get_sites();
			}
			else {
				$sites = wp_get_sites();
			}

			foreach($sites as $site) {

				if($wp_version > '4.6.0') {
					$blogId = $site->blog_id."_";
				}
				else {
					$blogId = $site['blog_id']."_";
				}

				if($blogId != 1) {
					$obj->createTables($blogId);
				}
			}
		}
	}

	public static function uninstallTables($blogId = '') {

		global $wpdb;

		$mailchimpPopupTable = $wpdb->prefix.$blogId."sg_popup_mailchimp";
		$mailchimpPopupSql = "DROP TABLE ". $mailchimpPopupTable;


		$wpdb->query($mailchimpPopupSql);
	}

	public static function uninstall() {

		self::uninstallTables();

		if(is_multisite()) {
			global $wp_version;
			if($wp_version > '4.6.0') {
				$sites = get_sites();
			}
			else {
				$sites = wp_get_sites();
			}
			
			foreach($sites as $site) {

				if($wp_version > '4.6.0') {
					$blogId = $site->blog_id."_";
				}
				else {
					$blogId = $site['blog_id']."_";
				}

				self::uninstallTables($blogId);
			}
		}
	}

	public static function deactivatePlugin($blogId = '') {

		global $wpdb;
		
		$deleteFromAddonsSql = "DELETE FROM ".$wpdb->prefix.$blogId."sg_popup_addons WHERE name='mailchimp'";
		$wpdb->query($deleteFromAddonsSql);
	}	

	public static function deactivate() {

		self::deactivatePlugin("");

		if(is_multisite()) {
			global $wp_version;
			if($wp_version > '4.6.0') {
				$sites = get_sites();
			}
			else {
				$sites = wp_get_sites();
			}
			
			foreach($sites as $site) {

				if($wp_version > '4.6.0') {
					$blogId = $site->blog_id."_";
				}
				else {
					$blogId = $site['blog_id']."_";
				}

				self::deactivatePlugin($blogId);
			}
		}
	}

}