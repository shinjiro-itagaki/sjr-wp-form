<?php
$sjr_def_inputs=[];
$name_replace_list = [];
$replace_name_on = false;

// @from
// @to
function func_sjr_replace_name($attrs)
{
    global $name_replace_list;
    $from = sjr_get($attrs,'from');
    $to   = sjr_get($attrs,'to');
    $name_replace_list[$from] = $to;
    return "";
}
add_shortcode('sjr_replace_name', 'func_sjr_replace_name');

function sjr_name_replace($func){
    global $name_replace_list;
    global $replace_name_on;
    $replace_name_on = true;
    $name_replace_list = [];
    $rtn = $func();
    $replace_name_on = false;
    return $rtn;
}

function get_input_def(string $sjr_type)
{
    global $sjr_def_inputs;
    return sjr_get($sjr_def_inputs, $sjr_type);
}

function sjr_mk_def_input(string $content, array $attrs)
{
    return sjr_mk_common($content, $attrs);
}


//=============-
// inputs
//=============-
// sjr_def_input(sjr_type)
// sjr_if_match_state(state,state_not)

// $sjr_def_if_match_states=[];



/*
[sjr_def_input sjr_type="person-name" maxlength="20" id=""]
  [sjr_if_match_state state="input"]
    <input id="{id}" type="text" name="{name}" maxlength="{maxlength}" {required} value="{value}" />
  [/sjr_if_match_state]
  [sjr_if_match_state state="confirm"]
    <span id="{id}">{value}</span>
  [/sjr_if_match_state]
[/sjr_def_input]
*/
function func_sjr_def_input(array $attrs, string $content)
{
    global $sjr_def_inputs;
    $sjr_type = sjr_get($attrs,SJR_TYPE);
    $sjr_def_inputs[$sjr_type] = sjr_mk_def_input($content, $attrs);
    return "";
}

add_shortcode('sjr_def_input', 'func_sjr_def_input');


// [sjr_input sjr_type="person-name"      name="first_name{suffix}"       requierd="required"]
// sjr_type
// value
// 
function func_sjr_input(array $attrs){
    global $name_replace_list;
    global $replace_name_on;

    if($replace_name_on){
    foreach( $name_replace_list as $from => $to )
    {
        $name = sjr_get($attrs,'name');
        if($name && $name == $from){
            $attrs['name'] = $to;
        }
    }
    }
    
    $sjr_type = sjr_get($attrs,SJR_TYPE);
    $def = null;
    if($sjr_type){
        $def = get_input_def($sjr_type);
    }
    if($def){
        $default_attrs = $def[SJR_ATTRS];
        $content = $def[SJR_CONTENT];
        return sjr_do_shortcode($content, $attrs, $default_attrs);
    }else{
        return "";
    }
}
add_shortcode('sjr_input', 'func_sjr_input');
