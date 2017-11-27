function init_modal(data){
	jQuery(".modal > div > span").html(data["titulo"]);
	jQuery.ajax({
        async:true, cache:false, type: 'POST', url: TEMA+"/admin/backend/"+data["modulo"]+"/modales/"+data["modal"]+".php",
        data: data["info"], 
        success:  function(HTML){
            jQuery(".modal > div > div").html( HTML );
            jQuery(".modal").css("display", "block");
            jQuery("body").css("overflow", "hidden");

        },
        beforeSend:function(){},
        error:function(e){
        	console.log(e);
        }
    });
    jQuery("#close_modal").on("click", function(e){
        cerrar(e);
    });

}

function cerrar(e){
    jQuery(".modal").css("display", "none");
    jQuery("body").css("overflow", "auto");
}