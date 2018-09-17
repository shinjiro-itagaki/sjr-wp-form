<?php
namespace sjr;

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
require_once( dirname(__FILE__) . '/interface.wrapper.php' );
require_once( dirname(__FILE__) . '/interface.page.php' );
require_once( dirname(__FILE__) . '/interface.user.php' );

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
    public function getID() : int {
        return $this->post->ID;
    }
    public function getContent() : string {
        return $this->post->post_content;
    }
    public function getType() : string{
        return $this->post->post_type;
    }
    public function getURL() : string{
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

class WP_FormData implements FormDataInterface
{
    private $post;
    private $meta = [];
    
    function __construct(WP_Post $post) {
        $this->post = $post;
    }
    
    public function getID() : int {
        return $this->post->ID;
    }
    
    public function getUserID() : int {
        return $this->post->post_author;
    }
    
    public function getData() : array {
        return get_post_meta($post_id);
    }

    public function setData(array $data) : array {
        $this->meta = $data;
        return get_post_meta($post_id);
    }

    public function save() : boolean {
        
    }
}

class WP_CreateUserResult implements CreateUserResult
{
    private $user_id = 0;
    private $errmsg = null; 
    function __construct($res) {
        if(is_numeric($res)){
            $this->user_id = $res;
        }else{
            $this->errmsg = print_r($res->get_error_messages(), true);
        }
    }
    
    public function isSuccess() : bool {
        return ($this->user_id > 0);
    }
    
    public function userID() : int {
        return $this->user_id;
    }
    
    public function errorMessage() : string {
        return $this->errmsg;
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

    function create_user(string $username, string $password, string $email) : CreateUserResult {
        $res = wp_create_user( $username, $password, $email );
        return new WP_CreateUserResult($res);
    }

    function find_form_data(  string $form_name, int $user_id) : FormDataInterface {
        $args = [
            'post_author' => $user_id,
            'post_type'   => PLUGIN_UUID_VAL,
            'post_status' => 'any',
        ];
    
        $posts = get_posts( $args );
        foreach($posts as $p){
            if ($uid == $unique_id){
                return new WP_FormData($p);
            }
        }
        return null;
    }
    
    function create_form_data(string $form_name, int $user_id, array $data) : FormDataInterface {
        $data = array(
            // 'ID'             => [ <投稿 ID> ] // 既存の投稿を更新する場合に指定。
            'post_author'  => $user_id,
            'post_content' => '',
            // 'post_name'      => [ <文字列> ] // 投稿のスラッグ。
            // 'post_title'     => [ <文字列> ] // 投稿のタイトル。
            'post_status'  => 'private',
            'post_type'    => PLUGIN_UUID_VAL,
        );

        // int|WP_Error)
        $res = wp_insert_post($data, true);
        if(is_numeric($res)){
            $post = get_post( $res, 'OBJECT');
            return new WP_FormData($post);
        }else{
            return null;
        }
    }
}

function new_wrapper() : Wrapper {
    return (new WP_Wrapper());
}
