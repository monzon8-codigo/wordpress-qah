<?php
/*
Plugin Name: Free Stock Photos Foter
Plugin URI: http://foter.com
Description: This plugin lets you easily search, manage and add free photos (more than 180 mln of them) to your blog posts.
Version: 1.5.4
Author: Innovaweb Sp. z o.o.
Author URI: http://www.innovaweb.pl
License: GPL2
*/

require_once(ABSPATH . '/wp-admin/includes/plugin.php');

define('FOTER_PLUGIN_URL', plugin_dir_url( __FILE__ ));
$plugin_data = get_plugin_data(__FILE__);
define('FOTER_PLUGIN_VER', $plugin_data['Version']);
        

function foter_add_icon($init_context) {
    $post_id = isset( $_REQUEST['post_id'] )? intval( $_REQUEST['post_id'] ) : 0;
    if (!$post_id) {
        global $post_ID, $temp_ID;
        $post_id = (int) (0 == $post_ID ? $temp_ID : $post_ID);
    }

    $icon_src = FOTER_PLUGIN_URL . '/img/camera.png';

    $camera_image = '<a href="'.FOTER_PLUGIN_URL .'foter-view.php?action=dashboard'.($post_id ? '&post_id='.$post_id : '').'&TB_iframe=1" id="foter-button" title="' . __('Add Photo via Free Stock Photos foter.com', 'foter') . '" class="thickbox">';
    $camera_image .= '<img src="' . $icon_src . '" alt="' . __('Add Photo via Free Stock Photos foter.com', 'foter') . '" />';
    $camera_image .= '</a>';

    return $init_context . $camera_image;
}

function add_foter_tinymce_plugin($plugin_array) {
   $plugin_array['foter'] =  FOTER_PLUGIN_URL . 'tinymce/foter/editor_plugin.js';
   return $plugin_array;
}

function foter_init() {
    if(is_admin()) {
        
        
        wp_register_script('foter.js', FOTER_PLUGIN_URL . 'js/foter.js?pver='.FOTER_PLUGIN_VER);
        
        if (!wp_script_is( 'jquery', 'registered' ))
            wp_register_script('jquery', FOTER_PLUGIN_URL . 'js/jquery.js?pver='.FOTER_PLUGIN_VER);
        
        wp_register_style('foter.css', FOTER_PLUGIN_URL . 'css/foter.css?pver='.FOTER_PLUGIN_VER);
        wp_register_style('foter_btns.css', FOTER_PLUGIN_URL . 'css/foter_btns.css?pver='.FOTER_PLUGIN_VER);
        wp_enqueue_style('foter_btns.css');
    }
}

add_filter('media_buttons_context', 'foter_add_icon');
add_filter("mce_external_plugins", 'add_foter_tinymce_plugin',1 );
add_action('init', 'foter_init');


