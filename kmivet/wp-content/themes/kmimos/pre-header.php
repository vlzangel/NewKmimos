<?php
	if( isset($_GET['init'])){
		global $wpdb;
		$sql = "
			SELECT 
				u.user_email AS mail,
				m.meta_value AS clave
			FROM 
				wp_users AS u
			INNER JOIN wp_usermeta AS m ON (m.user_id = u.ID)
			WHERE 
				u.ID = '{$_GET['init']}' AND 
				m.meta_key = 'user_pass'
			GROUP BY 
				u.ID
		";
		$data = $wpdb->get_row($sql);
		$info = array();
	    $info['user_login']     = sanitize_user($data->mail, true);
	    $info['user_password']  = sanitize_text_field($data->clave);
	    $user_signon = wp_signon( $info, true );
	    wp_set_auth_cookie($user_signon->ID);
	    header("location: ".get_home_url()."/perfil-usuario/?ua=profile");
	}

	if( isset($_GET['i'])){
		global $current_user;
        $_SESSION['id_admin'] = $current_user->ID;
        $_SESSION['admin_sub_login'] = "YES";
		global $wpdb;
		$sql = "SELECT ID FROM wp_users WHERE md5(ID) = '{$_GET['i']}'";
		$data = $wpdb->get_row($sql);
	    $user_id = $data->ID;
		$user = get_user_by( 'id', $user_id ); 
		if( $user ) {
		    wp_set_current_user( $user_id, $user->user_login );
		    wp_set_auth_cookie( $user_id );
		}
		if( isset($_GET['admin']) ){
	        $_SESSION['id_admin'] 		 = "";
	        $_SESSION['admin_sub_login'] = "";
	   		header("location: ".get_home_url()."/wp-admin/admin.php?page=bp_clientes");
		}else{
	   		header("location: ".get_home_url()."/perfil-usuario/?ua=profile");
		}
	}

	$is_iOS = false;
	if (isset($_SERVER['HTTP_USER_AGENT']) ){
		$iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
		$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
		$iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
		$webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");
		if( $iPod || $iPhone || $iPad || $webOS){
			$is_iOS = true;
		}
	}

	if( $_SESSION['wlabel'] == "quitar" ){
		unset($_SESSION['wlabel']);
	}

	$_SESSION['INFO_USER_AGENT'] = $info;
	function INFO_USER_AGENT()
	{
		$browser=array("IE","OPERA","MOZILLA","NETSCAPE","FIREFOX","SAFARI","CHROME");
		$os=array("WIN","MAC","LINUX");
	  
		$info['browser'] = "OTHER";
		$info['os'] = "OTHER";

		$info['ip'] = $_SERVER['REMOTE_ADDR'];

		foreach($browser as $parent)
		{
			$s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent);
			$f = $s + strlen($parent);
			$version = substr($_SERVER['HTTP_USER_AGENT'], $f, 15);
			$version = preg_replace('/[^0-9,.]/','',$version);
			if ($s)
			{
				$info['browser'] = $parent;
				$info['version'] = $version;
			}
		}
	  
		foreach($os as $val)
		{
			if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']),$val)!==false)
				$info['os'] = $val;
		}
	  
		return $info;
	}