var lat = null;
var lng = null;
var map = null;
var geocoder = null;
var marker = null;
         
jQuery(document).ready(function(){
     lat = jQuery('#lat').val();
     lng = jQuery('#long').val();
     jQuery('#pasar').click(function(){
        codeAddress();
        return false;
     });

     jQuery('#direccion').on("keypress", function(e){
        /*console.log( e );*/
        if( e.charCode == "13" ){
            codeAddress();
            e.preventDefault();
        }else{
            if( e.key == "Enter" ){
                codeAddress();
                e.preventDefault();
            }
        }
     });
});
     
function initialize() {
    lat = jQuery('#lat').val();
    lng = jQuery('#long').val();
    geocoder = new google.maps.Geocoder();
    if(lat !='' && lng != ''){
        var latLng = new google.maps.LatLng(lat,lng);
    }
    var myOptions = {
        center: latLng,
        zoom: 15,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    marker = new google.maps.Marker({
        map: map,
        position: latLng,
        draggable: true
    });

    google.maps.event.addListener(marker, 'dragend', function(){
        updatePosition(marker.getPosition());
    });
     
}
 
function codeAddress() {
    var estado = jQuery("#estado option:selected").text();
    var delegacion = jQuery("#delegacion option:selected").text();
    var address = document.getElementById("direccion").value;
    
    address = estado+"+"+delegacion+"+"+address;

    console.log( address );

    geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            marker.setPosition(results[0].geometry.location);
            updatePosition(results[0].geometry.location);
            google.maps.event.addListener(marker, 'dragend', function(){
                updatePosition(marker.getPosition());
            });
        } else {
            alert("No podemos encontrar la dirección\nPero no te preocupes, puedes ubicarla directamente en el mapa moviendo el pin.");
        }
    });
  }
   
function updatePosition(latLng) {
   jQuery('#lat').val(latLng.lat());
   jQuery('#long').val(latLng.lng());
}

(function(d, s){
    map = d.createElement(s), e = d.getElementsByTagName(s)[0];
    map.async=!0;
    map.setAttribute("charset","utf-8");
    map.src="//maps.googleapis.com/maps/api/js?key=AIzaSyBdswYmnItV9LKa2P4wXfQQ7t8x_iWDVME&sensor=true&callback=initialize";
    map.type="text/javascript";
    e.parentNode.insertBefore(map, e);
})(document,"script");