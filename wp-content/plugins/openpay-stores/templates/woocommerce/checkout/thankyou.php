<?php
/**
 * Thankyou page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


if ( $order ) : ?>

	<?php if ( $order->has_status( 'failed' ) ) : ?>

		<p><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'woocommerce' ); ?></p>

		<p><?php
			if ( is_user_logged_in() )
				_e( 'Please attempt your purchase again or go to your account page.', 'woocommerce' );
			else
				_e( 'Please attempt your purchase again.', 'woocommerce' );
		?></p>

		<p>
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
			<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My Account', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</p>

	<?php else : ?>
<!-- 	<style>
			.vlz_modal{
				position: fixed;
				top: 0px;
				left: 0px;
				width: 100%;
				height: 100%;
				display: table;
				z-index: 10000;
				background: rgba(0, 0, 0, 0.8);
				vertical-align: middle !important;
				display: none;
			}

			.vlz_modal_interno{
				display: table-cell;
				text-align: center;
				vertical-align: middle !important;
			}

			.vlz_modal_ventana{
			    position: relative;
			    display: inline-block;
			    width: 80%;
			    text-align: left;
			    box-shadow: 0px 0px 4px #FFF;
		        border-radius: 5px;
		        z-index: 1000;
			}

			.vlz_modal_titulo{
			    background: #FFF;
			    padding: 15px 10px;
			    font-size: 18px;
			    color: #52c8b6;
			    font-weight: 600;
			    border-radius: 5px 5px 0px 0px;
			}

			.vlz_modal_contenido{
			    background: #FFF;
			    height: 450px;
			    box-sizing: border-box;
			    padding: 5px 15px;
			    border-top: solid 1px #d6d6d6;
			    border-bottom: solid 1px #d6d6d6;
			    overflow: auto;
			    text-align: justify;
			}

			.vlz_modal_pie{
	    		background: #FFF;
			    padding: 15px 10px;
			    border-radius: 0px 0px 5px 5px;
			}

			.vlz_modal_fondo{
				position: fixed;
				top: 0px;
				left: 0px;
				width: 100%;
				height: 100%;
		        z-index: 500;
			}
			.vlz_boton_siguiente{
			    padding: 10px 50px;
			    background-color: #a8d8c9;
			    display: inline-block;
			    font-size: 16px;
			    border: solid 1px #2ca683;
			    border-radius: 3px;
			    float: right;
			    cursor: pointer;
			} 
			@media screen and (max-width: 750px){
				.vlz_modal_ventana{
					width: 90% !important;
				}
			}
	</style> -->
			<!-- Modal "Precios de Hosedajes"-->
							 
			<!-- <div id="jj_modal_finalizar_compra" class="vlz_modal">

				<div class="vlz_modal_interno">

					<div class='vlz_modal_fondo' onclick="jQuery('#jj_modal_finalizar_compra').css('display', 'none');"></div>

					<div class="vlz_modal_ventana jj_modal_ventana"S>

						<div class="vlz_modal_titulo">Finalizar Compra</div>

						<div class="vlz_modal_contenido" style="height: auto;">
								<p align="justify">
									Recuerda que deberás pagar el monto total de tu reservación en efectivo al momento de entregar tu peludo(s) al cuidador. El monto total a pagar lo encontrarás en el renglón "Total del servicio".
								</p>
						</div>
						<div class="vlz_modal_pie" style="border-radius: 0px 0px 5px 5px!important; height: 70px;">

							<input type='button' style="text-align: center;" class="vlz_boton_siguiente" value='Cerrar' onclick="jQuery('#jj_modal_finalizar_compra').css('display', 'none');" />

						</div>
					</div>
				</div>
			</div> -->
			<!-- /Modal "Precios de Hosedajes"-->

		<h1><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); ?></h1>

		<ul class="order_details">
			<li class="order">
				<?php _e( 'Order Number:', 'woocommerce' ); ?>
				<strong><?php echo $order->get_order_number(); ?></strong>
			</li>
			<li class="date">
				<?php _e( 'Date:', 'woocommerce' ); ?>
				<strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong>
			</li>
			<li class="total">
				<?php _e( 'Total:', 'woocommerce' ); ?>
				<strong><?php echo $order->get_formatted_order_total(); ?></strong>
			</li>
			<?php if ( $order->payment_method_title ) : ?>
			<li class="method">
				<?php _e( 'Payment Method:', 'woocommerce' ); ?>
				<strong><?php echo $order->payment_method_title; ?></strong>
			</li>
			<?php endif; ?>
		</ul>
                
                <?php if(WC()->session->__isset('pdf_url')): ?>
                    <div class="clear"></div>
                    <br>                    
                    <iframe id="pdf" src="<?php echo WC()->session->get( 'pdf_url' ) ?>" style="width:100%; height:950px;" frameborder="0"></iframe>
                <?php endif; ?>    
                
                <div class="clear"></div>

	<?php endif; ?>
                
        <?php do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id ); ?>
	<?php do_action( 'woocommerce_thankyou', $order->id ); ?>

<?php else : ?>

	<p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>

<?php endif; ?>

 
<!-- <script>
	//MODAL PRECIOS SUGERIDOS-----------------------------------------------------------------------------------------------

setTimeout(function(){
	jQuery('#jj_modal_finalizar_compra').css('display', 'table');

}, 1500);
		function ocultarModal(){
			jQuery('#jj_modal_finalizar_compra').fadeOut();
			jQuery('#jj_modal_finalizar_compra').css('display', 'none');
			modalOpend= true;
		}
	//MODAL PRECIOS SUGERIDOS------------------------------------------------------------------------------------------------
</script> -->




