<?php

// 設計方針が未定、stateはどうやって定義するか?
function func_sjr_if_match_state(array $attrs, string $content){
    //    global $sjr_def_if_match_states;
    //    $state = sjr_get($attrs,'state');
    // $sjr_def_if_match_states[$state] = sjr_mk_if_match_state($attrs, $content);
    return $content;
}
add_shortcode('sjr_if_match_state', 'func_sjr_if_match_state');
