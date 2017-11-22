//FULL WINDOW
function contenerdor(element){
    var height=jQuery(window).height();
    jQuery(element).css({'height':height+'px'});
}

function all_contain_window(element){
    jQuery('.contain_window').each(function(index, element) {
        contain_window(this);
    });
}

// jQuery(document).ready(function(e){
//     all_contain_window();
//     jQuery(window).resize(function(){
//         all_contain_window();
//     });
// });



// jQuery(document).on('click','section .down',function(e){
//     var section = jQuery(this).closest('section').next();
//     if(section.length>0){
//         jQuery('html, body').animate({scrollTop : section.offset().top+'px'});
//     }
// });
