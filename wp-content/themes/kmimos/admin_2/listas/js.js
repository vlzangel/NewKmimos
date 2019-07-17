function _new(e) {
	init_modal_2({
		"titulo": e.attr("data-titulo"),
		"modal":  e.attr("data-modal"),
		"info": {
			"ID": e.attr("data-id")
		}
	});
}

function importar_csv(_this){
	jQuery("#"+_this.attr("id")).on("change", function(e){
		files = e.target.files;
		var reader = new FileReader();
        reader.onload = (function(theFile) {
            return function(e) {
            	var temp = String(e.target.result).split(",");
                var words = CryptoJS.enc.Base64.parse(temp[1]);
                var csv = CryptoJS.enc.Utf8.stringify(words);
                jQuery("#importaciones").val( csv );
            };
       })(files[0]);
       reader.readAsDataURL(files[0]);

	});
}