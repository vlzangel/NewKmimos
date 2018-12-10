jQuery( document ).ready(function() {

    jQuery(".img_comp").on("click", function(e){
        

        if( jQuery(this).attr("data-id") == "img_3" ){
        
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
            }, 1000);

        }

        if( jQuery(this).attr("data-id") == "img_2" ){

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
            }, 1000);

        }

    });
    
});