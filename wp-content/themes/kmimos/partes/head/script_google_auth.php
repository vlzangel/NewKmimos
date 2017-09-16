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
					if( obj.length > 0){
					  jQuery.each( obj, function(i, o){ 
					  	attachSignin(o);
					  });
					}

				    var obj = document.getElementsByClassName("google_login");
					if( obj.length > 0){
					  jQuery.each( obj, function(i, o){ 
					  	attachSignon(o);
					  });
					}

				});
			};

			function attachSignin(element) {
				auth2.attachClickHandler(element, {},
				    function(googleUser) {

						$(\'.social_google_id\').val( googleUser.getBasicProfile().getId() );

				      	$(\'.social_email\').val( googleUser.getBasicProfile().getEmail() );
						$(\'.social_email\').parent(\'div\').addClass(\'focused\');
						
						var name = googleUser.getBasicProfile().getName().split(" ");
						if( name.length > 0 ){
					      	$(\'.social_firstname\').val( name[0] );
							$(\'.social_firstname\').parent(\'div\').addClass(\'focused\');
						}
						if( name.length > 1 ){
					      	$(\'.social_lastname\').val( name[1] );
							$(\'.social_lastname\').parent(\'div\').addClass(\'focused\');
					    }

					    $(".social-next-step").click();

				    }, function(error) {});
			}
			function attachSignon(element) {
				auth2.attachClickHandler(element, {},
				    function(googleUser) {

						$(\'.social_google_id\').val( googleUser.getBasicProfile().getId() );

				      	social_auth( googleUser.getBasicProfile().getId() );

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

