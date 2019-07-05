<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/froala-editor@3.0.0/css/froala_editor.pkgd.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@3.0.0/js/froala_editor.pkgd.min.js"></script>

<link rel='stylesheet' type='text/css' href='<?= getTema() ?>/admin_2/<?= $modulo ?>/css.css?v=<?= time() ?>'>
<script src='<?= getTema(); ?>/admin_2/<?= $modulo ?>/js.js?v=<?= time() ?>'></script>
<div class="container_listados">
    <div class='titulos'>
        <h2>Campañas</h2>
        <hr>
        <div class="clear"></div>
        <div class="botones_container">
            <input type='button' value='Nueva Campaña' onClick='_new( jQuery(this) )' data-id="" data-titulo="Nueva Campaña" data-modal="campaing_new" class="button button-primary button-large" />
        </div>
        <hr>
    </div>
    <div class="clear"></div>
    <div class='col-md-12'>
        <table id="example" class="table table-striped table-bordered nowrap" cellspacing="0" style="min-width: 100%;">
            <thead>
                <tr>
                    <th width="30">#</th>
                    <th>Nombre</th>
                    <th>Listas</th>
                    <th width="120">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>