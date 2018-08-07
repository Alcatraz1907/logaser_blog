<?php
/**
* Plugin Name: Popup Builder Mailchimp
* Plugin URI: http://popup-builder.com/
* Description: Integrate Mailchimp forms into the Popup Builder.
* Version:	1.2.7
* Author: Sygnoos
* Author URI: http://popup-builder.com/
* License: 
*/

require_once(dirname(__FILE__)."/config/config.php");
require_once(SGPB_MAILCHIMP_CLASSES."SGPBMain.php");

$sgpbMailchimpObj = new SGPBMain();
