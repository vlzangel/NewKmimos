jQuery(document).ready(function(){
	jQuery("#adminmenumain").remove();
	jQuery("#adminmenuwrap").remove();
	jQuery("#wpadminbar").remove();
	jQuery("#screen-meta-links").remove();
	jQuery("#screen-meta").remove();
	jQuery("#wpfooter").remove();

	jQuery("#wpcontent").css("width", "90%");
	jQuery("#wpcontent").css("margin", "0 auto");

	jQuery("#wpcontent").before("<header style='text-align:center;width:95%;margin:0 auto;'><img src='"+HOME+"/recursos/img/GENERALES/PNG/logo-verde.png' width='150px' ><hr></header>");
});
