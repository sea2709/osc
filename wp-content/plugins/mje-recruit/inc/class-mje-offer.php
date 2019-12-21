<?php
class MJE_Offer extends AE_Post_Custom{


	public static $instance;
	public $post_type;
    public $post_type_singular;
    public $post_type_regular;

	function __construct(){
        parent::__construct();
		$this->post_type = 'mje_offer';
          $this->meta = array(
            'et_budget','mjob_id','request_id','mjob_name',
        );
		$this->init_hook();
	}
	function init_hook(){
		add_action('init',array($this, 'register_post_type' ));
	}
	public static function get_instance()    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    function register_post_type(){
        $labels = array(
            'name'               => _x( 'Offers', 'post type general name', 'your-plugin-textdomain' ),
            'singular_name'      => _x( 'Offer', 'post type singular name', 'your-plugin-textdomain' ),
            'menu_name'          => _x( 'Offers', 'admin menu', 'your-plugin-textdomain' ),
            'name_admin_bar'     => _x( 'Offer', 'add new on admin bar', 'your-plugin-textdomain' ),
            'add_new'            => _x( 'Add New', 'Offer', 'your-plugin-textdomain' ),
            'add_new_item'       => __( 'Add New Offer', 'your-plugin-textdomain' ),
            'new_item'           => __( 'New Offer', 'your-plugin-textdomain' ),
            'edit_item'          => __( 'Edit Offer', 'your-plugin-textdomain' ),
            'view_item'          => __( 'View Offer', 'your-plugin-textdomain' ),
            'all_items'          => __( 'All Offers', 'your-plugin-textdomain' ),
            'search_items'       => __( 'Search Offers', 'your-plugin-textdomain' ),
            'parent_item_colon'  => __( 'Parent Offers:', 'your-plugin-textdomain' ),
            'not_found'          => __( 'No Offers found.', 'your-plugin-textdomain' ),
            'not_found_in_trash' => __( 'No Offers found in Trash.', 'your-plugin-textdomain' )
        );

        $args = array(
            'labels'             => $labels,
                    'description'        => __( 'Description.', 'mje_recruit' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => $this->post_type ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
        );

        register_post_type( $this->post_type, $args );

        $labels = array(
            'name'               => _x( 'Offers', 'post type general name', 'your-plugin-textdomain' ),
            'singular_name'      => _x( 'Offer', 'post type singular name', 'your-plugin-textdomain' ),
            'menu_name'          => _x( 'Offers', 'admin menu', 'your-plugin-textdomain' ),
            'name_admin_bar'     => _x( 'Offer', 'add new on admin bar', 'your-plugin-textdomain' ),
            'add_new'            => _x( 'Add New', 'Offer', 'your-plugin-textdomain' ),
            'add_new_item'       => __( 'Add New Offer', 'your-plugin-textdomain' ),
            'new_item'           => __( 'New Offer', 'your-plugin-textdomain' ),
            'edit_item'          => __( 'Edit Offer', 'your-plugin-textdomain' ),
            'view_item'          => __( 'View Offer', 'your-plugin-textdomain' ),
            'all_items'          => __( 'All Offers', 'your-plugin-textdomain' ),
            'search_items'       => __( 'Search Offers', 'your-plugin-textdomain' ),
            'parent_item_colon'  => __( 'Parent Offers:', 'your-plugin-textdomain' ),
            'not_found'          => __( 'No Offers found.', 'your-plugin-textdomain' ),
            'not_found_in_trash' => __( 'No Offers found in Trash.', 'your-plugin-textdomain' )
        );

        $args = array(
            'labels'             => $labels,
                    'description'        => __( 'Description.', 'mje_recruit' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'offer' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
        );

       // register_post_type( 'private_offer', $args );

    }
    function xconvert($post){
        if( is_numeric($post) ){
            $post = get_post($post);
        }
        foreach ($this->meta as $key) {
           $post->$key = get_post_meta($post->ID,$key, true);
        }
        $userdata = get_userdata($post->post_author);

        $mjob = get_post($post->mjob_id);
        if($mjob){
            $mjob_link = '<a target = "_blank" href="'.get_permalink($mjob->ID).' " mjob_id="'.$mjob->ID.'" > '.$mjob->post_title.'</a>';
            $post->custom_title = sprintf(__('%s used the service %s to submit an offer.','mje_recruit'),$userdata->user_login, $mjob_link);
            $post->mjob_name = $mjob->post_title;
        }
        $post->display_name = $userdata->display_name;


        return $post;

    }


	public function insert($args) {
        global $current_user, $user_ID;

        // strip tags
        foreach ($args as $key => $value) {
            if ((in_array($key, $this->meta) || in_array($key, $this->convert)) && is_string($args[$key]) && $key != 'post_content') {
                $args[$key] = strip_tags($args[$key]);
            }
        }

        // pre filter filter post args
        //$args = apply_filters('ae_pre_insert_' . $this->post_type, $args);
        if (is_wp_error($args)) return $args;

        $args = wp_parse_args($args, array(
            'post_type' => $this->post_type
        ));

        if (!isset($args['post_status'])) {
            $args['post_status'] = 'draft';
        }

        $mjob_id = (int) $args['mjob_id'];
        $mjob_title = $args['mjob_title'];
        $request_id = $args['request_id'];
        $request_author = $args['request_author'];
        $is_mjob_offered = is_mjob_offered( $mjob_id, $request_id );

        if( $is_mjob_offered ){
            return new WP_Error( 'is_offered', __( "Can not use twice times", "mje_recruit" ) );
        }
        $mjob = get_post($mjob_id);
        if( $mjob->post_author != $user_ID){
            return new WP_Error( 'mjob_empty', __( "Please select your service.", "mje_recruit" ) );
        }


        $args['post_title'] = $current_user->user_login .' use the mjob '.$mjob_title.' submit an offer';
        $args['post_content'] =  $current_user->user_login .' use the mjob '.$mjob_title.' submit an offer';
        $args['post_parent'] = $request_id;

        $result = wp_insert_post($args, true);

        /**
         * update custom field and tax
         */
        if ($result != false && !is_wp_error($result)) {
            $this->update_custom_field($result, $args);
            $args['ID'] = $result;
            $args['id'] = $result;

            $number_offers = count_offers_of_request($request_id) + 1;
            update_post_meta($request_id,'number_offers', $number_offers);
            $result = (object)$args;

            if (!empty($this->localize)) {
                foreach ($this->localize as $key => $localize) {
                    $a = array();
                    foreach ($localize['data'] as $loc) {
                        array_push($a, $result->$loc);
                    }

                    $result->$key = vsprintf($localize['text'], $a);
                }
            }
            $result->permalink = get_permalink($result->ID);
            $this->insert_notification($request_author,  $request_id, $mjob);

        }

        return $result;
    }
    function insert_notification( $request_author, $request_id, $mjob){
        global $user_ID;
        $message = "abc submit an offer on your request";
        $code = 'type=new_offer';
        $code .="&request_id=".$request_id;
        $code .= '&from='.$mjob->post_author;
        $code .="&to=".$request_author;
        $code .= '&message='.$message;
        $t = MJE_Notification_Action::get_instance()->create( $code, $request_author );
        update_post_meta($t, $user_ID . '_conversation_status', 'unread');
    }
}
    function is_mjob_offered($mjob_id, $request_id){
        global $wpdb;
        $offered =  $wpdb->get_row("
            SELECT     p.ID
                FROM        $wpdb->posts p
                INNER JOIN  $wpdb->postmeta mt
                ON p.ID = mt.post_id AND mt.meta_key = 'mjob_id'
                WHERE
                mt.meta_value = '{$mjob_id}' AND p.post_parent = $request_id AND p.post_status = 'publish' "
        );

        if ( null !== $offered ) {
            return true;
        }
        return false;

    }
    function get_list_mjob_of_request($request_id){
        global $wpdb;
        $sql = "
            SELECT     mt.meta_value
            FROM        $wpdb->posts p
            INNER JOIN  $wpdb->postmeta mt
            ON p.ID = mt.post_id AND mt.meta_key = 'mjob_id'
            WHERE
            p.post_parent = $request_id ";
        $ids =  $wpdb->get_results($sql, OBJECT);
        if($ids!== null){
            $temp = array();
            foreach ($ids as $key => $value) {
                $temp[] = (int) $value->meta_value;
            }
            return $temp;

        }

        return $ids;
    }
    function mOffer_disabled($id,$ids){
        if(in_array($id, $ids)){
            echo 'disabled';
            return ;
        }

    }
    function mje_submit_offer_form(){
        global $user_ID, $post;
        $status = $post->post_status;
        ?>

            <?php
            if($user_ID != $post->post_author &&  $status == 'publish' ){
                $mjob_ids = get_list_mjob_of_request($post->ID);?>
                 <div class="mjob-single-content box-shadow">
                    <div class="mjob-single-review mjob-single-block pad-lr-30">
                    <form class="js-submit-offer frm-submit-offer">
                        <div class="full form-group">
                            &nbsp;
                        </div>
                         <div class="full form-group">
                            <label><?php _e('SUBMIT YOUR OFFER','mje_recruit');?></label>
                            <p><?php  printf(__("Select the job you want to offer the user to purchase. To send a related offer, you need to <a href='%s'>'Post a service'</a> first, then user can view your order and make a custom order.","mje_recruit"),   et_get_page_link('post-service') );?></p>
                        </div>
                        <?php if( is_user_logged_in() ){ ?>

                                <input type="hidden" name="request_id" class="input-item" value="<?php echo $post->ID;?>" />
                                <input type="hidden" name="request_author" class="input-item" value="<?php echo $post->post_author;?>" />
                                <?php
                                $args = array(
                                    'post_type' => 'mjob_post',
                                    'post_status' =>'publish',
                                    'author' => $user_ID,
                                );
                                $mjobs = new WP_Query($args);
                                if($mjobs->have_posts() ){ ?>
                                    <div class="full form-group">
                                        <select class="form-control required Offer-id-row" required name="mjob_id">
                                            <option><?php _e('Choose a your service','mje_recruit');?></option>
                                            <?php while($mjobs->have_posts() ){
                                            $mjobs->the_post();?>
                                            <option value="<?php the_ID();?>" <?php mOffer_disabled(get_the_ID(),$mjob_ids);?> ><?php the_title(); ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                                    <div class=" fullform-group">
                                        <button class="btn-order waves-effect waves-light btn-submit btn-save"><?php _e('Submit','mje_recruit');?></button>
                                         <a class="btn-post-service" target="blank" href="<?php echo et_get_page_link('post-service'); ?>"><?php _e('Post a job', 'mje_recruit');?>
                                            <div class="plus-circle"><i class="fa fa-plus"></i></div>
                                        </a>
                                    </div>
                                    <?php

                                } else { ?>
                                    <a class="btn-post-service" target="blank" href="<?php echo et_get_page_link('post-service'); ?>"><?php _e('Post a job', 'mje_recruit');?>
                                    <div class="plus-circle"><i class="fa fa-plus"></i></div>
                                    </a>
                                <p>&nbsp;</p>
                                <?php }
                                wp_reset_query();

                                ?>


                        <?php } else { ?>
                           <p><?php _e('Please <a href="#" class="signin-link open-signin-modal">Signin</a> to submit an offer.','mje_recruit');?></p>
                        <?php } ?>
                    </form>
                </div>
            </div>
               <?php
        } else{
            if($status == 'archive'){ ?>
                <div class="mjob-single-content box-shadow">
                    <div class="mjob-single-review mjob-single-block pad-lr-30">

                        <p>&nbsp;</p>
                        <?php _e('<h4>This job is archived.</h4>','mje_recruit');?>
                    </div>
                </div>
            <?php
            }
        }
        ?>

        <?php
    }

//new MJE_Offer();


?>