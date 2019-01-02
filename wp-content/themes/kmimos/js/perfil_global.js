jQuery( document ).ready(function() {

    initImg("portada");

});

function press_btn(_this){
    jQuery( _this.attr('data-id') ).click();
}
