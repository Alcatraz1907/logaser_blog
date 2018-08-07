<?php
use \DrewM\MailChimp\MailChimp;
require_once(SGPB_MAILCHIMP_CLASSES.'MailChimp.php');

/**
 *
 * For One tome create mailchimp Api Object
 *
 */
class MailchimpSingleton {

    // Hold an instance of the class
    private static $apiKey;
 
    // The singleton method
    public static function getInstance($key) {

        if (!isset(self::$apiKey)) {
            self::$apiKey = new MailChimp($key);
        }
      
        return self::$apiKey;
    }

    private function __clone() {

    }

    private function __construct() {
    
    }
}