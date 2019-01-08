jQuery( document ).ready(function() {

    initImg("portada_2");

});

function press_btn(_this){
    jQuery( _this.attr('data-id') ).click();
}
