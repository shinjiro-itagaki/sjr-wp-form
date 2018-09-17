<?php
namespace sjr;

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
require_once( dirname(__FILE__) . '/interface.wrapper.php' );
require_once( dirname(__FILE__) . '/interface.page.php' );

function do_shortcode_impl(string $content){
    return do_shortcode($content);
}

function get_charset_collate_impl(){
    global $wpdb;
    return $wpdb->get_charset_collate();
}

function mk_table_name_impl(string $tname){
    global $wpdb;
    return $wpdb->prefix . $tname;
}

function exec_sql_impl(string $sql){
    global $wpdb;
    return dbDelta( $sql );
}

function db_insert_impl(string $table, array $values){
    global $wpdb;
    return $wpdb->insert($table, $values);
}

function install_tables(){
}

function uninstall_tables(){
}


function register_plugin_install_hooks(){
    if(function_exists('register_activation_hook')) {
        register_activation_hook( __FILE__,  'install_tables' );
    }    
    if(function_exists('register_deactivation_hook')) {
        register_deactivation_hook( __FILE__, 'uninstall_tables' );
    }
}

function register_requested_hook(string $func_name){
    add_filter('parse_request', $func_name);
}

class WP_Page implements PageInterface
{
    private $post;
    function __construct($post) {
        $this->post = $post;
    }
    public function getID(){
        return $this->post->ID;
    }
    public function getContent(){
        return $this->post->post_content;
    }
    public function getType(){
        return $this->post->post_type;
    }
    public function getURL(){
        return get_permalink( $this->post->ID );
    }
}

function slug_to_page_impl(string $slug) : PageInterface {
    $page = get_page_by_path( $slug,  OBJECT, ['post','page'] );
    if($page){
        return (new WP_Page($page));
    }else{
        return null;
    }
}

class WP_Wrapper implements Wrapper
{
    function add_shortcode(string $codename, $funcname){
        return \add_shortcode($codename, $funcname);
    }
    
    function do_shortcode(string $content) : string {
        return do_shortcode_impl($content);
    }
    
    function get_charset_collate(){
        return get_charset_collate_impl();
    }
    
    function mk_table_name(string $tname) {
        return mk_table_name_impl($tname);
    }
    
    function exec_sql(string $sql) {
        return exec_sql_impl($sql);
    }
    
    function db_insert(string $table, array $values) {
        return db_insert_impl($table, $values);
    }
    
    function register_plugin_install_hooks(){
        if(function_exists('register_activation_hook')) {
            register_activation_hook( __FILE__,  'install_tables' );
        }
        if(function_exists('register_deactivation_hook')) {
            register_deactivation_hook( __FILE__, 'uninstall_tables' );
        }
    }
    function register_requested_hook(){
        return add_filter('parse_request', $func_name);
    }

    function slug_to_page(string $slug) : PageInterface {
        return slug_to_page_impl($slug);
    }

    function create_user(string $username, string $password, string $email) : int{
        $res = wp_create_user( $username, $password, $email );
        if(is_numeric($res)){
            return $res;
        }else{
            return 0;
        }
    }
}

function new_wrapper() : Wrapper {
    return (new WP_Wrapper());
}
