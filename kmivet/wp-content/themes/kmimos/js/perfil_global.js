jQuery( document ).ready(function() {

    initImg("portada_2");

});

function initModal(id, CB){
    jQuery("#"+id+" form").on('submit', function(e){
    	e.preventDefault();
    	jQuery.post(
			AJAX+"?action=kv",
			jQuery(this).serialize(),
			function(data){
				CB(data);
				if( _table != undefined ){
					_table.ajax.reload();
				}
			}, 'json'
		);
    });
}

function openModal(modalId, title, btn, m, a, id){
	jQuery("#"+modalId+" .modal-title").html(title);
	jQuery("#"+modalId+" .modal-footer .btn-primary").html(btn);
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
