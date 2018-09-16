<?php

require_once( dirname( __FILE__ ) . "/common.php" );
require_once( dirname( __FILE__ ) . "/input.php" );

$sjr_def_page_components = [];

function sjr_get_page_component($name)
{
    global $sjr_def_page_components;
    return sjr_get($sjr_def_page_components,$name);
}

function sjr_set_page_component($name, $comp)
{
    global $sjr_def_page_components;
    $sjr_def_page_components[$name] = $comp;
}

function sjr_mk_def_page_component( $content, $attrs)
{
    return sjr_mk_common($content, $attrs);
}

function func_sjr_def_page_component($attrs, $content)
{
    $name = sjr_get($attrs,'name');
    sjr_do_shortcode($content);
    sjr_set_page_component($name, sjr_mk_def_page_component( $content, $attrs ));
    return "";
}
add_shortcode('sjr_def_page_component', 'func_sjr_def_page_component');

// @name
function func_sjr_include_component($attrs, $content)
{
    $name = sjr_get($attrs,'name');
    $page_component = sjr_get_page_component($name);
    if($page_component){
        return sjr_name_replace(function() use ($content, $attrs, $page_component) {
            sjr_do_shortcode($content, $attrs);
            $component_attrs = $page_component[SJR_ATTRS];
            $component_content = $page_component[SJR_CONTENT];
            return sjr_do_shortcode($component_content, $component_attrs, $attrs);
        });
    }else{
        global $sjr_def_page_components;
        return "jjjjjjj" . print_r($sjr_def_page_components,true);
    }
}
add_shortcode('sjr_include_component', 'func_sjr_include_component');
