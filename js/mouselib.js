/**
 * Global variables
 */
var b_mouse_down = false;
var b_mouse_drag = false;
var mouse_new_px = [0.0, 0.0];
var mouse_old_px = [0.0, 0.0];
var mouse_new_geo = [0.0, 0.0];
var mouse_old_geo = [0.0, 0.0];

/**
 * @param event
 */
function on_map_mouse_down(event) {
	b_mouse_down = true;
	b_mouse_drag = false;
	
	mouse_old_px = event.pixel;
	mouse_new_px = event.pixel;
	
	console.log('on_map_mouse_down');
}

/**
 * @param event
 */
function on_map_mouse_up(event) {
	mouse_new_geo = event.coordinate;
	process_mouse_events(true);
	
	//console.log('on_map_mouse_up');
	
	/*
	if( b_mouse_drag == false ) {
		var coord = event.coordinate;
		var pixel = get_map_mouse_pixel(coord);
		var f = get_selected_feature(pixel);
		//console.log(f);
		
		if( f != null ) {
			// Restore style
			if( selected_feature != null ) {
				clear_selected_feature();
			}
			
			// Set styles.
			selected_feature_prev_style = f.getStyle();
			selected_feature = f;
			f.setStyle(polygon_styles[1]);
			
			// Show overlay
			show_overlay(coord, selected_feature);
		} else {
			// In the case of no data, clear previously selected feature.
			if( selected_feature != null ) {
				clear_selected_feature();
			}
			map_overlay.style.visibility = 'hidden';
		}
	}*/
	
	b_mouse_down = false;
	b_mouse_drag = false;
}

/**
 * @param event
 */
function on_map_mouse_move(event) {
	// Pixel
	mouse_new_px = event.pixel;
	
	if( b_mouse_down == true ) {
		var dx = Math.abs(mouse_new_px[0] - mouse_old_px[0]);
		var dy = Math.abs(mouse_new_px[1] - mouse_old_px[1]);
	
		if((dx > 3) || (dy > 3)) {
			b_mouse_drag = true; 
		}
	}
	
	mouse_new_geo = event.coordinate;
	process_mouse_events(false);
}

/**
 *
 */
function get_map_mouse_pixel(coord) {
	//var pixel = olmap.getPixelFromCoordinate(coord);
	//return pixel;
}

/**
 *
 * @param m
 * @param keys
 */
function get_feature_info(m, px, keys) {
	var i;
	var ret = [];
	
	m.forEachFeatureAtPixel(px, function(f) {
		for( i = 0; i < keys.length; i++ ) {
			//ret.push(f.get(keys[i]));
			ret.push(f);
		}
	});
	
	return ret;
}