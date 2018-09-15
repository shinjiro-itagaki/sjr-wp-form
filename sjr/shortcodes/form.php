<?php

require_once( dirname( __FILE__ ) . "/common.php" );

$sjr_def_forms=[];
function sjr_mk_def_form(string $content, array $attrs)
{
    return sjr_mk_common($content, $attrs);
}

// @params name
function func_sjr_def_form($attrs, $content)
{
    global $sjr_def_forms;
    $name = sjr_get($attrs,'name');
    $sjr_def_forms[$name] = sjr_mk_def_form( $content, $attrs);
    return "";
}
add_shortcode('sjr_def_form', 'func_sjr_def_form');

function func_sjr_show_form($attrs, $content)
{
    global $sjr_def_forms;
    $form = null;
    
    $name = sjr_get($attrs,'name');
    $page = sjr_get($attrs,'page');
    
    if($name){
        $form = sjr_get($sjr_def_forms, $name);
    }
    
    if($form){
        $form_attrs   = $form[SJR_ATTRS];
        $form_content = $form[SJR_CONTENT];
        return sjr_do_shortcode($form_content, $attrs, $form_attrs);
    }else{
        $msg = print_r($sjr_def_forms,true);
        return "$msg <div>$name is not found.</div>";
    }
}
add_shortcode('sjr_show_form', 'func_sjr_show_form');
