<?php
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

function sjr_create_tables(){
    /* global $wpdb;
     * $charset_collate = $wpdb->get_charset_collate();
     * $table_name = $wpdb->prefix . "sjr_forms";
     * $table_name_values = $wpdb->prefix . "sjr_form_values";
     * $sql = "
       CREATE TABLE $table_name (
       id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
       created_date timestamp NOT NULL,
       modified_date timestamp
       ) $charset_collate;

       ";
     * dbDelta( $sql );
     * 
     * $sql = "
       CREATE TABLE $table_name_values (
       id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
       form_id INT UNSIGNED NOT NULL,
       key VARCHAR NOT NULL,
       val VARCHAR,
       UNIQUE KEY kv (form_id, key)
       ) $charset_collate;

       ";
     * dbDelta( $sql );
     * 

     * $wpdb->insert(
     *     $table_name,
     *     array(
     *         'time' => current_time( 'mysql' ),
     *         'name' => $welcome_name,
     *         'text' => $welcome_text,
     *     )
     * ); */
}

function sjr_drop_tables(){
    // echo "drop tables";
}
