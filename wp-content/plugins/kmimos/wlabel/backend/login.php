<form class="login" autocomplete="off" onsubmit="WhiteLabel_form_login(this); return false;" data-validate="<?php echo plugin_dir_url( __FILE__ ); ?>user/login.php">
	<div>
		<div class="logo_conteiner">
			<img src="<?php echo plugin_dir_url( dirname(__FILE__) ); ?>includes/img/logo.png" />
		</div>
	    <input type="text" name="user" value="" placeholder="User" autocomplete="off" />
	    <input type="password" name="pass" value="" placeholder="Password" autocomplete="off" />
	    <input type="submit" value="INGRESAR"/>
	    <div class="message"></div>
	</div>
</form>
