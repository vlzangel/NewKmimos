<?php
	extract($_GET);

	switch ( $tienda ) {
		case 'apple_kmimos':
		case 'apple_wlabel':
			echo '<meta HTTP-EQUIV="REFRESH" CONTENT="5;URL=https://apps.apple.com/mx/app/kmimos/id1247272074">';
		break;
		case 'android_kmimos':
		case 'android_wlabel':
			echo '<meta HTTP-EQUIV="REFRESH" CONTENT="5;URL=https://play.google.com/store/apps/details">';
		break;
	}
?>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-56422840-1', 'auto');
    ga('send', 'pageview');
</script>