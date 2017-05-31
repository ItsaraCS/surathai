/**
 *
 */
function create_vector_layer(data, epsg, style_func) {
	var tmp_feat = new ol.format.GeoJSON().readFeatures(data, {featureProjection :  epsg});
	var tmp_src = new ol.source.Vector({
		features : tmp_feat,
		wrapX : false
	});
	
	return new ol.layer.Vector({
		source : tmp_src,
		style : style_func
	});
}

/**
 *
 * @param f 	features
 * @param idx
 * @param a 	attribute
 * @param t		thresholds
 * @param s		styles
 */
function _show_thematic_map(f, idx, a, t, styles) {
	var n_classes = t.length;
	var i;
	var j;
	var c;	
	var t1;
	var t2;
	var val;

	// Loop through each feature
	for( i = 0; i < idx.length; i++ ) {
		val = a[i];
		
		// Find class.
		c = 0;
		for( j = 0; j < (n_classes-1); j++ ) {
			t1 = t[j];
			t2 = t[j+1];
			
			if((val >= t1) && (val <= t2)) {
				c = j;
				break;
			}
		}
		
		f[idx[i]].setStyle(styles[c+2]);
	}
}


/**
 * Create color ramp.
 *
 * 
 */
function create_color_ramp(n_classes, r, mode, inverse) {
	var n;
	var i;
	var t1;
	var t2;
	var k;
	var val;
	
	console.log('create_color_ramp');
	
	// reset all values.
	for( i = 0; i < n_classes; i++ ) {
		r[i] = [0, 0, 0, 1.0];
	}
	
	// Greeen
	/*t1 = 0.0;
	t2 = Math.floor(0.6 * n_classes);
	n  = t2 - t1 + 1;
	k = 0;
	console.log("Green: " + t1 + ":" + t2 + ":" + n);
	for( i = t1; i <= t2; i++ ) {
		console.log((k+1)/n);
		
		r[i][1] = Math.floor(((k+1)/n)*255);
		k++;
	}*/
	
	
	// blue
	/*t1 = Math.floor(0.1 * n_classes);
	t2 = Math.floor(0.9 * n_classes);
	var theta1 = 0;
	var theta2 = Math.PI;
	var theta_step = Math.PI/(t2 - t1);
	for( i = t1; i <= t2; i++ ) {
		val = Math.floor((Math.sin(theta1))*255);
		//console.log(val);
		r[i][2] = val; // blue
		
		theta1 += theta_step;
	}*/
	
	// Yellow to red
	if(mode == 'Y_R') {
		t1 = Math.floor(0.0 * n_classes);
		t2 = n_classes-1;
		n  = t2 - t1 + 1;
		k = 0;
		//console.log("RED: " + t1 + ":" + t2 + ":" + n);
		for( i = t1; i <= t2; i++ ) {
			val = Math.floor(((k+1)/n)*255);
			r[i][0] = 255;
			r[i][1] = 255 - val;
			r[i][2] = 0.0; // blue
			r[i][3] = 0.8; // alpha
			
			k++;
		}
	}
	// Green to red
	else if(mode == 'G_R') {
		t1 = Math.floor(0.0 * n_classes);
		t2 = n_classes-1;
		n  = t2 - t1 + 1;
		k = 0;
		//console.log("RED: " + t1 + ":" + t2 + ":" + n);
		for( i = t1; i <= t2; i++ ) {
			val = Math.floor(((k+1)/n)*255);
			r[i][0] = val;
			r[i][1] = 255 - val;
			r[i][2] = 0.0; // blue
			r[i][3] = 0.8; // alpha
			
			k++;
		}
	}
	// Blue-red
	else if(mode == 'B_R') {
		t1 = Math.floor(0.0 * n_classes);
		t2 = n_classes-1;
		n  = t2 - t1 + 1;
		k = 0;
		//console.log("RED: " + t1 + ":" + t2 + ":" + n);
		for( i = t1; i <= t2; i++ ) {
			val = Math.floor(((k+1)/n)*255);
			r[i][0] = val;
			r[i][1] = 0.0;
			r[i][2] = 255 - val;
			r[i][3] = 0.8;
			
			k++;
		}
	}
	// Blue-green-red
	else if(mode == 'B_G_R') {
		t1 = Math.floor(0.0 * n_classes);
		t2 = n_classes-1;
		n  = t2 - t1 + 1;
		k = 0;
		//console.log("RED: " + t1 + ":" + t2 + ":" + n);
		for( i = t1; i <= t2; i++ ) {
			val = Math.floor(((k+1)/n)*255);
			r[i][0] = val;
			r[i][1] = 0.0;
			r[i][2] = 255 - val;
			r[i][3] = 0.8;
			
			k++;
		}
		t1 = Math.floor(0.01 * n_classes);
		t2 = Math.floor(0.99 * n_classes);
		var theta1 = 0;
		var theta2 = Math.PI;
		var theta_step = Math.PI/(t2 - t1);
		for( i = t1; i <= t2; i++ ) {
			val = Math.floor((Math.sin(theta1))*255);
			r[i][1] = val;
			theta1 += theta_step;
		}
	}
}

/**
 *
 */
function create_style(f, w, l) {
	return new ol.style.Style({
			stroke: new ol.style.Stroke({ color: f, width: w }),
			fill: new ol.style.Fill({ color: l })
		})
}

/**
 * Zoom to specific map extent.
 *
 * @map
 * @ext
 * @maxZoom
 */
function zoom_to_extent(m, ext, maxZoom) {
	m.getView().fit(
				ext, 
				m.getSize(),
				{maxZoom: maxZoom});
}

