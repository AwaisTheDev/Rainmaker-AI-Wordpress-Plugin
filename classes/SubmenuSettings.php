<?php

/**
*@package PreQualifyLeads
*/

require_once 'AdminCallbacks.php';

class SubmenuSettings{

    public $callbacks;

    function __construct(){

        add_action('admin_init' , array($this , 'register_settings'));
        add_action('admin_init' , array($this , 'register_sections'));
        add_action('admin_init' , array($this , 'register_feilds'));
        $this->callbacks = new AdminCallbacks();

    }


    function  register_settings(){
        register_setting( 
            'PQL_settins_Option_group', 
            'disqualification_link',             
            array($this->callbacks, 'admin_option_group')
        );
        

        register_setting( 
            'PQL_settins_Option_group', 
            'qualification_link',             
            array($this->callbacks, 'admin_option_group')
        );

        register_setting( 
            'PQL_settins_Option_group', 
            'page_title',             
            array($this->callbacks, 'admin_option_group')
        );

        register_setting( 
            'PQL_settins_Option_group', 
            'image_link', 
            array($this->callbacks, 'admin_option_group')
        );

        register_setting( 
            'PQL_settins_Option_group', 
            'lead_collect_email', 
            array($this->callbacks, 'admin_option_group')
        );

        
    }

    function register_sections(){
 
        add_settings_section( 
            'PQL_admin_settings_section', 
            'Settings', 
            array($this->callbacks, 'admin_settings_section'), 
            'pql_servay_settings'
        );
    }

    function register_feilds(){
        add_settings_field( 
            'disqualification_link', 
            'Disqualification Link', 
             array($this->callbacks, 'admin_settings_disqualification_custom_link'), 
            'pql_servay_settings', 
            'PQL_admin_settings_section', 
            array(
                'label_for' => 'disqualification_link',
            )
        );

        add_settings_field( 
            'qualification_link', 
            'Qualification Link', 
             array($this->callbacks, 'admin_settings_qualification_custom_link'), 
            'pql_servay_settings', 
            'PQL_admin_settings_section', 
            array(
                'label_for' => 'qualification_link',
            )
        );

        add_settings_field( 
            'page_title', 
            'Page Title', 
             array($this->callbacks, 'admin_settings_page_title_custom_feild'), 
            'pql_servay_settings', 
            'PQL_admin_settings_section', 
            array(
                'label_for' => 'page_title',
            )
        );

        add_settings_field( 
            'image_link', 
            'Image Link', 
             array($this->callbacks, 'admin_settings_image_link_custom_feild'), 
            'pql_servay_settings', 
            'PQL_admin_settings_section', 
            array(
                'label_for' => 'image_link',
            )
        );

        add_settings_field( 
            'lead_collect_email', 
            'Email where you want to collect Leads', 
             array($this->callbacks, 'admin_settings_lead_collect_email_custom_feild'), 
            'pql_servay_settings', 
            'PQL_admin_settings_section', 
            array(
                'label_for' => 'lead_collect_email',
            )
        );

        
    }
    
}