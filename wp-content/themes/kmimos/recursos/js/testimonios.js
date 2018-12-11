jQuery( document ).ready(function() {

    jQuery(".img_comp").on("click", function(e){
        
        if( jQuery(this).attr("data-status") == "" || jQuery(this).attr("data-status") == undefined ){

            jQuery(".img_comp").attr("data-status", "no_activo");
        

            if( jQuery(this).attr("id") == "img_comp_3" ){
            
                jQuery("#img_comp_2").animate({
                    "top": "15px",
                    "right": "-50px",
                    "z-index": "20",
                    "width": "50%",
                    "height": "50%",
                    "opacity": "0.5",
                }, { duration: 500, queue: false });


                jQuery("#img_comp_3").animate({
                    "top": "40px",
                    "right": "60px",
                    "z-index": "30",
                    "width": "90%",
                    "height": "90%",
                    "opacity": "1",
                }, { duration: 500, queue: false });

                jQuery("#img_comp_1").animate({
                    "top": "250px",
                    "right": "-70px",
                    "z-index": "20",
                    "width": "55%",
                    "height": "55%",
                    "opacity": "0.5",
                }, { duration: 500, queue: false });

                setTimeout(function(){ 
                    jQuery("#img_comp_1").attr("data-id", "img_2");
                    jQuery("#img_comp_2").attr("data-id", "img_3");
                    jQuery("#img_comp_3").attr("data-id", "img_1");

                    jQuery('[data-id="img_1"]').attr("id", "img_comp_1");
                    jQuery('[data-id="img_2"]').attr("id", "img_comp_2");
                    jQuery('[data-id="img_3"]').attr("id", "img_comp_3");

                    jQuery(".img_comp").attr("data-status", "");
                    
                }, 1000);

            }

            if( jQuery(this).attr("id") == "img_comp_2" ){

                jQuery("#img_comp_2").animate({
                    "top": "40px",
                    "right": "60px",
                    "z-index": "30",
                    "width": "90%",
                    "height": "90%",
                    "opacity": "1",
                }, { duration: 500, queue: false }); // Img 1

                jQuery("#img_comp_3").animate({
                    "top": "250px",
                    "right": "-70px",
                    "z-index": "20",
                    "width": "55%",
                    "height": "55%",
                    "opacity": "0.5",
                }, { duration: 500, queue: false }); // Img 2
            
                jQuery("#img_comp_1").animate({
                    "top": "15px",
                    "right": "-50px",
                    "z-index": "20",
                    "width": "50%",
                    "height": "50%",
                    "opacity": "0.5",
                }, { duration: 500, queue: false }); // Img 3

                setTimeout(function(){ 
                    jQuery("#img_comp_1").attr("data-id", "img_3");
                    jQuery("#img_comp_2").attr("data-id", "img_1");
                    jQuery("#img_comp_3").attr("data-id", "img_2");

                    jQuery('[data-id="img_1"]').attr("id", "img_comp_1");
                    jQuery('[data-id="img_2"]').attr("id", "img_comp_2");
                    jQuery('[data-id="img_3"]').attr("id", "img_comp_3");

                    jQuery(".img_comp").attr("data-status", "");
                }, 1000);

            }

            if( jQuery(this).attr("id") == "img_movil_comp_1" ){

                jQuery("#img_movil_comp_1").animate({
                    "top": "10%",
                    "left": "10%",
                    "width": "80%",
                    "opacity": "1",
                    "z-index": "30",
                }, { duration: 500, queue: false });

                jQuery("#img_movil_comp_2").animate({
                    "top": "20%",
                    "left": "-50%",
                    "width": "60%",
                    "opacity": "0.5",
                    "z-index": "20",
                }, { duration: 500, queue: false });

                jQuery("#img_movil_comp_3").animate({
                    "top": "20%",
                    "left": "50%",
                    "width": "60%",
                    "z-index": "20",
                    "opacity": "0.5",
                }, { duration: 500, queue: false });

                setTimeout(function(){ 
                    
                    jQuery("#img_movil_comp_1").attr("data-id", "img_movil_3");
                    jQuery("#img_movil_comp_2").attr("data-id", "img_movil_1");
                    jQuery("#img_movil_comp_3").attr("data-id", "img_movil_2");

                    jQuery('[data-id="img_movil_1"]').attr("id", "img_movil_comp_1");
                    jQuery('[data-id="img_movil_2"]').attr("id", "img_movil_comp_2");
                    jQuery('[data-id="img_movil_3"]').attr("id", "img_movil_comp_3");

                    jQuery(".img_comp").attr("data-status", "");
                    
                }, 1000);

            }

            if( jQuery(this).attr("id") == "img_movil_comp_2" ){

                jQuery("#img_movil_comp_2").animate({
                    "top": "10%",
                    "left": "10%",
                    "width": "80%",
                    "opacity": "1",
                    "z-index": "30",
                }, { duration: 500, queue: false });

                jQuery("#img_movil_comp_3").animate({
                    "top": "20%",
                    "left": "-50%",
                    "width": "60%",
                    "opacity": "0.5",
                    "z-index": "20",
                }, { duration: 500, queue: false });

                jQuery("#img_movil_comp_1").animate({
                    "top": "20%",
                    "left": "50%",
                    "width": "60%",
                    "opacity": "0.5",
                    "z-index": "20",
                }, { duration: 500, queue: false });


                setTimeout(function(){ 
                    
                    jQuery("#img_movil_comp_1").attr("data-id", "img_movil_2");
                    jQuery("#img_movil_comp_2").attr("data-id", "img_movil_3");
                    jQuery("#img_movil_comp_3").attr("data-id", "img_movil_1");

                    jQuery('[data-id="img_movil_1"]').attr("id", "img_movil_comp_1");
                    jQuery('[data-id="img_movil_2"]').attr("id", "img_movil_comp_2");
                    jQuery('[data-id="img_movil_3"]').attr("id", "img_movil_comp_3");

                    jQuery(".img_comp").attr("data-status", "");
                    
                }, 1000);

            }

        }

    });
    
});