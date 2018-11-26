jQuery(document).ready(function() {

	jQuery("#form").on("submit", function(e){
		e.preventDefault();

		jQuery("#btn").val("Procesando...");
		jQuery("#btn").prop("disabled", true);

		var data = new FormData();
	    jQuery.each(jQuery('input[type=file]')[0].files, function(i, file) {
	        data.append(i, file);
	    });

	    var other_data = jQuery(this).serializeArray();
	    jQuery.each(other_data,function(key,input){
	        data.append(input.name,input.value);
	    });

	    jQuery.ajax({
	        url: jQuery(this).attr("action"),
	        data: data,
	        cache: false,
	        contentType: false,
	        processData: false,
	        type: 'POST',
	        success: function(data){
	        	jQuery("#btn").val("Procesar");
				jQuery("#btn").prop("disabled", false);
				var r = String(data).trim().split("-");
				if( r[0] == "error" ){
					alert(r[1]);
				}else{
	            	console.log( String(data).trim() );
					alert("Data procesada exitosamente!");
				}
	        }
	    });

	});
} );
