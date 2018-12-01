<?php
  wp_enqueue_style('finalizar', getTema()."/css/finalizar.css", array(), '1.0.0');
  wp_enqueue_style('finalizar_responsive', getTema()."/css/responsive/finalizar_responsive.css", array(), '1.0.0');
?>
<link rel='stylesheet' type='text/css' href='<?php echo getTema() ?>/admin/backend/agregar_cupon/style.css'>
<script src='<?php echo getTema(); ?>/admin/backend/agregar_cupon/script.js'></script>


<div class="container_listados">

    <div class='titulos' style="padding: 20px 0px 0px;">
        <h2>Agregar cupon a reserva</h2>
        <hr>
    </div>

    <div class="col-md-6">
      <form id="frm-cupon">
        <div class="form-group">
          <label for="reserva">Reserva</label>
          <input type="text" class="form-control" name="idReserva" id="reserva" placeholder="Reserva ID" value="199879" required>
        </div>
        <div class="form-group">
          <label for="cupon">Cupon</label>
          <input type="text" class="form-control" id="cupon" name="cupon" placeholder="Cupon" required>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" name="saldo"> Agregar saldo a favor al cliente?
          </label>
        </div>
        <button type="submit" class="btn btn-default">Aplicar cupon</button>
      </form>
    </div>

    <div class="col-md-5" style="border:1px solid #ccc; margin: 5px; display:inline-block; vertical-align:top; display:inline-block;" >
      <h3>Reserva Original</h3>
      <hr>
      <div style="width: 100%;" id="view_data_old"> Sin datos para mostrar </div>
    </div>
    <div class="col-md-5" style="border:1px solid #ccc; margin: 5px; display:inline-block; vertical-align:top; display:inline-block;" >
      <h3>Reserva Modificada</h3>
      <hr>
      <div style="width: 100%;" id="view_data_new"> Sin datos para mostrar </div>
    </div>
</div>
