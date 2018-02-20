jQuery(document).ready(function(){

 
	/*jQuery('[data-group="presentacion"] article').on('click', function(){
		jQuery('.hidden').removeClass('hidden');
		jQuery('[data-group="presentacion"]').addClass('hidden');
	});*/
 

		

    jQuery.post(
        HOME+"/procesos/faq/categorias.php",
        {},
        function(data){       	
        	var destacados= data.cantidad;
        	var iddestacados= data.id;
        	var destacados2= data.cantidad2;
        	var iddestacados2= data.id2;

        	
	        	if(destacados>=2){
					jQuery("#taxonomy-seccion").on("click", function(e){
					    	var id = e.target.id;	
					    	if(jQuery('#'+id).prop('checked') ) {
							    idnew=id.replace("in-seccion-", "");
							    if(idnew==iddestacados){
							    	alert('Ya se seleccionó el maximo de post para esta sección');
							        jQuery("#"+id).prop("checked", false);

							    }
						}
					    	


					});


			}

			 if (destacados2>=2){
				jQuery("#taxonomy-seccion").on("click", function(e){
					    	var id = e.target.id;	
					    	if(jQuery('#'+id).prop('checked') ) {
							    idnew=id.replace("in-seccion-", "");
							    if(idnew==iddestacados2){
							    	alert('Ya se seleccionó el maximo de post para esta sección');
							        jQuery("#"+id).prop("checked", false);

							    }
						}
					    	


					});



			}
         
        },
            "json"

    );





    


		
 			
	
});

