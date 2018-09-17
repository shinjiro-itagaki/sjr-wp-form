<?php
require_once( dirname( __FILE__ ) . '/interface.wrapper.php' );

$sjr_wrapper = null;
function init_wrapper(){
    global $sjr_wrapper;
    $sjr_wrapper = sjr\new_wrapper();
    $sjr_wrapper->register_plugin_install_hooks();
}

function wrapper() : sjr\Wrapper {
    global $sjr_wrapper;
    return $sjr_wrapper;
}

function getWrapper() : sjr\Wrapper {
    return wrapper();
}

init_wrapper();
