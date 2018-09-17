<div style='width: 600px;'>
	<img src='[URL_IMGS]/new/nueva/[HEADER]/header.png' style='width: 100%;' >
</div>

<div style='font-family: Verdana; width: 600px;'>

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
            color: #940d99;
            font-weight: 600;
        '>
            ¡Hola Administrador!
        </div>  

        <div style='
            font-size: 17px; 
            line-height: 1.07; 
            letter-spacing: 0.3px; 
            color: #000000; 
            text-align: left; 
            max-width: 518px; 
            width: 100%;
        '>
           	Recibimos una solicitud de <strong style="color: #940d99;">[name_cliente]</strong> para conocer a <strong>[name_cuidador]</strong>
        </div>

    </div>

	<div style='
		width: 269px;
	    font-size: 17px; 
	    font-weight: 600;
	    line-height: 1.07; 
	    letter-spacing: 0.3px; 
	    color: #000000; 
	    padding: 15px 30px;
	    text-align: center;
	    background-color: #e3e3e3;
	    margin: 0 auto 30px;
	'>
	    Tu código de solicitud es #<strong>[id_solicitud]</strong>
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
	       
	<div style="padding: 30px 30px 10px; text-align: center;">

		<table width="600px" cellpadding="0" cellspacing="0">
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
			min-width: 200px;
			padding: 10px 30px;
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
			width: auto;
			min-width: 200px;
			padding: 10px 30px;
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
	    font-family: Verdana;
	    width: 100%;
	    padding: 0px 30px 30px; 
	    box-sizing: border-box;
	'>

	    <div style='
	    	width: 550px;
	        font-size: 17px; 
	        font-weight: 600; 
	        letter-spacing: -0.1px; 
	        margin-bottom: 15px;
	        padding-bottom: 5px;
	        border-bottom: solid 1px #BBB;
	    '>
	        DETALLE DE LAS MASCOTAS
	    </div>

	    <table cellpadding="0" cellspacing="0" style='width: 550px;'>
	    	<tbody style="
	    		font-family: Verdana;
			    font-size: 17px;
			    line-height: 1.2;
			    letter-spacing: 0.3px;
			    color: #000000;
	    	">
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

		    </tbody>

	    </table>

	</div>
</div>