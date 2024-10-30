(function($) {
	$(document).ready(function($) {
		$("#nav").html( '<div class="nav-backtologin"><a href="' + iihlms_customlogin.login_url + '">&lt; ' + iihlms_customlogin.return_to_login + '</a></div><span id="login-password-reset-css"></span>' );
		$("#login").append('<p class="iihlms-login-footer">' + iihlms_customlogin.sitename + '</p>');
	});
})(jQuery);
