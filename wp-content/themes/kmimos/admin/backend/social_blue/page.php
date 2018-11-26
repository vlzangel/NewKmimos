<link rel='stylesheet' type='text/css' href='<?php echo getTema() ?>/admin/backend/social_blue/css.css'>
<script src='<?php echo getTema(); ?>/admin/backend/social_blue/js.js'></script>

<div class="container_listados">

    <div class='titulos' style="padding: 20px 0px 0px;">
        <h2>Importar Usuarios</h2>
        <hr>
    </div>

    <form id="form" action="<?= get_home_url()."/campaing/fb.php" ?>" method="POST" enctype="multipart/form-data" >

        <label>Seleccionaer archivo (.csv)</label>
        <input type="hidden" name="info" value="YES" />
        <input type="file" name="txt" accept=".csv" required />
        

        <div class='botones_container'>
            <input type='submit' id="btn" value='Procesar' class="btn btn-success" />
        </div>
    </form>
</div>