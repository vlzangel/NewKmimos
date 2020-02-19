<div style='text-align:center; margin-bottom: 0px;'>
    <img src='[KV_URL_IMGS]/CONSULTA/FINALIZACION/header.png' style='width: 100%;' >
</div>

<div style='font-family: Verdana; width: 100%'>

    <div style='
        font-size: 15px; 
        font-weight: 600;
        line-height: 1.07; 
        letter-spacing: 0.3px; 
        color: #000000; 
        padding: 25px 0px;
        text-align: center;
        background-color: #e3e3e3;
        margin-bottom: 30px;
    '>
        C&oacute;digo de cita #[CONSULTA_ID]
    </div>

    <div style='
        padding: 20px 30px; 
        font-size: 15px; 
        line-height: 1.07; 
        letter-spacing: 0.3px; 
        color: #FFF; 
        background-color: #6B169B;
        font-weight: 600;
    '>
        DATOS DEL VETERINARIO:
    </div>
    
    
    <div style="
        width: 100%;
        background-color: #efefee;
        padding: 10px;
        box-sizing: border-box;
    ">
        <div style="
            background-color: #FFF;
            padding: 20px;
        ">

            <div style="
                text-align: center;
                padding-bottom: 20px;
            ">
                <img src="[AVATAR_URL]" style="height: 150px;" />
            </div>

            <div style="
                font-size: 17px;
                font-weight: 600;
                padding: 0px;
                text-transform: capitalize;
            ">
                [NAME_VETERINARIO]
            </div>

            <div style="
                font-weight: 400;
                padding: 5px;
            ">
                <table width="100%" style='
                    padding: 0px;
                    font-size: 13px;
                '>
                    <tr>
                        <td style="width: 30px; text-align: left;">
                            
                        </td>
                        <td>
                            [TELEFONOS_VETERINARIO]
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30px; text-align: left;">
                            
                        </td>
                        <td>
                            [CORREO_VETERINARIO]
                        </td>
                    </tr>
                </table>
                
            </div>

        </div>
    </div>

</div>

<div style='
    font-family: Verdana;
    width: 100%;
    padding: 20px 10px 0px; 
    box-sizing: border-box;
'>
    <div style='
        font-size: 17px; 
        font-weight: 600; 
        letter-spacing: -0.1px; 
        margin-bottom: 5px;
        padding-bottom: 5px;
    '>
        DIAGNÓSTICO GENERAL
    </div>
    <div style="    
        text-align: justify;
        font-size: 17px;
        padding: 10px 0px;
    ">
        [DIAGNOSTICO]
    </div>
    <div style="    
        text-align: justify;
        font-size: 17px;
        padding: 5px 0px;
    ">
        [DIAGNOSTICO_NOTA]
    </div>
</div>

<div style='
    font-family: Verdana;
    width: 100%;
    padding: 20px 10px 20px; 
    box-sizing: border-box;
    border-bottom: solid 2px #CCC;
'>
    <div style='
        font-size: 17px; 
        font-weight: 600; 
        letter-spacing: -0.1px; 
        margin-bottom: 5px;
        padding-bottom: 5px;
    '>
        TRATAMIENTO Y RECOMENDACIONES
    </div>
    <div style="    
        text-align: justify;
        font-size: 17px;
        padding: 10px 0px;
    ">
        [TRATAMIENTO]
    </div>
</div>

<div style='text-align: center;'>
    <a href="[PDF]" style="    
        display: block;
        width: 90%;
        text-align: center;
        margin: 30px auto 0px;
        padding: 10px;
        border: solid 3px #000;
        color: #000;
        text-transform: uppercase;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
    "> 
        DESCARGAR RECIPE
    </a>
</div>

<div style='
    font-family: Verdana;
    width: 100%;
    padding: 20px 10px 0px; 
    box-sizing: border-box;
'>

    <div style="padding: 10px 0px 0px; font-size: 14px;">
        <strong style="color: #6B169B;">SERVICIO:</strong> [TIPO_SERVICIO]
    </div>

    <table cellpadding="0" cellspacing="0" style='padding: 15px 0px 20px; overflow: hidden; font-family: Arial; font-size: 12px; letter-spacing: 0.3px; color: #000000;'>
        <tr style=''>
            <td style='height: 20px; text-align: center; vertical-align: middle; width: 60px;'>
              <img style="padding: 3px 0px;" src='[KV_URL_IMGS]/CONSULTA/NUEVA/min_calendar.png'  > 
            </td>
            <td style='height: 20px; vertical-align: middle; font-size: 16px;'> 
                [FECHA]
            </td>
        </tr>
        
        <tr style=''>
            <td style='height: 20px; text-align: center; vertical-align: middle;'>
              <img style="padding: 3px 0px;" src='[KV_URL_IMGS]/CONSULTA/NUEVA/min_clock.png'  > 
            </td>
            <td style='height: 20px; vertical-align: middle; font-size: 16px;'> 
                [HORA]
            </td>
        </tr>
        
        <tr id="tipo_pago">
            <td style='height: 20px; text-align: center; vertical-align: middle;'>
              <img style="padding: 3px 0px;" src='[KV_URL_IMGS]/CONSULTA/NUEVA/min_cash.png'  > 
            </td>
            <td style='height: 20px; vertical-align: middle; font-size: 16px;'> 
                [TIPO_PAGO]
            </td>
        </tr>                                   
    </table>

    <table style="width: 100%; background-color: #e3e3e3;">
        <tr>
            <td style="text-align: left; padding: 10px 15px;">PAGO</td>
            <td style="text-align: right; padding: 10px 15px;"><strong>$ [TOTAL]</strong></td>
        </tr>
    </table>

</div>

<div style='text-align: center;'>
    <a href='[URL]'> 
        <img style='width: 100%; margin: 50px 0px 0px;' src='[KV_URL_IMGS]/dudas.png' />
    </a>
</div>

<div style='text-align: center;'>
    <a href='[URL]'> 
        <img style='width: 100%; margin: 50px 0px 20px;' src='[KV_URL_IMGS]/CONSULTA/NUEVA/BTN_KMIVET.jpg' />
    </a>
</div>