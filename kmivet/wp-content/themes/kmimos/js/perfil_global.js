jQuery( document ).ready(function() {
    initImg("portada_2");
});

function initModal(id, CB){
    jQuery("#"+id+" form").on('submit', function(e){
    	e.preventDefault();

    	var t = jQuery("#"+id+" .modal-footer .btn-primary").html();
    	jQuery("#"+id+" .modal-footer .btn-primary").html("Procesando...");
    	jQuery("#"+id+" .modal-footer .btn-primary").prop("disabled", true);

    	jQuery.post(
			AJAX+"?action=kv",
			jQuery(this).serialize(),
			function(data){
				if( data.status ){
					CB(data);
					if( _table != undefined ){
						_table.ajax.reload();
					}
				}else{
					alert( data.error );
				}
		    	jQuery("#"+id+" .modal-footer .btn-primary").html(t);
		    	jQuery("#"+id+" .modal-footer .btn-primary").prop("disabled", false);
			}, 'json'
		);
    });
}

function openModal(modalId, title, btn, m, a, id){
	jQuery("#"+modalId+" .modal-title").html(title);
	if( btn == '' ){
		jQuery("#"+modalId+" .modal-footer").css('display', 'none');
	}else{
		jQuery("#"+modalId+" .modal-footer").css('display', 'block');
		jQuery("#"+modalId+" .modal-footer .btn-primary").html(btn);
	}
	jQuery("#"+modalId+" [name='m']").val(m);
	jQuery("#"+modalId+" [name='a']").val(a);
	jQuery("#"+modalId+" [name='id']").val( id );

	jQuery.post(
		AJAX+"?action=kv&m="+m+"&a="+a+"_form",
		{},
		function(HTML){
			jQuery("#"+modalId+" .modal-body").html(HTML);
			jQuery("#"+modalId).modal('show');
		}
	);
}

function CB_perfil(URL){
	// console.log("CallBack");
	jQuery(".img-circle").attr("src", RAIZ+"imgs/Temp/"+URL);
	jQuery.post(
		RAIZ+"imgs/perfil.php",
		{
			user_id: USER_ID,
			img: URL,
			tipo: TIPO_USER
		},
		function(data){
			console.log( data );
		}
	);
}

function press_btn(_this){
    jQuery( _this.attr('data-id') ).click();
}

function btn_load_on(_this){
	var clase = _this.attr("class");
	_this.attr("class", "fas fa-circle-notch fa-spin");
	return clase;
}

function btn_load_off(_this, clase){
	_this.attr("class", clase);
}