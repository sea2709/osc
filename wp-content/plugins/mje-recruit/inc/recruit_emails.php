<?php
/**
 * add email from version 1.2
**/
class Recruit_Emails{
	function __construct(){
		add_action('do_after_submit_offer',array( $this,'new_offer_email_to_seller') );
		add_action('mje_after_insert_post',array( $this,'new_recruit_email_to_admin') );

	}
	function new_offer_email_to_seller($result){
		et_log('new_offer_email_to_seller');
		et_log($id);
		$offer_id = $result->ID;
		$mjob_id = $result->post_parent;
		$mail  = 	MJE_Mailing::get_instance();
		$message =  "<p>Dear [display_name],</p>
                    <p>Your mJob - [link] -  posted in [blogname] has a new offer.</p>
                  	You can check more this offer  here: [here].</p>
                    <p>Sincerely,<br>[blogname]</p>";

       // $offer 		= get_post($offer_id);
       	$mjob 		= get_post($mjob_id);
        $postID 	= $mjob_id;

	   	$message 	= $mail->filter_post_placeholder($message, $postID);
	   	$post_link 	= '<a href="' . get_permalink($postID) . '" >'. __('here', 'enginethemes') .'</a>';
	   	$message 	= str_ireplace('[here]', $post_link, $message);

	   	$authorID = $mjob->post_author;
        $user = get_userdata($authorID);
        $userEmail = $user->user_email;

        $message 	= str_ireplace('[display_name]', $user->user_login, $message);

        $subject = __('There is a new offer on your mJob','enginethemes');
	   	$mail->wp_mail($userEmail, $subject, $message, array(
	            'post' => $postID
	        ));

	}
	function new_recruit_email_to_admin($result){

		if($result->post_type == 'recruit'){
			$subject = sprintf(__('A new Recruit submitted on your site', 'enginethemes'));
	        $message = "<p>Hi,</p>
                       <p>User [author] has submitted a new Recuit on your site. You could review it [here].</p>
                       <p>Regards,<br>[blogname]</p>";
	        $postID = $result->ID;
	        $mail  =MJE_Mailing::get_instance();
	        $message = $mail->filter_post_placeholder($message, $postID);
	        $post_link = '<a href="' . get_permalink($postID) . '" >'. __('here', 'enginethemes') .'</a>';
	        $message = str_ireplace('[here]', $post_link, $message);
	        // Mail to admin
	        $mail->wp_mail(get_option('admin_email'), $subject, $message, array(
	            'post' => $postID
	        ));
		}
	}
}
new Recruit_Emails();