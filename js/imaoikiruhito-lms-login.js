(function($) {
	$(document).ready(function($) {
		$('#login').before('<header class="iihlms-login-header"><div class="iihlms-login-header-title-text-wrap"><a href="' + iihlms_customlogin.url + '" class="iihlms-login-header-title-text">' + iihlms_customlogin.sitename + '</a></div></header>');
	});
})(jQuery);
