<?php

// sjr_redirect_to slug=""
function func_sjr_redirect_to(array $attrs, string $content)
{
    $slug = sjr_get($attrs, 'slug');
    $path = slug_to_path($slug);
    sjr\redirect_to($url ? $url : "/");
}
wrapper()->add_shortcode('sjr_redirect_to', 'func_sjr_redirect_to');

// sjr_create_user varname="@xx" username="jjj" password="@xxx" email="{email}"
function func_sjr_create_user(array $attrs)
{
    if(is_post()){
        $varname  = sjr_get($attrs, 'varname');
        $username = sjr_get($attrs, 'username');
        $password = sjr_get($attrs, 'password');
        $email    = sjr_get($attrs, 'email');
        $res = wrapper()->create_user($username, $password, $email);
        sjr_set_var($varname, $res);
    }
    return "";
}
wrapper()->add_shortcode('sjr_create_user', 'func_sjr_create_user');
