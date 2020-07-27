/*
You can use this file with your scripts.
It will not be overwritten when you upgrade solution.
*/
$(document).ready(function() {
	$(window).scroll(function() {
		if ($(window).width() > 1280) {
			if ($(window).scrollTop() > 240) {
				$("#header .catalog_menu, .iblock-search").addClass("active");
			}
			else {
				$("#header .catalog_menu, .iblock-search").removeClass("active");
			}
		}
	});
});