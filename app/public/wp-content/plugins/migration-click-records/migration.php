<?php
/*
Plugin Name: Migration Click Records
Description: Create table wp_click_records.
Author: Lyssa
Version: 1.0
*/

function createTableClickRecords() {
    global $wpdb;
    $table = $wpdb->prefix . 'click_records'; 
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table (
        id INT NOT NULL AUTO_INCREMENT,
        date_record DATETIME,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

createTableClickRecords();
