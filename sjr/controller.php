<?php
namespace sjr\controller;

function on_post()
{
    echo print_r($_POST, true);
}

function on_requested($wp_query){
    if(\sjr\is_post()){
        on_post();
    }
}

\sjr\register_requested_hook('sjr\controller\on_requested');
