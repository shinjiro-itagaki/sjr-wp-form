<?php
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
<input name="{name}" /> 
 [/some_shortcode]

 [some_shortcode2 hoge="hogehoge" foo="foofoo"]
 <div>hoge = {hoge}</div>
 <div>foo  = {foo}</div>
   [some_shortcode name="{hoge}"]
 [/some_shortcode2]

=>
 
 <div>hoge = hogehoge</div>
 <div>foo  = foofoo</div>
<input name="hogehoge" /> 
*/
function sjr_do_shortcode(string $content, array $attrs=[], array $defaults=[]): string
{
    $attrs = $attrs + $defaults;
    foreach($attrs as $k => $v) {
        $k2 = "{". $k . "}";
        $content = str_replace($k2, $v, $content);
    }
    return do_shortcode($content);
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
        $post = get_page_by_path( $slug,  OBJECT, ['post','page'] );
        if($post){
            $content = $post->post_content;
            sjr_do_shortcode($content);
            $post_id = $post->ID;
            $post_type = $post->post_type;
        }
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
