<?php include_once( "ajax/get_configuracion.php" ); ?>
<link rel='stylesheet' type='text/css' href='<?php echo getTema() ?>/admin/backend/facturas_configuracion/reporte_configuracion.css'>
<script src='<?php echo getTema(); ?>/admin/backend/facturas_configuracion/reporte_configuracion.js'></script>
 
<div class="col-sm-12">
    <div class='titulos'>
        <h2>Configuraci&oacute;n SAT</h2>
        <hr>
    </div>
    <div class="clear"></div>

    <h5 style="margin-top:20px;">Configuraci&oacute;n Kmimos</h5>
    <form id="form-kmimos">
        <section class='col-sm-12'>
            <div class="input-container">
                <div>Serie de factura:</div>
                <input type="text" name="serie" value="<?php echo $data['cfdi_parametros']['serie']; ?>" >
                <small>El c&oacute;digo debe estar registrado en Enlace Fiscal</small>
            </div>
            <div class="input-container">
                <div>Serie de Cuidadores:</div>
                <input type="text" name="serie_cuidador" value="<?php echo $data['cfdi_parametros']['serie_cuidador']; ?>" >
                <small>El c&oacute;digo debe estar registrado en Enlace Fiscal</small>
            </div>
            <div class="input-container">
                <div>% IVA:</div>
                <input type="text" name="iva" value="<?php echo $data['cfdi_parametros']['iva']; ?>" >
                <small>Ejemplo: 0.16</small>
            </div>
            <div class="input-container">
                <div>RFC P&uacute;blico en General:</div>
                <input type="text" name="rfc_general" value="<?php echo $data['cfdi_parametros']['rfc_general']; ?>" >
            </div>
            <div class="input-container">
                <div>% Comision Cuidadores:</div>
                <input type="text" name="comision" value="<?php echo $data['cfdi_parametros']['comision']; ?>" >
                <small>Ejemplo: 1.25</small>
            </div>
            <div class="clear"></div>
        </section>
    </form>
    <div class="clear"></div>
    <div class='col-sm-12 botones_container'>
        <button type='button' id='actualizar_kmimos' class="btn btn-success" ><i class="fa fa-save"></i> Guardar</button>
    </div>    


    <h5 style="margin-top:20px;">C&oacute;digos Cat&aacute;logo SAT</h5>
    <form id="form-servicios">
        <section class="col-sm-12">
            <h5>Servicios</h5>
            <hr />
            <div class="input-container">
                <div>Hospedaje:</div>
                <div><input type="text" name="hospedaje" value="<?php echo $data['hospedaje']; ?>" ></div>
            </div>
            <div class="input-container">
                <div>Guarder&iacute;a:</div>
                <div><input type="text" name="guarderia" value="<?php echo $data['guarderia']; ?>" ></div>
            </div>
            <div class="input-container">
                <div>Paseo:</div>
                <div><input type="text" name="paseo" value="<?php echo $data['paseo']; ?>" ></div>
            </div>

            <div class="input-container">
                <div>Adiestramiento Basico:</div>
                <div><input type="text" name="adiestramiento_basico" value="<?php echo $data['adiestramiento_basico']; ?>" ></div>
            </div>
            <div class="input-container">
                <div>Adiestramiento Intermedio:</div>
                <div><input type="text" name="adiestramiento_intermedio" value="<?php echo $data['adiestramiento_intermedio']; ?>" ></div>
            </div>
            <div class="input-container">
                <div>Adiestramiento Avanzado:</div>
                <div><input type="text" name="adiestramiento_avanzado" value="<?php echo $data['adiestramiento_avanzado']; ?>" ></div>
            </div>
            <div class="clear"></div>
        </section>        

        <section class="col-sm-12">
            <h5>Servicios Adicionales</h5>
            <hr />
            <div class="input-container">
                <div>Baño:</div>
                <div><input type="text" name="banio" value="<?php echo $data['banio']; ?>" ></div>
            </div>
            <div class="input-container">
                <div>Corte de Pelo y Uñas:</div>
                <div><input type="text" name="corte" value="<?php echo $data['corte']; ?>" ></div>
            </div>
            <div class="input-container">
                <div>Visita al Veterinario:</div>
                <div><input type="text" name="veterinario" value="<?php echo $data['veterinario']; ?>" ></div>
            </div>

            <div class="input-container">
                <div>Limpieza Dental:</div>
                <div><input type="text" name="limpieza" value="<?php echo $data['limpieza']; ?>" ></div>
            </div>
            <div class="input-container">
                <div>Acupuntura:</div>
                <div><input type="text" name="acupuntura" value="<?php echo $data['acupuntura']; ?>" ></div>
            </div>
            <div class="clear"></div>
        </section>        

        <section class="col-sm-12">
            <h5>Servicios de Transporte</h5>
            <hr />
            <div class="input-container">
                <div>Transp. Sencillo - Rutas Cortas:</div>
                <div><input type="text" name="transp-sencillo-corto" value="<?php echo $data['transp-sencillo-corto']; ?>" ></div>
            </div>
            <div class="input-container">
                <div>Transp. Sencillo - Rutas Medias:</div>
                <div><input type="text" name="transp-sencillo-medio" value="<?php echo $data['transp-sencillo-medio']; ?>" ></div>
            </div>
            <div class="input-container">
                <div>Transp. Sencillo - Rutas Largas:</div>
                <div><input type="text" name="transp-sencillo-largo" value="<?php echo $data['transp-sencillo-largo']; ?>" ></div>
            </div>

            <div class="input-container">
                <div>Transp. Redondo - Rutas Cortas:</div>
                <div><input type="text" name="transp-redondo-corto" value="<?php echo $data['transp-redondo-corto']; ?>" ></div>
            </div>
            <div class="input-container">
                <div>Transp. Redondo - Rutas Medias:</div>
                <div><input type="text" name="transp-redondo-medio" value="<?php echo $data['transp-redondo-medio']; ?>" ></div>
            </div>
            <div class="input-container">
                <div>Transp. Redondo - Rutas Largas:</div>
                <div><input type="text" name="transp-redondo-largo" value="<?php echo $data['transp-redondo-largo']; ?>" ></div>
            </div>
            <div class="clear"></div>
        </section>
    </form>
    <div class="clear"></div>
    <div class='col-sm-12 botones_container'>
        <button type='button' id='actualizar_servicios' class="btn btn-success" ><i class="fa fa-save"></i> Guardar</button>
    </div>  
</div>
