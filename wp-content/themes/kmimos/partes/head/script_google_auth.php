<?php

	// *******************************
	// Google Oauth
	// *******************************
	$HTML .= '
		<script src="https://apis.google.com/js/api:client.js"></script>
		<script>
			var googleUser = {};
			var startApp = function() {
			gapi.load(\'auth2\', function(){
			  auth2 = gapi.auth2.init({
			    client_id: \'119129240685-fhsdkrcqqcpac4r07at7ms5k2mko3s0g.apps.googleusercontent.com\',
			    cookiepolicy: \'single_host_origin\',
			  });
			  attachSignin(document.getElementById(\'customBtn\'));
			  attachSignin(document.getElementById(\'customBtn1\'));
			  attachSignin(document.getElementById(\'customBtn2\'));
			});
			};

			function attachSignin(element) {
				console.log(element.id);
				auth2.attachClickHandler(element, {},
				    function(googleUser) {
				      	document.getElementById(\'google_auth_id\').innerText = "" +
				      		"<br>ID: " + googleUser.getBasicProfile().getId() +
				      		"<br>Name: " + googleUser.getBasicProfile().getName() +
				      		"<br>Email: " + googleUser.getBasicProfile().getEmail()
				      	;
				    }, function(error) {
				    <!--alert(JSON.stringify(error, undefined, 2));-->
				    });
			}
		</script>		
	';
	// ***********************************************
	// Funciones en [ googleUser.GetBasicProfile ]
	// ***********************************************
	// getBasicProfile().getId()
	// getBasicProfile().getName()
	// getBasicProfile().getGivenName()
	// getBasicProfile().getFamilyName()
	// getBasicProfile().getImageUrl()
	// getBasicProfile().getEmail()






	// *******************************
	// Estilos de prueba
	// *******************************
	// $HTML .= '
	// 		<style type="text/css">
	// 	#customBtn {
	// 	  display: inline-block;
	// 	  background: white;
	// 	  color: #444;
	// 	  width: 190px;
	// 	  border-radius: 5px;
	// 	  border: thin solid #888;
	// 	  box-shadow: 1px 1px 1px grey;
	// 	  white-space: nowrap;
	// 	}
	// 	#customBtn:hover {
	// 	  cursor: pointer;
	// 	}
	// 	span.label {
	// 	  font-family: serif;
	// 	  font-weight: normal;
	// 	}
	// 	span.icon {
	// 	  background: url(\'https://google-developers.appspot.com/identity/sign-in/g-normal.png\') transparent 5px 50% no-repeat;
	// 	  display: inline-block;
	// 	  vertical-align: middle;
	// 	  width: 42px;
	// 	  height: 42px;
	// 	}
	// 	span.buttonText {
	// 	  display: inline-block;
	// 	  vertical-align: middle;
	// 	  padding-left: 42px;
	// 	  padding-right: 42px;
	// 	  font-size: 14px;
	// 	  font-weight: bold;
	// 	  /* Use the Roboto font that is loaded in the <head> */
	// 	  font-family: \'Roboto\', sans-serif;
	// 	}
	// 	</style>
	// ';