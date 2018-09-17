<?php

require_once( dirname( __FILE__ ) . "/global.php" );

define("SJR_TYPE","sjr_type");
define("SJR_ATTRS","attr");
define("SJR_CONTENT","content");

function sjr_replace_by_vars($v){
    if(is_string($v)){
        $vars = sjr_get_vars();
        foreach($vars as $varname => $value ){
            $sym = "{". $varname . "}";
            $v = str_replace($sym,$value,$v);
        }
    }
    return $v;    
}

function sjr_get($attrs, $name, $if_not_found=null)
{
    $v = (isset($attrs[$name])) ? sjr_replace_by_vars($attrs[$name]) : $if_not_found;
    return $v;
}

function sjr_mk_common(string $content, array $attrs)
{
    return [SJR_ATTRS   => $attrs,
            SJR_CONTENT => $content];
}

function sjr_mk_if_match_state(string $content, array $attrs)
{
    return sjr_mk_common($content, $attrs);
}

/*
 [some_shortcode]
<input name="{name}" /> baa is {baa}
 [/some_shortcode]

 [some_shortcode2 hoge="hogehoge" foo="foofoo" baa="___"]
 <div>hoge = {hoge}</div>
 <div>foo  = {foo}</div>
   [some_shortcode name="{hoge}"]
 [/some_shortcode2]

=>
 
 <div>hoge = hogehoge</div>
 <div>foo  = foofoo</div>
<input name="hogehoge" /> baa is ___
*/
function sjr_do_shortcode(string $content, array $attrs=[], array $defaults=[]): string
{
    $attrs = $attrs + $defaults;
   
    return sjr_on_attrs($attrs, function($new_attrs) use ($content) {
        sjr_set_parent_attrs($new_attrs);
        $content = wrapper()->do_shortcode($content);
        
        foreach($new_attrs as $k => $v) {
            $k2 = "{". $k . "}";
            $content = str_replace($k2, $v, $content);
        }
        
        $vars = sjr_get_vars();
        foreach($vars as $k => $v){
            $k2 = "{". $k . "}";
            $content = str_replace($k2, $v, $content);
        }
        // return "<div>" . print_r($new_attrs, true) . "</div>" . do_shortcode($content);
        return $content;
    });
}

function slug_to_page(string $slug){
    return wrapper()->slug_to_page($slug);
}

function slug_to_path(string $slug){
    $page = slug_to_page($slug);
    if($page){
        return $page->getURL();
    }else{
        return null;
    }    
}

// [sjr_require name="sjr_def_inputs" debug=""]
function func_sjr_require(array $attrs)
{
    $slug = sjr_get($attrs, 'slug');
    $debug = sjr_get($attrs, 'debug');
    $post_id = "none";
    $post_type = "??";
    $content = "";
    if($slug){
        # post : 投稿ページ
        # page : 固定ページ
        $post = slug_to_page($slug);
        if($post){
            $content = $post->getContent();
            sjr_do_shortcode($content);
            $post_id = $post->getID();
            $post_type = $post->getType();
        }else{
            $content = "page of '$slug' is not found.";
        }
    }else{
        $content = "value of 'slug' is not found.";
    }
    
    if($debug){
        return "
<div style=''>
  <div>post_id = '$post_id', type='$post_type' </div>
  <pre>$content</pre>
</div>
";
    }else{
        return "";
    }
}
wrapper()->add_shortcode('sjr_require', 'func_sjr_require');

function func_sjr_set_var(array $attrs)
{
    $name = sjr_get($attrs, 'name');
    $val = sjr_get($attrs, 'value');
    sjr_set_var($name,$val);
    return "";
}
wrapper()->add_shortcode('sjr_set_var', 'func_sjr_set_var');

// sjr_get_var name="username"
function func_sjr_get_var(array $attrs)
{
    $name = sjr_get($attrs, 'name');
    return sjr_get_var($name);
}
wrapper()->add_shortcode('sjr_get_var', 'func_sjr_get_var');

// sjr_if true=""
function func_sjr_if(array $attrs, string $content)
{
    $varname = sjr_get($attrs, 'varname');
    $val = sjr_get_var($varname);
    if($val){
        return sjr_do_shortcode($content);
    }else{
        return "";
    }
}
wrapper()->add_shortcode('sjr_if', 'func_sjr_if');

function func_sjr_if_not(array $attrs, string $content)
{
    $varname = sjr_get($attrs, 'varname');
    $val = sjr_get_var($varname);
    if(!$val){
        return sjr_do_shortcode($content);
    }else{
        return "";
    }
}
wrapper()->add_shortcode('sjr_if_not', 'func_sjr_if_not');

function redirect_to(string $url){
    headers("Location: $url");
    exit;
}

function sjr_generate_password(int $length, $add_chars="") : string {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789' . $add_chars ;
    // $chars .= '!@#$%^&*()';
    // $chars .= '-_ []{}<>~`+=,.;:/?|';
 
    $password = '';
    $strlen = strlen($chars);
    for ( $i = 0; $i < $length; $i++ ) {
        $password .= substr($chars, wp_rand(0, $strlen - 1), 1);
    }
    return $password;
}

// sjr_generate_password varname="@new_password" add_chars=""
function func_sjr_generate_password(array $attrs)
{
    $varname = sjr_get($attrs, 'varname');
    $add_chars = sjr_get($attrs, 'add_chars', "");
    $val = sjr_generate_password(12);
    sjr_set_var($varname, $val);
    return "";
}
wrapper()->add_shortcode('sjr_generate_password', 'func_sjr_generate_password');
