<?php
if( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function add_extra_fee_to_total($total, $subtotal){
	$extra_fee_fixed = ae_get_option('extra_fee_fixed') ? (int) ae_get_option('extra_fee_fixed'): 0;
    $extra_percent= ae_get_option('extra_fee_percentage') ? (int) ae_get_option('extra_fee_percentage'): 0;
    if($extra_fee_fixed > 0 ){
    	$total = $total + $extra_fee_fixed;
    }
    if($extra_percent > 0 ){
    	$fee = $subtotal*$extra_percent/100;
    	$total = $total + $fee;
    }
    return $total;
}
add_filter('mje_get_total_mjob_order', 'add_extra_fee_to_total', 10 ,2);
function mje_ef_get_option( $name, $default = '' ) {
    return ae_get_option( 'mje_ef_' . $name, $default );
}

function mje_ef_update_option( $name, $value ) {
    ae_update_option( 'mje_ef_' . $name, $value);
}

function mje_ef_get_extra_fees_percentage() {
    return mje_ef_get_option( 'extra_fee_percentage', 10 );
}
function mje_show_extra_fee($subtotal){

    $extra_fee_fixed = ae_get_option('extra_fee_fixed') ? (int) ae_get_option('extra_fee_fixed'): 0;
    $extra_percent= ae_get_option('extra_fee_percentage') ? (int) ae_get_option('extra_fee_percentage'): 0;
    if( $extra_percent  > 0 ){ ?>

		<div class="row">
		    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
	            <p style = "margin-bottom: 20px;">
	            	<?php
		            	$fee = $subtotal*$extra_percent/100;
		            	$percent_label = ae_get_option('extra_fee_percent_label');
		            	echo $percent_label;

	            	?>
	            </p>
		    </div>
		    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 float-right">
		        <span class="price extra-fee-percent "><?php echo mje_format_price($fee ) ?></span> <br>
		    </div>
		</div>
		<?php
	}

	    if( $extra_fee_fixed > 0 ){
	    $fee = $subtotal*$extra_percent/100;
	    ?>
	    	<div class="row">
			    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
		            <p style = "margin-bottom: 20px;">
		            	<?php

		            	$fixed_label = ae_get_option('extra_fee_fixed_label');
			            echo $fixed_label;

		            	?>
		            </p>
			    </div>

			    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 float-right">
			            <span class="price extra-fee-fixed"><?php echo mje_format_price($extra_fee_fixed) ?></span> <br>
			    </div>
		   </div>
	    <?php } ?>
   <?php }
add_action('hook_mje_extra_fee','mje_show_extra_fee');