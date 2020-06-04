<?php 

add_shortcode( 'PreQualifyingLeads', 'PreQualifyingLeads_shortcode');


function PreQualifyingLeads_shortcode($atts){
    
    
    // General arguments
    $atts = array_change_key_case((array)$atts, CASE_LOWER);

    $attributes = shortcode_atts( 
        array(
            'category' => 'uncateorized'
        ), 
        $atts
    );

    //echo $attributes['category'];

    $post_category = $attributes['category'];


    $currentPage = get_query_var('paged');
    $posts = new WP_Query(array(
        'post_type' => 'pql_Servay',
        'paged' => $currentPage,
        'post_status' => 'publish',
        'category_name' => $post_category,
        'posts_per_page' => 1,
        'orderby' => 'meta_value',
        'orderby' => 'meta_value_num',
        'meta_key' => 'question_order',
        'order' => 'ASC'
    ));

    $count = $posts->found_posts;

    


    // Display Page Title

    if(isset($_POST['email-submit'])){
        $_SESSION['PQL_Email'] = $_POST['email'];
        $_SESSION['name'] = $_POST['fname'] ;
        $_SESSION['phone'] = $_POST['phone'];
        $_SESSION['questions']= array();
        $_SESSION['responses']= array();
    }

    
    $page_title = get_option('page_title');
    $disqualification_link = get_option('disqualification_link');
    $qualification_link = get_option('qualification_link');

    ?>


    <div class="title-wrapper">
        <div class="title">
            <h2><?php echo $page_title ?><h2>
        </div>
    </div>
    

    <?php
    if ($posts->have_posts()) {
        
        while ($posts->have_posts()) {

            $posts->the_post();
   
            $question_type =  get_post_meta(get_the_ID() , 'question_type' , true); 
            $choices =  get_post_meta(get_the_ID() , 'choices' , true); 
            
           
            //echo  'All Options = '.$choices;           
            $all_choices_arr = explode('|' , $choices);            
            //echo '<br> Question type is = '.$question_type;
            $correct_choices =  get_post_meta(get_the_ID() , 'correct_choices' , true);
            $correct_choices_arr = explode('|' , $correct_choices);   

            $next_page = get_next_posts_page_link( ); 
            if(isset($_POST['submit'])){
                $question_id = get_the_ID();
                $submitted_value = $_POST[$question_id ];

                
                //echo $question_id;

                if(is_array($submitted_value)){

                    $all_options = "";
                    foreach ($submitted_value as $value){
                        $value = trim($value);
                        $all_options.=$value ."|";
                    }
                    $_SESSION['responses'] = array_merge($_SESSION['responses'] , array($all_options));
                    foreach ($submitted_value as $value) {

                        $value = trim($value);
                        foreach($correct_choices_arr as $correct){
                            $correct = trim($correct);
                            if(($correct =='all')){
                                echo("<script>location.href = '".$next_page."'</script>");
                                exit;
                            }
                            if((strcasecmp( $correct , $value) == 0 )){
                                echo("<script>location.href = '".$next_page."'</script>");
                                exit;
                            }
                        }
                    }
                    disqualify_user($disqualification_link);
                    // echo("<script>location.href = '".$disqualification_link."'</script>");
                }
                else{
                    $_SESSION['responses'] = array_merge($_SESSION['responses'] , array($submitted_value));
                    foreach($correct_choices_arr as $correct){
                        $correct=trim($correct);

                        if((strcasecmp( $correct , $submitted_value) == 0 ) || ($correct =='all')){

                             //echo "next page";
                            echo("<script>location.href = '".$next_page."'</script>");
                            exit;
                        }
                    }

                    
                    disqualify_user($disqualification_link);
                    //echo("<script>location.href = '".$disqualification_link."'</script>");
                   
                }
                
            }

    ?>
<?php if(isset($_SESSION['PQL_Email'])){ ?>
<form action="?" method="post">    
<div class="pql-wrapper">
    
    <div class="question-wrapper">
        <div class="question">
        	<div class="question-title">
                <h3><?php the_title( ); ?></h3>

                <?php $_SESSION['questions'] = array_merge($_SESSION['questions'] , array(get_the_title( )) ); ?>
                
            </div>
        	<div class="choices">
               

            <?php //check if there are Single choice then generate Radio Buttons ?>

            <?php if($question_type == trim('Single Choice')){ ?>
            
            <?php foreach($all_choices_arr as $choice => $i){ ?>
            <?php 
                $id = get_the_id().$choice;
                $i = trim($i);
            ?>

            <input 
                type="radio" 
                value="<?php echo $i?>" 
                name="<?php echo get_the_ID( ); ?>"
                id="<?php echo $id; ?>"
                class="validateit"              
            >
            <label for="<?php echo $id; ?>"><?php echo  $i ?></label><br>

           <?php  } 

           //check if there are multiple choices then generate check boxes
        } elseif($question_type == trim('Multiple Choices ')) { ?>
            <?php foreach($all_choices_arr as $choice => $i){ ?>
            <?php 
                $id = get_the_id().$choice;
                $i = trim($i);
            ?>

            <input 
                type="checkbox" 
                value="<?php echo $i?>" 
                name="<?php echo get_the_ID( )."[]"; ?>"
                id="<?php echo $id; ?>"
                class="validateit"  
            >
            <label for="<?php echo $id; ?>"><?php echo  $i ?></label><br>

            <?php } ?>
        <?php } ?>
            
            </div>
            <div class="next-page-link">
                <input type="submit" value="submit" name="submit" id="submitBtn" class="submit-question" disabled>
            </div>
        </div>
        <div class="image">

        <?php $post_thumbnail =  get_the_post_thumbnail_url( get_the_ID() );

        if($post_thumbnail == ''){
            $post_thumbnail = esc_attr( get_option('image_link') );
        }
        ?>
        <img src="<?php echo $post_thumbnail ?>">
        	
        </div>
    </div>
</div>

</form>
    <?php } 
    else
    { ?>

        <div class="pql-wrapper">
            <div class="question-wrapper email-fom">
                <div class="question">
                    <form action="?" method="post" >
                        <label for="fname">Your Name</label>
                        <input type="text" name="fname" id="fname" placeolder="Enter your First Name" required>
                        <label for="phone">Your Phone</label>
                        <input type="tel" name="phone" id="phone" placeolder="Enter your Last Name" required>
                        <label for="email">Your Email</label>
                        <input type="email" name="email" id="email" placeolder="Enter your Email" required>
                        <input type="submit" name="email-submit" value="Proceed">
                    </form>
                </div>
                <div class="image">
                   <?php  $image = esc_attr( get_option('image_link') ); ?>
                   <img src="<?php echo $image ?>">
                </div>
            </div>

        </div>;

  <?php  }  ?>
    <?php   }
    }

    else{

        //redirect user to apointment page
        
        if($qualification_link != ''){
            qualify_user($qualification_link);

        }else{
            echo 'no More Question Redirect the user to Appointment Pages';
        }

    }

    wp_reset_postdata();
}


function disqualify_user($disqualification_link){
    $is_lead_qualified = false;
    send_email($is_lead_qualified);   
    unset_session_data(); 
    echo("<script>location.href = '".$disqualification_link."'</script>");
    exit;
}

function qualify_user($qualification_link){
    $is_lead_qualified = true;
    send_email($is_lead_qualified);
    unset_session_data(); 
    echo("<script>location.href = '".$qualification_link."'</script>");
    exit;
}

function send_email($is_lead_qualified){
    $lead_qualification_status = '';
    if($is_lead_qualified == true){
        $lead_qualification_status = "<b>Lead Qualified!</b> Lead Answered All Questions.";
    }else{
         $lead_qualification_status = "<b>Lead Disqualified!</b> Lead Could Not Answer all question correctly.";
    }

    require dirname(__FILE__,2).'/Assets/phpMailer/Exception.php';
    require dirname(__FILE__,2).'/Assets/phpMailer/PHPMailer.php';
    require dirname(__FILE__,2).'/Assets/phpMailer/SMTP.php';

    $mail = new \PHPMailer\PHPMailer\PHPMailer();


    //setting for TLS
    $mail->IsSMTP(); // enable SMTP
    //$mail->SMTPDebug = 3;
    $mail->SMTPAuth = true; // authentication enabled
    $mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for Gmail
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 587; // or 587
    

    //setting for SSL
    // $mail->isSMTP();
    // $mail->SMTPDebug = 3;
    // $mail->Host = "ssl://smtp.gmail.com";
    // $mail->SMTPAuth=true;
    // $mail->Port=465;
    // $mail->SMTPSecure='ssl';

    //EMail Settings
    
    $mail->Username="testit0699@gmail.com";
    $mail->Password="Punjabi12345";
    $send_to = get_option( 'lead_collect_email' );
    $mail->isHTML(true);
    $mail->setFrom($_SESSION['PQL_Email'],$_SESSION['name']);
    $mail->addAddress($send_to);
    $mail->Subject = "New Lead from RainmakerAI";

    $Lead_name = $_SESSION['name'];
    $Lead_email = $_SESSION['PQL_Email'];
    $Lead_phone = $_SESSION['phone'];
    $email_body ='
    <h3><b>You have a new Lead</b></h3><br>
    
    <b>Name</b>: '.$Lead_name .'<br>
    <b>Phone</b>: '.$Lead_phone .'<br>
    <b>Email</b>: '.$Lead_email .'<br>
    <b>------------------------------------------</b><br>
    '.$lead_qualification_status.'<br>
    <b>------------------------------------------</b>

    <h4>Questions Asked</h4>
    ';
    foreach($_SESSION['questions'] as $question){
         $email_body.= $question.'<br>' ;
    }

    $email_body.="<h4>Responses by Lead</h4>";

    foreach($_SESSION['responses'] as $response){
         $email_body.= $response.'<br>' ;
    }


    $mail->Body =$email_body; 
    if($mail->send()){
        echo '<h2>Redirecting to next Page</h2>';
    }
    else{
        echo 'Aghh..!!!there is some error.. We could not send your email';
        ?><br><br><?php
        echo $mail->ErrorInfo;
    }
}

function unset_session_data(){ 
    //echo '<script>alert("Reset User Data")</script>';   
    unset($_SESSION['PQL_Email']);
    unset($_SESSION['name']);
    unset($_SESSION['responses']);
    unset($_SESSION['questions']);
}