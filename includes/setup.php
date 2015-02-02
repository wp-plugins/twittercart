<?php
defined( 'ABSPATH' ) or die( 'Access denied!' );

//Base plugin options
function set_plugin_options()
{
    //add_option('tc_script_dir', '');
    //Add to cart #Hashtag
    add_option( 'tc_twitter_hashtag', 'mytwittercart' );

    //Add to wishlist hashtag
    add_option( 'tc_wishlist_hashtag', 'mytwitterwl' );

    //Support email
    add_option( 'tc_support_email', 'info@browserweb.com' );

    //Twitter API options
    add_option( 'tc_twitter_api_key', '' );
    add_option( 'tc_twitter_api_secret', '' );
    add_option( 'tc_twitter_access_token', '' );
    add_option( 'tc_twitter_access_token_secret', '' );

    add_option( 'tc_update_timestamp', time() );
}

//Create plugin DB tables
function set_plugin_tables()
{
    global $wpdb;

    //Posted product table
    $table_name = $wpdb->prefix . "tc_products";
    if ( $wpdb->get_var( "show tables like '$table_name'" ) != $table_name ) {

        $sql = "
                  CREATE TABLE IF NOT EXISTS `wp_tc_products` (
                    `tc_product_id` int(11) NOT NULL AUTO_INCREMENT,
                    `product_id` int(11) NOT NULL,
                    `twitter_status_id` varchar(255) NOT NULL,
                    `twitter_status_link` varchar(255) NOT NULL,
                    `twitter_status_text` varchar(255) NOT NULL,
                    PRIMARY KEY (`tc_product_id`)
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
                  
               ";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta( $sql );
    }

    //User accounts table
    $table_name = $wpdb->prefix . "tc_accounts";
    if ( $wpdb->get_var( "show tables like '$table_name'" ) != $table_name ) {

        $sql = "
                  CREATE TABLE IF NOT EXISTS `$table_name` (
                    `account_id` int(11) NOT NULL AUTO_INCREMENT,
                    `user_id` int(11) NOT NULL,
                    `twitter_account_id` varchar(255) NOT NULL,
                    `twitter_account` varchar(255) NOT NULL,
                    PRIMARY KEY (`account_id`)
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
               ";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta( $sql );
    }

    //Twitter orders table
    $table_name = $wpdb->prefix . "tc_twitter_orders";
    if ( $wpdb->get_var( "show tables like '$table_name'" ) != $table_name ) {
        $sql = "
                  CREATE TABLE IF NOT EXISTS `$table_name` (
                    `twitter_status_id` varchar(255) NOT NULL,
                    `in_reply_to_status_id` varchar(255) NOT NULL,
                    `user` varchar(255) NOT NULL,
                    `status` varchar(255) NOT NULL,
                    PRIMARY KEY (`twitter_status_id`)
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
               ";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta( $sql );
    }

    //Twitter wishlist table
    $table_name = $wpdb->prefix . "tc_twitter_wishlist";
    if ( $wpdb->get_var( "show tables like '$table_name'" ) != $table_name ) {
        $sql = "
                  CREATE TABLE IF NOT EXISTS `$table_name` (
                    `twitter_status_id` varchar(255) NOT NULL,
                    `in_reply_to_status_id` varchar(255) NOT NULL,
                    `user` varchar(255) NOT NULL,
                    `status` varchar(255),
                    PRIMARY KEY (`twitter_status_id`)
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
               ";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta( $sql );
    }

    //Twitter orders table
    $table_name = $wpdb->prefix . "tc_user_tokens";
    if ( $wpdb->get_var( "show tables like '$table_name'" ) != $table_name ) {
        $sql = "
                  CREATE TABLE IF NOT EXISTS `$table_name` (
                    `ut_id` int(11) NOT NULL AUTO_INCREMENT,
                    `user_id` int(11) NOT NULL,
                    `oauth_token` varchar(255) NOT NULL,
                    `oauth_token_secret` varchar(255) NOT NULL,
                    PRIMARY KEY (`ut_id`)
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
               ";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta( $sql );
    }

    //Twitter hashtags table
    $table_name = $wpdb->prefix . "tc_user_hashtags";
    if ( $wpdb->get_var( "show tables like '$table_name'" ) != $table_name ) {
        $sql = "
                   CREATE TABLE IF NOT EXISTS `$table_name` (
                    `uh_id` int(11) NOT NULL AUTO_INCREMENT,
                    `user_id` int(11) NOT NULL,
                    `hashtag` varchar(255),
                    `wishlist_hashtag` varchar(255),
                    PRIMARY KEY (`uh_id`)
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
               ";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta( $sql );
    }

    //Twitter hashtags table
    $table_name = $wpdb->prefix . "tc_user_showed";
    if ( $wpdb->get_var( "show tables like '$table_name'" ) != $table_name ) {
        $sql = "
                   CREATE TABLE IF NOT EXISTS `$table_name` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `user_id` int(11) NOT NULL,
                    `tweet_id` varchar(255),
                    PRIMARY KEY (`id`)
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
               ";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta( $sql );
    }

    //Twitter checked statusses table
    $table_name = $wpdb->prefix . "tc_checked";
    if ( $wpdb->get_var( "show tables like '$table_name'" ) != $table_name ) {
        $sql = "
                   CREATE TABLE IF NOT EXISTS `$table_name` (
                    `checked_id` int(11) NOT NULL AUTO_INCREMENT,
                    `hashtag` varchar(255) NOT NULL,
                    `status_id` varchar(255) NOT NULL,
                    PRIMARY KEY (`checked_id`)
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
               ";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta( $sql );
    }
}

function unset_plugin_options()
{
    //delete_option('tc_script_dir');
    delete_option( 'tc_twitter_hashtag' );
    delete_option( 'tc_twitter_api_key' );
    delete_option( 'tc_twitter_api_secret' );
    delete_option( 'tc_twitter_access_token' );
    delete_option( 'tc_twitter_access_token_secret' );
    delete_option( 'tc_wishlist_hashtag' );
    delete_option( 'tc_support_email' );
    delete_option( 'tc_update_timestamp' );
}

function unset_plugin_tables()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "tc_products";
    @$wpdb->query( "DROP TABLE $table_name" );
    $table_name = $wpdb->prefix . "tc_accounts";
    @$wpdb->query( "DROP TABLE $table_name" );
    $table_name = $wpdb->prefix . "tc_twitter_orders";
    @$wpdb->query( "DROP TABLE $table_name" );
    $table_name = $wpdb->prefix . "tc_twitter_wishlist";
    @$wpdb->query( "DROP TABLE $table_name" );
    $table_name = $wpdb->prefix . "tc_user_tokens";
    @$wpdb->query( "DROP TABLE $table_name" );
    $table_name = $wpdb->prefix . "tc_user_hashtags";
    @$wpdb->query( "DROP TABLE $table_name" );
    $table_name = $wpdb->prefix . "tc_user_showed";
    @$wpdb->query( "DROP TABLE $table_name" );
    $table_name = $wpdb->prefix . "tc_checked";
    @$wpdb->query( "DROP TABLE $table_name" );
}

//Set Twitter stream cron
function set_plugin_cron()
{
    wp_schedule_event( time(), 'once_second_interval', 'tc_second_event_hook' );
}

//Unset Twitter stream cron
function unset_plugin_cron()
{
    //wp_schedule_event(time(), 'second', 'tc_second_event_hook');
}
