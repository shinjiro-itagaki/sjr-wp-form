<?php
namespace sjr\controller;

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
    header("Location: $url");
    exit;
}

function on_post()
{
    echo print_r($_POST, true);
}

function on_requested($wp_query){
    if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST && isset($_POST[plugin_uuid_key()]) && $_POST[plugin_uuid_key()] == plugin_uuid_val()){
        on_post();
    }
}

\sjr\register_requested_hook('sjr\controller\on_requested');
