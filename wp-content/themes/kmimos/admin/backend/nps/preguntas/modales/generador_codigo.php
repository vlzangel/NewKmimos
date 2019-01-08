<?php
	include_once(dirname(__DIR__).'/lib/nps.php');

	$pregunta_id = 0;
	$msg_display = 'block';
	$content_display = 'none';
	if( isset($_POST['ID']) && $_POST['ID'] > 0 ){
		$msg_display = 'none';
		$content_display = 'block';

		$encuesta = $nps->get_pregunta_byId( $_POST['ID'] );
		$pregunta_id = $_POST['ID'];

		$home = $nps->db->get_var("SELECT option_value FROM wp_options WHERE option_name = 'siteurl'");
		$link = $home.'feedback/?o='.md5($pregunta_id).'&t=external&e=[Email del cliente]';

		$link_code = '<a style="display: inline-block;padding: 6px 12px;margin-top: 10px;margin-bottom: 10px;font-size: 14px;font-weight: 400;line-height: 1.42857143;text-align: center;white-space: nowrap;vertical-align: middle;-ms-touch-action: manipulation;touch-action: manipulation;cursor: pointer;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;background-image: none;border: 1px solid transparent;border-radius: 4px; color: #fff;background-color: #337ab7;border-color: #2e6da4;" href="'.$link.'">Feedback</a>';


		$link_opciones = '';
		for ($i=1; $i <= 10; $i++) { 	
			$link_opciones .= '<a href="'.$link.'&v='.$i.'"><div class="col-item">'.$i.'</div></a>';
		}

		$html_code = '<style>.titulo{ margin-top: 15px; margin-bottom: 15px; font-size:25px!important; font-weight:bold;text-align:center; } .col-item { display: inline-block; border: 1px solid #ccc; border-radius: 5px; text-align: center; width: 8%; padding: 10px; margin:2px;} 
			@media screen and (max-width: 650px) {
				.col-item { width: 100%; }
				.tag-nivel{ display: none;}
			}
			</style>
			<div style="border: 3px solid #ccc; border-radius: 10px; padding:10px; margin-top:20px;"><h1 class="titulo">'.utf8_encode($encuesta->pregunta).'</h1><div style="width: 100%; padding: 10px; "> 
				'.$link_opciones.'
			</div>
			<div class="tag-nivel">
				<div style="display:inline-block;width:30%;text-align:left;">Nada probable</div>
				<div style="display:inline-block;width:60%;text-align:right;">Extremadamente probable</div>
			</div>
			</div>';
	}
?>


<article class="input_container">

	<article style="padding:10px; border-radius: 5px; background:#f0ad4e; color:#ccc; display:<?php echo $msg_display;?>;">
		No existe pregunta registrada
	</article>

	<article id="codigo_campana" style="display:<?php echo $content_display; ?>">

		<h1><strong>NOMBRE CAMPA&Ntilde;A: <?php echo strtoupper($encuesta->titulo); ?></strong></h1>
		

		<p>Copia y pega el c&oacute;digo de cualquiera de las opciones en la plantilla del correo</p>

		<div>
			<p><strong>Opci&oacute;n #1:</strong> Link externo a la encuesta</p>
			<!-- Nav tabs -->
			<ul class="nav nav-tabs" group="tabpanel1">
				<li class="active"><a href="#link_codigo" aria-controls="link_codigo" group="tabpanel1" data-toggle="tab">C&oacute;digo</a></li>
				<li><a href="#link_preview" aria-controls="link_preview" group="tabpanel1" data-toggle="tab">Vista previa</a></li>
			</ul>
			<!-- Tab panes -->
			<div class="tab-content">
				<div group="tabpanel1" class="tab-pane active" id="link_codigo">
					<textarea row="8" col="10" style="text-align:left;height:75px;width: 100%;color:#000;margin-top:10px;" class="disabled well"><?php echo $link_code; ?></textarea>
				</div>
				<div group="tabpanel1" class="tab-pane text-center" id="link_preview">
					<?php echo $link_code; ?>
				</div>
			</div>
			<div style="margin-top: 5px;font-size: 12px;font-style: italic;"><small><strong>Enlace:</strong> <?php echo $link; ?></small></div>
		</div>

		<div>
			<p><strong>Opci&oacute;n #2:</strong> HTML</p>
			<!-- Nav tabs -->
			<ul class="nav nav-tabs" group="tabpanel2">
				<li class="active" role="presentation"><a href="#html_link" group="tabpanel2" aria-controls="profile" role="tab" data-toggle="tab">C&oacute;digo</a></li>
				<li role="presentation" ><a href="#html_preview" group="tabpanel2" aria-controls="home" role="tab" data-toggle="tab">Vista previa</a></li>
			</ul>
			<!-- Tab panes -->
			<div class="tab-content">
				<div role="tabpanel" group="tabpanel2" class="tab-pane active" id="html_link">
					<textarea row="8" col="10" style="text-align:left;height:75px;width: 100%;color:#000;margin-top:10px;" class="disabled well"><?php echo $html_code; ?></textarea>
				</div>
				<div role="tabpanel" group="tabpanel2" id="html_preview" class="tab-pane text-center">
					<?php echo $html_code; ?>
				</div>
			</div>
		</div>	
	</article>

</article>