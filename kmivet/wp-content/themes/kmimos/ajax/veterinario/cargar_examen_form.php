<div class="contenedor_examen">
	<?php
		$preguntas = get_answers($id);
		
		foreach ($preguntas as $key => $pregunta) {
			echo '
				<div>
					<label>'.$pregunta->question->content.'</label>
					<input type="text" name="preg_'.$pregunta->id.'" value="'.$pregunta->content.'" required />
				</div>
			';
		}
	?>
</div>