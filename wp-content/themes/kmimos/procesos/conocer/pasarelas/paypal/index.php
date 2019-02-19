<!DOCTYPE html>
<html>
<head>
	<title>PayPal | Testing</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<script src="https://www.paypal.com/sdk/js?client-id=AVUOYKnU8VsRyGCr1i_CL2vJRG09GdmkCXy8IWqETtAX1ZpW9VUf-V8GIpo1e5-KsGvL8N23E_apik0e"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
</head>
<body>
	<div id="paypal-button-container"></div>

	<script>

	// paypal.Buttons().render('#paypal-button-container');
	$(document).ready(function(){
 
		paypal.Buttons({
			locale: 'es-ES',
			style: {
				size: 'responsive',
				color: 'blue',
				shape: 'pill',
				label: 'pay',
			},
		    createOrder: function(){
			  return fetch('/paypal/create.php')
			    .then(function(res) {
			      return res.json();
			    }).then(function(data) {
			    	console.log(data);
			    	if(data.orderID != ''){
			    	  jQuery.each( data.links, function(i,v){
			    	  	
			    	  		if( v.rel == 'approve' ){
						    	// location.href = v.href;
			    	  		}
			    	  })
			    	}

			      return data.orderID;
			    });
			}
		}).render('#paypal-button-container');

	});
	</script>
</body>
</html>