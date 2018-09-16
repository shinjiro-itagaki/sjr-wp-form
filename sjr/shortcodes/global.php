<?php

$sjr_show = false;
$parent_attrs = [];
function sjr_on_attrs(array $current_attrs, $func)
{
    global $parent_attrs;
    $swap = [] + $parent_attrs;
    $new_attrs = $current_attrs + $parent_attrs;
    // $parent_attrs = $new_attrs;
    $rtn = $func($new_attrs);
    $parent_attrs = $swap;
    return $rtn;
}

function sjr_set_parent_attrs(array $attrs)
{
    global $parent_attrs;
    $parent_attrs = $attrs;
}

function sjr_is_on_show()
{
    global $sjr_show;
    return $sjr_show;
}

function sjr_on_show($func)
{
    global $sjr_show;
    $swp = $sjr_show;
    $sjr_show = true;
    $rtn = $func();
    $sjr_show = $swp;
    return $rtn;
}

$sjr_def_forms=[];
$sjr_pages = [];
$sjr_page_name = null;
function sjr_set_form(string $name, array $page)
{
    global $sjr_def_forms;
    $sjr_def_forms[$name]=$page;
}

function sjr_read_pages($before, $after)
{
    global $sjr_pages;
    $sjr_pages = [];
    $before();
    $after($sjr_pages);
}

function sjr_on_show_page($page_name, $func)
{
    global $sjr_page_name;
    $swp = $sjr_page_name;
    $sjr_page_name = $page_name;
    $rtn = $func();
    $sjr_page_name = $swp;
    return $rtn;
}

function sjr_get_form($name){
    global $sjr_def_forms;
    return  sjr_get($sjr_def_forms, $name);
}

function sjr_push_page(array $page)
{
    global $sjr_pages;
    array_push($sjr_pages, $page);
}

$sjr_def_form_displays=[];
function sjr_set_form_display(array $form_display, $name)
{
    global $sjr_def_form_displays;
    $sjr_def_form_displays[$name]= $form_display;
}

function sjr_get_form_display($name){
    global $sjr_def_form_displays;
    return sjr_get($sjr_def_form_displays, $name);
}
