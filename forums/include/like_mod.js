function get_url_param(sLink, sParam) {
	var sPageURL = sLink.split('?')[1];
	var sURLVariables = sPageURL.split('&');
	for (var i = 0; i < sURLVariables.length; i++) {
		var sParameterName = sURLVariables[i].split('=');
		if (sParameterName[0] == sParam) {
			return sParameterName[1];
		}
	}
	return '0';
}
$(function () {
	$('a.like').click(function () {
		var _this = $(this);
		var _pid = get_url_param(_this.attr('href'), 'pid');
		var _unlike = get_url_param(_this.attr('href'), 'unlike');
		_this.hide();
		$.ajax({
			cache: false,
			type: "GET",
			url: "like.php",
			data: { pid: _pid, unlike: _unlike },
			success: function () {
				// Toggle href
				var new_href = 'like.php?pid=' + _pid;
				if (_unlike == '0') {
					_this.text('Unlike');
					new_href += '&unlike=1';
				} else {
					_this.text('Like');
				}
				_this.attr('href', new_href);
				_this.show();
			}
		});
		return false;
	});
});