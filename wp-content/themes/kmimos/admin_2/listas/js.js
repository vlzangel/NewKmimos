function _new(e) {
	console.log("1");
	init_modal_2({
		"titulo": e.attr("data-titulo"),
		"modal": e.attr("data-modal"),
		"info": {
			"ID": e.attr("data-id")
		}
	});
}