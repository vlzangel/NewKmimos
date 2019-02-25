<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<!-- Load Facebook SDK for JavaScript -->
<div id="fb-root"></div>
<script>
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
window.fbAsyncInit = function() {
	FB.init({
		appId            : '264829233920818',
		autoLogAppEvents : true,
		xfbml            : true,
		version          : 'v3.2'
	});
};
</script>
<!-- Your customer chat code -->
<div class="fb-customerchat"
  attribution=setup_tool
  page_id="361445711358167">
</div>

</body>
</html>