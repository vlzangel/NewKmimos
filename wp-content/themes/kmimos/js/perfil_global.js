jQuery( document ).ready(function() {

    initImg("portada_2");

});

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
