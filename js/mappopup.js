var b_popup_shown = false;

/**
 *
 */
function show_popup_window(m, o, c, str) {
    var dvPopup = document.getElementById("popup-content");
	console.log('xx');
    dvPopup.innerHTML = str;
	o.setPosition(c);
	
	b_popup_shown = true;
}

/**
 *
 */
function hide_popup_window() {
	b_popup_shown = false;
	overlay.setPosition(undefined);
	popup_closer.blur();
	//return false;
}
