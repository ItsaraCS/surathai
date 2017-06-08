/**
 * Zoom to given geographic coordinates.
 *
 * @param o    Openlayer itself
 * @param m    Openlayer's map
 * @param lat  Latitude (horizontal)
 * @param lon  Longitude (vertical)
 * @param z    Target zoom level
 */
function zoom_to_factory(o, m, lat, lon, z) {
	m.getView().setCenter(o.proj.transform([lon, lat], 'EPSG:4326', 'EPSG:3857'));
	m.getView().setZoom(z);
}

/**
 *
 */
function search_load_point_layers() {
	getJSON(
		'data/geojson/excise_area_centroid_compact.geojson',
		function(data) {
			vec_branch_point = create_vector_layer(data, 'EPSG:3857', branch_point_style_function);
			map.addLayer(vec_branch_point);
			toggle_map_layer_visibility(vec_branch_point, false);
		}, 
		function(xhr) {
		}
	);
	getJSON(
		'data/geojson/factory_2126_point.geojson',
		function(data) {
			vec_factory_point = create_vector_layer(data, 'EPSG:3857', factory_point_style_function);
			map.addLayer(vec_factory_point);
			toggle_map_layer_visibility(vec_factory_point, false);
		}, 
		function(xhr) {
		}
	);
	getJSON(
		'data/geojson/lawbreaker_point.geojson',
		function(data) {
			vec_lawbreaker_point = create_vector_layer(data, 'EPSG:3857', lawbreaker_point_style_function);
			map.addLayer(vec_lawbreaker_point);
			toggle_map_layer_visibility(vec_lawbreaker_point, false);
		}, 
		function(xhr) {
		}
	);
	getJSON(
		'data/geojson/store_point.geojson',
		function(data) {
			vec_store_point = create_vector_layer(data, 'EPSG:3857', store_point_style_function);
			map.addLayer(vec_store_point);
			toggle_map_layer_visibility(vec_store_point, false);
		}, 
		function(xhr) {
		}
	);
	getJSON(
		'data/geojson/thaiwhisky_point.geojson',
		function(data) {
			vec_thaiwhisky_point = create_vector_layer(data, 'EPSG:3857', thaiwhisky_point_style_function);
			map.addLayer(vec_thaiwhisky_point);
			toggle_map_layer_visibility(vec_thaiwhisky_point, false);
		}, 
		function(xhr) {
		}
	);
}

/**
 * Point style
 */
function search_point_style_function(feature, resolution) {
	var my_dom = {
		text:           'normal',
		align:          'center',
		baseline:       'bottom',
		rotation:       '0',
		font:           'MS Sans Serif',
		weight:         'normal',
		size:           '13px',
		offsetX:        '0',
		offsetY:        '-10',
		color:          'rgba(0, 0, 0, 1.0)',
		outline:        'rgba(255, 255, 255, 0.0)',
		outlineWidth:   '4',
		maxreso:        '200'
	};
	return new ol.style.Style({
				image: new ol.style.Circle({
					radius: 2,
					fill: new ol.style.Fill({color: 'rgba(255, 0, 0, 0.8)'}),
					stroke: new ol.style.Stroke({color:  'rgba(255, 0, 0, 0.8)', width: 1})
				}),
				text: search_create_text_style(feature, 
									  resolution,
									  my_dom,
									  'FACTORY_TNAME')
				});
}

function search_create_text_style(feature, resolution, dom, field) {
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
		text: search_get_text(feature, resolution, dom, field),
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
function search_get_text(feature, resolution, dom, field) {
	var maxResolution = dom.maxreso;
	var r_code = feature.get(field);
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