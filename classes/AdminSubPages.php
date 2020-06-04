<?php

/**
*@package PreQualifyLeads
*/


class AdminSubPages{

    function __construct(){
        add_action( 'admin_menu', array($this ,  'PQL_Settings_submenu' ));
    }

    function PQL_Settings_submenu(){
        add_submenu_page(
            'edit.php?post_type=pql_servay',
            'PQL Settings',
            'Settings', 
            'manage_options',
            'pql_servay_settings', 
            array($this , 'PQL_admin_subpage_settings')
            ,111 
        );       

    }

    function PQL_admin_subpage_settings(){
        include dirname(__FILE__,2).'/Templates/admin_settings_templates.php';
    }

    

}

        

