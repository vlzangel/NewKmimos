<div style='width: 100%;'>
	<img src='[URL_IMGS]/new/nueva/[HEADER]/header.png' style='width: 100%;' />
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
            font-size: 19px; 
            letter-spacing: 0.4px; 
            padding-bottom: 30px; 
            text-align: left;
        '>
            ¡Hola <strong>[nombre_usuario]</strong>!
        </div>  

        <div style='
            font-size: 17px; 
            line-height: 1.07; 
            letter-spacing: 0.3px; 
            color: #000000; 
            text-align: left; 
        '>
           	Recibimos una solicitud de <strong style="color: #940d99;">[name_cliente]</strong> para conocerte
        </div>

    </div>

	<div style='
	    font-size: 17px; 
	    font-weight: 600;
	    line-height: 1.07; 
	    letter-spacing: 0.3px; 
	    color: #000000; 
	    padding: 15px 30px;
	    text-align: center;
	    background-color: #e3e3e3;
	    margin-bottom: 30px;
	'>
	    Tu código de solicitud es: <strong>#[id_solicitud] </strong>
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
	       
	<div style="padding: 30px 30px 10px; text-align: center;">

		<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td width="80" style="vertical-align: top; text-align: left;">
					<img src='[URL_IMGS]/icosNews/Icon-Calendar-Black.png' style='width: 70%;'>
				</td>
				<td style="vertical-align: top;">
					<strong  style="font-family: Verdana; font-size: 17px; color: #000000; text-align: left; display: block; padding-bottom: 10px;">DATOS DE LA REUNIÓN</strong>
					<table>
						<tbody style="font-family: Verdana; font-size: 17px; color: #000000; text-align: left;">
							<tr>
								<td style="padding: 5px 10px 5px 0px;">
									<img src='[URL_IMGS]/icosNews/min_calendar.png' style='text-align: left; width: 80%;'>
								</td>
								<td>
									[fecha]
								</td>
							</tr>
							<tr>
								<td style="padding: 5px 10px 5px 0px;">
									<img src='[URL_IMGS]/icosNews/min_clock.png' style='text-align: left; width: 80%;'>
								</td>
								<td>
									[hora] horas
								</td>
							</tr>
							<tr>
								<td style="padding: 5px 10px 5px 0px;">
									<img src='[URL_IMGS]/icosNews/Icon-phonebook.png' style='text-align: left; width: 80%;'>
								</td>
								<td>
									[lugar]
								</td>
							</tr>
						</tbody>
					</table>

				</td>
			</tr>
		</table>

		<div style="padding-top: 20px; text-align: left; font-size: 15px;">
			<strong style="color: #940d99; font-family: Verdana;">POSIBLE FECHA DE ESTADÍA:</strong> <span style="color: #000;">Del <strong>[desde]</strong> al <strong>[hasta]</strong> del [anio]</span>
		</div>

	</div>

	<div style="padding: 20px 30px 30px; text-align: center;">

		<a href='[ACEPTAR]' style='
			width: auto;
			min-width: 280px;
			padding: 10px 30px;
			background-color: #940d99;
			color: #FFF;
			font-weight: 600;
			margin-bottom: 20px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			border-radius: 2px;
		'>
			¡ACEPTAR!
		</a>

		<a href='[RECHAZAR]' style='
			width: auto;
			min-width: 280px;
			padding: 10px 30px;
			background-color: #FFF;
			color: #000;
			border: solid 2px #000;
			font-weight: 600;
			margin-bottom: 12px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			border-radius: 2px;
		'>
			AHORA NO PUEDO, RECHAZAR
		</a>

	</div>

	<div style='
	    font-family: Verdana;
	    width: 100%;
	    padding: 0px 30px 30px; 
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

	    <table cellpadding="0" cellspacing="0" style='width: 100%; font-family: Verdana; font-size: 15px;'>
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

	<div style="
	    font-size: 17px;
	    font-weight: 600;
	    line-height: 1.07;
	    letter-spacing: 0.3px;
	    color: #FFF;
	    padding: 15px 30px;
	    text-align: center;
	    background-color: #940d99;
	    margin: 0px 0px 30px;
	    border-radius: 2px;
	">
	    IMPORTANTE - SIGUIENTES PASOS
	</div>

	<table width="100%" cellpadding="0" cellspacing="0" style="font-family: Verdana; font-size: 17px;">
		<tr>
			<td width="100" style="padding: 15px 0px; vertical-align: middle; text-align: center;">
				<img src='[URL_IMGS]/icosNews/Icon-Calling-out_2.png' style='height: 50px; width: 50px;' >
			</td>
			<td style="padding: 10px 0px; vertical-align: middle; text-align: justify;">
				<strong>Márcale de volada al cliente</strong> para que se pongan de acuerdo en la logística para conocerse.
			</td>
		</tr>
		<tr>
			<td width="100" style="padding: 15px 0px; vertical-align: middle; text-align: center;">
				<img src='[URL_IMGS]/icosNews/Icon-Hands_2.png' style='height: 50px; width: 50px;' >
			</td>
			<td style="padding: 10px 0px; vertical-align: middle; text-align: justify;">
				Preséntate con el cliente cordial y  formalmente.<br> 
				<strong>Tip: Cuida tu imagen</strong> (Vestimenta casual)
			</td>
		</tr>
		<tr>
			<td width="100" style="padding: 15px 0px; vertical-align: middle; text-align: center;">
				<img src='[URL_IMGS]/icosNews/Icon-Photo_2.png' style='height: 50px; width: 50px;' >
			</td>
			<td style="padding: 10px 0px; vertical-align: middle; text-align: justify;">
				En caso de no conocerse en persona, <strong>pide que te envíen fotos del perro</strong> que llegará a tu casa para confirmar que sea tal cual lo describió su dueño. 
			</td>
		</tr>
		<tr>
			<td width="100" style="padding: 15px 0px; vertical-align: middle; text-align: center;">
				<img src='[URL_IMGS]/icosNews/Icon-Vaccination-card_2.png' style='height: 50px; width: 50px;' >
			</td>
			<td style="padding: 10px 0px; vertical-align: middle; text-align: justify;">
				<div style='margin-bottom: 9px;'><strong>Solicita</strong> que te compartan la cartilla de vacunación del perrito y verifica que sus <strong>vacunas</strong> estén al día.</div>
				<div><strong>Tip: Sin cartilla no se harán efectivos</strong> los beneficios veterinarios de Kmimos.</div>
			</td>
		</tr>
		<tr>
			<td width="100" style="padding: 15px 0px; vertical-align: middle; text-align: center;">
				<img src='[URL_IMGS]/icosNews/Icon-feel-the-puppy.png' style='height: 50px; width: 50px;' >
			</td>
			<td style="padding: 10px 0px; vertical-align: middle; text-align: justify;">
				<strong>Revisa al perrito</strong> y detecta si hubiese algún rasguño o golpe que pueda traer antes recibirlo, si detectas algo coméntale cordialmente al cliente y envíanos fotos vía whatsapp o correo.
			</td>
		</tr>
	</table>

</div>

<div style="
	background-color: #efefee; 
	font-family: Verdana; 
	font-size: 17px; 
	padding: 40px;
	text-align: center;
	margin-top: 30px;
">
	En caso de dudas, puedes contactarte con nuestro equipo de atención al cliente al teléfono (01) 55 3137 4829, Whatsapp +52 (33) 1261 4186, o al correo contactomex@kmimos.la
</div>

<div style="
    font-size: 17px;
    font-weight: 600;
    line-height: 1.07;
    letter-spacing: 0.3px;
    color: #FFF;
    padding: 15px 30px;
    text-align: center;
	background-color: #007bd3;
    margin: 0px 0px 30px;
    border-radius: 2px;
">
    PRESÉNTATE Y CONOCE A TU KMIAMIGO
</div>


<div style="
	font-family: Verdana; 
	font-size: 15px; 
	padding: 20px 30px 30px;
	text-align: justify;
">
	
	<div style="border-bottom: solid 1px #AAA;">
		
		<div style="padding-bottom: 20px;">
			Recuerda que cada perro tiene un comportamiento diferente, por lo que deberás tener la mayor información posible sobre sus comportamientos.
		</div>
		
		<div style='font-weight: bold; padding-bottom: 20px;'>
			SOBRE SU RUTINA DIARIA
		</div>
		
		<div style="padding-bottom: 20px;">
			Por ejemplo: ¿Sale a pasear? ¿A qué hora come y hace del baño?
		</div>
		
		<div style='font-weight: bold; padding-bottom: 20px;'>
			SOBRE SU COMPORTAMIENTO
		</div>
		
		<div style="padding-bottom: 20px;">
			Por ejemplo: ¿Cómo interactúa con otros perros y personas? ¿Cómo reacciona con un extraño?
		</div>
		
		<div style='font-weight: bold; padding-bottom: 20px;'>
			SOBRE SU &Aacute;NIMO
		</div>
		
		<div style="padding-bottom: 50px;">
			Por ejemplo: ¿Cómo se comporta cuando está triste o estresado? ¿Qué hace su dueño cuando está triste o estresado?
		</div>

	</div>

</div>
