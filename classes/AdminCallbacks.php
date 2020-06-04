<?php

/**
*@package PreQualifyLeads
*/



class AdminCallbacks{

    function admin_option_group($input){

        return $input;
    }

    function admin_settings_section(){
        echo '<div>
        You can set Qualification and Disqualification Links here.
        <br>

        Use <b><span class="instructions">[PreQualifyingLeads category="category_slug"]</span> </b> Shortcode to display the form in any page.<br>
        Replace "category_slug" with category slug in which category your question are placed.
        
        </div>';
    }

    
    function admin_settings_disqualification_custom_link(){

       $value = esc_attr( get_option('disqualification_link') );

       echo '<input type="text" value= "'. $value . '" name="disqualification_link" class="regular-text" placeholder="Disqualification Link">';
    }

    function admin_settings_qualification_custom_link(){

       $value = esc_attr( get_option('qualification_link') );

       echo '<input type="text" value= "'. $value . '" name="qualification_link" class="regular-text" placeholder="Qualification Link">';
    }

    function admin_settings_page_title_custom_feild(){

       $value = esc_attr( get_option('page_title') );

       echo '<input type="text" value= "'. $value . '" name="page_title" class="regular-text" placeholder="Enter Page Title">';
    }

    function admin_settings_image_link_custom_feild(){
         $value = esc_attr( get_option('image_link') );

        echo '<input type="text" value= "'. $value . '" name="image_link"  class="regular-text" placeholder="Image Link">';
    }

    function admin_settings_lead_collect_email_custom_feild(){
         $value = esc_attr( get_option('lead_collect_email') );

        echo '<input type="text" value= "'. $value . '" name="lead_collect_email"  class="regular-text" placeholder="Enter Email where you want to collect Leads">';
    }

    
}


    
