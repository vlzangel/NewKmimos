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
            [TIPO_SERVICIO] por [NAME_CLIENTE]
        </div>  

        <div style='
            font-size: 19px; 
            letter-spacing: 0.4px; 
            padding-bottom: 30px; 
            text-align: left;
        '>
            ¡Hola <strong>[NAME_CUIDADOR]</strong>!
        </div>  

        <div style='
            font-size: 17px; 
            line-height: 1.07; 
            letter-spacing: 0.3px; 
            color: #000000; 
            text-align: left; 
        '>
           	El cliente <strong style="color: #940d99;">[NAME_CLIENTE]</strong> te ha enviado una solicitud de reserva.
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
	    Reserva #: <strong>[ID_RESERVA]</strong>
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
			<a href='[RECHAZAR]' style='
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
    padding: 30px 10px; 
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
                Nombre / Raza
            </td>
            <td style='padding: 7px; border-bottom: solid 1px #940d99; border-left: solid 1px #940d99;'>
                Edad / Tamaño / Comportamiento
            </td>
        </tr>

        [MASCOTAS]

    </table>

</div>

<div style='
    font-family: Verdana;
    width: 100%;
    padding: 0px 0px 30px; 
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

	<div style='
	    font-family: Verdana;
	    width: 100%;
	    padding: 30px 0px; 
	    box-sizing: border-box;
	    border-top: solid 1px #CCC;
	    border-bottom: solid 1px #CCC;
	    margin-bottom: 30px;
	'>

	    <div style='
	        padding: 20px 30px; 
	        font-size: 17px; 
	        line-height: 1.07; 
	        letter-spacing: 0.3px; 
	        color: #FFF; 
	        background-color: #007bd3;
	        text-align: center;
	        font-weight: 600;
	    '>
	        Asegurate de aceptar o rechazar la reserva
	    </div>

	    <div style="font-size: 17px; text-align: justify; padding-top: 30px; color: #333;">
	    	Es necesario que confirmes si aceptas el servicio lo más pronto posible, de no ser así, pasadas las 8 horas el sistema cancelará esta solicitud y enviará automáticamente una recomendación al cliente sobre otros cuidadores sugeridos para atenderlo.
	    	<br><br>
	    	Si existe algún cambio en la reserva por favor asegúrate que el cliente esté enterado y de acuerdo con eso, posteriormente contacta al staff Kmimos a la brevedad para realizar los ajustes.
	    </div>
		
	</div>

	<div style='
	    font-family: Verdana;
	    width: 100%;
	    padding: 0px 0px 30px; 
	    box-sizing: border-box;
	'>

	    <div style='
	        padding: 20px 0px; 
	        font-size: 17px; 
	        line-height: 1.07; 
	        letter-spacing: 0.3px; 
	        color: #FFF; 
	        background-color: #940d99;
	        text-align: center;
	        font-weight: 600;
	    '>
	        IMPORTANTE - SIGUIENTES PASOS
	    </div>

	    <div style="font-size: 17px; text-align: justify; padding-top: 30px; color: #333;">
	    	
	    	<table style="font-size: 17px; text-align: justify; color: #333;">
	    		<tr>
	    			<td style="width: 100px; text-align: center; padding-bottom: 35px;">
	    				<img src='[URL_IMGS]/icosNews/Icon-Calling-out.png'>
	    			</td>
	    			<td style="padding-bottom: 35px;">
	    				<strong>Márcale de volada al cliente</strong> para que se pongan de acuerdo en la logística para conocerse.
	    			</td>
	    		</tr>
	    		<tr>
	    			<td style="width: 100px; text-align: center; padding-bottom: 35px;">
	    				<img src='[URL_IMGS]/icosNews/Icon-Hands.png'>
	    			</td>
	    			<td style="padding-bottom: 35px;">
	    				Preséntate con el cliente cordial y formalmente. <strong>Tip: Cuida tu imagen</strong> (Vestimenta casual).
	    			</td>
	    		</tr>
	    		<tr>
	    			<td style="width: 100px; text-align: center; padding-bottom: 35px;">
	    				<img src='[URL_IMGS]/icosNews/Icon-Photo.png'>
	    			</td>
	    			<td style="padding-bottom: 35px;">
	    				En caso de no conocerse en persona, <strong>pide que te envíen fotos del perr que llegará a tu casa</strong> para confirmar que sea tal cual lo describió su dueño.
	    			</td>
	    		</tr>
	    		<tr>
	    			<td style="width: 100px; text-align: center; padding-bottom: 35px;">
	    				<img src='[URL_IMGS]/icosNews/Icon-Vaccination-card.png'>
	    			</td>
	    			<td style="padding-bottom: 35px;">
	    				Solicita que te comparta la <strong>cartilla de vacunación</strong> del perrito y verifica que sus vacunas estén al día.<br>
	    				Tip: <strong>Sin cartilla no se harán efectivos</strong> los beneficios veterinarios de Kmimos.
	    			</td>
	    		</tr>
	    		<tr>
	    			<td style="width: 100px; text-align: center;;">
	    				<img src='[URL_IMGS]/icosNews/Icon-feel-the-puppy.png'>
	    			</td>
	    			<td>
	    				<strong>Revisa al perrito y detecta</strong> si hubiese algún rasguño o golpe que pueda traer antes de recibirlo. si detectas algo coméntale cordialmente al cliente y envíanos fotos vía whatsapp o correo.
	    			</td>
	    		</tr>
	    	</table>

	    </div>
		
	</div>

</div>

<div style='
	width: 100%; 
	padding: 30px 20px; 
	font-size: 17px; 
	font-family: Verdana; 
	background-color: #efefee;
	text-align: center;
	box-sizing: border-box;
'>   
    En caso de dudas, puedes contactarte con nuestro equipo de atención al cliente al teléfono 01 (55) 8526 1162, Whatsapp +52 1 (33) 1261 41 86, o al correo contactomex@kmimos.la
</div>

<div style='
	width: 100%;
    padding: 20px 0px; 
    font-size: 17px; 
    line-height: 1.07; 
    letter-spacing: 0.3px; 
    color: #FFF; 
    background-color: #007bd3;
    text-align: center;
    font-weight: 600;
	box-sizing: border-box;
'>
    PRESÉNTATE Y CONOCE A TU KMIAMIGO
</div>


<div style='
    font-family: Verdana;
    width: 100%;
    padding: 0px 10px 0px; 
    box-sizing: border-box;
	font-size: 17px; 
'>
	<div style="border-bottom: solid 1px #CCC; padding: 30px 0px; color: #333; text-align: justify;">
		Recuerda que cada perro tiene un comportamiento diferente, por lo que deberás tener la mayor información posible sobre sus comportamientos.
		<br><br>

		<strong>SOBRE SU RUTINA DIARIA</strong>
		<br><br>

		Por ejemplo: ¿Sale a pasear? ¿A qué hora come y hace del baño?
		<br><br>

		<strong>SOBRE SU COMPORTAMIENTO</strong>
		<br><br>

		Por ejemplo: ¿Cómo se comporta cuando está triste o estresado? ¿Qué hace su dueño cuando está triste o estresado?
	</div>
</div>