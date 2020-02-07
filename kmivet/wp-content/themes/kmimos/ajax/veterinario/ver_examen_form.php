<div class="contenedor_examen_ver">
	<?php
		$preguntas = get_answers($id);
		
		foreach ($preguntas as $key => $pregunta) {
			echo '
				<div>
					<label>'.$pregunta->question->content.'</label>
					<p>'.$pregunta->content.'</p>
				</div>
			';
		}
	?>
</div>