<input type="hidden" id="modal_action" value="delete" />
<div>
	<strong style="font-size: 16px;">¿Esta seguro de eliminar este <?= $sin ?>?</strong>
	<div style="padding: 10px 15px 0px;">
		<table>
			<tr>
				<td> <strong>Nombre</strong>: </td>
				<td> &nbsp;&nbsp; <?= $info->nombre ?> </td>
			</tr>
		</table>
	</div>
	<div class="alert alert-danger" role="alert" style="margin: 15px 0px 0px !important;">
		Si continua, todos los datos serán borrados del servidor.
	</div>
</div>