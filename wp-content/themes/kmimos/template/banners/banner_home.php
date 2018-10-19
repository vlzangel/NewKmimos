<?php extract($_POST); ?>
<div>
	<div id="PageSubscribe_NEW">
		<i class="exit fa fa-times" aria-hidden="true" onclick="SubscribePopUp_Close('#message.Msubscribe')"></i>
		<div class="section_1">
			<h2>Obtén tu 15% de descuento</h2>
			<p>&#161;solo déjanos tu correo!</p>
			<p>&#161;SUSCR&Iacute;BETE! y recibe el Newsletter con nuestras PROMOCIONES, TIPS DE CUIDADOS PARA MASCOTAS, etc.!</p>
		</div>
		<div class="section_2">
			<?= $FORM ?>
			<input type="hidden" id="wlabelSubscribe" value="<?= $SECCION ?>" >
			<div class="img_banner" style="background-image: url(<?= $HOME; ?>/recursos/img/BANNERS/Popup_Home/RESPONSIVE/Happy-dog.jpg)"></div>
		</div>
		<div class="section_3">
			*aplica para clientes nuevos, válido para un sólo uso
		</div>
	</div>
</div>