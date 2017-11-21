<footer class="footer">
	<div class="master">
		<div class="texto">
			Descarga nuestros términos y condiciones <a href="#">aquí</a>.<br>
			<img src="img/phone.png" class="phone">01 800 056 4667<br>
			<img src="img/logo-footer.png" class="logo-footer">
		</div>
		<div class="redes">
			<a href="https://www.instagram.com/kmimosmx/?hl=es" target="_blank"><img src="img/r1.png"></a>
			<a href="https://twitter.com/KmimosMx" target="_blank"><img src="img/r2.png"></a>
			<a href="https://www.facebook.com/KmimosMx" target="_blank"><img src="img/r3.png"></a>
			<a href="https://www.youtube.com/channel/UCIOJJlTD1184V_uk2nGUxZw" target="_blank"><img src="img/r4.png"></a>
		</div>
	</div>
</footer>
<script src="./js/jquery.js"></script>
<script src="./js/scripts.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>


<!-- BEGIN Modal -->

<!-- Modal -->
<div class="modal fade" id="modal_info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    
    <div class="modal-content" style="background: #de163b;">
      <div class="modal-body text-center" style=" 
      	background-image: url(img/bolsa.png); 
      	padding-right:0px;
      	padding-bottom:0px;
      	color:white;
  	    background-size: cover;
	    background-repeat: no-repeat;">
    	<div class="modal-header" style="    border-bottom: 0px solid transparent;">
	        <!-- button type="button" class="close" data-dismiss="modal" aria-label="Close" style="background: transparent;
			    border: 0px;
			    right: 12px;
			    position: absolute;
			    top: 7px;">
	        	<img data-dismiss="modal" src="img/cerrar.png" width="40px" height="40px">
	        </button -->
                 
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="
			    background: transparent;
			    border: 0px;
			    right: 12px;
			    position: absolute;
			    top: 5px;
			    font-size: 30px;
			    font-weight: bold;
			    ">
	        	<img data-dismiss="modal" src="img/close_white.png" width="20px" height="20px">
	        </button>                
      	</div>
  			
      		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-center">
      			<img src="img/texto.gif" class="img-responsive img-texto-modal" style="position: absolute;
    left: 65px;z-index:3;">
      		</div>
      		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 pull-right" style="padding-right:0px; text-align:right;">
      			<img src="img/perro_pata_rosa.png" class="img-responsive pull-right" style="border-radius: 6px;">
      		</div>
      		<div class="clearfix"></div>
      </div>
    </div>
  </div>
</div>
<!-- END Modal -->
<script type="text/javascript">
	setTimeout(mostrar_modal,3600);
	function mostrar_modal(){
		$('#modal_info').modal('show');
	}
</script>
<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-56422840-1', 'auto');
	  ga('send', 'pageview');

	</script>	
</body>
</html>