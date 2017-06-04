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

// Misc
var b_auto_zoom = true;
var MY_DEFAULT_LINE_COLOR = [ 0, 0, 0, 0.5 ];

// Data loading misc
var b_region_point_loaded = false;
var b_region_polygon_loaded = false;
var b_area_point_loaded = false;
var b_area_polygon_loaded = false;
var b_branch_point_loaded = false;
var b_data_ready = false;

// Generic layers
var ras_background = null;
var vec_region_point = null;
var vec_region_polygon = null;
var vec_area_point = null;
var vec_area_polygon = null;
var vec_branch_point = null;

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
	$('#dvloading').show();
	
	// Get UI elements
	container = document.getElementById('popup');
	ele_sel_region = document.getElementById('region'); // region names list 
	ele_sel_area = document.getElementById('area'); // area names list
	//ele_sel_branch = document.getElementById('branch'); // branch
	ele_btn_view = document.getElementById('btn-view'); // view button
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
	
	
	// Prepare chart
	prepare_chart();
	
	// Create color map.
	create_color_ramp(n_classes, colors, 'G_R', false);
	
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
	
	// Attribute data
	load_data_region('data/geojson/factory_sum_by_reg_code.geojson');
	load_data_region_monthly('data/geojson/factory_sum_by_reg_code_month.geojson');
	load_data_area('data/geojson/factory_sum_by_area_code.geojson');
	
	// Done
	console.log('Done.');
	$('#dvloading').hide();
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
				&& b_map_data_area_loaded;
	
	if(b_data_ready == false) { 
		console.log('...still loading...');
		return; 
	} else {
		console.log('data is ready...');
	}
	
	//
	// Initialize other components
	//
	// Set default style.
	set_default_region_polygon_style(vec_region_polygon.getSource().getFeatures());

	var projection = ol.proj.get('EPSG:3857');

	// Map instance.
	map = new ol.Map({
		layers : [vec_region_polygon],
		overlays: [overlay],//for popup
		target : 'map',
		view: new ol.View({
		center: [100, 13],
		projection: projection,
		zoom: 3
		})
	});
	
	// Add other layers
	map.addLayer(vec_area_polygon);
	map.addLayer(vec_branch_point);
	map.addLayer(vec_area_point);
	map.addLayer(vec_region_point);
	
	// Hide some layers by default
	toggle_map_layer_visibility(vec_area_polygon, false);
	toggle_map_layer_visibility(vec_area_point, false);
	toggle_map_layer_visibility(vec_branch_point, false);
	
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
	var filter_branch = $('#branch option:selected').val();
	var none = " / ";
	var fi;
	var ri;
	var vi;
	var sum;
	
	// Show area map
	console.log('filter_region', filter_region);
	console.log('--------------------');
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
				map_data_area);
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
	
	// Return if user donot select any feature.
	if(f.length == 0) {return;}
	
	// Check region code
	cj = parseInt(f[0].get('REG_CODE'));
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
	}
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
