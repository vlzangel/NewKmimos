<?php
	$no_login = '<a href="#" data-target="#popup-iniciar-sesion" >Valorar</a>';
	if( is_user_logged_in() ){
		$no_login = '<a href="'.get_home_url().'/perfil-usuario/historial/" >Valorar</a>';
	}
?>

<div class="modal_info_comentar">
	<div>
		¿Quieres hacer una valoración de éste cuidador y completar los huesitos para Cuidado, Puntualidad, Limpieza y Confianza?
	</div>
	<div>
		Debes hacer lo siguiente:
	</div>
	<div>
		<ol>
			<li>P&iacute;cale al bot&oacute;n VALORAR mostrado abajo.</li>
			<?php if( !is_user_logged_in() ){ ?>
				<li>Inicia sesi&oacute;n en Kmimos y dir&iacute;gete a tu perfil.</li>
			<?php } ?>
			<li>Ub&iacute;cate en el Historial de Reservas y haz click en los huesitos para valorar al cuidador.</li>
		</ol>
	</div>
	<div class="botones_container">
		<?php echo $no_login; ?>
		<a id="comentar">Comentar</a>
	</div>
</div>

<div class="modal_comentario_enviado" style="display: none">
	<div>
		Su comentario ha sido enviado, ser&aacute; publicado una vez sea aprobado por kmimos.
	</div>
</div>



<div class="comments" style="display: none;">
	<h3 id="reply-title" class="comment-reply-title">
		Deja un comentario
	</h3>
	<form action="/" onsubmit="return false;" method="post" id="commentform" class="comment-form golden-forms">
		<p class="comment-notes">
			<span id="email-notes">Tu dirección de correo electrónico no será publicada.</span>
			Los campos necesarios están marcados <span class="required">*</span>
		</p>

		<textarea id="comment" name="comment" class="textarea" placeholder="Tu comentario" required></textarea>
		<input type="text" name="author" id="author" class="input" placeholder="Nombre*" aria-required="true" required>
		<input type="email" name="email" id="email" class="input" placeholder="Dirección de correo*" aria-required="true" required>
		<div class="g-recaptcha" data-sitekey="6LeQPysUAAAAAKKvSp_e-dXSj9cUK2izOe9vGnfC"></div>

		<p class="form-submit">
			<input name="submit" type="submit" id="submit" class="submit button km-btn-primary" value="Publicar comentario">
			<input type="hidden" name="comment_post_ID" value="<?php echo get_the_ID(); ?>" id="comment_post_ID">
			<input type="hidden" name="comment_parent" id="comment_parent" value="0">
		</p>
	</form>
</div>
    