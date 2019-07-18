<?php
    include_once(__DIR__.'/wlabel.php');

    $is_iOS = false;
    if (isset($_SERVER['HTTP_USER_AGENT']) ){
        $iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
        $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
        $iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
        $webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");
        $macOS   = stripos($_SERVER['HTTP_USER_AGENT'],"Mac OS");
        if( $iPod || $iPhone || $iPad || $webOS || $macOS){
            $is_iOS = true;
        }
    }

    $class_body = ( $is_iOS ) ? "iOS" : "";   

    $_SESSION["CLIENTE_IP"] = $_SERVER["REMOTE_ADDR"];

?><!DOCTYPE html>
<html>
    <head>
        <title>PANEL WHITE LABEL</title>

        <script src='https://code.jquery.com/jquery-3.3.1.js'></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

        <!-- <script src='<?php echo getTema(); ?>/admin/recursos/js/jquery.dataTables.min.js'></script>
        <script src='<?php echo getTema(); ?>/admin/recursos/js/dataTables.bootstrap4.min.js'></script> -->

        <script src='https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js'></script>
        <script src='https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js'></script>
         
        <script src="<?php echo getTema(); ?>/admin/recursos/lib/dataTables.buttons.min.js"></script>
        <script src="<?php echo getTema(); ?>/admin/recursos/lib/buttons.flash.min.js"></script>
        <script src="<?php echo getTema(); ?>/admin/recursos/lib/jszip.min.js"></script>
        <script src="<?php echo getTema(); ?>/admin/recursos/lib/pdfmake.min.js"></script>
        <script src="<?php echo getTema(); ?>/admin/recursos/lib/vfs_fonts.js"></script>
        <script src="<?php echo getTema(); ?>/admin/recursos/lib/buttons.html5.min.js"></script>
        <script src="<?php echo getTema(); ?>/admin/recursos/lib/buttons.print.min.js"></script>
 
        <link rel='stylesheet' type='text/css' href='<?php echo getTema(); ?>/admin/recursos/css/bootstrap.css'>
        <link rel='stylesheet' type='text/css' href='<?php echo getTema(); ?>/admin/recursos/css/dataTables.bootstrap4.min.css'>
        <link rel='stylesheet' type='text/css' href='<?php echo getTema(); ?>/admin/recursos/css/buttons.dataTables.min.css'>

        <script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__); ?>includes/js/script.js?v=<?php echo time(); ?>"></script>
        <link media="all" type="text/css" rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ); ?>includes/css/style.css"/>

    </head>
    <body class="<?= $class_body; ?>">

        <div id="panel">
            <?php
                echo "<pre>";
                    print_r($_wlabel_user);
                echo "</pre>";
                if( $_wlabel_user->login ){
                    include_once('backend/panel.php');
                }else{
                    include_once('backend/login.php');
                }
            ?>
        </div>

        <script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__); ?>includes/js/filtros_fechas.js?v=<?php echo time(); ?>"></script>

        <?php
            echo "<pre style='display: none;'>";
                print_r($_SESSION["CLIENTE_IP"] );
            echo "</pre>";
        ?>

    </body>
</html>