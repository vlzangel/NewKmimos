<?php
    $title=$_wlabel_user->wlabel_result->title;
    $color=$_wlabel_user->wlabel_data->color;
    $coloralt=$_wlabel_user->wlabel_data->coloralt;

    $image=$_wlabel_user->wlabel_data->image;
    if($image==''){
        $image=$_wlabel_user->wlabel_data->imagelabel;
    }
?>
<div class="menu" style="background-color:<?php echo $color; ?>;" data-coloralt="<?php echo $coloralt; ?>" data-url="<?php echo plugin_dir_url(__DIR__); ?>">
    <?php
        if($image!=''){//
            echo '<img class="image" src="'.$image.'" alt='.$title.';>';
        }

    ?>
    <div class="item" data-module="ventas" onclick="WhiteLabel_panel_menu(this);">Ventas</div>
    <div class="item" data-module="clientes" onclick="WhiteLabel_panel_menu(this);">Usuarios Registrados</div>
    <div class="item" data-module="leads" onclick="WhiteLabel_panel_menu(this);">Leads</div>
    <div class="item" data-module="monitor" onclick="WhiteLabel_panel_menu(this);">Monitor</div>
    <!-- <div class="item" data-module="client-booking" onclick="WhiteLabel_panel_menu(this);">Reservas por Clientes</div> -->
    <div class="item" data-module="booking" onclick="WhiteLabel_panel_menu(this);">Reservas</div>
    <div class="item" data-module="primera_vez" onclick="WhiteLabel_panel_menu(this);">Primera Vez</div>
</div>
