<?php
$HTML .= '
<script>
  function statusChangeCallback(response) {
    if (response.status === \'connected\') {
      KmimosAPI();
    }
  }

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

  function KmimosAPI() {
    FB.api(\'/me\', {fields: \'last_name, email, name, id\'}, function(response) {

		document.getElementById(\'status\').innerHTML = "data: " +
			"Name: " + response.name +
			"Email: " + response.email +
			"ID: " + response.id
		;

    });
  }

  function login_facebook(){

    FB.getLoginStatus(function(response) {
      if(response.status == \'connected\'){
        KmimosAPI();
      }else{
       FB.login();
      }
    });



  }

</script>';