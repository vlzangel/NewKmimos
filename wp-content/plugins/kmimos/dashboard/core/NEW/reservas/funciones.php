<?php

	function num_for($monto){
		return number_format($monto, 2, ".", ",");
	}

	function get_cupones_reserva( $reserva_id ){
		global $wpdb;
	    // Cargar cupones 
	    $cupon_sql = "SELECT items.order_item_name as name, meta.meta_value as monto  
	    FROM `wp_woocommerce_order_items` as items 
	    INNER JOIN wp_woocommerce_order_itemmeta as meta ON meta.order_item_id = items.order_item_id
	    INNER JOIN wp_posts as p ON p.ID = ".$reserva_id." and p.post_type = 'wc_booking' 
	    WHERE 
	    meta.meta_key = 'discount_amount'
	    and items.`order_id` = p.post_parent";
	    $cupones = $wpdb->get_results($cupon_sql);

	    if( !empty($cupones) ){
	        // total cupones
	        $detalle[ 'info' ] = [
	        	'promo' => [
	        		'kmimos' => [
	        			'cupones' => [],
	        			'total' => 0,
	        		],
	        		'cuidador' => [
	        			'cupones' => [],
	        			'total' => 0,
	        		],
	        	]
	        ];
	        foreach ($cupones as $cupon) {
	            if( $cupon->monto > 0 ){
	                $tipo = $wpdb->get_var("SELECT m.meta_value 
	                    FROM wp_posts as p 
	                    INNER JOIN wp_postmeta as m ON m.post_id = p.ID AND m.meta_key = 'descuento_tipo' 
	                    WHERE post_title = '".$cupon->name."' AND post_type = 'shop_coupon'");
	                $cupon_tipo = ( $tipo != '' ) ? " Tipo: ".$tipo : '' ;
	                // Saldo a favor
	                $es_saldo = false;
	                if( strpos($cupon->name, 'saldo-') !== false ){
						$detalle[ 'info' ]['saldo'] = num_for($cupon->monto);
		                $es_saldo = true;
	                }
	                // Cupones a Kmimos
	                if( ($tipo == 'compartido' ||  $tipo == 'kmimos') || ( empty($tipo) && !$es_saldo ) ) {
	                	$monto_kmimos = $cupon->monto;	                
		                if( $tipo == 'compartido' ){
			                $percent_kmimos = $wpdb->get_var("SELECT m.meta_value 
			                    FROM wp_posts as p 
			                    INNER JOIN wp_postmeta as m ON m.post_id = p.ID AND m.meta_key = 'descuento_kmimos' 
			                    WHERE post_title = '".$cupon->name."' AND post_type = 'shop_coupon'");
			                if( $cupon->monto > 0 && $percent_kmimos > 0 ){
			                    $monto_kmimos = ( $cupon->monto * $percent_kmimos ) / 100;
			                }
		                }
	                	$detalle[ 'info' ]['promo']['kmimos']['cupones'][] = $cupon->name;
	                	$detalle[ 'info' ]['promo']['kmimos']['total'] += $monto_kmimos;
	                }
	                // Cupones a cuidador
	                if( $tipo == 'compartido' ||  $tipo == 'cuidador' ){
	                	$monto_cuidador = $cupon->monto;
		                if( $tipo == 'compartido' ){
			                $percent_cuidador = $wpdb->get_var("SELECT m.meta_value 
			                    FROM wp_posts as p 
			                    INNER JOIN wp_postmeta as m ON m.post_id = p.ID AND m.meta_key = 'descuento_cuidador' 
			                    WHERE post_title = '".$cupon->name."' AND post_type = 'shop_coupon'");
			                if( $cupon->monto > 0 && $percent_cuidador > 0 ){
			                    $monto_cuidador = ( $cupon->monto * $percent_cuidador ) / 100;
			                }
			            }	
	                	$detalle[ 'info' ]['promo']['cuidador']['cupones'][] = $cupon->name;
	                	$detalle[ 'info' ]['promo']['cuidador']['total'] += $monto_kmimos;	    
	                }
	            }
	        }
	    }
	    return $detalle;
	}
?>