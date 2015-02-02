<?php
defined( 'ABSPATH' ) or die( 'Access denied!' );

function admin_options_page()
{
    if ( !empty( $_POST ) ) {
        $hashtag = sanitize_text_field( $_POST['add_to_cart'] );
        $api_key = sanitize_text_field( $_POST['twt_api_key'] );
        $api_secret = sanitize_text_field( $_POST['twt_api_sec'] );
        $access_token = sanitize_text_field( $_POST['acc_tok'] );
        $access_token_secret = sanitize_text_field( $_POST['acc_tok_sec'] );
        $wishlist_hashtag = sanitize_text_field( $_POST['add_to_wish'] );

        update_option( 'tc_twitter_hashtag', $hashtag );
        update_option( 'tc_wishlist_hashtag', $wishlist_hashtag );
        update_option( 'tc_twitter_api_key', $api_key );
        update_option( 'tc_twitter_api_secret', $api_secret );
        update_option( 'tc_twitter_access_token', $access_token );
        update_option( 'tc_twitter_access_token_secret', $access_token_secret );
        //update_option('tc_site_dir', $site_dir);

        my_message( 'Option was successfully saved!' );
    }
    $hashtag = get_option( 'tc_twitter_hashtag' );
    $wishlist_hashtag = get_option( 'tc_wishlist_hashtag' );
    $api_key = get_option( 'tc_twitter_api_key' );
    $api_secret = get_option( 'tc_twitter_api_secret' );
    $access_token = get_option( 'tc_twitter_access_token' );
    $access_token_secret = get_option( 'tc_twitter_access_token_secret' );
    //$site_dir = get_option('tc_site_dir');

    echo require_once TC_TEMPLATES_PATH . 'options_page.php';
}

//Vendor options page
function vendor_options_page()
{
    require_once TC_PLUGIN_PATH . 'includes/user.php';
    if ( !empty( $_POST ) ) {
        delete_user_hashtag( get_current_user_id() );
        add_user_hashtag( get_current_user_id(), sanitize_text_field( $_POST['hashtag'] ) );
        add_user_wishlist_hashtag( get_current_user_id(), sanitize_text_field( $_POST['wishlist_hashtag'] ) );
    }
    $personalAccount = is_personal_account();
    $hashtag = get_user_hashtag( get_current_user_id() );
    $wishlist_hashtag = get_user_wishlist_hashtag( get_current_user_id() );
    echo require_once TC_TEMPLATES_PATH . 'options_page_vendor.php';
}

//Frontend options for vendors (Ajax loading)
function frontend_options_page()
{
    require_once TC_PLUGIN_PATH . 'includes/user.php';
    $account = get_user_twitter_account( get_current_user_id() );
    if ( !$account || empty( $account ) ) {
        $hashtag = false;
    } else {
        $hashtag = get_user_hashtag( get_current_user_id() );
    }

    die( json_encode( require_once TC_TEMPLATES_PATH . 'frontend_options_page.php' ) );
}

//Save frontend options
function save_frontend_options( $data )
{
    global $wpdb;
    $hashtag = strip_tags( trim( $data['hashtag'] ) );
    delete_user_hashtag( get_current_user_id() );
    add_user_hashtag( get_current_user_id(), $hashtag );
    die( 1 );
}

//Delete previous user hashtag
function delete_user_hashtag( $user_id )
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'tc_user_hashtags';
    @$wpdb->query( $wpdb->prepare(
                            "
                DELETE FROM $table_name WHERE user_id = %d
            ", $user_id
            ) );
}

//Add new user hashtag
function add_user_hashtag( $user_id, $hashtag )
{
    if ( $hashtag != "" ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'tc_user_hashtags';
        if ( isset_user_hashtag( $user_id ) ) {
            @$wpdb->query( $wpdb->prepare(
                                    "
                UPDATE $table_name SET hashtag = %s WHERE user_id = %d
            ", array(
                                $hashtag,
                                $user_id
                                    )
                    ) );
        } else {
            @$wpdb->query( $wpdb->prepare(
                                    "
                INSERT INTO $table_name (user_id, hashtag) VALUES(%d, %s)
            ", array(
                                $user_id,
                                $hashtag
                                    )
                    ) );
        }
    }
}

//Set user wishlist hashtag
function add_user_wishlist_hashtag( $user_id, $hashtag )
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'tc_user_hashtags';
    if ( isset_user_hashtag( $user_id ) ) {
        @$wpdb->query( $wpdb->prepare(
                                "
                UPDATE $table_name SET wishlist_hashtag = %s WHERE user_id = %d
            ", array(
                            $hashtag,
                            $user_id
                                )
                ) );
    } else {
        @$wpdb->query( $wpdb->prepare(
                                "
                INSERT INTO $table_name (user_id, wishlist_hashtag) VALUES(%d, %s)
            ", array(
                            $user_id,
                            $hashtag
                                )
                ) );
    }
}

//Check user hashtag
function isset_user_hashtag( $user_id )
{
    global $wpdb;
    $tablename = $wpdb->prefix . "tc_user_hashtags";

    $result = $wpdb->get_var( $wpdb->prepare(
                    "
                SELECT uh_id FROM $tablename WHERE user_id = %d
            ", $user_id
            ) );
    if ( !$result || empty( $result ) ) {
        return FALSE;
    }
    return TRUE;
}

function is_personal_account()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'tc_accounts';

    $user_id = get_current_user_id();
    $result = $wpdb->get_var( $wpdb->prepare(
                    "
                SELECT twitter_account FROM $table_name WHERE user_id = %d
            ", $user_id
            ) );
    if ( empty( $result ) || !$result ) {
        return FALSE;
    }
    return $result;
}

function send_dev_request( $message )
{
    $email = get_option( 'tc_support_email' );
    wp_mail( $email, 'New support request', $message );
}
