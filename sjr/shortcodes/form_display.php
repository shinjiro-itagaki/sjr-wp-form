<?php

function sjr_mk_def_form_display(string $content, array $attrs)
{
    return sjr_mk_common($content, $attrs);
}

// @params name
function func_sjr_def_form_display($attrs, $content)
{
    $name = sjr_get($attrs,'name');
    sjr_set_form_display(sjr_mk_def_form_display( $content, $attrs), $name);
    return "";
}
add_shortcode('sjr_def_form_display', 'func_sjr_def_form_display');

// @params name only
function func_sjr_show_form_display(array $default_attrs)
{
    $name = sjr_get($default_attrs,'name');
    $form_display = sjr_get_form_display($name);
    if($form_display){
        $content = $form_display[SJR_CONTENT];
        $attrs = $form_display[SJR_ATTRS];
        return sjr_do_shortcode($content, $attrs, ['name' => $name]);
    }else{
        return "";
    }
}
add_shortcode('sjr_show_form_display', 'func_sjr_show_form_display');

function sjr_on_form_display_posted(array $default_attrs, string $content)
{
    $name = sjr_get($attrs,'name');
    $form_display = sjr_get_form_display($name);
    if($form_display){
        $disp_content = $form_display[SJR_CONTENT];
        $disp_attrs = $form_display[SJR_ATTRS];
        sjr_do_shortcode($disp_content, $attrs, $default_attrs);
    }else{
        return "";
    }    
}
add_shortcode('sjr_on_form_display_posted', 'func_sjr_on_form_display_posted');
