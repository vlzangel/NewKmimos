<?php
	$PopUpSection='home';
    $bodyClass=get_body_class();
    if(array_key_exists('wlabel',$_SESSION)){
    	$PopUpSection=$_SESSION['wlabel'];
    }else if(in_array('page-template-blog',$bodyClass)){
    	$PopUpSection='PopUpBlog';
    }else if(in_array('single-post',$bodyClass)){
    	$PopUpSection='PopUpBlogPost';
    }

	$checkparam = 'true';
	if(isset($_GET['utm_campaign'])){
	    if($_GET['utm_campaign']=='landing_white_label_volaris_kmimos'){
	        $checkparam = 'false';
	    }
	}
	    
/*	$HTML .= "
	<script type=\"text/javascript\">
	    var checkparam = {$checkparam};
		function SubscribePopUp_Create(html){
		    var element = '#message.Msubscribe';
		    if(jQuery(element).length==0){
		        jQuery('body').append('<div id=\"message\" class=\"Msubscribe\"></div>');
		        jQuery(element).append('<div class=\"contain\"></div>');
		    }

		    jQuery(element).find('.contain').html(html);
		    jQuery(element).fadeIn(5,function(){ 
		    });
		}

		jQuery(document).ready(function(e){
	        var body= jQuery('body');
	        if((body.hasClass('home') && checkparam) || (body.hasClass('page-template-blog') || body.hasClass('single-post'))){
	            SubscribeTime = setTimeout(function(){
	                SubscribeSite();
	            }, 10);
	        }
	    });";

	   	if( $PopUpSection == "petco" ){
	   		$HTML .= " 
	   			function SubscribeSite(){
			        clearTimeout(SubscribeTime);

			        var dog = '';

			        var html = 
			        '<div id=\"PageSubscribe\" style=\"padding: 0px;\">'+
			        '	<img src=\"".getTema()."/images/new/banner_petco_3.png\" class=\"banner_home_petco\" >'+
			        '	<img src=\"".getTema()."/images/new/banner_petco_movil_4.png\" class=\"banner_home_petco_movil\" >'+
			        '	<div class=\"banner_home_petco_components\"> '+
			        ' 		<i class=\"exit fa fa-times\" aria-hidden=\"true\" onclick=\"SubscribePopUp_Close(\'#message.Msubscribe\')\"></i>' +
			        '		<div class=\"section section1\">'+
			        '			<div class=\"section section2\">". subscribe_input($PopUpSection)."</div>'+
			        '			<input type=\"hidden\" id=\"wlabelSubscribe\" value=\"".$PopUpSection."\" >'+
			        '		</div>' +
			        '	</div>' +
			        '</div>';

			        SubscribePopUp_Create(html);
			    }
	   		";
	   	}else{
	   		$HTML .= "
	   			function SubscribeSite(){
			        clearTimeout(SubscribeTime);

			        var dog = '<img height=\"70\" align=\"bottom\" src=\"https://www.kmimos.com.mx/wp-content/uploads/2017/07/propuestas-banner-09.png\">' +
			            '<img height=\"20\" align=\"bottom\" src=\"https://www.kmimos.com.mx/wp-content/uploads/2017/07/propuestas-banner-10.png\">';

			        var html='<div id=\"PageSubscribe\"><i class=\"exit fa fa-times\" aria-hidden=\"true\" onclick=\"SubscribePopUp_Close(\'#message.Msubscribe\')\"></i>' +
			            '<div class=\"section section1\"><span>G&aacute;nate <strong>$50</strong> pesos en tu primera reserva</span><br>&#8216;&#8216;Aplica para clientes nuevos&#8217;&#8217;<div class=\"images\">'+dog+'</div></div>' +
			            '<div class=\"section section2\"><span><strong>&#161;SUSCR&Iacute;BETE!</strong> y recibe el Newsletter con nuestras <strong>PROMOCIONES, TIPS DE CUIDADOS PARA MASCOTAS,</strong> etc.!</span>". subscribe_input($PopUpSection)." </div>' +
			        '	<input type=\"hidden\" id=\"wlabelSubscribe\" value=\"".$PopUpSection."\" >'+
			            '</div>';

			        SubscribePopUp_Create(html);
			    }
	   		";
	   	} $HTML .= "
	</script>";*/

?>

	<script type="text/javascript">
	    var checkparam = <?= $checkparam ?>;
		function _SubscribePopUp_Create(html){
		    var element = '#message.Msubscribe';
		    if(jQuery(element).length == 0){
		        jQuery('body').append('<div id="message" class="Msubscribe"></div>');
		        jQuery(element).append('<div class="contain"></div>');
		    }

		    jQuery(element).find('.contain').load(html, {
		    	HOME: HOME,
		    	FORM: "<?= str_replace('"', '\"', subscribe_input($PopUpSection) ); ?>",
		    	SECCION: "<?= $PopUpSection ?>"
		    });
		    jQuery(element).fadeIn(5,function(){});
		}

		jQuery(document).ready(function(e){
	        var body= jQuery('body');
	        if((body.hasClass('home') && checkparam) || (body.hasClass('page-template-blog') || body.hasClass('single-post') || body.hasClass('page-template-page-paseos-php')  ) ){
	            SubscribeTime = setTimeout(function(){
	                SubscribeSite();
	            }, 10);
	        }
	    });

		function SubscribeSite(){
	        clearTimeout(SubscribeTime);
	        _SubscribePopUp_Create( HOME+"/template/banners/banner_home.php" );
	    }
	</script>