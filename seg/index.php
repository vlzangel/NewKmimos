<!DOCTYPE html>
<html>
	<head>
		
		<!-- Google Tag Manager -->
			<script>
				(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
				new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
				j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
				'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
				})(window,document,'script','dataLayer','GTM-5SG9NM');
			</script>
		<!-- End Google Tag Manager -->
			<script>
				window.dataLayer = window.dataLayer || [];
				function gtag(){dataLayer.push(arguments);}
				gtag('js', new Date());
				gtag('config', 'UA-56422840-1');
			</script>
		<!-- Facebook Pixel Code --> 
			<script> !function(f,b,e,v,n,t,s) {if(f.fbq)return;n=f.fbq=function(){n.callMethod? n.callMethod.apply(n,arguments):n.queue.push(arguments)}; if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0'; n.queue=[];t=b.createElement(e);t.async=!0; t.src=v;s=b.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t,s)}(window,document,'script', 'https://connect.facebook.net/en_US/fbevents.js');  fbq('init', '105485829783897');  fbq('track', 'PageView'); </script> <noscript>  <img height='1' width='1' src='https://www.facebook.com/tr?id=105485829783897&ev=PageView&noscript=1'/> </noscript>
		<!-- End Facebook Pixel Code -->

		<script type="text/javascript" src="jquery.min.js"></script>
		<script type="text/javascript">
			jQuery( document ).ready(function() {

				ga("send", "event", "wlabel", "click", "<?= ($_GET['banner']) ?>_traking_banner", "1");
				fbq("track", "<?= ($_GET['banner']) ?>_traking_banner"); 

				setTimeout(function(){
					Location.href = '<?= base64_decode($_GET['url']) ?>'; 
				}, 1000);
			});
		</script>
	</head>
	<body>

	</body>
</html>
<div id="fb-root"></div>