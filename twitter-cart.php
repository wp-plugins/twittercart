<?php
/*
  Plugin Name: Twitter Cart
  Description: Promote and sell your Woocommerce Products on Twitter using TwitterCart. #Hashtag add-to-cart Wordpress Plugin. Free version
  Author: Browserweb Inc.
  Version: 1.3.36
 */

defined( 'ABSPATH' ) or die( 'Access denied!' );

define( 'TC_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'TC_TEMPLATES_PATH', TC_PLUGIN_PATH . 'includes/templates/' );
define( 'TC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'TC_LIBS_PATH', TC_PLUGIN_PATH . 'libs/' );
define( 'TC_IMG_URL', TC_PLUGIN_URL . 'assets/images/' );
define( 'BASE_SITE_URL', get_site_url() );
define( 'TC_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'TC_MULTIVENDOR', TRUE );

$upload = wp_upload_dir();
define( 'BASE_UPLOAD_URL', $upload['baseurl'] );

require_once TC_PLUGIN_PATH . 'includes/actions.php';
require_once TC_PLUGIN_PATH . 'includes/functions.php';
