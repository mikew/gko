document.observe('dom:loaded', function() {
	var flashes = $$('.flash');
	// alert(success.length);
	if(flashes.length > 0) {
		new PeriodicalExecuter(function() {
			flashes.each(function(flash) {
				flash.fade();
			});
		}, 3);
	}
});