<div style='width: 100%;'>
	<img src='[KV_URL_IMGS]/CONSULTA/NUEVA/header.png' style='width: 100%;' >
</div>

<div style='font-family: Verdana; width: 100%'>

    <div style='
        padding: 30px; 
        font-size: 14px; 
        line-height: 1.07; 
        letter-spacing: 0.3px; 
        color: #000000;
    '>
        <div style='
            font-size: 26px; 
            font-weight: bold; 
            letter-spacing: 0.4px; 
            color: #940d99; 
            padding-bottom: 30px; 
            text-align: left;
            text-transform: capitalize;
        '>
            ¡Hola [NOMBRE_VETERINARIO]!
        </div>  

        <div style='
            font-size: 17px; 
            line-height: 1.07; 
            letter-spacing: 0.3px; 
            color: #000000; 
            text-align: left; 
        '>
            El cliente <strong style="color: #940d99;">[NOMBRE_CLIENTE]</strong> te ha enviado una solicitud de consulta.
        </div>

    </div>

    <div style='
        font-size: 17px; 
        font-weight: 600;
        line-height: 1.07; 
        letter-spacing: 0.3px; 
        color: #000000; 
        padding: 15px 0px;
        text-align: center;
        background-color: #e3e3e3;
    '>
        Consulta #: <strong>[CONSULTA_ID]</strong>
    </div>
           
    <div style="padding: 30px 0px; text-align: center;">

        <div style='
            font-size: 17px; 
            color: #000; 
            margin-bottom: 20px;
        '>
            ¿Aceptas la solicitud?
        </div>
        
        <div style="text-align: center;">
            <a href='[ACEPTAR]?u=cui' style='
                max-width: 250px;
                width: 100%;
                padding: 10px;
                background-color: #940d99;
                color: #FFF;
                font-weight: 600;
                margin: 0px 0px 20px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 13px;
            '>
                ¡ACEPTAR!
            </a>
        </div>

        <div style="text-align: center;">
            <a href='[RECHAZAR]?u=cui' style='
                max-width: 250px;
                width: 100%;
                padding: 10px;
                background-color: #FFF;
                color: #000;
                border: solid 2px #000;
                font-weight: 600;
                margin: 0px 0px 20px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 13px;
            '>
                AHORA NO PUEDO, RECHAZAR
            </a>
        </div>

    </div>

    <div style='
        padding: 20px 30px; 
        font-size: 17px; 
        line-height: 1.07; 
        letter-spacing: 0.3px; 
        color: #FFF; 
        background-color: #940d99;
        font-weight: 600;
    '>
        DATOS DEL CLIENTE
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
                font-size: 17px;
                font-weight: 600;
                padding: 20px 0px 0px;
                text-transform: capitalize;
            ">
                [NOMBRE_CLIENTE]
            </div>

            <div style="
                font-weight: 400;
                padding: 5px;
            ">
                <table width="100%" style='
                    padding: 0px;
                    font-size: 10px;
                '>
                    <tr>
                        <td style="width: 30px; text-align: left;">
                            <img style="width: 20px; padding: 7px 0px;" src="[KV_URL_IMGS]/CONSULTA/NUEVA/Icon-Phone.png" />
                        </td>
                        <td>
                            [TELEFONOS_CLIENTE]
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30px; text-align: left;">
                            <img style="width: 20px; padding: 7px 0px;" src="[KV_URL_IMGS]/CONSULTA/NUEVA/Icon-Mail.png" />
                        </td>
                        <td>
                            [CORREO_CLIENTE]
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
    padding: 0px 10px 0px; 
    box-sizing: border-box;
'>

    <div style='
        font-size: 17px; 
        font-weight: 600; 
        letter-spacing: -0.1px; 
        margin-bottom: 5px;
        padding-bottom: 5px;
        border-bottom: solid 1px #BBB;
        margin-top: 15px;
    '>
        DETALLE DEL SERVICIO
    </div>

    <table cellpadding="0" cellspacing="0" style='padding: 15px 0px 20px; overflow: hidden; font-family: Arial; font-size: 12px; letter-spacing: 0.3px; color: #000000;'>
        
        <tr style=''>
            <td style='height: 20px; text-align: left; vertical-align: middle; font-weight: 600; padding-right: 15px;'>
                FECHA
            </td>
            <td style='height: 20px; vertical-align: middle; font-size: 17px;'> 
                [FECHA]
            </td>
        </tr>
        
        <tr style=''>
            <td style='height: 20px; text-align: left; vertical-align: middle; font-weight: 600; padding-right: 15px;'>
                PRECIO
            </td>
            <td style='height: 20px; vertical-align: middle; font-size: 17px;'> 
                [PRECIO]<small>$</small>
            </td>
        </tr>
                                                        
    </table>

</div>