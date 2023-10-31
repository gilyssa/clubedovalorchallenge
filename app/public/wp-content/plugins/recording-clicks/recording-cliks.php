<?php
/*
Plugin Name: Recording Clicks
Description: Save each click record in the database.
Author: Lyssa
Version: 1.0
*/
function addingClick() {
    global $wpdb;
    $wpdb->insert(
        'wp_click_records',
        array('date_record' => current_time('mysql', 1)),
        array('%s')
    );
}

function printButton() {
    return '<button id="click-button">Clique Aqui</button>';
}

add_shortcode('count_button_clicks', 'printButton');

function ajaxSolicitation() {
    addingClick();
    wp_send_json_success();
}

function EnqueueScripts() {
    wp_enqueue_script('custom-scripts', plugins_url('recording-clicks/ajax-requests.js'), array('jquery'), null, true);
    wp_localize_script('custom-scripts', 'Ajax', array(
        'ajaxurl' => admin_url('admin-ajax.php')
    ));
    wp_enqueue_style('custom-styles', plugins_url('recording-clicks/custom-styles.css'));
}

add_action('wp_enqueue_scripts', 'EnqueueScripts');
add_action('wp_ajax_addingClick', 'ajaxSolicitation');
add_action('wp_ajax_nopriv_addingClick', 'ajaxSolicitation');
