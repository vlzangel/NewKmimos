<?php $_wlabel_user->LogOut_Html(); ?>

<div id="loading">
	<table>
		<tr>
			<td>
				<img src="<?= plugin_dir_url( __DIR__ ); ?>includes/img/cargando.gif" />
			</td>
		</tr>
	</table>
</div>

<div class="section">
    <?php
        include_once(dirname(__FILE__).'/content/menu.php');
        include_once(dirname(__FILE__).'/content/modules.php');
    ?>
</div>
<?= $_wlabel->Css(); ?>


