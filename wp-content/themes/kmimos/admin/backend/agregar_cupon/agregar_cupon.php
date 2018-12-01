
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
          <input type="text" class="form-control" name="idReserva" id="reserva" placeholder="Reserva ID" value="199879">
        </div>
        <div class="form-group">
          <label for="cupon">Cupon</label>
          <input type="text" class="form-control" id="cupon" name="cupon" placeholder="Cupon">
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" name="saldo"> Agregar saldo a favor al cliente?
          </label>
        </div>
        <button type="submit" class="btn btn-default">Aplicar cupon</button>
      </form>
    </div>

    <div class="col-md-6" id="view_data"></div>
</div>
