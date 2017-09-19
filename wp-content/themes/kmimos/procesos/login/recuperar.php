<?php
define('WP_USE_THEMES', false);
//include_once(ABSPATH."vlz_config.php");
$config = dirname(__DIR__,5)."/wp-config.php";
if(file_exists($config)){
    include_once($config);
}


add_filter( 'wp_mail_content_type','recover_set_content_type' );
function recover_set_content_type(){
    return "text/html";
}


$email = $_POST['email'];
$conn = new mysqli($host, $user, $pass, $db);
$message = '';


$user_data="";
if(empty($email)){
    echo 'email Vacio';
    exit();

}else if(strpos($email,'@')){
    $user_data = get_user_by( 'email', trim($email));

    if(empty($user_data)){
        echo 'email no existe';
        exit();
    }

} else {
    $login = trim($email);
    $user_data = get_user_by('login', $login);

    if(empty($user_data)){
        echo 'usuario no existe';
        exit();
    }

}


if (!$user_data){
    echo 'error en datos';
    exit();
}


// redefining user_login ensures we return the right case in the email
$user_login = $user_data->user_login;
$user_email = $user_data->user_email;
$user_name = $user_data->user_name;

do_action('retrieve_password', $user_login);
$allow = apply_filters('allow_password_reset', true, $user_data->ID);

if (!$allow){
    return false;
}else if (is_wp_error($allow)){
    echo 'error en datos';
    exit();
}

$key = '';//$wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
$keyKmimos=md5($user_data->ID);
update_user_meta($user_data->ID, 'clave_temp', $keyKmimos);

if ( empty($key) ) {

    $key = wp_generate_password(20, false);
    do_action('retrieve_password_key', $user_login, $key);

    if ( empty( $wp_hasher ) ) {
        require_once(ABSPATH.'wp-includes/class-phpass.php');
        $wp_hasher = new PasswordHash( 8, true );
    }

    //Change this
    //$hashed = $wp_hasher->HashPassword( $key );
    $hashed = time().':'.$wp_hasher->HashPassword($key);
    $wpdb->update($wpdb->users, array('user_activation_key' => $hashed), array('user_login' => $user_login));
}

$url_activate=site_url()."/wp-login.php?action=rp&key=$key&login=".rawurlencode($user_login);
$url_activate=site_url()."/restablecer/?r=".$keyKmimos;

//MESSAGE
$mail_file=dirname(dirname(__DIR__)).'/template/mail/recuperar.php';
$message_mail=file_get_contents($mail_file);
$message_mail=str_replace('[name]',$user_login,$message_mail);
$message_mail=str_replace('[url]',$url_activate,$message_mail);
//$message_mail=htmlentities($message_mail);

//MAIL
$subjet='Cambiar contraseña en Kmimos';
$message=kmimos_get_email_html($subjet, $message_mail, 'Saludos,', false, true);
wp_mail($user_email,  $subjet, $message);

echo 'Hemos enviado los pasos para restablecer la contraseña a tu correo.';
exit();

?>