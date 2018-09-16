<?php

require_once( dirname( __FILE__ ) . "/global.php" );

define("SJR_TYPE","sjr_type");
define("SJR_ATTRS","attr");
define("SJR_CONTENT","content");

function sjr_get(array $attrs, string $name)
{
    return ((isset($attrs[$name])) ? $attrs[$name] : null );
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
        foreach($new_attrs as $k => $v) {
            $k2 = "{". $k . "}";
            $content = str_replace($k2, $v, $content);
        }
        // return "<div>" . print_r($new_attrs, true) . "</div>" . do_shortcode($content);
        sjr_set_parent_attrs($new_attrs);
        return do_shortcode($content);
    });    
}

function slug_to_page($slug){
    return get_page_by_path( $slug,  OBJECT, ['post','page'] );
}

function slug_to_path($slug){
    $page = slug_to_page($slug);
    if($page){
        return get_permalink( $page->ID );
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
            $content = $post->post_content;
            sjr_do_shortcode($content);
            $post_id = $post->ID;
            $post_type = $post->post_type;
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
add_shortcode('sjr_require', 'func_sjr_require');
