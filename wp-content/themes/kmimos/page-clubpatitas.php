<?php
	/*
        Template Name: Club patitas
    */
    session_start();
    if( isset($_SESSION['CPF']) && $_SESSION['CPF'] == 'OK' ){
    	unset($_SESSION['CPF']);
    	header('location:'.get_home_url().'/club-patitas-felices/compartir');
    }

    $no_top_menu = false;
    $url_img = get_home_url() .'/wp-content/themes/kmimos/images/club-patitas/';
	$user = wp_get_current_user();
	
	$display_registro = '';
	$center_content = '';
	$readonly='';	

	$email = '';
	$nombre = '';
	$apellido = '';
	$usuario_titulo = '';
    
	if( isset($user->ID) && $user->ID > 0 ){
		$nombre = " ";
		$nombre .= get_user_meta( $user->ID, 'first_name', true );
		$nombre .= " ";
		$nombre .= get_user_meta( $user->ID, 'last_name', true );
		if( !empty($nombre) ){
			$nombre = ' ' .$user->user_firstname;
		}
		$email = $user->user_email;
		$apellido = $user->user_lastname ;
		$readonly = 'readonly';
	}
	$cupon = get_user_meta( $user->ID, 'club-patitas-cupon', true );
 	if( !empty($cupon) ){
		$display_registro = 'hidden';
		$center_content = 'col-md-offset-2';
		$usuario_titulo = $nombre;
	}

    wp_enqueue_style('club_style', getTema()."/css/club-patitas-felices.css", array(), '1.0.0');
    wp_enqueue_style('club_responsive', getTema()."/css/responsive/club-patitas-felices.css", array(), '1.0.0');
	wp_enqueue_script('club_script2', getTema()."/js/club-patitas-felices.js", array(), '2.0.0');

	get_header();
?>
	 
	<header class="row" style="background-image: url(<?php echo getTema().'/images/club-patitas/Kmimos-Club-de-las-patitas-felices-2.jpg'; ?>)">
		<div class="col-xm-12 col-sm-12 col-md-12">
			<?php if( !is_user_logged_in() || empty($cupon) ){ ?>
				<a href="#" data-target="#popup-iniciar-sesion" role="button" data-toggle="modal">Iniciar sesi&oacute;n</a>
				<span style="display:none; margin:0px 10px;">|</span>
				<a href="javascript:;" style="display:none; padding-right: 15px">Registrar</a>
			<?php }else{ ?>
				<a href="<?php echo get_home_url(); ?>/club-patitas-felices/creditos">Ver mis créditos</a>
				<span style="margin:0px 10px;">|</span>
	            <a href="<?php echo get_home_url(); ?>/club-patitas-felices/compartir">Obtener mi código</a>
			<?php } ?>
		</div>
		<div class="col-sm-12 col-xs-12 col-md-6 text-center pull-right">
			<img src="<?php echo getTema().'/images/club-patitas/Kmimos-Club-de-las-patitas-felices-5.png'; ?>">
			<h2>Club de las patitas felices</h2>
			<p class="subtitle">El club que te recompensa por que tus amigos reserven estadías con Kmimos</p>
		</div>
	</header>
	<div class="body-club">
		<aside class="col-xs-12 col-sm-12 col-md-7 hidden-md hidden-lg">
			<h3 class="text-center gotham-bold" style="font-size: 33px; font-weight:bold; margin:10px 0px;"><strong style="color:#0D7AD8;">¡Bienvenido al club<?php echo $usuario_titulo; ?>!</strong></h3>
		</aside>
		<aside id="sidebar" class="col-xs-12 col-sm-12 col-md-4 <?php echo $display_registro; ?>">
			<div class="text-center col-md-10 col-md-offset-1 text-center">
				<h3 class="title-secundario">¡Estás a un paso de ser parte del club!</h3>
				<form method="post" action="<?php echo get_home_url(); ?>/club-patitas-felices" id="form-registro">
					<!-- input type="hidden" name="redirect" value="1" -->
					<div class="col-md-12 col-sm-12 col-xs-12">
						<input required class="form-control" style="margin:5px 0px; border-radius: 10px;" type="text" name="nombre" placeholder="Nombre" value="<?php echo $nombre; ?>" <?php echo $readonly; ?>>
					</div>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<input required class="form-control" style="margin:5px 0px; border-radius: 10px;" type="text" name="apellido" placeholder="Apellido" value="<?php echo $apellido; ?>" <?php echo $readonly; ?>>
					</div>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<input required class="form-control" style="margin:5px 0px; border-radius: 10px;" type="email" name="email" placeholder="Direccion correo electronico" value="<?php echo $email; ?>" <?php echo $readonly; ?>>
					</div>
					 
				    <label class="terminos">
				      <input name="terminos" required type="checkbox"> <strong>Acepto los <a href="javascript:;" data-toggle="modal" data-target="#myModal">términos y condiciones</a> del club</strong>
				    </label>
					 
					<button type="submit" class="btn btn-club btn-lg btn-info">
						Genera tu c&oacute;digo aqu&iacute;
					</button>
				</form>
			</div>
		</aside>
		<section id="club-content" class="col-xs-12 col-sm-12 col-md-7 <?php echo $center_content; ?>">
			<div>
				<h3 class="hidden-xs hidden-sm text-left gotham-bold" style="font-weight:bold;"><strong style="color:#0D7AD8;">¡Bienvenido al club<?php echo $usuario_titulo; ?>!</strong></h3>

			 	<p class="text-justify">
					El club de las patitas felices te recompensa con $150 MXN para que los uses en cualquiera de nuestros servicios. Es muy sencillo, por cada vez que compartas tu código de las patitas felices, tu referido obtendrá <strong>$150 MXN para utilizarlo en su primera reserva</strong> y una vez que complete su reservación a ti se te abonarán tus $150 MXN de crédito en Kmimos. 
			 	</p>
			</div>
			<div class="body-content">

				<div class="media-body">

					<div class="media">
					  <div class="media-left">
					    <a href="#">
					      <img width="70px" class="media-object" src="<?php echo $url_img; ?>Kmimos-Club-de-las-patitas-felices-7.png">
					    </a>
					  </div>
					  <div class="media-body text-left ">
					  	<p>Inscríbete al club de manera fácil y recibe tu código único del club.</p>
					  </div>
					</div>
					<div class="media">
					  <div class="media-left">
					    <a href="#">
					      <img width="70px" class="media-object" src="<?php echo $url_img; ?>Kmimos-Club-de-las-patitas-felices-8.png">
					    </a>
					  </div>
					  <div class="media-body text-left">
					  	<p>Comparte tu código con tus amigos, familiares, conocidos, etc.. Ellos obtendrán <strong>$150 MXN para realizar su primera reserva</strong> con Kmimos
						</p>
					  </div>
					</div>
					<div class="media">
					  <div class="media-left">
					    <a href="#">
					      <img width="70px" class="media-object" src="<?php echo $url_img; ?>Kmimos-Club-de-las-patitas-felices-9.png">
					    </a>
					  </div>
					  <div class="media-body text-left">
					  	<p>Cada vez que alguien use tu código y complete una reserva con Kmimos <strong>recibirás $150 MXN</strong> en crédito para que lo uses en servicios de nuestra plataforma. ¡Lo mejor es que son <strong>totalmente acumulables!</strong></p>
					  </div>
					</div>

				</div>

			</div>
			<?php if( !is_user_logged_in() || empty($cupon) ){ ?>
				<div class="hidden-md hidden-lg">
					<a href="#" data-target="#popup-iniciar-sesion" style="color:#fff!important; cursor: pointer;" class="btn btn-club btn-info" role="button" data-toggle="modal">Iniciar sesi&oacute;n</a>
					<button id="registrar-cpf" class="btn btn-info" style="color: #fff; width: 100%; margin: 5px 0px; padding: 10px; background: #007bdc; border:1px solid #007bdc; cursor: pointer;">Registrarme</button>
				</div>
			<?php } ?>
		</section>
	</div>

<?php include_once( 'partes/CPF/login.php' ); ?>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="margin-top:120px;">
    <div class="modal-content " >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
      </div>
      <div class="modal-body" style="height: 60vh;overflow-y: auto;overflow-x: hidden;">
      	<div class="text-center">  		
	      	<img src="<?php echo getTema().'/images/club-patitas/Kmimos-Club-de-las-patitas-felices-6.png'; ?>">
	      	<h3 style="font-weight: bold">T&eacute;rminos y condiciones del club de las patitas felices</h3>
      	</div>
        <?php
			include 'terminos_HTML.php';
			$NEW_HTML_TERMINOS = "";
			$parrafos = explode("\n", $HTML_TERMINOS);
			foreach ($parrafos as $parrafo) {
				$NEW_HTML_TERMINOS .= "<p>".$parrafo."</p>";
			}
			echo $NEW_HTML_TERMINOS;
        ?>
      </div> 
    </div>
  </div>
</div>	

<script type="text/javascript">
<?php session_start();
if( !isset($_SESSION['CPF_Track']) ) {$_SESSION['CPF_Track'] = 1; ?>
	evento_google_kmimos('CPF_Home');
<?php }else{ 
	echo '// $_SESSION['CPF_Track'] = '.$_SESSION['CPF_Track'];
} ?>
</script>

<?php 
	$no_display_footer = true;
 	get_footer(); 
?>
