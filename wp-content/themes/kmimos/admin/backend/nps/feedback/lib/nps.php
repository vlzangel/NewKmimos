<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );
date_default_timezone_set('America/Mexico_City');

$nps = new NPS();

class NPS {
	
	public $db;
	
	public function NPS(){
		$this->raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))))));
		if( !isset($db) || is_string( $db ) ){
			include($this->raiz.'/vlz_config.php');
			if( !class_exists('db') ){
				include($this->raiz.'/wp-content/themes/kmimos/procesos/funciones/db.php');
			}
		    $db = new db( new mysqli($host, $user, $pass, $db) );
		}
		$this->db = $db;
	}

	public function get_estatus( $index='', $type="" ){
		$estatus = [
	        [
        		'html' => '<div class="table-alert alert-default">Pendiente</div>',
        		'name' => 'Pendiente',
	    	],
	        [
	        	'html'=>'<div class="table-alert alert-success">Completado</div>',
	        	'name'=>'Completado',
	        ],
	        [
	        	'html'=>'<div class="table-alert alert-danger">Incompleto</div>',
	        	'name'=>'Incompleto',
	        ],
		];

		$item = ( array_key_exists($index, $estatus) )? $estatus[$index] : [];
		// if( !empty($type) ){
		// 	$resultado = $item[ $type ];
		// }else{
		// 	$resultado = $item;
		// }

		return $estatus;
	}

	public function get_preguntas( $desde, $hasta ){
		$where = "";
		if( !empty($desde) && !empty($hasta) ){
			$where = " where fecha_creacion >= '{$desde} 00:00:00' and fecha_creacion <= '{$hasta} 23:59:59' ";
		}
		$sql = "SELECT * FROM nps_preguntas {$where} order by id desc";
		return $this->db->get_results($sql);
	}

	public function get_encuesta_byId( $id=0 ){
		$sql = "SELECT * FROM nps_respuestas WHERE pregunta = {$id}";
		return $this->db->get_results($sql);
	}

	public function get_comentarios_byCode( $id=0 ){
		$sql = "SELECT * FROM nps_comentario WHERE code = '{$id}' ORDER BY fecha asc";
		return $this->db->get_results($sql);
	}

	public function get_pregunta_byId( $id=0 ){
		$sql = "SELECT * FROM nps_preguntas WHERE id = {$id}";
		return $this->db->get_row($sql);
	}

	public function feedback_byId( $id ){
		return $this->db->get_results( 'SELECT * FROM nps_respuestas WHERE pregunta = '.$id );
	}

	public function feedback_group_ptos( $id ){
		return $this->db->get_results( "SELECT puntos, count(id) as cant FROM nps_respuestas WHERE pregunta = $id GROUP BY puntos" );
	}

	public function get_score_nps_detalle( $pregunta_id ){
		$feedbacks = $this->feedback_byId( $pregunta_id );
		$score_group = [
            'pasivos' => [ 'ptos' => 0, 'porcentaje' => 0 ], 
            'promoters' => [ 'ptos' => 0, 'porcentaje' => 0 ], 
            'detractores' => [ 'ptos' => 0, 'porcentaje' => 0 ], 
            'total_rows' => 0,
            'score_nps' => 0,
        ];
       
        if( $feedbacks != false ){
            foreach ( $feedbacks as $row ) {
                $score_group['total_rows']++;
                if( $row->puntos > 0 && $row->puntos <= 6 ){
                    $score_group['detractores']['ptos']++;
                }else if( $row->puntos == 7 || $row->puntos == 8 ){
                    $score_group['pasivos']['ptos']++;
                }else if( $row->puntos == 9 || $row->puntos == 10 ){
                    $score_group['promoters']['ptos']++;
                }
            }
            if( $score_group['detractores']['ptos'] > 0 ){
                $score_group['detractores']['porcentaje'] =   number_format(($score_group['detractores']['ptos'] * 100) / $score_group['total_rows'] ,2);
            }
            if( $score_group['pasivos']['ptos'] > 0 ){
                $score_group['pasivos']['porcentaje'] =   number_format(( $score_group['pasivos']['ptos'] * 100 ) / $score_group['total_rows'] ,2);
            }
            if( $score_group['promoters']['ptos'] > 0 ){
                $score_group['promoters']['porcentaje'] =   number_format(( $score_group['promoters']['ptos'] * 100 ) / $score_group['total_rows'] ,2);
            }

            $score_group['score_nps'] = number_format( $score_group['promoters']['porcentaje'] - $score_group['detractores']['porcentaje'], 2 );
        }

        return $score_group;
	}

	public function get_score_nps_global(){
		$score_group = [
            'pasivos' => [ 'ptos' => 0, 'porcentaje' => 0 ], 
            'promoters' => [ 'ptos' => 0, 'porcentaje' => 0 ], 
            'detractores' => [ 'ptos' => 0, 'porcentaje' => 0 ], 
            'total_rows' => 0,
            'score_nps' => 0,
        ];
		
		$feedbacks = $this->db->get_results( "SELECT tipo, count(id) as cant FROM nps_respuestas GROUP BY tipo" );
		if( $feedbacks != false ){
			foreach ($feedbacks as $item) {
				$score_group[ strtolower($item->tipo) ]['ptos'] = $item->cant;
				$score_group['total_rows'] += $item->cant;
			}

			if( $score_group['detractores']['ptos'] > 0 ){
	            $score_group['detractores']['porcentaje'] = number_format( ($score_group['detractores']['ptos'] * 100) / $score_group['total_rows'], 2 ) ;
	        }
	        if( $score_group['pasivos']['ptos'] > 0 ){
	            $score_group['pasivos']['porcentaje'] = number_format( ( $score_group['pasivos']['ptos'] * 100 ) / $score_group['total_rows'] , 2 );
	        }
	        if( $score_group['promoters']['ptos'] > 0 ){
	            $score_group['promoters']['porcentaje'] = number_format(( $score_group['promoters']['ptos'] * 100 ) / $score_group['total_rows'] , 2 );
	        }

	        $score_group['score_nps'] = number_format( $score_group['promoters']['porcentaje'] - $score_group['detractores']['porcentaje'], 2 );
	    }
        return $score_group;
	}
}
