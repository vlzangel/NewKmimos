<style type="text/css">
	@page {
        margin: 10mm;
    }
	* { font-family: DejaVu Sans; font-size: 7pt; }
	body { margin: 0pt; }
	th, td { padding: 0pt 10pt; }
	.border { border: solid 1pt #000; }	
	.border_sin_top { border-top: 0pt; }	
	.border_sin_bottom { border-bottom: 0pt; }	
	.border_sin_left { border-left: 0pt; }	
	.border_sin_right { border-right: 0pt; }	
	.left { text-align: left; }
	.right { text-align: right; }
	.center { text-align: center; }
	.bold { font-weight: 700; }
	.sin_padding { padding: 0pt; }
	.red { color: #c30000;  }
</style>

<table width="100%">
	<tr>
		<td>
			<img src="<?= __DIR__ ?>/logo.png" style="height: 40pt; margin-bottom: 2pt;" />
		</td>
		<td style="vertical-align: top;">
			<div>Dr(a). [VETERINARIO]</div>
			<div>Cédula Profesional: [CEDULA]</div>
		</td>
	</tr>
</table>

<table width="100%" style="margin-top: 20pt;">
	<tr>
		<td style="background-color: #EEE; padding: 2pt 10pt 3pt !important;">
			<strong>Paciente: </strong> [PACIENTE]
		</td>
	</tr>
	<tr>
		<td>
			<strong>Edad: </strong> [EDAD] años
		</td>
	</tr>
	<tr>
		<td>
			<strong>Medidas Generales: </strong>
		</td>
	</tr>
	<tr>
		<td>
			[TRATAMIENTO]
		</td>
	</tr>
	<tr>
		<td style="padding-top: 10pt;">
			<div style="font-weight: 600;">Dr(a). [VETERINARIO]</div>
			<div style="font-weight: 600;">Cédula Profesional: [CEDULA]</div>
		</td>
	</tr>
</table>