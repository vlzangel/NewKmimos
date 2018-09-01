<div style='width: 100%;'>
    <img src='[URL_IMGS]/new/confirmaciones/[HEADER]/header.png' style='width: 100%;' >
</div>

<div style='font-family: Verdana; width: 100%; padding: 30px 0px 0px;'>

	<div style='margin-bottom: 15px; font-size: 14px; line-height: 1.07; letter-spacing: 0.3px; color: #000000;'>
        
        [MODIFICACION] 

        <div style='padding-bottom: 10px; text-align: center;'>
            <img style='' src='[URL_IMGS]/check.png' >
        </div>  

        <div style='
            font-size: 24px;
            font-weight: 600; 
            letter-spacing: 0.4px; 
            color: #000;
            padding-bottom: 10px; 
            text-align: center;
        '>
            ¡TODO ESTA LISTO!
        </div>  

        <div style='
            font-size: 17px; 
            line-height: 1.07; 
            letter-spacing: 0.3px; 
            color: #000000; 
            text-align: justify;
            display: block;
            max-width: 500px;
            margin: 30px auto;
        '>
            La reserva <strong>#[id_reserva]</strong> ha sido <span style="color: #940d99; font-size: 20px; vertical-align: middle; font-weight: 600;">CONFIRMADA</span> exitosamente de acuerdo a tu petición.
        </div>

	</div>

    <div style='
        padding: 20px 30px; 
        font-size: 17px; 
        line-height: 1.07; 
        letter-spacing: 0.3px; 
        color: #FFF; 
        background-color: #e3e3e3;
        font-weight: 600;
        text-align: center;
        color: #000;
        margin-bottom: 30px;
    '>
        Tu código de reserva es: #[id_reserva]
    </div>

    <div style='
        padding: 20px 30px; 
        font-size: 17px; 
        line-height: 1.07; 
        letter-spacing: 0.3px; 
        color: #FFF; 
        background-color: #007bd3;
        font-weight: 600;
    '>
        DATOS DEL CLIENTE
    </div>
    
    [DATOS_CLIENTE]

</div>

<div style='
    font-family: Verdana;
    width: 100%;
    padding: 30px; 
    box-sizing: border-box;
'>

    <div style='
        font-size: 17px; 
        font-weight: 600; 
        letter-spacing: -0.1px; 
        margin-bottom: 15px;
        padding-bottom: 5px;
        border-bottom: solid 1px #BBB;
    '>
        DETALLE DE LAS MASCOTAS
    </div>

    <table cellpadding="0" cellspacing="0" style='width: 100%;'>
        <tr style='
            color: #940d99; 
            line-height: 1.07; 
            letter-spacing: 0.3px; 
            font-weight: 600;
            font-size: 14px;
            text-align: center;
        '>
            <td style='padding: 7px; width: 20px; border-bottom: solid 1px #940d99;'>
                Nombre
            </td>
            <td style='padding: 7px; border-bottom: solid 1px #940d99; border-left: solid 1px #940d99;'>
                Raza
            </td>
            <td style='padding: 7px; border-bottom: solid 1px #940d99; border-left: solid 1px #940d99;'>
                Edad
            </td>
            <td style='padding: 7px; border-bottom: solid 1px #940d99; border-left: solid 1px #940d99;'>
                Tamaño
            </td>
            <td style='padding: 7px; border-bottom: solid 1px #940d99; border-left: solid 1px #940d99;'>
                Comportamiento
            </td>
        </tr>

        [mascotas]

    </table>

</div>

<div style='
    font-family: Verdana;
    width: 100%;
    padding: 0px 30px; 
    box-sizing: border-box;
'>

    <div style='
        font-size: 17px; 
        font-weight: 600; 
        letter-spacing: -0.1px; 
        margin-bottom: 15px;
        padding-bottom: 5px;
        border-bottom: solid 1px #BBB;
    '>
        DETALLE DEL SERVICIO
    </div>

    [DETALLES_SERVICIO]

    <div style='
        overflow: hidden; 
        padding-top: 15px;
        margin-bottom: 30px;
    '>
        [SERVICIOS]

        [TOTALES]

    </div>
</div>