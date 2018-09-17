<?php
namespace sjr;

require_once( dirname( __FILE__ ) . '/interface.page.php' );

interface Wrapper
{
    function add_shortcode(string $codename, $funcname);
    function do_shortcode(string $content) : string;
    function get_charset_collate();
    function mk_table_name(string $tname);
    function exec_sql(string $sql);
    function db_insert(string $table, array $values);
    function register_plugin_install_hooks();
    function register_requested_hook();
    function slug_to_page(string $slug) : PageInterface;
    
    // return 0 if failed
    function create_user(string $username, string $password, string $email) : int;
}

