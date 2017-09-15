<?php
$HTML .= '
<script>
  function statusChangeCallback(response) {}

  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  	FB.init({
  	  appId    : \'264829233920818\',
  	  cookie   : true,
  	  xfbml    : true,
  	  version  : \'v2.8\'
  	});

  	FB.getLoginStatus(function(response) {
  	  statusChangeCallback(response);
  	});
  };

  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, \'script\', \'facebook-jssdk\'));


  function login(){
    FB.login(function(response) {
      if (response.authResponse) {
        KmimosAPI();
        if (response.status == "connected") {
          KmimosAPI();
        }
      }
    });
  }

  function KmimosAPI() {
    FB.getLoginStatus(function(response) {
      if (response.status == \'connected\') {
        FB.api(\'/me\', {fields: \'first_name, last_name, email, name, id\'}, function(response) {
          $( ".social_facebook_id" ).val( response.id );
          $( ".social_firstname" ).val( response.first_name );
          $( ".social_lastname" ).val( response.last_name );
          $( ".social_email" ).val( response.email );
          $( ".social_firstname" ).val( response.name );
          $( ".social-next-step" ).click();
          console.log("conectado");
        });
        FB.logout();

      }
    });

  }

  function login_facebook(){
    FB.getLoginStatus(function(response) {
      if (response.status == \'connected\') {
        KmimosAPI();
      }else{
        login();
      }
    });
  
  }

</script>';