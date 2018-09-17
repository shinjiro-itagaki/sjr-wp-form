<?php

// sjr_redirect_to slug=""
function func_sjr_redirect_to(array $attrs, string $content)
{
    $slug = sjr_get($attrs, 'slug');
    $url = slug_to_path($slug);
    // sjr\redirect_to($url ? $url : "/");
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
