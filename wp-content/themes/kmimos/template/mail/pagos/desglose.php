
<br>
<div style="margin: 10px 0px;">
	<div style="padding: 20px 30px; 
        font-size: 17px; 
        line-height: 1.07; 
        letter-spacing: 0.3px; 
        color: #FFF; 
        background-color: #00d2c6;
        font-weight: 600;">
        DATOS DE TRANSFERENCIA
    </div>
	<br>
	<div style="padding:1px 0px;"><strong>Referencia:</strong>  [transaccion_id]</div>
	<div style="padding:1px 0px;"><strong>Titular:</strong>  [titular]</div>
	<div style="padding:1px 0px;"><strong>Cuenta:</strong>  [cuenta]</div>
	<div style="padding:1px 0px;"><strong>Total:</strong>  [total]</div>
	<div style="padding:1px 0px;"><strong>Estatus:</strong>  [estatus]</div>
</div>
<br>

<div style="padding: 20px 30px; 
        font-size: 17px; 
        line-height: 1.07; 
        letter-spacing: 0.3px; 
        color: #FFF; 
        background-color: #00d2c6;
        font-weight: 600;">
	DETALLE DE TRANSFERENCIA
</div>
<br>
<table width="100%" style="width: 100%;">
	<thead>
		<tr style="color:#940d99;line-height:1.07;letter-spacing:0.3px;font-weight:600;font-size:14px;">
			<td width="65%" style="padding: 7px; border-bottom: solid 1px #940d99; vertical-align: middle; text-align: center;">CONCEPTO</td>
			<td width="35%" style="padding: 7px; border-bottom: solid 1px #940d99; border-left: solid 1px #940d99; vertical-align: middle; text-align: right!important;">MONTO</td>
		</tr>
	</thead>
	<tbody>
		[desglose_detalle]		
	</tbody>
</table>


<table cellpadding="0" cellspacing="0" style='
        font-size: 15px; 
        font-weight: bold; 
        line-height: 1.5; 
        letter-spacing: 0.2px; 
        color: #000000; 
        width: 100%;
        margin: 0px 0px 5px;
        background-color: #efefee;
    '>
    <tr style=''>
        <td style='text-align: left; vertical-align: middle;  padding: 5px 7px; font-weight: 400;'>
            TOTAL
        </td>
        <td style='padding: 5px 7px 5px 0px; text-align: right;'>
            $ [total]
        </td>
    </tr>
</table>		