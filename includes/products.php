<?php
defined( 'ABSPATH' ) or die( 'Access denied!' );

/*
 * Product pages
 */

//Products section (admin panel)
function generate_products_page()
{
    //Product was posted, and now available for buying
    if ( $_GET['posted'] ) {
        my_message( 'Product was successfully posted to twitter!' );
    }

    $products = get_all_products();
    $post_url = get_admin_url() . 'admin.php?page=tc_post_product&id=';
    echo require_once TC_TEMPLATES_PATH . 'products_page.php';
    set_datatables();
}

function generate_post_product_page()
{
    //Check product ID
    if ( !isset( $_GET['id'] ) || empty( $_GET['id'] ) ) {
        my_message( 'Invalid product ID!', TRUE );
        exit();
    }

    //Posting product to twitter
    if ( $_POST['submited'] ) {
        require_once TC_PLUGIN_PATH . 'includes/twitter.php';
        if ( $_POST['img_attach'] == 'on' ) {
            $image = get_product_image_url( sanitize_text_field( $_GET['id'] ) );
            $status_text = sanitize_text_field( $_POST['status_text'] );
            post_product_with_media( $status_text, sanitize_text_field( $_GET['id'] ), $image );
        } else {
            $status_text = sanitize_text_field( $_POST['status_text'] );
            post_product_without_media( $status_text, sanitize_text_field( $_GET['id'] ) );
        }
        wp_redirect( get_admin_url() . 'admin.php?page=tc_products&posted=true' );
    } else {
        //Get posting form
        $product_id = ( int ) $_GET['id'];
        $product = tc_get_product( $product_id );
        $default_status_text = get_default_status_text( $product );
        echo require_once TC_TEMPLATES_PATH . 'post_product.php';
    }
}

//Get Product For Posting
function get_pfp( $product_id )
{
    $pfp['product'] = tc_get_product( $product_id );
    $pfp['default_status_text'] = get_default_status_text( $pfp['product'] );
    return $pfp;
}

/*
 * Product fucntions
 */

//Get all products (post with type 'product')
function get_all_products()
{
    $products = array();
    if ( tc_is_admin() ) {
        $args = array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => '-1', 'orderby' => 'date' );
        $posts = get_posts( $args );
        foreach ( $posts as $product ) {
            $productObj = new WC_Product( $product->ID );
            $products[$product->ID]['id'] = $product->ID;
            $products[$product->ID]['title'] = $product->post_title;
            $products[$product->ID]['permalink'] = get_permalink( $product->ID );
            $products[$product->ID]['image'] = get_product_image( $product->ID );
            $products[$product->ID]['price'] = tc_format_woo_price( $productObj->price );
            $products[$product->ID]['date'] = $product->post_date;
            $products[$product->ID]['category'] = get_category_str( $product->ID );
            $products[$product->ID]['in_stock'] = get_stock_count( $product->ID );
            $products[$product->ID]['twitter_status_id'] = get_twitter_status_id( $product->ID );
            $products[$product->ID]['twitter_status_text'] = get_twitter_status_text( $product->ID );
            $products[$product->ID]['twitter_status_link'] = get_twitter_status_link( $product->ID );
        }
    } else {
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'author' => get_current_user_id()
        );

        $posts = get_posts( $args );
        foreach ( $posts as $product ) {
            $productObj = new WC_Product( $product->ID );
            $products[$product->ID]['id'] = $product->ID;
            $products[$product->ID]['title'] = $product->post_title;
            $products[$product->ID]['permalink'] = get_permalink( $product->ID );
            $products[$product->ID]['image'] = get_product_image( $product->ID );
            $products[$product->ID]['price'] = tc_format_woo_price( $productObj->price );
            $products[$product->ID]['date'] = $product->post_date;
            $products[$product->ID]['in_stock'] = get_stock_count( $product->ID );
            $products[$product->ID]['category'] = get_category_str( $product->ID );
            $products[$product->ID]['twitter_status_id'] = get_twitter_status_id( $product->ID );
            $products[$product->ID]['twitter_status_text'] = get_twitter_status_text( $product->ID );
            $products[$product->ID]['twitter_status_link'] = get_twitter_status_link( $product->ID );
        }
    }
    return $products;
}

function tc_format_woo_price( $price )
{
    $decimals = get_option( 'woocommerce_price_num_decimals' );
    $currency = get_woocommerce_currency_symbol();
    $position = get_option( 'woocommerce_currency_pos' );

    $price = number_format( $price, $decimals );

    if ( $position == 'left' ) {
        return $currency . $price;
    } elseif ( $position == 'left_space' ) {
        return $currency . "&nbsp;" . $price;
    } elseif ( $position == 'right' ) {
        return $price . $currency;
    } else {
        return $price . '&nbsp;' . $currency;
    }
}

//Get product data array
function tc_get_product( $product_id )
{
    $post = get_post( $product_id );
    $productObj = new WC_Product( $post->ID );

    $return = array();
    $return['id'] = $post->ID;
    $return['title'] = $post->post_title;
    $return['permalink'] = get_permalink( $post->ID );
    $return['image'] = get_product_image( $post->ID );
    $return['img_url'] = get_product_image_url( $post->ID );
    $return['price'] = $productObj->price;
    $return['date'] = $post->post_date;
    $return['twitter_status_id'] = get_twitter_status_id( $post->ID );
    $return['twitter_status_text'] = get_twitter_status_text( $post->ID );

    return $return;
}

//Return post (product) image
function get_product_image( $product_id, $width = 40, $height = 40 )
{
    return get_the_post_thumbnail( $product_id, array( $width, $height ) );
}

//Return url of product image
function get_product_image_url( $product_id )
{
    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ) );
    return $image[0];
}

//Return ID of twitter post to this product
function get_twitter_status_id( $product_id )
{
    global $wpdb;
    $tablename = $wpdb->prefix . "tc_products";
    $result = $wpdb->get_var( $wpdb->prepare(
                    "
                SELECT twitter_status_id FROM $tablename WHERE product_id = %d
            ", $product_id
            ) );
    if ( empty( $result ) ) {
        $result = FALSE;
    }
    return $result;
}

//Check twitter order product
function tc_product_is_active( $product_id )
{
    global $wpdb;
    $status_id = get_twitter_status_id( $product_id );
    $table_name = $wpdb->prefix . "tc_twitter_orders";
    $sql = "";
    $result = $wpdb->get_var( $wpdb->prepare(
                    "
                SELECT status FROM $table_name WHERE in_reply_to_status_id = %s
            ", $status_id
            ) );
    if ( $result == 'active' ) {
        return TRUE;
    } else {
        return FALSE;
    }
}

//Return text of twitter post to this product
function get_twitter_status_text( $product_id )
{
    global $wpdb;
    $tablename = $wpdb->prefix . "tc_products";
    $result = $wpdb->get_var( $wpdb->prepare(
                    "
                SELECT twitter_status_text FROM $tablename WHERE product_id = %d
            ", $product_id
            ) );
    if ( empty( $result ) ) {
        $result = 'Not posted';
    }
    return $result;
}

//Return link to the twitter post to this product
function get_twitter_status_link( $product_id )
{
    global $wpdb;
    $tablename = $wpdb->prefix . "tc_products";
    $result = $wpdb->get_var( $wpdb->prepare(
                    "
                SELECT twitter_status_link FROM $tablename WHERE product_id = %d
            ", $product_id
            ) );
    if ( empty( $result ) ) {
        $result = FALSE;
    }
    return $result;
}

//Return default text for twitter post
function get_default_status_text( $product )
{
    $text = $product['title'];
    $text .= " - " . get_woocommerce_currency_symbol() . $product['price'] . PHP_EOL;
    $text = substr( $text, 0, 98 );
    $text .= $product['permalink'];
    return $text;
}

//Check posted products
function posted_already( $product_id )
{
    global $wpdb;
    $tablename = $wpdb->prefix . "tc_products";
    $sql = "";
    $result = $wpdb->get_var( $wpdb->prepare(
                    "
                SELECT twitter_status_id FROM $tablename WHERE product_id = %d
            ", $product_id
            ) );
    if ( !empty( $result ) ) {
        return TRUE;
    }
    return FALSE;
}

function get_stock_count( $product_id )
{
    global $wpdb;
    $tablename = $wpdb->prefix . "postmeta";
    $result = $wpdb->get_var( $wpdb->prepare(
                    "
                SELECT meta_value FROM $tablename WHERE meta_key = '_stock' AND post_id = %d
            ", $product_id
            ) );
    return $result;
}

//Check Twitter priduct remove
function tc_cart_update()
{

    if ( !session_id() ) {
        session_start();
    }

    global $woocommerce;
    $cart = $woocommerce->cart->get_cart();
    if ( isset( $_SESSION['tc_products'] ) && !empty( $_SESSION['tc_products'] ) ) {
        foreach ( $_SESSION['tc_products'] as $tc_product ) {
            if ( $tc_product['status'] == 'added' ) {
                $found = FALSE;
                foreach ( $cart as $wooItem ) {
                    if ( $wooItem['product_id'] == $tc_product['id'] ) {
                        $found = TRUE;
                    }
                }
                if ( !$found ) {
                    $_SESSION['tc_products'][$tc_product['id']]['status'] = 'removed';
                    tc_set_removed( $tc_product['id'] );
                }
            }
        }
    }
}

function tc_set_removed( $product_id )
{
    global $wpdb;
    require_once TC_PLUGIN_PATH . "includes/user.php";
    $status_id = get_twitter_status_id( $product_id );
    $user_account = get_user_twitter_account( get_current_user_id() );
    $table_name = $wpdb->prefix . "tc_twitter_orders";
    $sql = "";
    //die(var_dump($sql));
    @$wpdb->query( $wpdb->prepare(
                            "
                UPDATE $table_name SET status = %s WHERE in_reply_to_status_id = %s AND user = %s
            ", array(
                        'removed',
                        $status_id,
                        $user_account
                            )
            ) );
}

//Get reply qty by products
function tc_get_most_replied()
{
    $return = array();
    global $wpdb;
    $table_name = $wpdb->prefix . "tc_products";
    $sql = "SELECT * FROM $table_name";
    $result = $wpdb->get_results( $sql );
    $total_replies = 0;
    if ( !empty( $result ) ) {
        require_once TC_PLUGIN_PATH . 'includes/twitter.php';
        foreach ( $result as $productObj ) {
            $currentProduct = tc_get_product( $productObj->product_id );
            $replies_count = tc_get_replies_count( $productObj->twitter_status_id );
            $currentProduct['replies_count'] = $replies_count;
            $total_replies += $replies_count;
            $return[] = $currentProduct;
        }
    }
    usort( $return, 'replies_compare' );
    $data['products'] = $return;
    $data['total_replies'] = $total_replies;
    return $data;
}

function get_most_replied_single()
{
    $all = tc_get_most_replied();
    $return['product'] = $all['products'][0]['title'];
    $return['count'] = $all['products'][0]['replies_count'];
    return $return;
}

function get_most_retweeted_single()
{
    $all = tc_get_most_retweeted();
    $return['product'] = $all[0]['title'];
    $return['count'] = $all[0]['retweets_count'];
    return $return;
}

function replies_compare( $a, $b )
{
    return strcmp( $b['replies_count'], $a['replies_count'] );
}

//Get retweet qty by products
function tc_get_most_retweeted()
{
    $return = array();
    global $wpdb;
    $table_name = $wpdb->prefix . "tc_products";
    $sql = "SELECT * FROM $table_name";
    $result = $wpdb->get_results( $sql );
    if ( !empty( $result ) ) {
        require_once TC_PLUGIN_PATH . 'includes/twitter.php';
        foreach ( $result as $productObj ) {
            $currentProduct = tc_get_product( $productObj->product_id );
            $currentProduct['retweets_count'] = tc_get_retweets_count( $productObj->twitter_status_id );
            $return[] = $currentProduct;
        }
    }
    usort( $return, 'retweets_compare' );
    return $return;
}

function get_category_str( $id )
{
    $return = "";
    $terms = get_the_terms( $id, 'product_cat' );
    if ( $terms ) {
        foreach ( $terms as $term ) {
            $return .= $term->name . ", ";
        }
        $return = substr( $return, 0, -2 );
    }
    return $return;
}

function retweets_compare( $a, $b )
{
    return strcmp( $b['retweets_count'], $a['retweets_count'] );
}

//Total posted products count
function get_total_posted_products()
{
    global $wpdb;
    $tablename = $wpdb->prefix . 'tc_products';
    $sql = "SELECT COUNT(*) FROM $tablename";
    $result = $wpdb->get_var( $sql );
    return $result;
}

function get_latest_posted_products( $limit = 4 )
{
    global $wpdb;
    $return = array();

    $table_name = $wpdb->prefix . "tc_products";
    $sql = "SELECT * FROM $table_name ORDER BY tc_product_id DESC LIMIT " . $limit;
    $result = $wpdb->get_results( $sql );
    if ( !empty( $result ) ) {
        foreach ( $result as $productObj ) {
            $currentProduct = tc_get_product( $productObj->product_id );
            $return[$currentProduct['id']]['img'] = get_product_image_url( $currentProduct['id'] );
            $return[$currentProduct['id']]['link'] = $currentProduct['permalink'];
            $return[$currentProduct['id']]['title'] = $currentProduct['title'];
            $return[$currentProduct['id']]['price'] = tc_format_woo_price( $currentProduct['price'] );
        }
    }
    return $return;
}

function get_new_not_posted_products()
{
    $posted = get_latest_posted_products( 1000 );

    $products = array();
    if ( tc_is_admin() ) {
        $args = array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => '-1', 'orderby' => 'date' );
        $posts = get_posts( $args );
        foreach ( $posts as $product ) {
            $productObj = new WC_Product( $product->ID );
            $products[$product->ID]['id'] = $product->ID;
            $products[$product->ID]['title'] = $product->post_title;
            $products[$product->ID]['permalink'] = get_permalink( $product->ID );
            $products[$product->ID]['image'] = get_product_image_url( $product->ID );
            $products[$product->ID]['price'] = tc_format_woo_price( $productObj->price );
        }
    } else {
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'author' => get_current_user_id()
        );

        $posts = get_posts( $args );
        foreach ( $posts as $product ) {
            $productObj = new WC_Product( $product->ID );
            $products[$product->ID]['id'] = $product->ID;
            $products[$product->ID]['title'] = $product->post_title;
            $products[$product->ID]['permalink'] = get_permalink( $product->ID );
            $products[$product->ID]['image'] = get_product_image_url( $product->ID );
            $products[$product->ID]['price'] = tc_format_woo_price( $productObj->price );
        }
    }

    $counter = 0;
    $return = array();
    foreach ( $products as $id => $product ) {
        if ( $counter >= 4 ) {
            break;
        }
        if ( !isset( $posted[$id] ) ) {
            $return[$id] = $product;
            $counter++;
        }
    }
    return $return;
}

function post_product_from_account( $data )
{
    require_once TC_PLUGIN_PATH . 'includes/twitter.php';
    if ( $data['attach'] == 'on' ) {
        $result = post_product_with_media_account( $data['message'], $data['product'], get_product_image_url( $data['product'] ) );
    } else {
        $result = post_product_without_media_account( $status_text, $product_id );
    }
    die( json_encode( $result ) );
}

/*function tc_check_user_cart()
{
    global $woocommerce;
    $cart = $woocommerce->cart->get_cart();
    
    $cart_product_ids = array();
    foreach ($cart as $cart_product){
        $cart_product_ids[$cart_product['product_id']] = $cart_product['product_id'];
    }
    foreach ($_SESSION['products'] as $tc_product){
        if(!isset($cart_product_ids[$tc_product])){
            set_removed_status($tc_product);
        }
    }
}*/
