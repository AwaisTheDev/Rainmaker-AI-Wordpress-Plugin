<?php
/**
*@package PreQualifyLeads
*/

/*
Plugin Name: RainmakerAI
Plugin URI: www.mdawais.com
Description: This is a plugin helps you to collect leads by asking them question.
version: 1.0.0
Author: Muhammad Awais
Author URI: www.mdawais.com
Licence: GPLv2 or Later
Text Domain: RainmakerAI
*/

if(! defined('ABSPATH')){
    die;
}

add_action( 'init', 'PQL_start_session' );

function PQL_start_session(){
    if(!session_id()){
        session_start();
    }
}

define('PQL_PLUGIN_PATH', plugin_dir_path( __FILE__));
define('PQL_PLUGIN_URL', plugin_dir_url( __FILE__));
define('PQL_PLUGIN_NAME', plugin_basename(__file__));


require_once 'classes/CreatePostType.php';
require_once 'classes/AdminSubPages.php';
require_once 'classes/SubmenuSettings.php';
require_once 'Includes/enqueueScripts.php';
require_once 'Templates/shortcode.php';

new CreatePostType();
new AdminSubPages();
new SubmenuSettings();
new enqueueScripts();


register_activation_hook( __FILE__, 'MyFirstPluginActivate' );
function MyFirstPluginActivate(){
    flush_rewrite_rules( );
}

register_deactivation_hook( __FILE__, 'MyFirstPluginDeactivate' );
function MyFirstPluginDeactivate(){
    flush_rewrite_rules( );
}



