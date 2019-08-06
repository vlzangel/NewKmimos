<?php
	function init_page($modulo){
		echo '<script> 
			var MODULO_ACTUAL = "'.$modulo.'"; 
			var ADMIN_AJAX = "'.admin_url('admin-ajax.php').'"; 
		</script>';
		include_once(__DIR__.'/importador.php');
        include_once(__DIR__.'/'.$modulo.'/page.php');
        include_once(dirname(__DIR__).'/admin_2/recursos/modal.php');
	}

	add_action ('admin_menu', function () {
		global $MODULOS_ADMIN_2;

        $parents = [];
        $no_parents = [];
        foreach ($MODULOS_ADMIN_2 as $key => $value) {
            if( $value["parent"] == "" ){
                $parents[] = $value;
            }else{
                $no_parents[ $value["level"] ] = $value;
            }
        }

		foreach($parents as $opcion){
            add_menu_page(
                $opcion['title'],
                $opcion['short-title'],
                $opcion['access'],
                "vlz-bootstrap-".$opcion['slug'],
                $opcion['modulo'],
                $opcion['icon'],
                $opcion['position']
            );
        }

        ksort($no_parents);

        foreach($no_parents as $opcion){
            add_submenu_page(
                "vlz-bootstrap-".$opcion['parent'],
                $opcion['title'],
                $opcion['short-title'],
                $opcion['access'],
                $opcion['slug'],
                $opcion['modulo']
            );
        }
	});
?>