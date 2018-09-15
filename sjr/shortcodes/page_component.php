<?php

require_once( dirname( __FILE__ ) . "/shortcodes/input.php" );

global $sjr_def_page_components = [];

function sjr_mk_def_page_component( $content, $attrs)
{
    return sjr_mk_common($component, $attrs);
}

function func_sjr_def_page_component($attrs, $content)
{
    global $sjr_def_page_components;
    $name = sjr_get($attrs,'name');
    $sjr_def_page_components[$name] = sjr_mk_def_page_component( $content, $attrs);
    return "";
}
add_shortcode('sjr_def_page_component', 'func_sjr_def_page_component');

// @name
function func_sjr_include_component($attrs, $content)
{
    global $sjr_def_page_components;
    $name = sjr_get($attrs,'name');
    $page_component = sjr_get($sjr_def_page_components, $name);
    if($page_component){
        sjr_name_replace_on();
        sjr_do_shortcode($content, $attrs);
        $component_attrs = $page_component[SJR_ATTRS];
        $component_content = $page_component[SJR_CONTENT];
        $rtn = sjr_do_shortcode($component_content, $component_attrs, $attrs);
        sjr_name_replace_off();
        return $rtn;
    }else{
        return "";
    }
}
add_shortcode('sjr_include_component', 'func_sjr_include_component');
