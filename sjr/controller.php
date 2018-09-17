<?php
namespace sjr\controller;

function on_post()
{
    // echo print_r($_POST, true);
    $slug = sjr_get($_POST, 'slug');
    $page = slug_to_page($slug);

    if($page){
        $content = $page->getContent();
        /* echo "<br />===========<br />";
         * echo print_r($content,true);
         * echo "<br />===========<br />";  */
        sjr_do_shortcode($content);
    }
    return "";
}

function on_requested($wp_query){
    if(\sjr\is_post()){
        on_post();
    }
}

\sjr\register_requested_hook('sjr\controller\on_requested');
