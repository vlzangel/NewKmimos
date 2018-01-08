<?php
    /*
        Template Name: Coberturas de Servicios Veterinarios
    */

	get_header();
$HTML = '<div style="text-align: justify">
<strong><u>ESPECIFICACIÓN QUE SE ADHIERE Y FORMA PARTE INTEGRANTE DE LA PÓLIZA 01-047-07000977-0000-01</u></strong>

<strong>Asegurado:</strong><br>
<strong>KMIMOS, S.A.P.I. DE C.V.</strong><br>

<strong><u>Riesgo asegurado:</u></strong>
<p>
GMX Seguros se obliga a pagar la indemnización que el Asegurado deba a un tercero a consecuencia de uno o más  hechos que realizados sin dolo, ya sea por culpa o por el uso de cosas peligrosas, causen un daño previsto en esta póliza a terceras personas con motivo de su actividad como cuidador de mascotas.
</p>
<p>
Los daños amparados bajo la cobertura de responsabilidad civil comprenden: lesiones corporales, enfermedades, muerte, así como el deterioro o destrucción de bienes. Los perjuicios que resulten y el daño moral sólo se cubren cuando sean consecuencia directa e inmediata de los citados daños.
</p>
<p>
No serán materia de cobertura las indemnizaciones adicionales, cuando sean impuestas por autoridad judicial, por las agravantes con las cuales haya actuado para la realización del daño, incluso cuando dichas agravantes sean considerados como parte de una indemnización identificada bajo el rubro o el concepto de daño moral.
</p>
<strong>
Funcion de análisis y defensa jurídica:
</strong>

<p>
Queda a cargo de GMX Seguros y dentro del límite de responsabilidad asegurado en esta póliza, el pago de los gastos de defensa legal del Asegurado.
</p>
<p>
Dichos gastos incluyen la tramitación judicial, la extrajudicial, así como el análisis de las reclamaciones de terceros, aun cuando ellas sean infundadas, las primas de fianzas requeridas procesalmente y las cauciones.
</p>
<p>
La responsabilidad civil materia del seguro se determina conforme a la legislación vigente en los Estados Unidos Mexicanos.
</p>
<strong>Base de indemnizacion:</strong>
<p>
GMX Seguros indemnizará cuando los hechos que causen daño hayan ocurrido durante la vigencia de la póliza.
</p>
<strong>
Coberturas:
</strong>

<p>
•	RC Actividades e Inmuebles.
</p>
<p>
•	RC Legal de empleados y trabajadores.
</p>
<p>
•	RC por daños que sufran las mascotas, bajo custodia y/o control del asegurado, sujeto a un sublímite de $1oo,ooo.oo M.N. por mascota y en el agregado anual de $5oo,ooo.oo M.N.

<strong>Límite máximo de responsabilidad:</strong>
<p>$1,ooo,ooo.oo M.N. por evento y en el agregado anual. Incluye gastos de defensa jurídica.
</p>
<strong>
Condiciones Especiales:
</strong>

<p>•	Todos	los	términos	y	condiciones	conforme	al	texto	de	Seguro	GMX	de	RC	General W_R.C.General_zo.o7.zo16.z.
</p>
<p>•	Gastos de defensa incluidos en la suma asegurada, no se consideran adicionales.
</p>

<strong>Exclusiones:</strong>

<p>
Adicionalmente a las exclusiones mencionadas en el condicionado general de esta póliza, aplican las siguientes:
</p>
<p>
•	RC Profesional.
</p>
<p>
Se entiende como tal, aquella responsabilidad imputable al Asegurado resultante de una acción, error u omisión involuntaria, negligente o imperita en el ejercicio de sus servicios, que en la mayoría de las veces, refiere a errores cometidos por personas que ejercen las actividades propias de la titulación que poseen, de conformidad con la cédula, licencia o permiso de las autoridades de determinado país.
</p>
<p> 
La obligación de la Aseguradora comprenderá la responsabilidad por daños directos al patrimonio de los clientes del Asegurado, traduciéndose a una pérdida o menoscabo sufrido en el patrimonio, así como los perjuicios que necesariamente hubiera obtenido de no haber ocurrido los daños que se causen a los clientes del Asegurado y el daño moral respectivo, como consecuencia de los citados daños.
</p>
<p>
•	Multas y/o sanciones.
</p>
<p>
•	Contaminación.
</p>
<p>
•	RC Patronal y/o compensación laboral.
</p>
<p>
•	Demandas y/o reclamaciones provenientes del extranjero.
</p>
<p>
•	Abuso de confianza.
</p>
<p>
•	Caso fortuito y/o fuerza mayor.
</p>
<p>
•	Entidades y operaciones ubicadas en el extranjero.
</p>
<p>
•	Garantía por el servicio de entrenamiento.
</p>
<p>
•	Reclamaciones por robo y/o pérdida de los accesorios de las mascotas.
</p>
<p>
•	RC por atención medica veterinaria.
</p>
<p>
•	No serán materia de cobertura las reclamaciones derivadas del incumplimiento de obligaciones contraídas por contrato, en el cual se tenga por objeto la ejecución de trabajos en tiempo y calidad convenidos.
</p>
<p>
•	No serán materia de cobertura las indemnizaciones adicionales, cuando sean impuestas por autoridad judicial, por las agravantes con las cuales haya actuado para la realización del daño, incluso cuando dichas agravantes sean considerados como parte de una indemnización identificada bajo el rubro o el concepto de daño moral.
</p>
<p>
•	GMX Seguros no serán responsable de pagar daños y/o costos originados en, basados en, atribuibles a reclamaciones generadas por o resultantes de, directa o indirectamente, total o parcialmente actividad(es) que tengan que ver con países, entidades y/o personas Sancionados por el Departamento de Estado de los Estados Unidos de Norteamérica.
</p>
<strong>
Deducibles:
</strong>
<p>
•	General: 1o% de toda y cada reclamación con mínimo de $4,5oo.oo M.N.
</p>
</div>'; ?>

<style type="text/css">
	#terminos_container u {
	    font-size: 16px;
	    padding: 10px 0px;
	    display: block;
	    color: #000;
	}
	#terminos_container strong {
	    font-size: 14px;
	    color: #000;
	}
</style>
<div class="km-ficha-bg" style="background-image:url(<?php echo getTema()."/images/new/km-ficha/km-bg-ficha.jpg);" ?>" >
	<div class="overlay"></div>
</div>

<div id="terminos_container" class="container">
<?php
	$parrafos = explode("\n", $HTML);
	foreach ($parrafos as $parrafo) {
		echo "<p>".$parrafo."</p>";
	}
?>
</div>

<?php get_footer();  ?>
