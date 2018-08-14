<div style='width: 100%;'>
    <img src='[URL_IMGS]/new/confirmaciones/header.png' style='width: 100%;' >
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
            text-align: left;
            display: block;
            max-width: 500px;
            margin: 30px auto;
        '>
	    	Tu solicitud de reserva ha sido <span style="color: #940d99; font-size: 20px; vertical-align: middle; font-weight: 600;">CONFIRMADA</span> por el cuidador <strong>[name_cuidador]</strong>
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
        background-color: #940d99;
        font-weight: 600;
    '>
        DATOS DEL CUIDADOR
    </div>
    
    [DATOS_CUIDADOR]

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
        <table cellpadding="0" cellspacing="0" style='box-sizing: border-box; width: 100%; background-color: #FFF; font-weight: bold; line-height: 1.5; letter-spacing: 0.2px; color: #000000; margin-bottom: 15px;'>
            <tr style=' font-family: Verdana; font-size: 13px;'>
                <td style=' width: 80px; background-color: #f4f4f4; text-align: center; vertical-align: middle;'>
                    <img src='[URL_IMGS]/icosNews/dog_black.png'>
                </td>
                <td style=' width: 150px; padding: 7px; text-align: center; border-bottom: solid 1px #940d99;'>
                    Cantidad
                </td>
                <td style=' width: 170px; padding: 7px; text-align: center; border-bottom: solid 1px #940d99; border-left: solid 1px #940d99;'>
                    Tiempo
                </td>
                <td style=' width: 100px; padding: 7px; text-align: center; border-bottom: solid 1px #940d99; border-left: solid 1px #940d99;'>
                    Precio c/u
                </td>
                <td style=' width: 100px; padding: 7px; text-align: center; border-bottom: solid 1px #940d99; border-left: solid 1px #940d99;'>
                    Subtotal
                </td>
            </tr>

            [desglose]

            [ADICIONALES]
            [TRANSPORTE]


        </table>

        [TOTALES]

    </div>
</div>

<div style='
    font-family: Verdana;
    font-size: 16px;
    color: #666;
    line-height: 1.3;
    letter-spacing: 0.3px;
    text-align: left;
    display: block;
    padding: 30px 0px 40px;
    border-top: solid 1px #CCC;
    border-bottom: solid 1px #CCC;
'>

    <div style='padding-bottom: 30px; text-align: center;'>
        <img style='' src='[URL_IMGS]/icosNews/info.png' >
    </div> 

    <div style="text-align: justify;">
        Si necesitas cancelar el servicio te pedimos que notifiques al cuidador y al staff kmimos con al menos 48 horas de anticipación a la fecha de inicio de la reserva, de lo contrario se cobrará un monto del 20% sobre el total de la reserva por concepto de la cancelación tardía.
    </div> 

</div>