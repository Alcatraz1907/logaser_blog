<?php
/**
 * Created by PhpStorm.
 * User: mitrik
 * Date: 11/5/13
 * Time: 12:18 PM
 */

/*
Plugin Name: Custom tag url
Version: 1.0.0
Author: Dmytro Dmytrashchuk
Description: This plugin allows add some suffix to tag's URL
*/

/* Add suffix "-tag" to all existing tags when the plugin is activated */
register_activation_hook(__FILE__, 'ctu_add_suffix_to_existing');

function ctu_add_suffix_to_existing()
{
    global $wpdb;
    $query = "SELECT * FROM $wpdb->terms AS t
              INNER JOIN $wpdb->term_taxonomy AS tt ON tt.term_id = t.term_id
              WHERE tt.taxonomy = 'post_tag'";
    $allTags = $wpdb->get_results($query);
    $isUpdatedFlag = false;
    foreach ($allTags as $tag) {
        $term_id = (int)$tag->term_id;
        $slug = $tag->slug;
        if (substr($slug, -4) != '-tag') {
            $slug .= '-tag';
            $wpdb->update( $wpdb->terms, compact( 'slug' ), compact( 'term_id' ) );
            $isUpdatedFlag = true;
        }
    }
    if ($isUpdatedFlag) {
        flush_rewrite_rules();
    }
}


function ctu_add_suffix_on_create_post_tag($term_id, $tt_id)
{
    global $wpdb;
    $term_id = (int)$term_id;
    $slug = $wpdb->get_var($wpdb->prepare( "SELECT slug FROM $wpdb->terms WHERE term_id = %d", $term_id));
    if (substr($slug, -4) != '-tag') {
        $slug .= '-tag';
        $wpdb->update( $wpdb->terms, compact( 'slug' ), compact( 'term_id' ) );
        flush_rewrite_rules();
    }
}

function ctu_add_suffix_on_create_post_tag_ajax($term_id, $tt_id)
{
    ctu_add_suffix_on_create_post_tag($term_id, $tt_id);
}
add_action('create_post_tag', 'ctu_add_suffix_on_create_post_tag');
add_action('wp_ajax_create_post_tag', 'ctu_add_suffix_on_create_post_tag_ajax');

add_action('edit_post_tag', 'ctu_add_suffix_on_create_post_tag');
add_action('wp_ajax_edit_post_tag', 'ctu_add_suffix_on_create_post_tag_ajax');
