<?php
defined( 'ABSPATH' ) or die( 'Access denied!' );

//Binding twitter account
function get_tc_frontend()
{
    $user_twitter = get_user_twitter_account( get_current_user_id() );
    $site_url = BASE_SITE_URL;
    echo require_once TC_TEMPLATES_PATH . 'user_twitter_form.php';
}

//Get user twitter account
function get_user_twitter_account( $user_id )
{
    global $wpdb;
    $table_name = $wpdb->prefix . "tc_accounts";
    $sql = "SELECT twitter_account FROM $table_name WHERE user_id = $user_id";
    return $wpdb->get_var( $sql );
}

//Get user twitter token
function get_user_token()
{
    $user_id = get_current_user_id();
    global $wpdb;
    $table_name = $wpdb->prefix . "tc_user_tokens";
    $sql = "SELECT oauth_token FROM $table_name WHERE user_id = $user_id";
    return $wpdb->get_var( $sql );
}

//Get user twitter secret
function get_user_secret()
{
    $user_id = get_current_user_id();
    global $wpdb;
    $table_name = $wpdb->prefix . "tc_user_tokens";
    $sql = "SELECT oauth_token_secret FROM $table_name WHERE user_id = $user_id";
    return $wpdb->get_var( $sql );
}

//Set new account
function set_user_twitter_account( $accountSelected = FALSE )
{
    if ( $accountSelected ) {
        $request_data = $accountSelected;
    } else {
        $request_data = $_POST['account'];
    }
    $account = str_replace( "@", "", strip_tags( trim( $request_data ) ) );
    delete_previous_account( get_current_user_id() );
    bind_new_account( get_current_user_id(), $account );
}

//Get user account
function tc_get_user_twitter_account( $user_id )
{
    global $wpdb;
    $table_name = $wpdb->prefix . "tc_accounts";
    return $wpdb->get_var( $wpdb->prepare(
            "
                SELECT twitter_account FROM $table_name WHERE user_id = %d
            ", $user_id
    ) );
}

function delete_previous_account( $user_id )
{
    global $wpdb;
    $table_name = $wpdb->prefix . "tc_accounts";
    $wpdb->query( $wpdb->prepare(
            "
                DELETE FROM $table_name WHERE user_id = %d
            ", $user_id
    ) );
}

function delete_previous_tokens( $user_id )
{
    global $wpdb;
    $table_name = $wpdb->prefix . "tc_user_tokens";
    $wpdb->query( $wpdb->prepare(
            "
                DELETE FROM $table_name WHERE user_id = %d
            ", $user_id
    ) );
}

//Check and add to cart twitter cart (UPDATED 16.10.2014 NEED TESTING)

function tc_update_user_cart( $products )
{
    global $woocommerce;
    if ( is_object( $woocommerce->cart ) ) {
        if ( !session_id() ) {
            session_start();
        }

        require_once TC_PLUGIN_PATH . "includes/products.php";
        foreach ( $products as $product_id ) {
            //check if product already in cart
            if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) {
                $found = false;
                $cart_total = 30;
                if ( tc_product_is_active( $product_id ) ) {
                    foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
                        $_product = $values['data'];
                        if ( $_product->id == $product_id ) {
                            $found = true;
                        }
                    }
                    // if product not found, add it
                    if ( !$found ) {
                        $_SESSION['tc_products'][$product_id]['id'] = $product_id;
                        $_SESSION['tc_products'][$product_id]['status'] = 'added';

                        $woocommerce->cart->add_to_cart( $product_id );
                    }
                }
            } else {

                // if no products in cart, add it
                $_SESSION['tc_products'][$product_id]['id'] = $product_id;
                $_SESSION['tc_products'][$product_id]['status'] = 'added';

                $woocommerce->cart->add_to_cart( $product_id );
            }
        }
        if ( !empty( $products ) ) {
            $_SESSION['tc_products_uploaded'] = TRUE;
        }
    }
}

function tc_update_user_wishlist( $products )
{
    global $woocommerce;
    if ( class_exists( 'YITH_WCWL' ) ) {
        global $yith_wcwl;
        if ( !session_id() ) {
            session_start();
        }

        require_once TC_PLUGIN_PATH . "includes/products.php";
        foreach ( $products as $product_id ) {
            $prod = wc_get_product( $product_id );
            $yith_wcwl->details['product_id'] = "undefined";
            $yith_wcwl->details['add_to_wishlist'] = $product_id;
            $yith_wcwl->details['product_type'] = $prod->product_type;
            $yith_wcwl->details['action'] = "add_to_wishlist";
            $yith_wcwl->details['user_id'] = get_current_user_id();
            $yith_wcwl->add();
        }
        if ( !empty( $products ) ) {
            $_SESSION['tc_wishlist_uploaded'] = TRUE;
        }
    }
}

function bind_new_account( $user_id, $account )
{
    global $wpdb;
    $tablename = $wpdb->prefix . "tc_accounts";
    $wpdb->insert(
            $tablename, array(
        'user_id' => $user_id,
        'twitter_account' => $account
            ), array(
        '%d',
        '%s'
            )
    );
    @add_user_meta( get_current_user_id(), 'tc_max_timeline', '0', TRUE );
    update_user_meta( get_current_user_id(), 'tc_max_timeline', '1' );
}

function bind_new_tokens( $user_id, $oauth_token, $oauth_secret )
{
    global $wpdb;
    $tablename = $wpdb->prefix . "tc_user_tokens";
    //die(var_dump($tablename));
    $wpdb->insert(
            $tablename, array(
        'user_id' => $user_id,
        'oauth_token' => $oauth_token,
        'oauth_token_secret' => $oauth_secret
            ), array(
        '%d',
        '%s',
        '%s'
            )
    );
}

function save_user_tokens( $oauth_token, $oauth_token_secret )
{
    delete_previous_tokens( get_current_user_id() );
    bind_new_tokens( get_current_user_id(), $oauth_token, $oauth_token_secret );
}

function get_user_hashtag( $user_id )
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'tc_user_hashtags';
    $sql = "SELECT hashtag FROM $table_name WHERE user_id = $user_id";
    $result = $wpdb->get_var( $sql );
    if ( !$result || empty( $result ) ) {
        return FALSE;
    }
    return $result;
}

function get_user_wishlist_hashtag( $user_id )
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'tc_user_hashtags';
    $sql = "SELECT wishlist_hashtag FROM $table_name WHERE user_id = $user_id";
    $result = $wpdb->get_var( $sql );
    if ( !$result || empty( $result ) ) {
        return FALSE;
    }
    return $result;
}

function deactivate_user_twitter_account( $user_id )
{
    global $wpdb;
    $tablename = $wpdb->prefix . 'tc_accounts';
    $wpdb->query( $wpdb->prepare(
                    "
                DELETE FROM $tablename WHERE user_id = %d
            ", $user_id
    ) );
    $tablename = $wpdb->prefix . 'tc_user_tokens';
    $wpdb->query( $wpdb->prepare(
                    "
                DELETE FROM $tablename WHERE user_id = %d
            ", $user_id
    ) );
}
