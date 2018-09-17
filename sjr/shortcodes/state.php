<?php

require_once( dirname( __FILE__ ) . "/common.php" );

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

function func_sjr_set_state($attrs){
    $state = sjr_get($attrs,'state');
    if($state){
        sjr_set_state($state);
    }
}
add_shortcode('sjr_set_state', 'func_sjr_set_state');
