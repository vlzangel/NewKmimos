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

				  var obj = document.getElementsByClassName("google_auth");
				  $.each( obj, function(i, o){ 
				  	attachSignin(o);
				  });

				});
			};

			function attachSignin(element) {
				auth2.attachClickHandler(element, {},
				    function(googleUser) {
						$(\'.social_google_id\').val( googleUser.getBasicProfile().getId() );
				      	$(\'.social_email\').val( googleUser.getBasicProfile().getEmail() );
						var name = googleUser.getBasicProfile().getName().split(" ");
						if( name.length > 0 ){
					      	$(\'.social_firstname\').val( name[0] );
						}
						if( name.length > 1 ){
					      	$(\'.social_lastname\').val( name[1] );
					    }

					    $(".social-next-step").click();

				    }, function(error) {});
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

