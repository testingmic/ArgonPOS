$(() => {
	$(`button[class~="validate-account"]`).on('click', function(e) {
		$(`div[class="tabset"] input[id="tab1"]`).trigger('click');
	});
});