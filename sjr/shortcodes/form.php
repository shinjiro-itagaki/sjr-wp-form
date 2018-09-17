<?php

define("SJR_FORM_PAGES","form_pages");

require_once( dirname( __FILE__ ) . "/common.php" );

function sjr_mk_def_form(string $content, array $attrs, array $pages)
{
    $rtn = sjr_mk_common($content, $attrs);
    $rtn[SJR_FORM_PAGES] = $pages;
    return $rtn;
}

function sjr_mk_page(string $content, array $attrs)
{
    return sjr_mk_common($content, $attrs);
}

// @params name
function func_sjr_def_form($attrs, $content)
{
    sjr_read_pages(function() use ($content) {
        sjr_do_shortcode($content);
    },function($sjr_pages) use ($content, $attrs) {
        $name = sjr_get($attrs,'name');
        sjr_set_form($name, sjr_mk_def_form( $content, $attrs, $sjr_pages));
    });
    
    return "";
}
add_shortcode('sjr_def_form', 'func_sjr_def_form');

function func_sjr_show_form($attrs, $content)
{
    return sjr_on_show(function() use ($attrs) {
        $form = null;
        $name = sjr_get($attrs,'name');
     
        if($name){
            $form = sjr_get_form($name);
        }
        
        if($form){
            $page = sjr_get($attrs,'page');
            $form_attrs   = $form[SJR_ATTRS];
            $form_content = $form[SJR_CONTENT];
            return sjr_on_show_page($page, function() use ($form_content, $attrs, $form_attrs) {
                return sjr_do_shortcode($form_content, $attrs, $form_attrs);
            });
        }else{
            $msg = print_r($sjr_def_forms,true);
            return "$msg <div>$name is not found.</div>";
        }
    });
}
add_shortcode('sjr_show_form', 'func_sjr_show_form');

function sjr_show_page($attrs, $content)
{
    return sjr_do_shortcode($content, $attrs);
}

function func_sjr_page($attrs, $content)
{
    if(sjr_is_on_show()){
        return sjr_show_page($attrs, $content);
    }else{
        sjr_push_page(sjr_mk_page( $content, $attrs));
        return "";
    }
}
add_shortcode('sjr_page', 'func_sjr_page');

function func_sjr_on_load_form_data($attrs, $content)
{
    
}
add_shortcode("sjr_on_load_form_data", 'func_sjr_on_load_form_data');

// == form
// sjr_add_form_data name="username" value="{tel}"
// ==
