<?php
namespace sjr\controller;

function init($wp_query){
    echo print_r($_POST, true);
    // header("Location: http://www.yahoo.co.jp");
    // exit;
}

add_filter('parse_request', 'sjr\controller\init');

