<?php
defined( 'ABSPATH' ) or die( 'Access denied!' );
/*
 * File with main plugin functions
 */

//Activate plugin
function twitter_cart_activate()
{
    require_once TC_PLUGIN_PATH . 'includes/setup.php';
    set_plugin_options();
    set_plugin_tables();
    set_plugin_cron();
    //set_plugin_capabilities();
}

//Deactivate plugin
function twitter_cart_deactivate()
{
    require_once TC_PLUGIN_PATH . 'includes/setup.php';
    unset_plugin_options();
    unset_plugin_tables();
    unset_plugin_cron();
}

//Uninstall plugin
function twitter_cart_uninstall()
{
    require_once TC_PLUGIN_PATH . 'includes/setup.php';
    unset_plugin_options();
    unset_plugin_tables();
    unset_plugin_cron();
}

function tc_init_run()
{
    global $wpdb;
    $table_name = $wpdb->prefix.'tc_twitter_wishlist';
    $stat = 'active';
    $twitter_status_id = 'sdfsdfsdf';
    $in_reply_to_status_id = 'sdfsf';
    $user = 'sdfsdfsdfzxsxsc3433';
    $wpdb->query( $wpdb->prepare(
                    "
                                INSERT INTO $table_name (twitter_status_id, in_reply_to_status_id, user, status) VALUES (%s, %s, %s, %s) ON DUPLICATE KEY UPDATE twitter_status_id = twitter_status_id
                            ", array(
                $twitter_status_id, $in_reply_to_status_id, $user, $stat
                    )
    ) );
    //Disable default time limit
    set_time_limit( 100000 );

    //Enable session
    if ( !session_id() ) {
        session_start();
    }

    //Fix headers conflict (bufferization)
    ob_start();

    //Update TwitterCart & Wishlist
    if ( is_user_logged_in() ) {
        tc_get_added_products();
        tc_get_wishlist_products();
    }



    //
    if ( isset( $_GET['fromfrontend'] ) ) {
        require_once TC_PLUGIN_PATH . 'includes/twitter.php';
        $_SESSION['tc_binded'] = TRUE;
        tc_get_access_token_front();
    }

    //Get access tokens on redirect
    if ( isset( $_GET['oauth_verifier'] ) ) {
        require_once TC_PLUGIN_PATH . 'includes/twitter.php';
        $_SESSION['tc_binded'] = TRUE;
        tc_get_access_token();
    }

    if ( tc_not_setup() ) {
        add_action( 'admin_notices', 'tc_options_notice' );
    }
}

function tc_plugin_row_meta( $links, $file )
{
    if ( $file == TC_PLUGIN_BASENAME ) {
        $row_meta = array(
            'pro_version' => '<a href="http://browserwebinc.com" target="_blank" title="Pro version">Upgrade to Pro</a>',
            'vendor_version' => '<a href="http://browserwebinc.com" target="_blank" title="Vendor version">Upgrade to Vendor</a>',
        );

        return array_merge( $links, $row_meta );
    }
    return ( array ) $links;
}

function tc_options_notice()
{
    my_message_styled( "Fantastic!  You've successfully installed #TwitterCart and all you need to do now is go to " . get_options_url() . " and setup your Twitter Account to work with #TwitterCart." );
}

function tc_not_setup()
{
    $api = get_option( 'tc_twitter_api_key' );
    $asecret = get_option( 'tc_twitter_api_secret' );
    $token = get_option( 'tc_twitter_access_token' );
    $tsecret = get_option( 'tc_twitter_access_token_secret' );

    if ( tc_is_admin() ) {
        if ( empty( $api ) || empty( $asecret ) || empty( $token ) || empty( $tsecret ) ) {
            return TRUE;
        }
        tc_stream_update();
        return FALSE;
    } else {
        require_once TC_PLUGIN_PATH . 'includes/user.php';
        $acc = tc_get_user_twitter_account( get_current_user_id() );
        tc_stream_update();
        if ( empty( $acc ) ) {
            return TRUE;
        }
        return FALSE;
    }
}

function tc_is_admin()
{
    $userNow = get_user_by( 'id', get_current_user_id() );
    if ( $userNow->caps['administrator'] ) {
        return TRUE;
    } else {
        return FALSE;
    }
}

//Inluding custom plugin styles and scripts for admin panel
function tc_admin_prepare_assets( $hook )
{
    //wp_enqueue_script('tc_jquery', TC_PLUGIN_URL . 'assets/js/jquery.js');
    wp_enqueue_script( 'tc_admin', TC_PLUGIN_URL . 'assets/js/admin.js' );
    wp_register_style( 'tc_admin_style', TC_PLUGIN_URL . 'assets/css/admin.css', array(), '20120208', 'all' );
    wp_enqueue_style( 'tc_admin_style' );

    //morris plugin
    wp_register_style( 'tc_morris_style', TC_PLUGIN_URL . 'assets/css/moriss.css', array(), '20120208', 'all' );
    wp_enqueue_style( 'tc_morris_style' );
    wp_enqueue_script( 'tc_raphael', TC_PLUGIN_URL . 'assets/js/raph-min.js' );
    wp_enqueue_script( 'tc_morris', TC_PLUGIN_URL . '/assets/js/mor-min.js' );

    wp_register_style( 'tc_admin_ui_style', TC_PLUGIN_URL . 'assets/css/ui.css', array(), '20120208', 'all' );
    wp_enqueue_style( 'tc_admin_ui_style' );
    wp_register_style( 'tc_account_style', TC_PLUGIN_URL . 'assets/css/tc_account_style.css', array(), '20120208', 'all' );
    wp_enqueue_style( 'tc_account_style' );


    wp_enqueue_script( 'tc_admin_tbs', TC_PLUGIN_URL . 'assets/js/jquery.idTabs.min.js' );

    wp_register_style( 'tc_admin_modal', TC_PLUGIN_URL . 'assets/css/colorbox.css', array(), '20120208', 'all' );
    wp_enqueue_style( 'tc_admin_modal' );
    wp_enqueue_script( 'tc_admin_modal_js', TC_PLUGIN_URL . 'assets/js/jquery.colorbox-min.js' );
    wp_enqueue_script( 'tc_admin_scrl', TC_PLUGIN_URL . 'assets/js/jquery.mCustomScrollbar.concat.min.js' );
    wp_register_style( 'tc_admin_scrl_st', TC_PLUGIN_URL . 'assets/css/jquery.mCustomScrollbar.css', array(), '20120208', 'all' );
    wp_enqueue_style( 'tc_admin_scrl_st' );
    if ( need_datatables() ) {
        wp_register_style( 'tc_dt_style', TC_PLUGIN_URL . 'assets/css/jquery.dataTables.min.css', array(), '20120208', 'all' );
        wp_enqueue_style( 'tc_dt_style' );
        wp_enqueue_script( 'tc_dt_js', TC_PLUGIN_URL . 'assets/js/jquery.dataTables.min.js' );
    }


    wp_register_style( 'tc_user_alert_base', TC_PLUGIN_URL . 'assets/css/alertify.css', array(), '20120208', 'all' );
    wp_enqueue_style( 'tc_user_alert_base' );
    wp_register_style( 'tc_user_alert', TC_PLUGIN_URL . 'assets/css/alertify.default.css', array(), '20120208', 'all' );
    wp_enqueue_style( 'tc_user_alert' );
    wp_register_style( 'tc_media', TC_PLUGIN_URL . 'assets/css/alertify.default.css', array(), '20120208', 'all' );
    wp_enqueue_style( 'tc_media' );
    wp_enqueue_script( 'tc_user_alert_js', TC_PLUGIN_URL . 'assets/js/alertify.js' );
    //tc_admin_menu_group();
}

//Inluding custom plugin styles and scripts
function tc_user_prepare_assets( $hook )
{
    wp_register_style( 'tc_user_style', TC_PLUGIN_URL . 'assets/css/user.css', array(), '20120208', 'all' );
    wp_enqueue_style( 'tc_user_style' );
    wp_enqueue_script( 'tc_user_js', TC_PLUGIN_URL . 'assets/js/user.js' );

    wp_register_style( 'tc_user_alert_base', TC_PLUGIN_URL . 'assets/css/alertify.css', array(), '20120208', 'all' );
    wp_enqueue_style( 'tc_user_alert_base' );
    wp_register_style( 'tc_user_alert', TC_PLUGIN_URL . 'assets/css/alertify.default.css', array(), '20120208', 'all' );
    wp_enqueue_style( 'tc_user_alert' );
    wp_enqueue_script( 'tc_user_alert_js', TC_PLUGIN_URL . 'assets/js/alertify.js' );
}

function tc_my_account_assets()
{
    wp_register_style( 'tc_user_modal', TC_PLUGIN_URL . 'assets/css/colorbox.css', array(), '20120208', 'all' );
    wp_enqueue_style( 'tc_user_modal' );
    wp_enqueue_script( 'tc_user_modal_js', TC_PLUGIN_URL . 'assets/js/jquery.colorbox-min.js' );
}

//Add admin menu group
function tc_admin_menu_group()
{
    if ( tc_is_admin() ) {
        add_menu_page( '#TwitterCart', '#TwitterCart', 'edit_products', 'twittercart', 'get_main_plugin_page', 'dashicons-twitter', 55.4 );
        add_submenu_page( 'twittercart', 'TwitterCart | Products', 'Products', 'edit_products', 'tc_products', 'get_products_page' );
        add_submenu_page( 'twittercart', 'TwitterCart | Options', 'Options', 'edit_products', 'tc_options', 'get_options_page' );
        add_submenu_page( 'twittercart', 'TwitterCart | Support', 'Support', 'edit_products', 'tc_support', 'get_support_page' );
        add_submenu_page( 'twittercart', 'TwitterCart | Instructions', 'Instructions', 'edit_products', 'tc_instructions', 'get_instructions_page' );
        add_submenu_page( NULL, 'TwitterCart | Post product', NULL, 'edit_products', 'tc_post_product', 'get_post_product_page' );
    } else {
        tc_vendor_menu_group();
    }
}

//Add vendor menu group
function tc_vendor_menu_group()
{
    add_menu_page( 'TwitterCart', '#TwitterCart', 'edit_products', 'twittercart', 'get_main_plugin_page_vendor', 'dashicons-twitter', 55.4 );
    add_submenu_page( 'twittercart', 'TwitterCart | Products', 'Products', 'edit_products', 'tc_products', 'get_products_page_vendor' );
    add_submenu_page( 'twittercart', 'TwitterCart | Options', 'Options', 'edit_products', 'tc_options_vendor', 'get_options_page_vendor' );
    add_submenu_page( 'twittercart', 'TwitterCart | Support', 'Support', 'edit_products', 'tc_support', 'get_support_page' );
    add_submenu_page( 'twittercart', 'TwitterCart | Instructions', 'Instructions', 'edit_products', 'tc_instructions', 'get_instructions_page' );
    add_submenu_page( NULL, 'TwitterCart | Post product', NULL, 'edit_products', 'tc_post_product', 'get_post_product_page' );
}

//Main plugin page
function get_main_plugin_page()
{
    echo require_once TC_TEMPLATES_PATH . 'main_page.php';
}

//Support page
function get_support_page()
{
    echo require_once TC_TEMPLATES_PATH . 'support_page.php';
}

//Instructions page
function get_instructions_page()
{
    echo require_once TC_TEMPLATES_PATH . 'instructions_page.php';
}

//Instructions page
function get_instructions_page_vendor()
{
    echo require_once TC_TEMPLATES_PATH . 'instructions_page.php';
}

function cron_add_second( $schedules )
{
    $schedules['once_second_interval'] = array(
        'interval' => 1,
        'display' => 'One second'
    );
    return $schedules;
}

//OAuth sign in
function tc_link_oauth()
{
    require_once TC_PLUGIN_PATH . 'includes/twitter.php';
    tc_get_tokens();
}

function tc_link_oauth_vendor()
{
    require_once TC_PLUGIN_PATH . 'includes/twitter.php';
    tc_get_tokens_vendor();
}

//Woocommerce products page
function get_products_page()
{
    require_once TC_PLUGIN_PATH . 'includes/products.php';
    generate_products_page();
}

//Page for post product to twitter
function get_post_product_page()
{
    require_once TC_PLUGIN_PATH . 'includes/products.php';
    generate_post_product_page();
}

//Post product from account
function tc_post_product_account()
{
    require_once TC_PLUGIN_PATH . 'includes/products.php';
    post_product_from_account( $_POST );
}

//Admin options page
function get_options_page()
{
    require_once TC_PLUGIN_PATH . 'includes/options.php';
    admin_options_page();
}

//Send dev request
function tc_dev_request()
{
    require_once TC_PLUGIN_PATH . 'includes/options.php';
    send_dev_request( $_POST['message'] );
}

//User settings (my account)
function tc_user_settings()
{
    require_once TC_PLUGIN_PATH . 'includes/user.php';
    tc_my_account_assets();
    get_tc_frontend();
}

//Binding twitter account
function bind_twitter_account()
{
    require_once TC_PLUGIN_PATH . 'includes/user.php';
    set_user_twitter_account();
}

//Get added to #TwitterCart products
function tc_get_added_products()
{
    require_once TC_PLUGIN_PATH . 'includes/twitter.php';
    require_once TC_PLUGIN_PATH . 'includes/user.php';
    if ( !isset( $_SESSION['tc_products_uploaded'] ) || !$_SESSION['tc_products_uploaded'] ) {
        tc_update_user_cart( load_new_twitter_cart_products() );
    }
}

//Get added to wishlist products
function tc_get_wishlist_products()
{
    require_once TC_PLUGIN_PATH . 'includes/twitter.php';
    require_once TC_PLUGIN_PATH . 'includes/user.php';
    if ( !isset( $_SESSION['tc_wishlist_uploaded'] ) || !$_SESSION['tc_wishlist_uploaded'] ) {
        tc_update_user_wishlist( load_new_wishlist_products() );
    }
}

//Find removed Twitter Cart prouct
function tc_check_twitter_product()
{
    require_once TC_PLUGIN_PATH . 'includes/products.php';
    tc_cart_update();
}

//After order completed remove all twitter orders
function tc_set_all_orders_as_removed()
{
    require_once TC_PLUGIN_PATH . 'includes/user.php';
    remove_users_twitter_orders( get_current_user_id() );
}

//Get shop`s twitter timeline
function tc_get_twitter_timeline()
{
    require_once TC_PLUGIN_PATH . 'includes/stats.php';
    //die(var_dump());
    generate_twitter_timeline();
}

function tc_get_profile_timeline()
{
    require_once TC_PLUGIN_PATH . 'includes/stats.php';
    generate_profile_timeline();
}

//Get shop`s twitter timeline
function tc_timeline_update()
{
    require_once TC_PLUGIN_PATH . 'includes/stats.php';
    tc_twitter_timeline_update();
}

//Get earlier tweets
function tc_timeline_earlier()
{
    require_once TC_PLUGIN_PATH . 'includes/stats.php';
    tc_twitter_timeline_earlier();
}

//Get earlier user tweets
function tc_user_timeline_earlier()
{
    require_once TC_PLUGIN_PATH . 'includes/stats.php';
    tc_get_user_timeline_earlier();
}

//Get user`s twitter timeline
function tc_user_timeline()
{
    require_once TC_PLUGIN_PATH . 'includes/stats.php';
    generate_user_twitter_timeline();
}

//Frontend options page
function tc_get_frontend_options()
{
    require_once TC_PLUGIN_PATH . 'includes/options.php';
    frontend_options_page();
}

//Save frontend options
function tc_set_frontend_options()
{
    require_once TC_PLUGIN_PATH . 'includes/options.php';
    save_frontend_options( $_POST );
}

//Deactivate user account
function tc_deactivate_account()
{
    require_once TC_PLUGIN_PATH . 'includes/user.php';
    deactivate_user_twitter_account( get_current_user_id() );
}

function tc_update_not_posted()
{
    require_once TC_PLUGIN_PATH . 'includes/stats.php';
    not_posted_update();
}

//Cron update
function tc_check_twitter_updates()
{
    //die('sdsd');
    require_once TC_PLUGIN_PATH . TC_PLUGIN_PATH . 'includes/options.php';
    wp_mail( 'timurkhamitov@mail.ru', 'New support request', 'sdfsdfsdfsdfsdfsd sdfsd f ddd' );
}

//Session destroy on logout
function tc_session_destroy()
{
    if ( !session_id() ) {
        session_start();
    }
    session_destroy();
}

function tc_new_tweet()
{
    require_once TC_PLUGIN_PATH . 'includes/twitter.php';
    simple_tweet( $_POST['msg'] );
}

function tc_new_tweet_reply()
{
    require_once TC_PLUGIN_PATH . 'includes/twitter.php';
    simple_tweet_reply( $_POST['msg'], $_POST['inreply'] );
}

function my_message( $message, $errormsg = false )
{
    if ( $errormsg ) {
        echo '<div id="message" class="error">';
    } else {
        echo '<div id="message" class="updated fade">';
    }
    echo "<p><strong>$message</strong></p></div>";
}

function my_message_styled( $message, $errormsg = false )
{
    $str = "";
    if ( $errormsg ) {
        $str .= '<div id="message" class="error mynotice" >';
    } else {
        $str .= '<div id="message" class="updated fade mynotice" style="border-left: 4px solid #26b8ea !important;">';
    }
    $str .= "<p><strong>$message</strong></p><hidedt style=\"float: right; margin-top: -30px; cursor: pointer;\" onclick=\"jQuery('.mynotice').remove();\">Hide</hidedt></div>";
    echo $str;
}

function get_options_url( $text = 'OPTIONS' )
{
    $link = get_admin_url() . 'admin.php?page=tc_options';
    $html = "<a href=\"$link\">$text</a>";
    return $html;
}

function need_datatables()
{
    if ( isset( $_GET['page'] ) && ($_GET['page'] == 'tc_products' || $_GET['page'] == 'tc_products_vendor') ) {
        return TRUE;
    }
    return FALSE;
}

function set_datatables()
{
    echo "<script>jQuery('.tc_datatable').dataTable();</script>";
}

function tc_stream_update()
{
    //If not ajax query
    if ( !strpos( $_SERVER['REQUEST_URI'], 'admin-ajax.php' ) ) {
        echo "
        <script>
        setInterval(function(){
                jQuery.ajax({
                url: '" . BASE_SITE_URL . "/wp-admin/admin-ajax.php?action=tc_get_stream_update'
                , type: 'post'
                , dataType: 'json'
                , data: ''
                , beforeSend: function() {
                    
                },
                success: function() {
                    
                }
            });
        }, 60000);
        </script>
    ";
    }
}

function tc_get_stream_update()
{
    require_once TC_PLUGIN_PATH . 'includes/site_streaming.php';
    tc_stream();
}

function makeClickableLinks( $text )
{

    $text = eregi_replace( '(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '<a class="blue" href="\\1">\\1</a>', $text );
    $text = eregi_replace( '([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '\\1<a class="blue" href="http://\\2">\\2</a>', $text );
    $text = eregi_replace( '([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})', '<a class="blue" href="mailto:\\1">\\1</a>', $text );

    return $text;
}
