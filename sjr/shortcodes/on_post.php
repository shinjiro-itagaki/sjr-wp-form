<?php

require_once(dirname(__FILE__) . '/global.php');

// on_post
function func_sjr_on_post($attrs, $content)
{
    if(sjr\is_post()){
        $prefix = sjr_get($attrs, 'post_varname_prefix', '');
        return sjr_on_vars(function() use ($content, $prefix){
            foreach($_POST as $k => $v){
                sjr_set_var($prefix.$k, $v);
            }
            return sjr_do_shortcode($content);
        });
        return sjr_do_shortcode($content);
    }
    return "";
}
wrapper()->add_shortcode('sjr_on_post', 'func_sjr_on_post');

// sjr_redirect_to slug="" url=""
function func_sjr_redirect_to($attrs, $content)
{
    $url  = sjr_get($attrs, 'url');
    $slug = sjr_get($attrs, 'slug');

    if($slug){
        $url = slug_to_path($slug);
    }
    sjr\redirect_to($url ? $url : "./");
}
wrapper()->add_shortcode('sjr_redirect_to', 'func_sjr_redirect_to');

// sjr_create_user varname="@xx" username="jjj" password="@xxx" email="{email}"
function func_sjr_create_user(array $attrs)
{
    if(sjr\is_post()){
        $varname  = sjr_get($attrs, 'varname');
        $username = sjr_get($attrs, 'username');
        $password = sjr_get($attrs, 'password');
        $email    = sjr_get($attrs, 'email');

        /* echo "=====userinfo====<br />";
         * echo $varname;
         * echo $username;
         * echo $password;
         * echo $email;
         * echo "-----<br />"; */
        
        $res = wrapper()->create_user($username, $password, $email);
        if($res){
            sjr_set_var($varname, ($res->isSuccess() ? $res->userID() : null));
        }
        // return print_r($res,true);
        // return 
    }
    return "";
}
wrapper()->add_shortcode('sjr_create_user', 'func_sjr_create_user');
