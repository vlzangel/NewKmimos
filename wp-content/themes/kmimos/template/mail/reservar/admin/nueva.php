<div style='width: 100%;'>
	<img src='[URL_IMGS]/new/nueva/[HEADER]/header.png' style='width: 100%;' >
</div>

<div style='font-family: Verdana; width: 100%'>

    <div style='
        padding: 30px; 
        font-size: 14px; 
        line-height: 1.07; 
        letter-spacing: 0.3px; 
        color: #000000;
    '>
        
        [MODIFICACION] 

        <div style='
            font-size: 26px; 
            font-weight: bold; 
            letter-spacing: 0.4px; 
            color: #940d99; 
            padding-bottom: 30px; 
            text-align: left;
        '>
            <strong>¡Hola Administrador!</strong>
        </div>  

        <div style='
            font-size: 19px; 
            letter-spacing: 0.4px; 
            padding-bottom: 30px; 
            text-align: left;
        '>
            [TIPO_SERVICIO] por [name_cliente]
        </div>  

        <div style='
            font-size: 17px; 
            line-height: 1.07; 
            letter-spacing: 0.3px; 
            color: #000000; 
            text-align: left; 
        '>
           	El cliente <strong style="color: #940d99;">[name_cliente]</strong> ha realizado una solicitud de reserva.
        </div>

    </div>

	<div style='
	    font-family: Verdana;
	    width: 100%
	    font-size: 17px; 
	    font-weight: 600;
	    line-height: 1.07; 
	    letter-spacing: 0.3px; 
	    color: #000000; 
	    padding: 15px 30px;
	    text-align: center;
	    background-color: #e3e3e3;
	'>
	    Reserva #: <strong>[id_reserva] </strong>
	</div>
	       
	<div style="padding: 30px; text-align: center;">

		<div style='
			font-size: 17px; 
			color: #000; 
			margin-bottom: 20px;
		'>
			¿Aceptas la solicitud?
		</div>

		<a href='[ACEPTAR]' style='
			max-width: 300px;
			width: 100%;
			padding: 10px;
			background-color: #940d99;
			color: #FFF;
			font-weight: 600;
			margin-bottom: 20px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
		'>
			¡ACEPTAR!
		</a>

		<a href='[RECHAZAR]' style='
			max-width: 300px;
			width: 100%;
			padding: 10px;
			background-color: #FFF;
			color: #000;
			border: solid 2px #000;
			font-weight: 600;
			margin-bottom: 12px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
		'>
			AHORA NO PUEDO, RECHAZAR
		</a>

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

        [MASCOTAS]

    </table>

</div>

<div style='
    font-family: Verdana;
    width: 100%;
    padding: 0px 30px 0px; 
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

            [DESGLOSE]

            [ADICIONALES]
            [TRANSPORTE]


        </table>

        [TOTALES]

    </div>

</div>