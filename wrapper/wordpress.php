<?php
namespace sjr;

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

function do_shortcode_impl(string $content){
    return do_shortcode($content);
}

function get_charset_collate_impl(){
    global $wpdb;
    return $wpdb->get_charset_collate();
}

function mk_table_name_impl(string $tname){
    global $wpdb;
    return $wpdb->prefix . $tname;
}

function exec_sql_impl(string $sql){
    global $wpdb;
    return dbDelta( $sql );
}

function db_insert_impl(string $table, array $values){
    global $wpdb;
    return $wpdb->insert($table, $values);
}

function install_tables(){
}

function uninstall_tables(){
}


function register_plugin_install_hooks(){
    if(function_exists('register_activation_hook')) {
        register_activation_hook( __FILE__,  'install_tables' );
    }    
    if(function_exists('register_deactivation_hook')) {
        register_deactivation_hook( __FILE__, 'uninstall_tables' );
    }
}

