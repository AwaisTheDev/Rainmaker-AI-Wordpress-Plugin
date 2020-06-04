<?php

/**
*@package PreQualifyLeads
*/

class enqueueScripts{

    function __construct(){
        add_action( 'admin_enqueue_scripts', array($this , 'enqueue_admin_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this , 'enqueue_frontend_scripts' ) );
    }

    function enqueue_admin_scripts(){
        wp_enqueue_style( 'plugin_style', PQL_PLUGIN_URL.'/Assets/style.css', array(),'1.0.0', 'all');
    }

    function enqueue_frontend_scripts(){
        wp_enqueue_style( 'plugin_frontend_style', PQL_PLUGIN_URL.'/Assets/front-style.css', array(),'1.0.0', 'all');
        wp_enqueue_script('Custom_Jquery' , PQL_PLUGIN_URL.'/Assets/Jquery/jquery.min.js');
        wp_enqueue_script( 'plugin_frontend_script', PQL_PLUGIN_URL.'/Assets/main.js' , array('Custom_Jquery'));
    }
    
}