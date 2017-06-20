var filer_region = null;
var styleCache = {};

// UI elements
var container = null;
var ele_btn_view = null;
var ele_popup = null;
var ele_sel_region = null;
var ele_sel_area = null;
//var ele_sel_branch = null;
var ele_chart = null;
var chart_context = null;
var chart_container = null;
var ele_legend_box = null;

// Misc
var b_auto_zoom = true;
var MY_DEFAULT_LINE_COLOR = [ 0, 0, 0, 0.5 ];

// Data loading misc
var b_region_point_loaded = false;
var b_region_polygon_loaded = false;
var b_area_point_loaded = false;
var b_area_polygon_loaded = false;
var b_branch_point_loaded = false;
var b_factory_point_loaded = false;
var b_case_point_loaded = false;
var b_data_ready = false;

// Generic layers
var ras_background = null;
var vec_region_point = null;
var vec_region_polygon = null;
var vec_area_point = null;
var vec_area_polygon = null;
var vec_branch_point = null;
var vec_factory_point = null;
var vec_case_point = null;

// Case data
var map_data = null; // total number of cases, sum by region code
var map_data_monthly = null; // monthly number of cases, sum by region code
var map_data_area = null; // area data
var b_map_data_loaded = false;
var b_map_data_monthly_loaded = false;
var b_map_data_area_loaded = false;

// Polygon colors
var n_classes = 5;
var colors = [];
var thresholds = [];

// Mouse interaction
var selected_feature = null;
var feature_style_selected = null;
var feature_style_old = null;

// Overlay (popup window)
var popup_container = null;
var popup_content = null;
var popup_closer = null;
var overlay = null;

// Styles
var polygon_styles = [];
var default_region_polygon_styles = [];
var default_polygon_thematic_style = [];

// Region data (sorted by region code)
var regions_info = {regions:[[],[],[],[],[],[],[],[],[],[]]};
var region_ext = [];

/**
 * On document loaded.
 */
function on_page_loaded() {
	// Show progress dialog
	console.log('Loading...');
	
	// Get UI elements
	container = document.getElementById('popup');
	ele_sel_region = document.getElementById('region'); // region names list 
	ele_sel_area = document.getElementById('area'); // area names list
	//ele_sel_branch = document.getElementById('branch'); // branch
	ele_btn_view = document.getElementById('btn-view'); // view button
	ele_legend_box = document.getElementById('map_legend'); // legend box
	// Popup
	popup_container = document.getElementById('popup');
	popup_content = document.getElementById('popup-content');
	popup_closer = document.getElementById('popup-closer');
	
	// Create an overlay to anchor the popup to the map.
	overlay = new ol.Overlay(({
					element: popup_container,
					autoPan: true,
					autoPanAnimation: {duration: 250}
	}));
	
	//console.log(popup_closer);
	popup_closer.onclick = function() {
		overlay.setPosition(undefined);
	};
	
	//--EDIT BY ITSARA
	prepare_chart({
		yAxes: 'จำนวนใบอนุญาต'
	}); // Prepare chart
	prepare_layer_toggler('map_layer_toggler'); // Prepare layer toggler

	// Create color map.
	create_color_ramp(n_classes, colors, 'Y_R', false);
	
	//default_region_polygon_styles.push(create_style(MY_DEFAULT_LINE_COLOR, 1, [255, 255, 255, 1.0]));
	
	// 0-th style is white.
	polygon_styles.push(create_style(MY_DEFAULT_LINE_COLOR, 1, [255, 255, 255, 0.0]));
	// Shaded by region
	for( i = 0; i < 10; i++ ) {
		default_region_polygon_styles.push(
			create_style(
				MY_DEFAULT_LINE_COLOR, 
				1, 
				categories[i]
			)
		);
	}
	
	// Default thematic map color
	for( i = 0; i < n_classes; i++ ) {
		default_polygon_thematic_style.push(
			create_style(
				MY_DEFAULT_LINE_COLOR, 
				1, 
				[colors[i][0], 
				 colors[i][1],
				 colors[i][2],
				 colors[i][3]]
			)
		);										
	}
		
	// Prepare data
	prepare_region_and_area('data/geojson/excise_area_centroid_compact.geojson');
	
	// Insert region list
	//--EDIT BY ITSARA
	/*$('#region').append('<option value="01">สรรพสามิตภาคที่ 1</option>');
	$('#region').append('<option value="02">สรรพสามิตภาคที่ 2</option>');
	$('#region').append('<option value="03">สรรพสามิตภาคที่ 3</option>');
	$('#region').append('<option value="04">สรรพสามิตภาคที่ 4</option>');
	$('#region').append('<option value="05">สรรพสามิตภาคที่ 5</option>');
	$('#region').append('<option value="06">สรรพสามิตภาคที่ 6</option>');
	$('#region').append('<option value="07">สรรพสามิตภาคที่ 7</option>');
	$('#region').append('<option value="08">สรรพสามิตภาคที่ 8</option>');
	$('#region').append('<option value="09">สรรพสามิตภาคที่ 9</option>');
	$('#region').append('<option value="10">สรรพสามิตภาคที่ 10</option>');*/
	
	// ------------------------------------------------------------
	// Load map data
	// ------------------------------------------------------------
	// vector data
	load_data_region_polygon('data/geojson/excise_region.geojson');
	load_data_region_point('data/geojson/point_region.geojson');
	load_data_area_point('data/geojson/excise_area_centroid_compact.geojson');
	load_data_area_polygon('data/geojson/area_dissolved.geojson');
	load_data_branch_point('data/geojson/excise_branch_centroid.geojson');
	load_data_factory_point('data/geojson/factory_2126_point.geojson');
	
	// Attribute data
	//load_data_region('data/geojson/license_sum_by_reg_code.geojson');
	load_data_region('http://210.4.143.51/Surathai01/API/taxmapAPI.php?data=license_reg&year=2017');
	load_data_region_monthly('data/geojson/license_sum_by_reg_code_month.geojson');
	load_data_area('data/geojson/license_sum_by_area_code.geojson');
	
	// Done
	console.log('Done.');
}

/**
 * Process data.
 */
function process_loaded_data() {
	b_data_ready = b_region_polygon_loaded
				&& b_region_point_loaded
				&& b_area_point_loaded
				&& b_branch_point_loaded
				&& b_area_polygon_loaded
				&& b_map_data_loaded
				&& b_map_data_monthly_loaded
				&& b_map_data_area_loaded
				&& b_factory_point_loaded;
	
	if(b_data_ready == false) { 
		console.log('...still loading...');
		return; 
	} else {
		console.log('data is ready...');
		$('#dvloading').hide();
	}
	
	//
	// Initialize other components
	//
	// Set default style.
	set_default_region_polygon_style(vec_region_polygon.getSource().getFeatures());

	var projection = ol.proj.get('EPSG:3857');

	//ADD By AM
	window.app = {};
    var app = window.app;
    app.defaultZoom = function(opt_options) {

        var options = opt_options || {};

        var defaultZoomBtn = document.createElement('button');
        defaultZoomBtn.innerHTML = '<i class="fa fa-globe" aria-hidden="true"></i>';

        var handledefaultZoom = function(e) {
            map.getView().setCenter(ol.proj.transform([103.0, 8.5], 'EPSG:4326', 'EPSG:3857'));
			map.getView().setZoom(5.5);
        };

        defaultZoomBtn.addEventListener('click', handledefaultZoom, false);

        var element = document.createElement('div');
        element.className = 'defaultZoom ol-unselectable ol-control';
        element.appendChild(defaultZoomBtn);

        ol.control.Control.call(this, {
            element: element,
            target: options.target
        });

    };
    ol.inherits(app.defaultZoom, ol.control.Control);

    map = new ol.Map({
        controls: ol.control.defaults({
            attributionOptions: ({
                collapsible: false
            })
        }).extend([
            new app.defaultZoom()
        ]),
		layers : [vec_region_polygon],
		overlays: [overlay],//for popup
		target : 'map',
		view: new ol.View({
			center: [100, 13],
			projection: projection,
			zoom: 3
		})
	});
	//ADD By AM
	
	// Map instance.
	// map = new ol.Map({
	// 	layers : [vec_region_polygon],
	// 	overlays: [overlay],//for popup
	// 	target : 'map',
	// 	view: new ol.View({
	// 	center: [100, 13],
	// 	projection: projection,
	// 	zoom: 3
	// 	})
	// });
	
	// Add other layers
	map.addLayer(vec_area_polygon);
	map.addLayer(vec_branch_point);
	map.addLayer(vec_area_point);
	map.addLayer(vec_region_point);
	map.addLayer(vec_factory_point);
	
	// Hide some layers by default
	toggle_map_layer_visibility(vec_area_polygon, false);
	toggle_map_layer_visibility(vec_area_point, false);
	toggle_map_layer_visibility(vec_branch_point, false);
	toggle_map_layer_visibility(vec_factory_point, false);
	
	$('#dvloading').hide().fadeOut();
	
	map.getView().setCenter(ol.proj.transform([103.0, 8.5], 'EPSG:4326', 'EPSG:3857'));
	map.getView().setZoom(5.5);
	
	// Add mouse event listeners
	//map.on('pointerdown', on_map_mouse_down);
	//map.on('pointerup', on_map_mouse_up);
	//map.on('pointermove', on_map_mouse_move);
	//map.on('click', on_map_mouse_up);
	map.on('singleclick', show_feature_info);	
	
	// Add interaction
	var select = new ol.interaction.Select({
	  layers: [vec_region_polygon]
	});
	map.addInteraction(select);

	// Attach event listeners
	ele_sel_region.onchange = on_ele_sel_region_change;
	ele_sel_area.onchange = on_ele_sel_area_change;
	//ele_sel_branch.onchange = on_ele_sel_branch_change;
	ele_btn_view.onclick = show_map;
	
	// Region extends
	cal_region_extends(region_ext,
					   vec_region_polygon.getSource().getFeatures(),
					   0.0);
	
	// Show chart
	update_chart_data(chart_context, 
					  chart_container, 
					  map_data_monthly,
					  'COUNT');
}


// ----------------------------------------------------------------
// OpenLayer's map style functions.
// ----------------------------------------------------------------

// ----------------------------------------------------------------
// VIEW BUTTON
// ----------------------------------------------------------------
/**
 *
 */
function show_map() {
	var i;
	var filter_year = $('#year').val();
	var filter_region = $('#region option:selected').val();
	var filter_area = $('#area option:selected').val();
	var none = " / ";
	var fi;
	var ri;
	var vi;
	var sum;
	
	console.log('--------------------');
	console.log('filter_year', filter_year);
	console.log('filter_region', filter_region);
	console.log('filter_area', filter_area);
	console.log('--------------------');
	
	// No any region selected. So, show overall region map
	if((filter_region == '00') || (filter_region == '') || (!filter_region)) {
		console.log('overall');
		
		// Reset area polygon.
		// Set default style : all white
		var vf;
		vf = vec_area_polygon.getSource().getFeatures();
		//for( i = 0; i < vf.length; i++ ) {
		//	vf[i].setStyle(polygon_styles[0]);
		//}
		
		// Create thematic map based on region data
		show_thematic_map_region(vec_region_polygon.getSource().getFeatures(), map_data);
		
		// Show graph of all regions
		update_chart_data_region(chart_context, 
								 chart_container, 
								 map_data_monthly,
								 'COUNT',
								 -999,
								 '');
	// User selects a region, check area selector
	} else {
		// Parse region code
		var target_reg_code = parseInt(ele_sel_region.value);
		
		// Show area's thematic map
		show_thematic_map_area(
					vec_area_polygon.getSource().getFeatures(),
					target_reg_code,
					map_data_area);
						
		// User do not select any area.
		if((filter_area == '-999') || (filter_area == '') 
		|| (!filter_area) || (filter_area < 0)) {
			console.log('zoom to region');
			
			update_chart_data_region(chart_context, 
								 chart_container, 
								 map_data_monthly,
								 'COUNT',
								 target_reg_code,
								 '');
		// User selects an area.
		} else {
			console.log('zoom to area', filter_area);
			
			update_chart_data_region(chart_context, 
								 chart_container, 
								 map_data_monthly,
								 'COUNT',
								 target_reg_code,
								 filter_area);
		}
	}
	
	
	return;
	/*

	
	
	// Show region map
	if((filter_region == '00') || (filter_region == '') || (!filter_region)) {
		console.log('xxxx');
		// Reset area polygon.
		// Set default style : all white
		var vf;
		vf = vec_area_polygon.getSource().getFeatures();
		for( i = 0; i < vf.length; i++ ) {
			vf[i].setStyle(polygon_styles[0]);
		}
		show_thematic_map_region(vec_region_polygon.getSource().getFeatures(), map_data);
		return; 
	}
	
	var target_reg_code = parseInt(ele_sel_region.value); // region code
	
	sum = 0;
	for (i = 0; i < map_data.features.length; i++ ) {
		fi = map_data.features[i].properties;
		
		ri = fi.field_18; // region code
		val = fi.field_13; // tax value
		
		if(ri && val && !isNaN(ri) && !isNaN(val)) {
			if( ri == target_reg_code ) {
				//console.log('  ', target_reg_code, ri, val);
			}
		}
	}
	
	// Show thematic map
	show_thematic_map_area(
				vec_area_polygon.getSource().getFeatures(),
				target_reg_code,
				map_data_area);*/
}

// ----------------------------------------------------------------
// SELECT
// ----------------------------------------------------------------

// ----------------------------------------------------------------
// OpenLayer's mouse functions.
// ----------------------------------------------------------------
/**
 *
 */
function show_feature_info(evt) {
	var filter_year = $('#year').val();
	var filter_region = $('#region option:selected').val();
	var filter_area = $('#area option:selected').val();
	
	// Get feature information
	var coordinate = evt.coordinate;
	var hdms = ol.coordinate.toStringHDMS(
					ol.proj.transform(
						coordinate, 
						'EPSG:3857', 
						'EPSG:4326'));
	
	
	var pixel = get_map_mouse_pixel(map, coordinate);
	var f = get_feature_info(map, pixel);
	var f_count = 0;
	var f_sum = 0;
	var i;
	var ci, cj;
	var str = "";
	var label = "";
	
	// Return if user donot select any feature.
	if(f.length == 0) {return;}
	
	console.log('--------------------');
	console.log('length', f.length);
	console.log('filter_year', filter_year);
	console.log('filter_region', filter_region);
	console.log('filter_area', filter_area);
	console.log('--------------------');
	
	// No any region selected. So, show overall region data
	if((filter_region == '00') || (filter_region == '') || (!filter_region)) { 
		console.log('overall');
		
		// Check region code
		cj = parseInt(f[0].get('REG_CODE'));
		for (i = 0; i < map_data.features.length; i++ ) {
			ci = map_data.features[i].properties.REG_CODE;
			
			// Found
			if(ci == cj) {
				f_count = map_data.features[i].properties.COUNT;
				f_sum = map_data.features[i].properties.SUM;
				
				str += "<h3><a href=\"search_tax.php\">สรรพสามิตภาคที่ " + cj + "</a></h3>";
				str += "<table>";
					str += "<tr>";
						str += "<td>จำนวนใบอนุญาต</td>";
						str += "<td class=\"center\">" + Number(f_sum).toLocaleString('en', { minimumFractionDigits: 0 }) + " ใบ</td>";
					str += "</tr>";
				str += "</table>";
				popup_content.innerHTML = str;
				overlay.setPosition(coordinate);
				
				break;
			}
		}
		
	// User selects a region, check area selector
	} else {
		// User do not select any area.
		/*if((filter_area == '-999') || (filter_area == '') 
		|| (!filter_area) || (filter_area < 0)) {
			console.log('zoom to region');
			
		// User selects an area.
		} else {
			console.log('one area', label);
		}*/
		
		console.log('zoom to region');
		console.log(f[0]);
		cj = f[0].get('AREA_CODE');
		for (i = 0; i < map_data_area.features.length; i++ ) {
			ci = map_data_area.features[i].properties.AREA_CODE;
			
			// Found
			if(ci == cj) {
				label = get_dropdown_text( document.getElementById('area').options, ci);
				if(label == '') { return; }
				
				f_sum = map_data_area.features[i].properties.VAL_SUM;
				
				str += "<h3><a href=\"search_tax.php\">" + label + "</a></h3>";
				str += "<table>";
					str += "<tr>";
						str += "<td>จำนวนใบอนุญาต</td>";
						str += "<td class=\"center\">" + Number(f_sum).toLocaleString('en', { minimumFractionDigits: 0 }) + " ใบ</td>";
					str += "</tr>";
				str += "</table>";
				popup_content.innerHTML = str;
				overlay.setPosition(coordinate);
				
				break;
			}
		}
	}
	return;
	
	
	
	// Check region code
	/*cj = parseInt(f[0].get('REG_CODE'));
	for (i = 0; i < map_data.features.length; i++ ) {
		ci = map_data.features[i].properties.REG_CODE;
		if(ci == cj) {
			f_count = map_data.features[i].properties.COUNT;
			f_sum = map_data.features[i].properties.SUM;
			
			str += "<h3><a href=\"search_tax.php\">สรรพสามิตภาคที่ " + cj + "</a></h3>";
			str += "<table>";
				str += "<tr>";
					str += "<th>มูลค่า</th>";
				str += "</tr>";
				str += "<tr>";
					str += "<td class=\"center\">" + f_sum.toFixed(2) + "</td>";
				str += "</tr>";
			str += "<t/able>";
			popup_content.innerHTML = str;
			overlay.setPosition(coordinate);
			
			break;
		}
	}*/
}
/**
 * Post-process data after user release mouse button.
 */
function process_mouse_events(b_show_popup) {
}

// ----------------------------------------------------------------
// OpenLayer's map popup functions.
// ----------------------------------------------------------------

// ----------------------------------------------------------------
// JSChart functions.
// ----------------------------------------------------------------

// ----------------------------------------------------------------
// THEMATIC MAP SECTION
// ----------------------------------------------------------------
