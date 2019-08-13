<?php
// posts_orderby
Class MJE_Feauted_Front{
	function __construct(){
		add_filter( 'mje_loop_item_css', array($this,'add_featured_css_in_lopp'), 10 , 2);
		add_filter( 'mje_home_loop_item_css', array($this,'add_featured_css_in_lopp'), 10 , 2);
		add_filter( 'mje_mjob_item_class', array($this,'add_featured_css_in_lopp'), 10 , 2 );
		add_action( 'pre_get_posts', array($this, 'mje_mjob_category'), 99 );
		add_filter('pre_query_filter_ajax_args',array($this, 'change_query_ajax_args') );

		//add_action( 'list_featured_package', array($this, 'html_list_featured_package') );
		add_shortcode( 'mjobs_featured', array($this, 'shorcode_feautured_mjobs' ));

		add_action( 'wp_footer', array($this, 'mje_featured_css') );
		add_filter( 'mje_mjob_filter_query_args', array($this, 'change_default_args_in_ajax') );
		add_filter('response_fetch_mjob_post', array($this, 'add_3_feautured_on_each_page_ajax'), 10 ,2 );


	}
	function change_default_args_in_ajax($query_args){
		$query = $_REQUEST['query'];
		if ( isset($query['is_archive_mjob_post']) && $query['is_archive_mjob_post'] == TRUE
			|| isset($query['mjob_category']) && !empty($query['mjob_category'] ) ){
			$orderby = isset($query['orderby']) ? $query['orderby'] : '';
			if(! $orderby || $orderby == 'date'){
				$query_args['meta_key'] = 'et_featured';
	            $query_args['meta_value'] = '1';
	            $query_args['meta_compare'] = '!=';
	        }
		}
		return $query_args;
	}
	function add_3_feautured_on_each_page_ajax($data, $query_args){

		if( isset($query_args['s']) )
			return $data;

		$args = array(
			'post_type' => 'mjob_post',
			'post_status' => 'publish',
			'meta_key' => 'et_featured',
			'meta_value' => '1',
			'posts_per_page' => 3,
			//'page' => $query_args['page'],
			'paged' => $query_args['paged'],
		);
		if(!empty( $query_args['tax_query'] )){
			$args['tax_query'] = $query_args['tax_query'];
		}
		//var_dump($args);
		$f_query = new WP_Query($args);
		if( $f_query->have_posts() ){
			global $ae_post_factory;
            $post_object = $ae_post_factory->get('mjob_post');
            $temp = array();
			while ( $f_query->have_posts() ) {
				$f_query->the_post();
				global $post;
				//var_dump($post);
				//$convert->mjob_class = 'mjob-item';
                $convert = $post_object->convert($post);
                $temp[] = $convert;
                //array_unshift( $data['posts'], $convert );

			}
			$t = array_merge($temp, $data['posts']);
			$data['posts'] = $t;
			//var_dump($data['posts']);
			// if( ! empty( $temp ) )
			// 	array_unshift( $data['posts'], $temp );
		}
		return $data;
	}
	function add_featured_css_in_lopp($css_class, $mjob){
		if( $mjob->et_featured ){
			$css_class[] = 'featured-item';
		}
		return $css_class;
	}

	function shorcode_feautured_mjobs($args){

		$args = array(
			'post_type' => 'mjob_post',
			'post_status' => 'publish',
			'orderby'        => 'rand',
			'meta_key'   => 'et_featured',
			'meta_value' => '1',
			'meta_compare' => 'f_compare',
			'posts_per_page'=> number_mjob_featured_show(),
		);
		$class = "row mjob-list";
		$loop_class = "col-lg-3 col-md-3 col-sm-6 col-mobile-12 featured-item ";
		if( ! is_home()  ){
			$args['posts_per_page'] = 3;
			$class = 'row mjob-list ';
			$loop_class = 'col-lg-4 col-md-4 col-sm-6 col-xs-6 col-mobile-12 item_js_handle featured-item';
		}
		if( is_tax() ){
			$term_id = get_queried_object()->term_id;;

			$args['tax_query'] = array(
				array(
					'taxonomy' => 'mjob_category',
					'field'    => 'term_id',
					'terms'    => $term_id,
				));

		}
		wp_reset_query();

		$f_query = new WP_Query($args);
		global $post_data;
		ob_start();

		if( $f_query->have_posts() ):
			global $ae_post_factory;
			$post_object = $ae_post_factory->get('mjob_post');

			if( is_home()  )
			echo  ' <ul class="'.$class.'">';

			while ( $f_query->have_posts() ) : $f_query->the_post();
				global $post;

	            $convert = $post_object->convert( $post );
	            $post_data[] = $convert;
	            echo '<li class="'.$loop_class.'">';
	           	mje_get_template( 'template/mjob-item.php', array( 'current' => $convert ) );
	            echo '</li>';

			endwhile;
			if( is_home()  )
			echo '</ul>';
		endif;
		wp_reset_query();
		return ob_get_clean();

	}
	function mje_mjob_category($query){

		if ( ! is_admin() && $query->is_main_query() ){

			if ( $query->is_tax() || is_post_type_archive('mjob_post') ) {

				$meta_compare = $query->get('meta_compare');
				if( $meta_compare != 'f_compare'){
					$query->set( 'meta_key', 'et_featured' );
					$query->set( 'meta_value', '1' );
					$query->set( 'meta_compare', '!=' );
				} else {
					$query->set( 'meta_compare', '==' );
				}
	 	 	}

	 	 	if ( $query->is_search ){
	 	 		$orderby = isset( $_GET['orderby']) ? $_GET['orderby'] : '';

	 	 		if( empty($orderby ) || $orderby == 'et_featured' ){
		 	 		$query->set( 'meta_key', 'et_featured' );
		 	 		$query->set( 'orderby', 'meta_value_num date' );

		 	 		// #1111 in search page - no ajax or in direct url search
		 	 	}
	 	 	}
	 	}

 	 	return $query;

	}
	function change_query_ajax_args($query){

		$orderby = isset( $query['orderby']) ? $query['orderby'] : '';
		if( is_search() && empty($orderby) ){
            $query['orderby'] = 'et_featured';  // #1111 in ajax for page 2.ax
            // only available in ajax
        }
        return $query;
	}

	function mje_featured_css(){
		?>
		<style type="text/css">
			.featured-item .mjob-item, .mjob-item.featured-item{
				border:0px solid #d68200;
			}
			.featured-item .mjob-item .mjob-item__title h2 a{
				color: #d68200;
			}
			.featured-item  .mjob-item .mjob-item__image > a:after,
			.mjob-item.featured-item .mjob-item__image > a:after{
			    content: '<?php echo mjob_featured_ribbon_text();?>';
			    transform: rotate(45deg);
			    position: absolute;
			    top: 19px;
			    right: -20px;
			    font-size: 11px;
			    background: #ff7800;
			    color: #fff;
			    padding: 0px 20px;
			    text-transform: uppercase;
			    width: 88px;
			    text-align: center;

			}
			.post-type-archive-mjob_post .featured-item .mjob-item .status-label{
				display: none;
			}
		</style>
		<?php
	}
}
new MJE_Feauted_Front();