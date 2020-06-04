<?php

/**
*@package PreQualifyLeads
*/

class CreatePostType{


    function __construct(){
        
        
        add_action( 'init' , array( $this , 'PQL_register_cpt' ));
        add_action( "admin_init", array( $this , "PQL_Add_post_meta" ) );
        add_action( 'save_post', array( $this , 'PQL_save_post_details' ));
        add_filter( 'manage_pql_servay_posts_columns',array($this , 'edit_pql_admin_columns' ));
        add_action( 'manage_pql_servay_posts_custom_column',array($this , 'populate_pql_admin_columns' ) , 10, 2);
        add_filter( 'manage_edit-pql_servay_sortable_columns',array($this, 'PQL_sort_category_column'));


    }

    function edit_pql_admin_columns($columns){
         $columns = array(
            'cb' => $columns['cb'],            
            'title' => __( 'Title' ),
            'question_type' => __( 'Question type' ),
            'question_order' => __( 'Question Order' ),
            'thumbnail' => __( 'Image' ),
            'category' => __( 'Category' ),
         );
          return $columns;
    }


    function populate_pql_admin_columns($column, $post_id){

        if( $column === 'thumbnail'){
             echo (get_the_post_thumbnail( $post_id, array( 80, 80 )) ) ? get_the_post_thumbnail( $post_id, array( 80, 80 )) : 'Add Image' ;
        }
        if( $column === 'question_type'){
            echo get_post_meta($post_id , 'question_type' , true); 
        }
        if( $column === 'question_order'){
            echo get_post_meta($post_id , 'question_order' , true); 
        }
        if( $column === 'category'){
            $post_categories = wp_get_post_categories( $post_id );

            foreach($post_categories as $c){
                $cat = get_category( $c );
                echo $cat->name;
            }

        }

    }
    
    function PQL_register_cpt(){
        $labels = array(
            'name' => _x('RainmakerAI', 'post type general name'),
            'singular_name' => _x('Question', 'post type singular name'),
            'add_new' => __('Add new'),
            'add_new_item' => __('Add New Question'),
            'edit_item' => __('Edit Question'),
            'new_item' => __('New Question'),
            'view_item' => __('View Question'),
            'search_items' => __('Search Question'),
            'not_found' =>  __('Nothing found'),
            'not_found_in_trash' => __('Nothing found in Trash'),
            'parent_item_colon' => ''
        );
        
        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'query_var' => true,
            'menu_icon' => 'dashicons-testimonial',
            'rewrite' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title','thumbnail'),
            'taxonomies' => array( 'category' ),

        ); 

        register_post_type( 'PQL_Servay' , $args );

    }

    function PQL_Add_post_meta(){
        add_meta_box( 'PQL_Question_order', 'Select Question Order',array($this ,  'select_question_order'), 'PQL_Servay' , 'side', 'low' );
        add_meta_box( 'question_meta', 'Question Information', array($this , 'question_info'), 'PQL_Servay', 'normal','low');
    }

    function select_question_order(){
        global $post;
        $custom = get_post_custom($post->ID);
        $question_order = (isset($custom["question_order"][0])) ? $custom["question_order"][0] : '' ;
        ?>
        <label>Select order</label>
        <input type="number" name="question_order" value="<?php echo $question_order; ?>" />
        <?php
    }
    function question_info(){


        global $post;
        $custom = get_post_custom($post->ID);
        $question_type = (isset($custom["question_type"][0])) ? $custom["question_type"][0] : '' ;
        $question_type = trim($question_type );
        $choices = (isset($custom["choices"][0])) ? $custom["choices"][0] : '' ;
        $correct_choices = (isset($custom["correct_choices"][0])) ? $custom["correct_choices"][0] : '' ;
        ?>

        <p><label>Select Question Type</label>
        <select name="question_type" >
            <option value="Multiple Choices" <?php if($question_type =='Multiple Choices') echo ' selected="selected"' ;  ?>>Multiple Choices</option>
            <option value="Single Choice" <?php if($question_type =='Single Choice') echo ' selected="selected"' ; ?>>Single Choice</option>       
            
        </select></p>

        <p><label>Enter Question Choices <b>( Separated with a | )</b> </label><br>
        <textarea cols="80" rows="5" name="choices"><?php  echo $choices; ?></textarea></p>

        <p><label>Enter Correct Choices <b>( Separated with a | )</b><br> Insert <b>all</b> if all choices are correct or you want to redirect user no matter what he selects </label><br>
        <textarea cols="80" rows="5" name="correct_choices"><?php  echo $correct_choices; ?></textarea></p>

        <?php

    }
    

    function PQL_save_post_details(){
        global $post;

        update_post_meta((isset($post->ID) ? $post->ID : ''), "question_type", (isset($_POST["question_type"])) ? $_POST["question_type"] : '');
        update_post_meta((isset($post->ID) ? $post->ID : ''), "choices", (isset($_POST["choices"])) ? $_POST["choices"] : '');
        update_post_meta((isset($post->ID) ? $post->ID : ''), "question_order", (isset($_POST["question_order"])) ? $_POST["question_order"] : '');
        update_post_meta((isset($post->ID) ? $post->ID : ''), "correct_choices", (isset($_POST["correct_choices"])) ? $_POST["correct_choices"] : '');
    } 
    
    function PQL_sort_category_column( $columns ) {
        $columns['category'] = 'category';
        return $columns;
    }

    
}



