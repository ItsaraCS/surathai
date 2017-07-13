var myDom = {
	region_polygons: {
		text:           'normal',
		align:          'center',
		baseline:       'bottom',
		rotation:       '0',
		font:           'Verdana',
		weight:         'bold',
		size:           '12px',
		offsetX:        '0',
		offsetY:        '-8',
		color:          '#000000',
		outline:        'rgba(255,255,255,0)',
		outlineWidth:   '0',
		maxreso:        '4000'
	},
	region_points: {
		text:           'normal',
		align:          'center',
		baseline:       'bottom',
		rotation:       '0',
		font:           'MS Sans Serif',
		weight:         'bold',
		size:           '15px',
		offsetX:        '0',
		offsetY:        '-10',
		color:          'rgba(0, 0, 0, 1.0)',
		outline:        'rgba(255, 255, 255, 0.0)',
		outlineWidth:   '4',
		maxreso:        '6000'
	},
	area_points: {
		text:           'normal',
		align:          'center',
		baseline:       'bottom',
		rotation:       '0',
		font:           'MS Sans Serif',
		weight:         'normal',
		size:           '14px',
		offsetX:        '0',
		offsetY:        '-8',
		color:          'rgba(0, 0, 0, 0.8)',
		outline:        'rgba(255, 255, 255, 0.0)',
		outlineWidth:   '2',
		maxreso:        '1200'
	},
	branch_points: {
		text:           'normal',
		align:          'center',
		baseline:       'bottom',
		rotation:       '0',
		font:           'MS Sans Serif',
		weight:         'normal',
		size:           '11px',
		offsetX:        '0',
		offsetY:        '-8',
		color:          'rgba(0, 0, 0, 0.5)',
		outline:        'rgba(255, 255, 255, 0.0)',
		outlineWidth:   '2',
		maxreso:        '400'
	},
	factory_points: {
		text:           'normal',
		align:          'center',
		baseline:       'bottom',
		rotation:       '0',
		font:           'MS Sans Serif',
		weight:         'normal',
		size:           '11px',
		offsetX:        '0',
		offsetY:        '-8',
		color:          'rgba(0, 0, 0, 0.5)',
		outline:        'rgba(255, 255, 255, 0.0)',
		outlineWidth:   '2',
		maxreso:        '200'
	},
	lawbreaker_points: {
		text:           'normal',
		align:          'center',
		baseline:       'bottom',
		rotation:       '0',
		font:           'MS Sans Serif',
		weight:         'normal',
		size:           '11px',
		offsetX:        '0',
		offsetY:        '-8',
		color:          'rgba(0, 0, 0, 0.5)',
		outline:        'rgba(255, 255, 255, 0.0)',
		outlineWidth:   '2',
		maxreso:        '100'
	},
};


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
 *
 */
function create_LUT(nc, cl, t, min, max, range) {
	var c;
	var num_f = cl.length;
	for( i = 0; i < num_f; i++ ) {
		val = cl[i].VAL;
		if(val <= min) {
			c = 0
		} else if(val >= max) {
			c = nc-1
		} else {
			c = Math.floor((val - min)/range);
		}
		if(c >= nc) { c = nc-1; }
		cl[i].COLOR_INDEX = c;
	}
}

/**
 *
 */
function set_feature_style(vf, cl, styles) {
	var i;
	for( i = 0; i < vf.length; i++ ) {
		vi = vf[i];
		vi.setStyle(styles[cl[i].COLOR_INDEX]);
	}
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

// ===================================================
// STYLES
// ===================================================
/**
 * Create text style
 */
function create_text_style(feature, resolution, dom, field) {
	var align = dom.align;
	var baseline = dom.baseline;
	var size = dom.size;
	var offsetX = parseInt(dom.offsetX, 10);
	var offsetY = parseInt(dom.offsetY, 10);
	var weight = dom.weight;
	var rotation = parseFloat(dom.rotation);
	var font = weight + ' ' + size + ' ' + dom.font;
	var fillColor = dom.color;
	var outlineColor = dom.outline;
	var outlineWidth = parseInt(dom.outlineWidth, 10);

	return new ol.style.Text({
		textAlign: align,
		textBaseline: baseline,
		font: font,
		text: get_text(feature, resolution, dom, field),
		fill: new ol.style.Fill({color: fillColor}),
		stroke: new ol.style.Stroke({color: outlineColor, width: outlineWidth}),
		offsetX: offsetX,
		offsetY: offsetY,
		rotation: rotation
	});
}
/**
 * Get labeled text.
 */
function get_text(feature, resolution, dom, field) {
	var maxResolution = dom.maxreso;
	var r_code = feature.get('REG_CODE');
	var idx = parseInt(r_code) - 1;
	
	// Get value to label
	var text;
	if( field == '' ) {
		text = odata[idx].VAL.toFixed(2);
	} else {
		text = feature.get(field);
	}

	if (resolution > maxResolution) {
		text = '';
	}
	
	if (text == 0.0) {
		//text = '';
	}
	
	//console.log('resolution', resolution, 'maxreso', maxResolution);

	return text;
}
/**
 * Crate styles
 */
function region_polygon_style_function(feature, resolution) {
	return new ol.style.Style({
					image: new ol.style.Circle({
						radius: 4,
						fill: new ol.style.Fill({color: 'rgba(0, 0, 0, 0.8)'}),
						stroke: new ol.style.Stroke({color: 'red', width: 0.1})
					}),
					stroke : new ol.style.Stroke({
								color : 'rgba(0, 0, 0, 0.5)',
								width : 2
					}),
				});
}

/**
 * Crate style
 */
function region_point_style_function(feature, resolution) {
	return new ol.style.Style({
				image: new ol.style.Circle({
					radius: 4,
					fill: new ol.style.Fill({color: 'rgba(255, 255, 255, 0.8)'}),
					stroke: new ol.style.Stroke({color: 'rgba(0, 0, 0, 0.8)', width: 2})
				}),
				text: create_text_style(feature, 
									  resolution, 
									  myDom.region_points,
									  'REG_TNAME')
				});
}

/**
 * Crate style
 */
function area_polygon_style_function(feature, resolution) {
	return new ol.style.Style({
					fill : new ol.style.Fill({
							color : 'rgba(255, 255, 255, 0.1)',
					}),
					stroke : new ol.style.Stroke({
							color : 'rgba(0, 0, 0, 0.1)',
							width : 1
					}),
					text : new ol.style.Text({
							font : '12px Calibri,sans-serif',
							fill : new ol.style.Fill({
									color : '#000'
							}),
							stroke : new ol.style.Stroke({
									color : '#fff',
									width : 1
							})
					})
				});
}

/**
 * Crate style
 */
function area_point_style_function(feature, resolution) {
	return new ol.style.Style({
				image: new ol.style.Circle({
					radius: 2,
					fill: new ol.style.Fill({color: 'rgba(255, 255, 255, 0.8)'}),
					stroke: new ol.style.Stroke({color: 'rgba(0, 0, 0, 0.8)', width: 2})
				}),
				text: create_text_style(feature, 
									  resolution,
									  myDom.area_points,
									  'AREA_TNAME')
				});
}


/**
 * Crate style
 */
function branch_point_style_function(feature, resolution) {
	return new ol.style.Style({
				image: new ol.style.Circle({
					radius: 3,
					fill: new ol.style.Fill({color: 'rgba(80, 255, 80, 0.80)'}),
					stroke: new ol.style.Stroke({color: 'rgba(0, 0, 0, 0.90)', width: 1})
				}),
				text: create_text_style(feature, 
									  resolution,
									  myDom.branch_points,
									  'BRAN_TNAME')
				});
}

/**
 * Crate style
 */
function factory_point_style_function(feature, resolution) {
	return new ol.style.Style({
				image: new ol.style.Circle({
					radius: 3,
					fill: new ol.style.Fill({color: 'rgba(255, 0, 255, 0.80)'}),
					stroke: new ol.style.Stroke({color: 'rgba(0, 0, 0, 0.90)', width: 1})
				}),
				/*text: create_text_style(feature, 
									  resolution,
									  myDom.factory_points,
									  'FACTORY_TNAME')*/
				});
}

/**
 * Crate style
 */
function lawbreaker_point_style_function(feature, resolution) {
	return new ol.style.Style({
				image: new ol.style.Circle({
					radius: 3,
					fill: new ol.style.Fill({color: 'rgba(0, 255, 255, 0.80)'}),
					stroke: new ol.style.Stroke({color: 'rgba(0, 0, 0, 0.90)', width: 1})
				}),
				/*text: create_text_style(feature, 
									  resolution,
									  myDom.branch_points,
									  'ACCUSER_K_SUSPECT_T')*/
				});
}

/**
 * Crate style
 */
function store_point_style_function(feature, resolution) {
	return new ol.style.Style({
				image: new ol.style.Circle({
					radius: 3,
					fill: new ol.style.Fill({color: 'rgba(0, 0, 255, 0.80)'}),
					stroke: new ol.style.Stroke({color: 'rgba(0, 0, 0, 0.90)', width: 1})
				}),
				/*text: create_text_style(feature, 
									  resolution,
									  myDom.branch_points,
									  'ID')*/
				});
}

/**
 * Crate style
 */
function thaiwhisky_point_style_function(feature, resolution) {
	return new ol.style.Style({
				image: new ol.style.Circle({
					radius: 3,
					fill: new ol.style.Fill({color: 'rgba(255, 150, 0, 0.80)'}),
					stroke: new ol.style.Stroke({color: 'rgba(0, 0, 0, 0.90)', width: 1})
				}),
				/*text: create_text_style(feature, 
									  resolution,
									  myDom.branch_points,
									  'ADDRESS')*/
				});
}


/**
 * Toggle layer visibility.
 *
 * @layer	Layer to be configured
 * @mode    Ture or false
 */
function toggle_map_layer_visibility(layer, mode) {
	layer.setVisible(mode);
}


/**
 *
 */
function prepare_layer_toggler(e) {
	var ele = document.getElementById(e);
	
	var ctn_office = null;
	var ctn_factory = null;
	var ctn_case = null;
	var ctn_store = null;
	var ctn_whisky = null;

	// Offices
	ctn_office = document.createElement("div");
	ctn_office.className = 'layer_block';
	ctn_office.innerHTML = '<input type="checkbox" id="chk_office" name="chk_office" onclick="update_layer_visibility(\'branch\');" /> สำนักงานสรรพสามิต';
	
	// Factories
	ctn_factory = document.createElement("div");
	ctn_factory.className = 'layer_block';
	ctn_factory.innerHTML = '<input type="checkbox" id="chk_factory" name="chk_factory" onclick="update_layer_visibility(\'factory\');" /> โรงงานสุรา';
	
	// Illegal cases
	ctn_case = document.createElement("div");
	ctn_case.className = 'layer_block';
	ctn_case.innerHTML = '<input type="checkbox" id="chk_lawbreaker" name="chk_lawbreaker" onclick="update_layer_visibility(\'lawbreaker\');" /> ผู้กระทำผิด';
	
	// Stores
	ctn_store = document.createElement("div");
	ctn_store.className = 'layer_block';
	ctn_store.innerHTML = '<input type="checkbox" id="chk_store" name="chk_store" onclick="update_layer_visibility(\'store\');" /> ร้านค้า';
	
	// Thai whisky
	ctn_whisky = document.createElement("div");
	ctn_whisky.className = 'layer_block';
	ctn_whisky.innerHTML = '<input type="checkbox" id="chk_thaiwhisky" name="chk_thaiwhisky" onclick="update_layer_visibility(\'thaiwhisky\');" /> ร้านยาดอง';
	
	// Add children
	ele.appendChild(ctn_office);
	ele.appendChild(ctn_factory);
	ele.appendChild(ctn_case);
	ele.appendChild(ctn_store);
	ele.appendChild(ctn_whisky);
	
	// Attach event listener
	ele = document.getElementById('map_layer_title');
	ele.addEventListener("click", function() {
		$('#map_layer_toggler').toggle(250);
	});
}