<div class="contenedor_examen_ver">
	<div>
		<label>Diagnóstico</label>
		<div class="diagnostico_container">
			<?php
				$info = get_appointment($id);
				echo $info["result"]->diagnostic->diagnostic->title."<br>";
				echo "<strong>Notas: </strong> ".$info["result"]->diagnostic->notes;
			?>
		</div>
	</div>
</div>

<div class="contenedor_examen_ver">
	<div>
		<label>Medicamentos Recetados</label>
		<?php
			$res = get_medicines($id);
			foreach ($res['r'] as $key => $med) {
				echo "<div class='medicamento_item'><strong>{$med->medicine->name} {$med->medicine->presentation}</strong> ({$med->indication})</div>";
			}
		?>
	</div>
</div>