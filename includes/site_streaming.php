<?php
defined( 'ABSPATH' ) or die( 'Access denied' );

function tc_stream()
{

    if ( tc_need_stream_update() ) {
        global $wpdb;

        $hashtags = array();

        $tablename = $wpdb->prefix . 'tc_user_hashtags';
        $sql = "SELECT hashtag FROM $tablename";
        $result = $wpdb->get_results( $sql );

        foreach ( $result as $item ) {
            $hashtags[] = str_replace( '#', '', $item->hashtag );

            $since_id = tc_get_since_id( $item->hashtag );
            tc_check_hashtag_updates( $item->hashtag, $since_id );
        }
        $hashtags[] = get_option( 'tc_twitter_hashtag' );
    }
}

function tc_need_stream_update()
{
    $lastupdate = get_option( 'tc_update_timestamp' );
    $currenttimestamp = time();
    $diff = $currenttimestamp - $lastupdate;
    if ( $diff > 10 ) {
        update_option( 'tc_update_timestamp', $currenttimestamp );
        return TRUE;
    } else {
        return FALSE;
    }
}

function tc_get_since_id( $hashtag )
{
    global $wpdb;
    $tablename = $wpdb->prefix . 'tc_checked';
    $sql = "";
    $result = $wpdb->get_var( $wpdb->prepare(
                    "
                SELECT status_id FROM $tablename WHERE hashtag = %s
            ", $hashtag
            ) );
    if ( isset( $result['status_id'] ) && !empty( $result['status_id'] ) && $result['status_id'] ) {
        return $result['status_id'];
    }
    return FALSE;
}

function tc_check_hashtag_updates( $hashtag, $since_id )
{
    require_once TC_PLUGIN_PATH . 'includes/twitter.php';
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/search/tweets.json';
    $requestMethod = 'GET';
    if ( $since_id ) {
        $getfield = "?q=" . urlencode( "#" . $hashtag ) . "&since_id=" . $since_id;
    } else {
        $getfield = "?q=" . urlencode( "#" . $hashtag );
    }
    $response = json_decode( $twitter->setGetfield( $getfield )->buildOauth( $url, $requestMethod )->performRequest() );
    //die(var_dump($response));
    if ( !empty( $response->statuses ) ) {
        $ids = array();
        foreach ( $response->statuses as $status ) {

            $twitter_status_id = $status->id_str;
            $in_reply_to_status_id = $status->in_reply_to_status_id_str;

            $ids[] = $twitter_status_id;

            if ( $in_reply_to_status_id != NULL ) {
                $ourUser = tc_get_shop_user( $status->user->screen_name );
                if ( $ourUser != FALSE ) {
                    $product_c_id = tc_check_product_isset( $in_reply_to_status_id );
                    if ( $product_c_id != FALSE ) {
                        if ( tc_product_available( $in_reply_to_status_id ) ) {
                            $status_text = "@" . $status->user->screen_name . " This product was successfully added to your cart! " . rand( 0, 100 );
                            tc_bg_send_reply( $in_reply_to_status_id, $status_text );
                            tc_bg_send_reply_by_email( $ourUser, $product_c_id, 'added' );
                            tc_mail_to_admin( $ourUser, $product_c_id, 'added' );
                            tc_change_stock_count( $product_c_id );
                        } else {
                            $status_text = "@" . $status->user->screen_name . "  This type of product isn`t currently supported. It`s out of stock! " . rand( 0, 100 );
                            tc_bg_send_reply( $in_reply_to_status_id, $status_text );
                            tc_bg_send_reply_by_email( $ourUser, $product_c_id, 'outofstock' );
                            tc_mail_to_admin( $ourUser, $product_c_id, 'outofstock' );
                        }
                    }
                } else {
                    $status_text = "@" . $status->user->screen_name . " This type of product isn`t currently supported. BTW, your account is not enabled for #$hashtag " . rand( 0, 100 );
                    tc_bg_send_reply( $in_reply_to_status_id, $status_text );
                    tc_bg_send_reply_by_email( $ourUser, $product_c_id, 'unauthorized', $hashtag );
                    tc_mail_to_admin( $ourUser, $product_c_id, 'unauthorized', $hashtag );
                }
            }
        }
        tc_bg_set_checked( $hashtag, max( $ids ) + 10 );
    }
}

function tc_check_product_isset( $in_reply_to_status_id )
{
    global $wpdb;
    $tablename = $wpdb->prefix . 'tc_products';
    $result = $wpdb->get_var( $wpdb->prepare(
                    "
                SELECT product_id FROM $tablename WHERE twitter_status_id = %s
            ", $in_reply_to_status_id
            ) );
    if ( !empty( $result ) && $result ) {
        return $result;
    }
    return FALSE;
}

function tc_get_shop_user( $twitter_account )
{
    global $wpdb;
    $tablename = $wpdb->prefix . 'tc_accounts';
    $result = $wpdb->get_var( $wpdb->prepare(
                    "
                SELECT user_id FROM $tablename WHERE twitter_account = %s
            ", $twitter_account
            ) );
    if ( !empty( $result ) && $result ) {
        return $result;
    }
    return FALSE;
}

function tc_product_available( $status_id )
{
    global $wpdb;
    $tablename = $wpdb->prefix . 'tc_products';
    $result = $wpdb->get_var( $wpdb->prepare(
                    "
                SELECT product_id FROM $tablename WHERE twitter_status_id = %s
            ", $status_id
            ) );

    $tablename = $wpdb->prefix . 'postmeta';

    $result = $wpdb->get_var( $wpdb->prepare(
                    "
                SELECT meta_value FROM $tablename WHERE post_id = %d AND meta_key = %s
            ", array(
                $result,
                '_stock_status'
                    )
            ) );

    if ( $result == 'instock' ) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function tc_bg_send_reply( $in_reply_to_status_id, $status )
{
    $twitter = connect_twitter();

    $url = 'https://api.twitter.com/1.1/statuses/update.json';
    $requestMethod = 'POST';
    $postfields = array( 'in_reply_to_status_id' => $in_reply_to_status_id, 'status' => $status );
    $response = json_decode( $twitter->buildOauth( $url, $requestMethod )->setPostfields( $postfields )->performRequest() );
    if ( empty( $response->errors ) ) {
        return true;
    } else {
        return false;
    }
}

function tc_bg_send_reply_by_email( $user_id, $product_id, $operation_status, $hashtag = FALSE )
{
    global $wpdb;
    $tablename = $wpdb->prefix . 'users';
    $email = $wpdb->get_var( $wpdb->prepare(
                    "
                SELECT user_email FROM $tablename WHERE ID = %d
            ", $user_id
            ) );

    if ( $operation_status == 'added' ) {
        $tablename = $wpdb->prefix . 'posts';
        $sql = "";
        $product_name = $wpdb->get_var( $wpdb->prepare(
                        "
                    SELECT post_title FROM $tablename WHERE ID = %d
                ", $product_id
                ) );
        $text = "Product \"$product_name\" was successfully added to your cart!";
        mail( $email, 'Product added to your cart', $text );
    } elseif ( $operation_status == 'unauthorized' ) {
        $text = "This type of product isn`t currently supported. BTW, your account is not enabled for #$hashtag ";
        mail( $email, 'Enable your twitter account', $text );
    } else {
        $text = "This type of product isn`t currently supported. It`s out of stock! ";
        mail( $email, 'Product is out of stock', $text );
    }
}

function tc_mail_to_admin( $user_id, $product_id, $operation_status, $hashtag = FALSE )
{
    global $wpdb;
    if ( $user_id ) {
        $tablename = $wpdb->prefix . 'users';
        $sql = "";
        $login = $wpdb->get_var( $wpdb->prepare(
                        "
                    SELECT user_login FROM $tablename WHERE ID = %d
                ", $user_id
                ) );
    }

    $email = get_option( 'admin_email' );

    if ( $operation_status == 'added' ) {
        $tablename = $wpdb->prefix . 'posts';
        $product_name = $wpdb->get_var( $wpdb->prepare(
                        "
                    SELECT post_title FROM $tablename WHERE ID = %d
                ", $product_id
                ) );
        $text = "User $login add product \"$product_name\" to his cart!";
        mail( $email, 'Product added to cart', $text );
    } elseif ( $operation_status == 'unauthorized' ) {
        $text = "User try to use TwitterCart. But his account is not enabled for #$hashtag ";
        mail( $email, 'Unauthorized using TwitterCart', $text );
    } else {
        $text = "User try to use TwitterCart. But product is out of stock! ";
        mail( $email, 'Product is out of stock', $text );
    }
}

function tc_bg_set_checked( $hashtag, $id )
{
    global $wpdb;
    $tablename = $wpdb->prefix . 'tc_checked';
    $sql = "";
    $wpdb->query( $wpdb->prepare(
                    "
                DELETE FROM $tablename WHERE hashtag = %s
            ", $hashtag
    ) );
    $wpdb->query( $wpdb->prepare(
                    "
                INSERT INTO $tablename (hashtag, status_id) VALUES (%s, %s)
            ", array(
                $hashtag,
                $id
                    )
    ) );
}

function tc_change_stock_count( $product_id )
{
    if ( tc_in_stock_limited( $product_id ) ) {
        global $wpdb;
        $tablename = $wpdb->prefix . 'postmeta';
        $wpdb->query( $wpdb->prepare(
                        "
                    UPDATE $tablename SET meta_value = meta_value + 1 WHERE post_id = %d AND meta_key = %d
                ", array(
                    $product_id,
                    '_stock'
                        )
        ) );
    }
}
