<?php
defined( 'ABSPATH' ) or die( 'Access denied!' );

//Return Twitter API object
function connect_twitter( $token = FALSE, $secret = FALSE )
{
    require_once TC_LIBS_PATH . 'twitter-api/TwitterAPIExchange.php';
    require_once TC_PLUGIN_PATH . 'includes/user.php';
    if ( $token ) {
        $settings = array(
            'oauth_access_token' => $token,
            'oauth_access_token_secret' => $secret,
            'consumer_key' => get_option( 'tc_twitter_api_key' ),
            'consumer_secret' => get_option( 'tc_twitter_api_secret' )
        );
    } else {
        $token = get_user_token();
        $secret = get_user_secret();
        if ( !empty( $token ) && $token ) {
            $settings = array(
                'oauth_access_token' => $token, //get_option('tc_twitter_access_token'),
                'oauth_access_token_secret' => $secret, //get_option('tc_twitter_access_token_secret'),
                'consumer_key' => get_option( 'tc_twitter_api_key' ),
                'consumer_secret' => get_option( 'tc_twitter_api_secret' )
            );
        } else {
            $settings = array(
                'oauth_access_token' => get_option( 'tc_twitter_access_token' ),
                'oauth_access_token_secret' => get_option( 'tc_twitter_access_token_secret' ),
                'consumer_key' => get_option( 'tc_twitter_api_key' ),
                'consumer_secret' => get_option( 'tc_twitter_api_secret' )
            );
        }
    }
    return new TwitterAPIExchange( $settings );
}

//Product post without attaches
function post_product_without_media( $status_text, $product_id )
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/statuses/update.json';
    $requestMethod = 'POST';
    $postfields = array( 'status' => $status_text );
    $response = json_decode( $twitter->buildOauth( $url, $requestMethod )->setPostfields( $postfields )->performRequest() );
    save_twitter_response( $response, $product_id );
}

//Product post with attaches
function post_product_with_media( $status_text, $product_id, $image )
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/statuses/update_with_media.json';
    $requestMethod = 'POST';
    $postfields = array( 'media[]' => file_get_contents( $image ), 'status' => $status_text );
    $response = json_decode( $twitter->buildOauth( $url, $requestMethod )->setPostfields( $postfields )->performRequest() );
    save_twitter_response( $response, $product_id );
}

function post_product_with_media_account( $status_text, $product_id, $image )
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/statuses/update_with_media.json';
    $requestMethod = 'POST';
    $postfields = array( 'media[]' => file_get_contents( $image ), 'status' => $status_text );
    $response = json_decode( $twitter->buildOauth( $url, $requestMethod )->setPostfields( $postfields )->performRequest() );
    save_twitter_response( $response, $product_id );
    if ( !isset( $response->errors ) || empty( $response->errors ) ) {
        return "The product was successfully posted to Twitter!";
    } else {
        return "Some error. Try again later";
    }
}

function post_product_without_media_account( $status_text, $product_id )
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/statuses/update.json';
    $requestMethod = 'POST';
    $postfields = array( 'status' => $status_text );
    $response = json_decode( $twitter->buildOauth( $url, $requestMethod )->setPostfields( $postfields )->performRequest() );
    save_twitter_response( $response, $product_id );
    if ( !isset( $response->errors ) || empty( $response->errors ) ) {
        return "The product was successfully posted to Twitter!";
    } else {
        return "Some error. Try again later";
    }
}

//Load new twitter orders (search by hashtag using Twitter API)
function load_new_twitter_cart_products()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "tc_twitter_orders";


    $allHashtags = get_add_hashtags();

    //Get users twitter account
    require_once TC_PLUGIN_PATH . 'includes/user.php';
    $user_account = tc_get_user_twitter_account( get_current_user_id() );

    $products = array();

    foreach ( $allHashtags as $add_to_cart_hashtag ) {
        //Search tweets by user and #hashtag
        $twitter = connect_twitter();
        $url = 'https://api.twitter.com/1.1/search/tweets.json';
        $requestMethod = 'GET';
        $getfield = "?q=" . urlencode( "from:" . $user_account . "#" . $add_to_cart_hashtag );
        $response = json_decode( $twitter->setGetfield( $getfield )->buildOauth( $url, $requestMethod )->performRequest() );

        if ( isset( $response->statuses ) && $response->statuses ) {
            foreach ( $response->statuses as $status ) {
                $twitter_status_id = $status->id_str;
                $in_reply_to_status_id = $status->in_reply_to_status_id_str;
                $user = $status->user->screen_name;

                $wpdb->query( $wpdb->prepare(
                                "
                            INSERT INTO $table_name (twitter_status_id, in_reply_to_status_id, user, status) VALUES (%s, %s, %s, %s) ON DUPLICATE KEY UPDATE twitter_status_id = twitter_status_id
                        ", array(
                            $twitter_status_id,
                            $in_reply_to_status_id,
                            $user,
                            'active'
                                )
                ) );
                $products[] = get_product_id_by_status( $in_reply_to_status_id );
            }
        }
    }
    return $products;
}

//Load new twitter wishlist (search by hashtag using Twitter API)
function load_new_wishlist_products()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "tc_twitter_wishlist";


    $allHashtags = get_wl_hashtags();

    //Get users twitter account
    require_once TC_PLUGIN_PATH . 'includes/user.php';
    $user_account = tc_get_user_twitter_account( get_current_user_id() );

    $products = array();

    foreach ( $allHashtags as $wishlist_hashtag ) {
        //Search tweets by user and #hashtag
        $twitter = connect_twitter();
        $url = 'https://api.twitter.com/1.1/search/tweets.json';
        $requestMethod = 'GET';
        $getfield = "?q=" . urlencode( "from:" . $user_account . "#" . $wishlist_hashtag );
        $response = json_decode( $twitter->setGetfield( $getfield )->buildOauth( $url, $requestMethod )->performRequest() );

        if ( isset( $response->statuses ) && $response->statuses ) {
            foreach ( $response->statuses as $status ) {
                if ( !tc_added_to_wishlist( $status->id_str ) ) {
                    $twitter_status_id = $status->id_str;
                    $in_reply_to_status_id = $status->in_reply_to_status_id_str;
                    $user = $status->user->screen_name;
                    $stat = 'active';
                    $wpdb->query( $wpdb->prepare(
                                    "
                                INSERT INTO $table_name (twitter_status_id, in_reply_to_status_id, user, status) VALUES (%s, %s, %s, %s) ON DUPLICATE KEY UPDATE twitter_status_id = twitter_status_id
                            ", array(
                                $twitter_status_id, $in_reply_to_status_id, $user, $stat
                                    )
                    ) );
                    $products[] = get_product_id_by_status( $in_reply_to_status_id );
                }
            }
        }
    }
    return $products;
}

function tc_unfollow_user( $screen_name )
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/friendships/destroy.json';
    $requestMethod = 'POST';
    $postfields = array( 'screen_name' => $screen_name );
    $response = json_decode( $twitter->buildOauth( $url, $requestMethod )->setPostfields( $postfields )->performRequest() );
}

function tc_follow_user( $screen_name )
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/friendships/create.json';
    $requestMethod = 'POST';
    $postfields = array( 'screen_name' => $screen_name );
    $response = json_decode( $twitter->buildOauth( $url, $requestMethod )->setPostfields( $postfields )->performRequest() );
}

//Return Woocommerce product ID (Post ID)
function get_product_id_by_status( $twitter_status_id )
{
    global $wpdb;
    $table_name = $wpdb->prefix . "tc_products";
    return $wpdb->get_var( $wpdb->prepare(
                            "SELECT product_id FROM $table_name WHERE twitter_status_id = %s", $twitter_status_id
                    )
    );
}

//Return home timeline tweets
function get_home_timeline_tweets( $count )
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/statuses/home_timeline.json';
    $requestMethod = 'GET';
    $getfield = "?count=$count";
    $response = json_decode( $twitter->setGetfield( $getfield )->buildOauth( $url, $requestMethod )->performRequest() );
    $return = array();
    if ( $response ) {
        foreach ( $response as $tweet ) {
            $return[$tweet->id_str]['id'] = $tweet->id_str;
            $return[$tweet->id_str]['created_at'] = $tweet->created_at;
            $return[$tweet->id_str]['text'] = $tweet->text;
            $return[$tweet->id_str]['user_id'] = $tweet->user->id_str;
            $return[$tweet->id_str]['user_name'] = $tweet->user->name;
            $return[$tweet->id_str]['user_screen_name'] = $tweet->user->screen_name;
            $return[$tweet->id_str]['user_avatar'] = $tweet->user->profile_image_url;

            //Retweet and favorite count
            $return[$tweet->id_str]['retweet_count'] = $tweet->retweet_count;
            $return[$tweet->id_str]['favorite_count'] = $tweet->favorite_count;
        }
        @add_user_meta( get_current_user_id(), 'tc_max_timeline', max( array_keys( $return ) ), true );
        update_user_meta( get_current_user_id(), 'tc_max_timeline', max( array_keys( $return ) ) );
    }
    return $return;
}

function get_profile_timeline_tweets( $count )
{
    require_once TC_PLUGIN_PATH . 'includes/user.php';
    $username = get_user_twitter_account( get_current_user_id() );
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    $requestMethod = 'GET';
    $getfield = "?screen_name=$username&count=$count";
    $response = json_decode( $twitter->setGetfield( $getfield )->buildOauth( $url, $requestMethod )->performRequest() );
    $return = array();
    if ( $response ) {
        foreach ( $response as $tweet ) {
            $return[$tweet->id_str]['id'] = $tweet->id_str;
            $return[$tweet->id_str]['created_at'] = $tweet->created_at;
            $return[$tweet->id_str]['text'] = $tweet->text;
            $return[$tweet->id_str]['user_id'] = $tweet->user->id_str;
            $return[$tweet->id_str]['user_name'] = $tweet->user->name;
            $return[$tweet->id_str]['user_screen_name'] = $tweet->user->screen_name;
            $return[$tweet->id_str]['user_avatar'] = $tweet->user->profile_image_url;

            //Retweet and favorite count
            $return[$tweet->id_str]['retweet_count'] = $tweet->retweet_count;
            $return[$tweet->id_str]['favorite_count'] = $tweet->favorite_count;
        }
        @add_user_meta( get_current_user_id(), 'tc_max_timeline', max( array_keys( $return ) ), true );
        update_user_meta( get_current_user_id(), 'tc_max_timeline', max( array_keys( $return ) ) );
    }
    return $return;
}

function tc_get_utl( $screen_name )
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    $requestMethod = 'GET';
    $getfield = "?screen_name=$screen_name&count=200";
    $response = json_decode( $twitter->setGetfield( $getfield )->buildOauth( $url, $requestMethod )->performRequest() );
    $return = array();
    if ( $response ) {
        foreach ( $response as $tweet ) {
            $return[$tweet->id_str]['id'] = $tweet->id_str;
            $return[$tweet->id_str]['created_at'] = $tweet->created_at;
            $return[$tweet->id_str]['text'] = $tweet->text;
            $return[$tweet->id_str]['user_id'] = $tweet->user->id_str;
            $return[$tweet->id_str]['user_name'] = $tweet->user->name;
            $return[$tweet->id_str]['user_screen_name'] = $tweet->user->screen_name;
            $return[$tweet->id_str]['user_avatar'] = $tweet->user->profile_image_url;

            //Retweet and favorite count
            $return[$tweet->id_str]['retweet_count'] = $tweet->retweet_count;
            $return[$tweet->id_str]['favorite_count'] = $tweet->favorite_count;
        }
    }
    return $return;
}

//Get twitter timeline update
function get_timeline_update_tweets()
{
    $since_id = get_user_meta( get_current_user_id(), 'tc_max_timeline', true );
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/statuses/home_timeline.json';
    $requestMethod = 'GET';
    $getfield = "?since_id=$since_id&count=100";
    $response = json_decode( $twitter->setGetfield( $getfield )->buildOauth( $url, $requestMethod )->performRequest() );
    $return = array();
    if ( $response ) {
        foreach ( $response as $tweet ) {
            if ( !isset( $return[$tweet->id_str] ) ) {
                if ( !showed_earlier( get_current_user_id(), $return[$tweet->id_str] ) ) {
                    $return[$tweet->id_str]['id'] = $tweet->id_str;
                    $return[$tweet->id_str]['created_at'] = $tweet->created_at;
                    $return[$tweet->id_str]['text'] = $tweet->text;
                    $return[$tweet->id_str]['user_id'] = $tweet->user->id_str;
                    $return[$tweet->id_str]['user_name'] = $tweet->user->name;
                    $return[$tweet->id_str]['user_screen_name'] = $tweet->user->screen_name;
                    $return[$tweet->id_str]['user_avatar'] = $tweet->user->profile_image_url;

                    //Retweet and favorite count
                    $return[$tweet->id_str]['retweet_count'] = $tweet->retweet_count;
                    $return[$tweet->id_str]['favorite_count'] = $tweet->favorite_count;
                }
            }
        }
        update_user_meta( get_current_user_id(), 'tc_max_tmieline', max( array_keys( $return ) ) );
    }
    return $return;
}

//Get twitter timeline update
function get_timeline_earlier_tweets( $max )
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/statuses/home_timeline.json';
    $requestMethod = 'GET';
    $getfield = "?max_id=$max&count=15";
    $response = json_decode( $twitter->setGetfield( $getfield )->buildOauth( $url, $requestMethod )->performRequest() );
    $return = array();
    if ( $response ) {
        foreach ( $response as $tweet ) {
            if ( !isset( $return[$tweet->id_str] ) ) {
                if ( $tweet->id_str != $max ) {
                    $return[$tweet->id_str]['id'] = $tweet->id_str;
                    $return[$tweet->id_str]['created_at'] = $tweet->created_at;
                    $return[$tweet->id_str]['text'] = $tweet->text;
                    $return[$tweet->id_str]['user_id'] = $tweet->user->id_str;
                    $return[$tweet->id_str]['user_name'] = $tweet->user->name;
                    $return[$tweet->id_str]['user_screen_name'] = $tweet->user->screen_name;
                    $return[$tweet->id_str]['user_avatar'] = $tweet->user->profile_image_url;

                    //Retweet and favorite count
                    $return[$tweet->id_str]['retweet_count'] = $tweet->retweet_count;
                    $return[$tweet->id_str]['favorite_count'] = $tweet->favorite_count;
                }
            }
        }
    }
    return $return;
}

//Get user timeline update
function get_user_earlier_tweets( $max )
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    $requestMethod = 'GET';
    $getfield = "?max_id=$max&count=50&screen_name=" . tc_get_user_twitter_account( get_current_user_id() );
    $response = json_decode( $twitter->setGetfield( $getfield )->buildOauth( $url, $requestMethod )->performRequest() );
    $return = array();
    if ( $response ) {
        foreach ( $response as $tweet ) {
            if ( !isset( $return[$tweet->id_str] ) ) {
                if ( $tweet->id_str != $max ) {
                    $return[$tweet->id_str]['id'] = $tweet->id_str;
                    $return[$tweet->id_str]['created_at'] = $tweet->created_at;
                    $return[$tweet->id_str]['text'] = $tweet->text;
                    $return[$tweet->id_str]['user_id'] = $tweet->user->id_str;
                    $return[$tweet->id_str]['user_name'] = $tweet->user->name;
                    $return[$tweet->id_str]['user_screen_name'] = $tweet->user->screen_name;
                    $return[$tweet->id_str]['user_avatar'] = $tweet->user->profile_image_url;

                    //Retweet and favorite count
                    $return[$tweet->id_str]['retweet_count'] = $tweet->retweet_count;
                    $return[$tweet->id_str]['favorite_count'] = $tweet->favorite_count;
                }
            }
        }
    }
    return $return;
}

function simple_tweet( $message )
{
    if ( empty( $_FILES ) ) {
        $twitter = connect_twitter();
        $url = 'https://api.twitter.com/1.1/statuses/update.json';
        $requestMethod = 'POST';
        $postfields = array( 'status' => $message );
        $response = json_decode( $twitter->buildOauth( $url, $requestMethod )->setPostfields( $postfields )->performRequest() );
    } else {

        $path = TC_PLUGIN_PATH . $_FILES['file']['name'];
        move_uploaded_file( $_FILES['file']['tmp_name'], $path );

        $twitter = connect_twitter();
        $url = 'https://api.twitter.com/1.1/statuses/update_with_media.json';
        $requestMethod = 'POST';
        $postfields = array( 'media[]' => file_get_contents( $path ), 'status' => $message );
        $response = json_decode( $twitter->buildOauth( $url, $requestMethod )->setPostfields( $postfields )->performRequest() );
        unlink( $path );
    }

    if ( empty( $response->errors ) ) {
        die( json_encode( TRUE ) );
    } else {
        die( json_encode( $response->errors[0]->message ) );
    }
}

//Get replies of user
function get_reply_tweets( $count = FALSE )
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/search/tweets.json';
    $requestMethod = 'GET';

    //Get users twitter account
    require_once TC_PLUGIN_PATH . 'includes/user.php';
    $user_account = tc_get_user_twitter_account( get_current_user_id() );
    if ( !$user_account ) {
        $user_account = get_username_via_api( get_current_user_id() );
    }

    if ( $count ) {
        $getfield = "?q=" . urlencode( "@" . $user_account ) . "&count=" . $count;
    } else {
        $getfield = "?q=" . urlencode( "@" . $user_account ) . "&count=100";
    }
    $response = json_decode( $twitter->setGetfield( $getfield )->buildOauth( $url, $requestMethod )->performRequest() );
    $return = array();
    if ( $response ) {
        if ( isset( $response->statuses ) && !empty( $response->statuses ) ) {
            foreach ( $response->statuses as $tweet ) {
                $return[$tweet->id_str]['id'] = $tweet->id_str;
                $return[$tweet->id_str]['created_at'] = $tweet->created_at;
                $return[$tweet->id_str]['text'] = $tweet->text;
                $return[$tweet->id_str]['user_id'] = $tweet->user->id_str;
                $return[$tweet->id_str]['user_name'] = $tweet->user->name;
                $return[$tweet->id_str]['user_screen_name'] = $tweet->user->screen_name;
                $return[$tweet->id_str]['user_avatar'] = $tweet->user->profile_image_url;

                //Retweet and favorite count
                $return[$tweet->id_str]['retweet_count'] = $tweet->retweet_count;
                $return[$tweet->id_str]['favorite_count'] = $tweet->favorite_count;
            }
            if ( !session_id() ) {
                session_start();
                $_SESSION['tc_max_reply_id'] = max( array_keys( $return ) );
            }
        } else {
            $return = array();
        }
    }
    return $return;
}

//Get retweets of user
function get_retweet_tweets( $countArg = FALSE )
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/statuses/retweets_of_me.json';
    $requestMethod = 'GET';

    if ( $countArg ) {
        $getfield = "?count=" . $countArg;
    } else {
        $getfield = "?count=100";
    }

    $response = json_decode( $twitter->setGetfield( $getfield )->buildOauth( $url, $requestMethod )->performRequest() );

    $return = array();
    if ( $response ) {
        foreach ( $response as $tweet ) {
            $return[$tweet->id_str]['id'] = $tweet->id_str;
            $return[$tweet->id_str]['created_at'] = $tweet->created_at;
            $return[$tweet->id_str]['text'] = $tweet->text;
            $return[$tweet->id_str]['user_id'] = $tweet->user->id_str;
            $return[$tweet->id_str]['user_name'] = $tweet->user->name;
            $return[$tweet->id_str]['user_screen_name'] = $tweet->user->screen_name;
            $return[$tweet->id_str]['user_avatar'] = $tweet->user->profile_image_url;

            //Retweet and favorite count
            $return[$tweet->id_str]['retweet_count'] = $tweet->retweet_count;
            $return[$tweet->id_str]['favorite_count'] = $tweet->favorite_count;
        }
        if ( !session_id() ) {
            session_start();
            $_SESSION['tc_max_retweet_id'] = max( array_keys( $return ) );
        }
    }
    return $return;
}

//Get followers of user
function tc_get_followers( $cursor = FALSE )
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/followers/list.json';
    $requestMethod = 'GET';

    if ( $cursor ) {
        $getfield = "?count=21&cursor=" . $cursor;
    } else {
        $getfield = "?count=21";
    }

    $response = json_decode( $twitter->setGetfield( $getfield )->buildOauth( $url, $requestMethod )->performRequest() );
    $return = array();
    if ( $response ) {
        foreach ( $response->users as $follower ) {
            $return['tweets'][$follower->id_str]['id'] = $follower->id_str;
            $return['tweets'][$follower->id_str]['name'] = $follower->name;
            $return['tweets'][$follower->id_str]['screen_name'] = $follower->screen_name;
            $return['tweets'][$follower->id_str]['description'] = $follower->description;
            $return['tweets'][$follower->id_str]['avatar'] = $follower->profile_image_url;
            $return['tweets'][$follower->id_str]['background'] = $follower->profile_banner_url . '/600x200';
            $return['tweets'][$follower->id_str]['bgcolor'] = $follower->profile_link_color;
            $return['tweets'][$follower->id_str]['followers_count'] = $follower->followers_count;
            $return['tweets'][$follower->id_str]['friends_count'] = $follower->friends_count;
            $return['tweets'][$follower->id_str]['following'] = is_following( get_user_twitter_account( get_current_user_id() ), $follower->screen_name );
        }
        $return['cursor'] = $response->next_cursor_str;
    }
    return $return;
}

function is_following( $current_user, $follower )
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/friendships/show.json';
    $requestMethod = 'GET';

    $getfield = "?source_screen_name=$current_user&target_screen_name=" . $follower;

    $response = json_decode( $twitter->setGetfield( $getfield )->buildOauth( $url, $requestMethod )->performRequest() );
    return $response->relationship->source->following;
}

//Get following
function tc_get_following( $cursor = FALSE )
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/friends/list.json';
    $requestMethod = 'GET';
    if ( $cursor ) {
        $getfield = "?count=21&cursor=" . $cursor;
    } else {
        $getfield = "?count=21";
    }
    $response = json_decode( $twitter->setGetfield( $getfield )->buildOauth( $url, $requestMethod )->performRequest() );
    $return = array();
    if ( $response ) {
        foreach ( $response->users as $following ) {
            $return['tweets'][$following->id_str]['id'] = $following->id_str;
            $return['tweets'][$following->id_str]['name'] = $following->name;
            $return['tweets'][$following->id_str]['screen_name'] = $following->screen_name;
            $return['tweets'][$following->id_str]['description'] = $following->description;
            $return['tweets'][$following->id_str]['avatar'] = $following->profile_image_url;
            $return['tweets'][$following->id_str]['background'] = $following->profile_banner_url . '/600x200';
            $return['tweets'][$following->id_str]['bgcolor'] = $following->profile_link_color;
            $return['tweets'][$following->id_str]['followers_count'] = $following->followers_count;
            $return['tweets'][$following->id_str]['friends_count'] = $following->friends_count;
        }
        $return['cursor'] = $response->next_cursor_str;
    }
    return $return;
}

//Get availbale dialogs
function tc_get_dialogs()
{
    //Result array
    $dialogs = array();

    //Inbox part
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/direct_messages.json';
    $requestMethod = 'GET';
    $response = json_decode( $twitter->buildOauth( $url, $requestMethod )->performRequest() );

    foreach ( $response as $message ) {
        if ( isset( $dialogs[$message->sender->screen_name] ) ) {
            if ( $dialogs[$message->sender->screen_name]['id'] < $message->id_str ) {
                $dialogs[$message->sender->screen_name]['id'] = $message->id_str;
                $dialogs[$message->sender->screen_name]['text'] = $message->text;
                $dialogs[$message->sender->screen_name]['type'] = 'inbox';
            }
        } else {
            $dialogs[$message->sender->screen_name]['screen_name'] = $message->sender->screen_name;
            $dialogs[$message->sender->screen_name]['name'] = $message->sender->name;
            $dialogs[$message->sender->screen_name]['profile_image'] = $message->sender->profile_image_url;
            $dialogs[$message->sender->screen_name]['id'] = $message->id_str;
            $dialogs[$message->sender->screen_name]['text'] = $message->text;
            $dialogs[$message->sender->screen_name]['type'] = 'inbox';
        }
    }

    //Outbox part
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/direct_messages/sent.json';
    $requestMethod = 'GET';
    $response = json_decode( $twitter->buildOauth( $url, $requestMethod )->performRequest() );

    foreach ( $response as $message ) {
        if ( isset( $dialogs[$message->recipient->screen_name] ) ) {
            if ( $dialogs[$message->recipient->screen_name]['id'] < $message->id_str ) {
                $dialogs[$message->recipient->screen_name]['id'] = $message->id_str;
                $dialogs[$message->recipient->screen_name]['text'] = $message->text;
                $dialogs[$message->recipient->screen_name]['type'] = 'outbox';
            }
        } else {
            $dialogs[$message->recipient->screen_name]['screen_name'] = $message->recipient->screen_name;
            $dialogs[$message->recipient->screen_name]['name'] = $message->recipient->name;
            $dialogs[$message->recipient->screen_name]['profile_image'] = $message->recipient->profile_image_url;
            $dialogs[$message->recipient->screen_name]['id'] = $message->id_str;
            $dialogs[$message->recipient->screen_name]['text'] = $message->text;
            $dialogs[$message->recipient->screen_name]['type'] = 'outbox';
        }
    }

    return $dialogs;
}

//Get inbox messages of user
function tc_get_inbox()
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/direct_messages.json';
    $requestMethod = 'GET';
    $response = json_decode( $twitter->buildOauth( $url, $requestMethod )->performRequest() );
    $return = array();
    if ( $response ) {
        foreach ( $response as $message ) {
            $return[$message->id_str]['id'] = $message->id_str;
            $return[$message->id_str]['text'] = $message->text;
            $return[$message->id_str]['sender_name'] = $message->sender->screen_name;
            $return[$message->id_str]['avatar'] = $message->sender->profile_image_url;
        }
    }
    if ( !empty( $return ) ) {
        if ( !session_id() ) {
            session_start();
        }
        $_SESSION['tc_last_inbox'] = max( array_keys( $return ) );
    }
    return $return;
}

//Get outbox messages of user
function tc_get_outbox()
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/direct_messages/sent.json';
    $requestMethod = 'GET';
    $response = json_decode( $twitter->buildOauth( $url, $requestMethod )->performRequest() );
    $return = array();
    if ( $response ) {
        foreach ( $response as $message ) {
            $return[$message->id_str]['id'] = $message->id_str;
            $return[$message->id_str]['text'] = $message->text;
            $return[$message->id_str]['sender_name'] = $message->sender->screen_name;
            $return[$message->id_str]['avatar'] = $message->sender->profile_image_url;
            $return[$message->id_str]['recept_name'] = $message->recipient->screen_name;
        }
    }

    return $return;
}

//Get favorites
function tc_get_favorites()
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/favorites/list.json';
    $requestMethod = 'GET';

    //Get users twitter account
    require_once TC_PLUGIN_PATH . 'includes/user.php';
    $user_account = tc_get_user_twitter_account( get_current_user_id() );

    $getfield = "?screen_name=" . urlencode( $user_account );
    $response = json_decode( $twitter->setGetfield( $getfield )->buildOauth( $url, $requestMethod )->performRequest() );
    $return = array();
    if ( $response ) {
        foreach ( $response as $tweet ) {
            $return[$tweet->id_str]['id'] = $tweet->id_str;
            $return[$tweet->id_str]['created_at'] = $tweet->created_at;
            $return[$tweet->id_str]['text'] = $tweet->text;
            $return[$tweet->id_str]['user_id'] = $tweet->user->id_str;
            $return[$tweet->id_str]['user_name'] = $tweet->user->name;
            $return[$tweet->id_str]['user_screen_name'] = $tweet->user->screen_name;
            $return[$tweet->id_str]['user_avatar'] = $tweet->user->profile_image_url;

            //Retweet and favorite count
            $return[$tweet->id_str]['retweet_count'] = $tweet->retweet_count;
            $return[$tweet->id_str]['favorite_count'] = $tweet->favorite_count;
        }
    }
    return $return;
}

//Retweet admin
function retweet_status( $status_id )
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/statuses/retweet/' . $status_id . '.json';
    $requestMethod = 'POST';
    $postfields = array( 'trim_user' => 1 );
    $response = json_decode( $twitter->buildOauth( $url, $requestMethod )->setPostfields( $postfields )->performRequest() );
    if ( empty( $response->errors ) ) {
        die( json_encode( $response->id_str ) );
    } else {
        die( json_encode( FALSE ) );
    }
}

//Retweet user
function retweet_status_user( $status_id )
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/statuses/retweet/' . $status_id . '.json';
    $requestMethod = 'POST';
    $postfields = array( 'trim_user' => 1 );
    $response = json_decode( $twitter->buildOauth( $url, $requestMethod )->setPostfields( $postfields )->performRequest() );
    if ( empty( $response->errors ) ) {
        die( json_encode( $response->id_str ) );
    } else {
        die( json_encode( FALSE ) );
    }
}

function delete_direct_message( $id )
{
    $twitter = connect_twitter();
    $url = "https://api.twitter.com/1.1/direct_messages/destroy.json?id=$id";
    $requestMethod = 'POST';
    $postfields = array( 'id' => $id );
    $getfield = '?id=' . $id;
    $response = $twitter->buildOauth( $url, $requestMethod )->setPostfields( $postfields )->performRequest(); //performRequest());
    die( var_dump( $response ) );
}

//Retweet admin
function retweet_status_destroy( $status_id )
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/statuses/destroy/' . $status_id . '.json';
    $requestMethod = 'POST';
    $postfields = array( 'trim_user' => 1 );
    $response = json_decode( $twitter->buildOauth( $url, $requestMethod )->setPostfields( $postfields )->performRequest() );
    if ( empty( $response->errors ) ) {
        die( json_encode( TRUE ) );
    } else {
        die( json_encode( FALSE ) );
    }
}

//Add to favorites admin
function favorite_status( $status_id )
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/favorites/create.json';
    $requestMethod = 'POST';
    $postfields = array( 'id' => $status_id );
    $getfield = "?id=" . $status_id;
    $response = json_decode( $twitter->buildOauth( $url, $requestMethod )->setPostfields( $postfields )->performRequest() );
    if ( empty( $response->errors ) ) {
        die( json_encode( "Post $status_id was successfully added to favorites!" ) );
    } else {
        die( json_encode( $response->errors['0']->message ) );
    }
}

//Remove from favorites admin
function favorite_status_destroy( $status_id )
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/favorites/destroy.json';
    $requestMethod = 'POST';
    $postfields = array( 'id' => $status_id );
    $getfield = "?id=" . $status_id;
    $response = json_decode( $twitter->buildOauth( $url, $requestMethod )->setPostfields( $postfields )->performRequest() );
    if ( empty( $response->errors ) ) {
        die( json_encode( TRUE ) );
    } else {
        die( json_encode( FALSE ) );
    }
}

//Send reply
function tc_send_reply( $in_reply_to_status_id, $status )
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/statuses/update.json';
    $requestMethod = 'POST';
    $postfields = array( 'in_reply_to_status_id' => $in_reply_to_status_id, 'status' => $status );
    $response = json_decode( $twitter->buildOauth( $url, $requestMethod )->setPostfields( $postfields )->performRequest() );
    if ( empty( $response->errors ) ) {
        die( json_encode( TRUE ) );
    } else {
        die( json_encode( FALSE ) );
    }
}

//Send reply
function simple_tweet_reply( $status, $in_reply_to_status_id )
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/statuses/update.json';
    $requestMethod = 'POST';
    $postfields = array( 'in_reply_to_status_id' => $in_reply_to_status_id, 'status' => $status );
    $response = json_decode( $twitter->buildOauth( $url, $requestMethod )->setPostfields( $postfields )->performRequest() );
    if ( empty( $response->errors ) ) {
        die( json_encode( TRUE ) );
    } else {
        die( json_encode( FALSE ) );
    }
}

//Send direct
function tc_send_direct( $screen_name, $text )
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/direct_messages/new.json';
    $requestMethod = 'POST';
    $postfields = array( 'screen_name' => $screen_name, 'text' => $text );
    $response = json_decode( $twitter->buildOauth( $url, $requestMethod )->setPostfields( $postfields )->performRequest() );
    if ( empty( $response->errors ) ) {
        $responseOur['id'] = $response->id_str;
        die( json_encode( $responseOur ) );
    } else {
        die( json_encode( FALSE ) );
    }
}

//Send direct
function tc_send_direct_user( $screen_name, $text )
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/direct_messages/new.json';
    $requestMethod = 'POST';
    $postfields = array( 'screen_name' => $screen_name, 'text' => $text );
    $response = json_decode( $twitter->buildOauth( $url, $requestMethod )->setPostfields( $postfields )->performRequest() );
    $_SESSION['tc_last_inbox'] = $response->id_str;
    if ( empty( $response->errors ) ) {
        die( json_encode( TRUE ) );
    } else {
        die( json_encode( FALSE ) );
    }
}

//Save response data
function save_twitter_response( $response, $product_id )
{
    global $wpdb;
    $twitter_status_id = $response->id_str;
    $twitter_status_text = $response->text;
    $twitter_status_link = "https://twitter.com/" . $response->user->screen_name . "/status/" . $twitter_status_id;
    $tablename = $wpdb->prefix . "tc_products";
    $wpdb->insert(
            $tablename, array(
        'product_id' => $product_id,
        'twitter_status_id' => $twitter_status_id,
        'twitter_status_link' => $twitter_status_link,
        'twitter_status_text' => $twitter_status_text
            ), array(
        '%d',
        '%s',
        '%s',
        '%s'
            )
    );
    return TRUE;
}

//Return count of replies for status
function tc_get_replies_count( $status_id )
{
    $reply_count = 0;

    //Get users twitter account
    require_once TC_PLUGIN_PATH . 'includes/user.php';
    $user_account = tc_get_user_twitter_account( get_current_user_id() );

    //Get system hashtag
    $add_to_cart_hashtag = get_option( 'tc_twitter_hashtag' );

    //Search tweets by user
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/search/tweets.json';
    $requestMethod = 'GET';
    $getfield = "?q=" . urlencode( "@" . $user_account . "#" . $add_to_cart_hashtag );
    $response = json_decode( $twitter->setGetfield( $getfield )->buildOauth( $url, $requestMethod )->performRequest() );

    if ( !empty( $response->statuses ) ) {
        foreach ( $response->statuses as $status ) {
            if ( $status->in_reply_to_status_id_str == $status_id ) {
                $reply_count++;
            }
        }
    }
    return $reply_count;
}

//Who retweeted
function get_retweeted_users( $status_id )
{
    $twitter = connect_twitter();
    $url = "https://api.twitter.com/1.1/statuses/retweets/$status_id.json";
    $requestMethod = 'GET';

    $result = array();

    $response = json_decode( $twitter->buildOauth( $url, $requestMethod )->performRequest() );
    if ( $response ) {
        foreach ( $response as $status ) {
            $result[$status->id_str]['user_name'] = $status->user->screen_name;
            $result[$status->id_str]['user_avatar'] = $status->user->profile_image_url;
        }
    }
    return $result;
}

//Who replied
function get_replied_users( $status_id )
{
    //Get system hashtag
    $add_to_cart_hashtag = get_option( 'tc_twitter_hashtag' );

    //Search tweets by user
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/search/tweets.json';
    $requestMethod = 'GET';
    $getfield = "?q=" . urlencode( "#" . $add_to_cart_hashtag );
    $response = json_decode( $twitter->setGetfield( $getfield )->buildOauth( $url, $requestMethod )->performRequest() );

    $result = array();

    if ( !empty( $response ) ) {
        foreach ( $response->statuses as $status ) {
            if ( $status->in_reply_to_status_id_str == $status_id ) {
                $result[$status->id_str]['user_name'] = $status->user->screen_name;
                $result[$status->id_str]['user_avatar'] = $status->user->profile_image_url;
            }
        }
    }
    return $result;
}

//Get Twitter screen name
function get_username_via_api( $userId, $token = false, $secret = false )
{
    $twitter = connect_twitter( $token, $secret );
    $url = 'https://api.twitter.com/1.1/account/settings.json';
    $requestMethod = 'GET';
    $response = json_decode( $twitter->buildOauth( $url, $requestMethod )->performRequest() );
    return $response->screen_name;
}

//Get Twitter user data
function get_user_data_twitter()
{
    $twitter = connect_twitter( $token, $secret );
    $url = 'https://api.twitter.com/1.1/account/verify_credentials.json';
    $requestMethod = 'GET';
    $response = json_decode( $twitter->buildOauth( $url, $requestMethod )->performRequest() );
    $data = array();
    if ( $response ) {
        $data['id'] = $response->id_str;
        $data['name'] = $response->name;
        $data['screen_name'] = $response->screen_name;
        $data['following'] = $response->friends_count;
        $data['followers'] = $response->followers_count;
        $data['tweets'] = $response->statuses_count;
        $data['profile_background'] = $response->profile_banner_url . '/600x200';
        $data['bgcolor'] = $response->profile_link_color;
        $data['profile_image'] = $response->profile_image_url;
    }
    return $data;
}

//Return count of retweets for status
function tc_get_retweets_count( $status_id )
{
    $retweet_count = 0;

    //Get users twitter account
    require_once TC_PLUGIN_PATH . 'includes/user.php';
    $user_account = tc_get_user_twitter_account( get_current_user_id() );

    //Search tweets by user
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/statuses/retweets/' . $status_id . '.json';
    $requestMethod = 'GET';
    $getfield = "?id=" . $status_id;
    $response = json_decode( $twitter->setGetfield( $getfield )->buildOauth( $url, $requestMethod )->performRequest() );

    //Add retweets count
    $retweet_count += count( $response );

    return $retweet_count;
}

//OAuth test
function tc_get_tokens()
{
    require_once TC_PLUGIN_PATH . 'libs/oauth/tmhOAuth.php';
    $tmhOAuth = new tmhOAuth( array(
        'consumer_key' => get_option( 'tc_twitter_api_key' ),
        'consumer_secret' => get_option( 'tc_twitter_api_secret' ),
            ) );
    // send request for a request token
    $tmhOAuth->request( "POST", $tmhOAuth->url( "oauth/request_token", "" ), array(
        // pass a variable to set the callback
        'oauth_callback' => add_query_arg( array( 'fromfrontend' => 'true' ), get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) )
    ) );
    //die(var_dump($tmhOAuth->response));
    if ( $tmhOAuth->response["code"] == 200 ) {

        // get and store the request token
        $response = $tmhOAuth->extract_params( $tmhOAuth->response["response"] );
        $_SESSION["authtoken"] = $response["oauth_token"];
        $_SESSION["authsecret"] = $response["oauth_token_secret"];

        // state is now 1
        $_SESSION["authstate"] = 1;

        // redirect the user to Twitter to authorize
        $url = $tmhOAuth->url( "oauth/authorize", "" ) . '?oauth_token=' . $response["oauth_token"];
        die( json_encode( $url ) );
        exit;
    }
    return false;
}

function tc_get_tokens_vendor()
{

    require_once TC_PLUGIN_PATH . 'libs/oauth/tmhOAuth.php';
    $tmhOAuth = new tmhOAuth( array(
        'consumer_key' => get_option( 'tc_twitter_api_key' ),
        'consumer_secret' => get_option( 'tc_twitter_api_secret' ),
            ) );
    // send request for a request token
    $tmhOAuth->request( "POST", $tmhOAuth->url( "oauth/request_token", "" ), array(
        // pass a variable to set the callback
        'oauth_callback' => add_query_arg( array( 'vendoroauth' => 'true' ), get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) )
    ) );
    //die(var_dump($tmhOAuth->response));
    if ( $tmhOAuth->response["code"] == 200 ) {

        // get and store the request token
        $response = $tmhOAuth->extract_params( $tmhOAuth->response["response"] );
        $_SESSION["authtoken"] = $response["oauth_token"];
        $_SESSION["authsecret"] = $response["oauth_token_secret"];

        // state is now 1
        $_SESSION["authstate"] = 1;

        // redirect the user to Twitter to authorize
        $url = $tmhOAuth->url( "oauth/authorize", "" ) . '?oauth_token=' . $response["oauth_token"];
        die( json_encode( $url ) );
        exit;
    }
    return false;
}

//Get Twitter API tokens
function tc_get_access_token()
{
    require_once TC_PLUGIN_PATH . 'libs/oauth/tmhOAuth.php';
    $tmhOAuth = new tmhOAuth( array(
        'consumer_key' => get_option( 'tc_twitter_api_key' ),
        'consumer_secret' => get_option( 'tc_twitter_api_secret' ),
            ) );

    // set the request token and secret we have stored
    $tmhOAuth->config["user_token"] = $_SESSION["authtoken"];
    $tmhOAuth->config["user_secret"] = $_SESSION["authsecret"];

    // send request for an access token
    $oavf = $_GET["oauth_verifier"];
    $tmhOAuth->request( "POST", $tmhOAuth->url( "oauth/access_token", "" ), array(
        // pass the oauth_verifier received from Twitter
        'oauth_verifier' => $oavf
    ) );

    if ( $tmhOAuth->response["code"] == 200 ) {

        // get the access token and store it in a cookie
        $response = $tmhOAuth->extract_params( $tmhOAuth->response["response"] );
        if ( verify_access_token( $response["oauth_token"], $response["oauth_token_secret"] ) ) {
            require_once TC_PLUGIN_PATH . 'includes/user.php';
            save_user_tokens( $response["oauth_token"], $response["oauth_token_secret"] );
            set_user_twitter_account( get_username_via_api( get_current_user_id(), $response["oauth_token"], $response["oauth_token_secret"] ) );
        }

        // redirect user to clear leftover GET variables
        header( "Location: " . get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) );
        exit;
    }
    return false;
}

function tc_get_access_token_front()
{
    require_once TC_PLUGIN_PATH . 'libs/oauth/tmhOAuth.php';
    $tmhOAuth = new tmhOAuth( array(
        'consumer_key' => get_option( 'tc_twitter_api_key' ),
        'consumer_secret' => get_option( 'tc_twitter_api_secret' ),
            ) );

    // set the request token and secret we have stored
    $tmhOAuth->config["user_token"] = $_SESSION["authtoken"];
    $tmhOAuth->config["user_secret"] = $_SESSION["authsecret"];

    // send request for an access token
    $oavff = sanitize_text_field( $_GET["oauth_verifier"] );
    $tmhOAuth->request( "POST", $tmhOAuth->url( "oauth/access_token", "" ), array(
        // pass the oauth_verifier received from Twitter
        'oauth_verifier' => $oavff
    ) );

    if ( $tmhOAuth->response["code"] == 200 ) {

        // get the access token and store it in a cookie
        $response = $tmhOAuth->extract_params( $tmhOAuth->response["response"] );
        if ( verify_access_token( $response["oauth_token"], $response["oauth_token_secret"] ) ) {
            require_once TC_PLUGIN_PATH . 'includes/user.php';
            save_user_tokens( $response["oauth_token"], $response["oauth_token_secret"] );
            set_user_twitter_account( get_username_via_api( get_current_user_id(), $response["oauth_token"], $response["oauth_token_secret"] ) );
        }

        // redirect user to clear leftover GET variables
        header( "Location: " . add_query_arg( array( 'frontbind' => 'frontbind' ), get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ) );
        exit;
    }
    return false;
}

function verify_access_token( $oauth_token, $oauth_token_secret )
{
    require_once TC_PLUGIN_PATH . 'libs/oauth/tmhOAuth.php';
    $tmhOAuth = new tmhOAuth( array(
        'consumer_key' => get_option( 'tc_twitter_api_key' ),
        'consumer_secret' => get_option( 'tc_twitter_api_secret' ),
            ) );

    $tmhOAuth->config["user_token"] = $oauth_token;
    $tmhOAuth->config["user_secret"] = $oauth_token_secret;

    // send verification request to test access key
    $tmhOAuth->request( "GET", $tmhOAuth->url( "1.1/account/verify_credentials" ) );

    // HTTP 200 means we were successful
    return ($tmhOAuth->response["code"] == 200);
}

function get_new_twitter_inbox( $cursor, $user )
{
    $twitter = connect_twitter();
    $url = 'https://api.twitter.com/1.1/direct_messages.json';
    $requestMethod = 'GET';
    $getfield = "?since_id=$cursor";
    $response = json_decode( $twitter->setGetfield( $getfield )->buildOauth( $url, $requestMethod )->performRequest() );
    $return = array();
    if ( $response ) {
        foreach ( $response as $message ) {
            if ( $message->sender->screen_name == $user ) {
                $return[$message->id_str]['id'] = $message->id_str;
                $return[$message->id_str]['text'] = $message->text;
                $return[$message->id_str]['sender_name'] = $message->sender->screen_name;
                $return[$message->id_str]['avatar'] = $message->sender->profile_image_url;
            }
        }
    }

    return $return;
}

function tc_added_to_wishlist( $status_id )
{
    global $wpdb;
    $tablename = $wpdb->prefix . "tc_twitter_wishlist";
    $result = $wpdb->get_var( $wpdb->prepare(
                    "
                SELECT status FROM $tablename WHERE twitter_status_id = %s
            ", $status_id
            ) );
    if ( $result == 'active' ) {
        return TRUE;
    }
    return FALSE;
}

function get_add_hashtags()
{
    global $wpdb;

    $hashtags = array();

    //Get system hashtag
    $add_to_cart_hashtag = get_option( 'tc_twitter_hashtag' );

    $tablename = $wpdb->prefix . "tc_user_hashtags";
    $sql = "SELECT hashtag FROM $tablename";
    $res = $wpdb->get_results( $sql );
    foreach ( $res as $single ) {
        if ( $single->hashtag != "" ) {
            $hashtags[] = $single->hashtag;
        }
    }
    $hashtags[] = $add_to_cart_hashtag;
    return $hashtags;
}

function get_wl_hashtags()
{
    global $wpdb;

    $hashtags = array();

    //Get system hashtag
    $wl_hashtag = get_option( 'tc_wishlist_hashtag' );

    $tablename = $wpdb->prefix . "tc_user_hashtags";
    $sql = "SELECT wishlist_hashtag FROM $tablename";
    $res = $wpdb->get_results( $sql );
    foreach ( $res as $single ) {
        if ( $single->wishlist_hashtag != "" ) {
            $hashtags[] = $single->wishlist_hashtag;
        }
    }
    $hashtags[] = $wl_hashtag;
    return $hashtags;
}

function showed_earlier( $user_id, $tweet_id )
{
    global $wpdb;
    $table_name = $wpdb->prefix . "tc_user_showed";
    $sql = "";
    $result = $wpdb->get_var( $wpdb->prepare(
                    "
                SELECT COUNT(id) FROM $table_name WHERE user_id = %d AND tweet_id = %s
            ", array(
                $user_id,
                $tweet_id
                    )
            ) );
    if ( $result > 0 ) {
        return TRUE;
    }
    return FALSE;
}
