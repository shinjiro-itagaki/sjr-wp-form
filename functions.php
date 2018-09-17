<?php
namespace sjr;

define("PLUGIN_UUID_KEY","plugin_uuid");
define("PLUGIN_UUID_VAL","13a00325-1873-42cb-8202-7d36f19ebc9d");

function plugin_uuid_key(){
    return PLUGIN_UUID_KEY;
}

function plugin_uuid_val(){
    return PLUGIN_UUID_VAL;
}

function plugin_uuid_value(){
    return plugin_uuid_val();
}

function redirect_to($url){
    if(is_post()){
        header("Location: $url", true, 303);
        exit;
    }
}

function is_post(){
    return ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST && isset($_POST[plugin_uuid_key()]) && $_POST[plugin_uuid_key()] == plugin_uuid_val());
}

require_once( dirname( __FILE__ ) . '/wrapper/init.php' );
require_once( dirname( __FILE__ ) . '/sjr/shortcodes.php' );
require_once( dirname( __FILE__ ) . '/sjr/controller.php' );
