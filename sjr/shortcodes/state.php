<?php

require_once( dirname( __FILE__ ) . "/common.php" );

$current_state = "input";

function on_state($state,$func){
    global $current_state;
    $old = $current_state;
    $current_state = $state;
    $rtn = $func();
    $current_state = $old;
    return $rtn;
}

// 設計方針が未定、stateはどうやって定義するか?
function func_sjr_if_match_state(array $attrs, string $content){
    global $current_state;
    //    global $sjr_def_if_match_states;
    $state = sjr_get($attrs,'state');
    if($state && $state == $current_state){
        return sjr_do_shortcode($content, $attrs);
    }else{
        return "";
    }
}
add_shortcode('sjr_if_match_state', 'func_sjr_if_match_state');
