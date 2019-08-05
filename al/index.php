<?php
	extract($_GET);

	switch ( $t ) {
		case 'apple_kmimos':
		case 'apple_wlabel':
			echo '<meta http-equiv="refresh" content="1;URL=https://apps.apple.com/mx/app/kmimos/id1247272074" >';
		break;
		case 'android_kmimos':
		case 'android_wlabel':
			echo '<meta http-equiv="refresh" content="1;URL=https://play.google.com/store/apps/details?id=com.it.kmimos" >';
		break;
	}
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-56422840-1', 'auto');
    ga('send', 'pageview');
</script>