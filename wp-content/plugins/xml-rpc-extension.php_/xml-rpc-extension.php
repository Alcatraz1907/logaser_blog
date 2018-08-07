<?php
/**
 * @package xml-rpc-extension
 * @version 0.1
 */
/*
Plugin Name: Xml Rpc Extension
Author: Alex Dubchak
Version: 0.1
*/

function add_new_xmlrpc_methods($methods){
    $methods['logaster.getRecentPostsByCategory'] = 'getRecentPostsByCategory';
    return $methods;
}

function getRecentPostsByCategory($args){
    $results = get_posts(array('numberposts' => $args[0],'category' => $args[1]));
    foreach ($results as &$entry) {
        while (($startPos = strpos($entry->post_content, '[caption')) !== false) {
            $endPos = strpos($entry->post_content, '[/caption]', $startPos) + strlen('[/caption]');
            $substring = substr($entry->post_content, $startPos, $endPos - $startPos);
            $entry->post_content = str_replace($substring, '', $entry->post_content);
        }    
    }
    return $results;
}

add_filter( 'xmlrpc_methods', 'add_new_xmlrpc_methods' );

?>
