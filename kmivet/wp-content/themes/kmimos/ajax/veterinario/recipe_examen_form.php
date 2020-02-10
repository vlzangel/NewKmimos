<div>
	
	<div class="search_medicamento_campos">
		<div class="search_medicamento">
			<label>Medicamento</label>
			<input type="text" id="search_medicamento_input" name="query" />
			<ul class="search_list"></ul>
		</div>

		<div>
			<label>Indicaciones</label>
			<input type="text" id="indications" name="indications" />
			<input type="hidden" id="medicine_id" name="medicine_id" />
			<input type="hidden" id="appointment_id" name="appointment_id" value="<?= $id ?>" />
		</div>
	</div>

	<div class="">
		<table id="search_medicamento_agregados" class="table table-striped table-bordered nowrap dataTable no-footer">
			<thead>
				<tr>
					<th>Medicamento</th>
					<th>Indicación</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="2">
						No hay medicamentos recetados
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div>
		<label>Tratamiento</label>
		<textarea id="tratamiento" name="tratamiento" required></textarea>
	</div>
</div>

<script type="text/javascript">
	jQuery("#search_medicamento_input").on('keyup', function(e){
		var v = jQuery(this).val();
		if( v.length > 3 ){
			jQuery.post(
				AJAX+"?action=kv&m=veterinario&a=query",
				{ query: v },
				function(r){
					console.log(r);
					if( r.length > 0 ){
						var HTML = '';
						jQuery.each(r, function(i, v){
							HTML += '<li data-id="'+v.id+'">'+v.name+' ('+v.presentation+')</li>';
						});
						jQuery(".search_list").html(HTML);
						jQuery(".search_list li").unbind('click').bind('click', function(e){
							var id = jQuery(this).data("id");
							jQuery("#medicine_id").val(id);
							jQuery("#search_medicamento_input").val(jQuery(this).html());
							jQuery(".search_list").css("display", "none");
						});
						jQuery(".search_list").css("display", "block");
					}else{
						jQuery(".search_list").css("display", "none");
					}
				},
				'json'
			);
		}
	});
	recipe_get();
</script>