<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}

// Modificacion Ángel Veloz
$DS = kmimos_session();
if( !$DS ){
    $ver_formulario = " style='display: block;' ";
    if( isset($DS["no_pagar"]) ){
    	$ver_formulario = " style='display: none;' ";
    }
}else{
	if( WC()->cart->total-WC()->cart->tax_total == 0 ){
    	$ver_formulario = " style='display: none;' ";
    }
} ?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
	<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>
		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
        <div id="customer_details" <?php echo $ver_formulario; ?> >
			<div class="col-1">
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
			</div>
		</div>
		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
		<p style="display: none;"><strong>(<span style="color: red">*</span>)  Campos obligatorios</strong></p>
	<?php endif; ?>

	<h3 id="order_review_heading" style="font-size: 20px; font-weight: 600;"><?php _e( 'Datos de la reserva', 'woocommerce' ); ?></h3>

	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

	<div id="order_review" class="woocommerce-checkout-review-order">
		<?php 
			do_action( 'woocommerce_checkout_order_review' );
		?>
	</div>
	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

<script>

</script>

<style type="text/css">
	tbody .product-total,
	.cart-subtotal td,
	.cart-discount td,
	.order-total td,
	.order-paid td,
	.vlz_totales td,
	.order-remaining td
	{
		text-align: right;
	}
	#add_payment_method #payment ul.payment_methods, .woocommerce-checkout #payment ul.payment_methods>li>label {
	    color: #54c8a7;
	    font-size: large;
	    font-weight: bold;
	    text-shadow: 3px 2px 12px rgba(255, 255, 255, 0.57);
	}
	.wc-terms-and-conditions a{
		font-size: 15px;
	    color: #54c8a7;
	    font-weight: 600;
	}

	.woocommerce form input, .woocommerce form select {
	    padding: 8px 10px;
	    outline: none !important;
	    border-bottom: solid 1px #CCC !important;
	}

	.woocommerce td.product-name dl.variation dd {
	    padding: 0px;
	    margin: 0px;
	}
	.woocommerce td.product-name dl.variation dd p:last-child {
	    margin-bottom: 0;
	    margin: 0px;
	}

	.woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt {
	    background-color: #59c9a8 !important;
	    color: #fff;
	    -webkit-font-smoothing: antialiased;
	    font-weight: 400;
	}

	@media (max-width: 592px){
		#add_payment_method #payment ul.payment_methods, .woocommerce-checkout #payment ul.payment_methods>li>label {
			font-size: x-small;
		}

	}
</style>