<?php
    include_once(__DIR__.'/wlabel.php');
?><!DOCTYPE html>
<html>
    <head>
        <?php
        //wp_head();
        //<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>  ?>
        <!-- <script type="text/javascript" src="<?php echo site_url('/wp-includes/js/jquery/jquery.js'); ?>" type="text/javascript"></script> -->
        <title>PANEL WHITE LABEL</title>

        <script src='<?php echo getTema(); ?>/admin/recursos/js/jquery-1.12.4.min.js'></script>
        <script src='<?php echo getTema(); ?>/admin/recursos/js/jquery.dataTables.min.js'></script>
        <script src='<?php echo getTema(); ?>/admin/recursos/js/dataTables.bootstrap4.min.js'></script>
        <script src='<?php echo getTema(); ?>/admin/recursos/js/dataTables.buttons.min.js'></script>

        <link rel='stylesheet' type='text/css' href='<?php echo getTema(); ?>/admin/recursos/css/bootstrap.css'>
        <link rel='stylesheet' type='text/css' href='<?php echo getTema(); ?>/admin/recursos/css/dataTables.bootstrap4.min.css'>
        <link rel='stylesheet' type='text/css' href='<?php echo getTema(); ?>/admin/recursos/css/buttons.dataTables.min.css'>

        <script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__); ?>includes/js/script.js?v=<?php echo time(); ?>"></script>
        <link media="all" type="text/css" rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ); ?>includes/css/style.css"/>
    </head>
    <body>

    <div id="panel">
        <?php
        if($_wlabel_user->login){
            include_once('backend/panel.php');
        }else{
            include_once('backend/login.php');
        }
        ?>
    </div>

    </body>
</html>