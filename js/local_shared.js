/**
 *
 */
function set_default_region_polygon_style(vf) {
	var i;
	var vi;
	var region_code;
	
	// Set default style.
	for( i = 0; i < vf.length; i++ ) {
		vi = vf[i];
		region_code = parseInt(vi.get('REG_CODE'));
		vi.setStyle(default_region_polygon_styles[region_code]);
	}
}

/**
 * Prepare regions, areas, and branches data.
 */
function prepare_region_and_area(url) {
	getJSON(
		url,
		function(data) {
			var i;
			var j;
			var r_code;
			var a_code;
			var idx;
			var n_data = data.features.length; 
			var fi;
			//console.log('n_data', n_data);
			
			for(i = 0; i < n_data; i++) {
				fi = data.features[i];
				
				r_code = parseInt(fi.properties.REG_CODE);
				idx = r_code - 1;
				
				regions_info.regions[idx].push({
					REG_CODE:  "",
					REG_TNAME:  "",
					areas: {
						ID: fi.properties.ID,
						REG_CODE: fi.properties.REG_CODE,
						AREA_CODE: fi.properties.AREA_CODE,
						AREA_TNAME: fi.properties.AREA_TNAME,
					}}
				);
			}
		}, function(xhr) {
			// On error...
		}
	);
}

/**
 *
 */
function prepare_layer_toggler(e) {
	var ele = document.getElementById(e);
	
	var ctn_office = null;
	var ctn_factory = null;
	var ctn_case = null;	

	// Offices
	ctn_office = document.createElement("div");
	ctn_office.className = 'layer_block';
	ctn_office.innerHTML = '<input type="checkbox" id="chk_office" name="chk_office" onclick="update_layer_visibility();" /> สำนักงาน';
	
	// Factories
	ctn_factory = document.createElement("div");
	ctn_factory.className = 'layer_block';
	ctn_factory.innerHTML = '<input type="checkbox" id="chk_factory" name="chk_factory" onclick="update_layer_visibility();" /> โรงงาน';
	
	// Illegal cases
	ctn_case = document.createElement("div");
	ctn_case.className = 'layer_block';
	ctn_case.innerHTML = '<input type="checkbox" id="chk_case" name="chk_office" onclick="update_layer_visibility();" disabled /> ผู้กระทำผิด';
	
	ele.appendChild(ctn_office);
	ele.appendChild(ctn_factory);
	ele.appendChild(ctn_case);
}

/**
 *
 */
function update_layer_visibility() {
	if(vec_branch_point != null) {
		toggle_map_layer_visibility(vec_branch_point, 
									document.getElementById('chk_office').checked);
	}
	if(vec_factory_point != null) {
		toggle_map_layer_visibility(vec_factory_point, 
									document.getElementById('chk_factory').checked);
	}
	//toggle_map_layer_visibility(vec_region_polygon, true);
	//toggle_map_layer_visibility(vec_area_polygon, false);
	//toggle_map_layer_visibility(vec_area_point, false);
	//toggle_map_layer_visibility(vec_branch_point, false);
}


/**
 * Load region boundary
 */
function load_data_region_polygon(url) {
	getJSON(
		url,
		function(data) {
			// Create a new vector layer.
			vec_region_polygon = create_vector_layer(data, 
											'EPSG:3857',
											region_polygon_style_function);
			b_region_polygon_loaded = true;
			process_loaded_data();
		}, 
		function(xhr) {
			// On error...
		}
	);
}

/**
 * Load region's centoid
 */
function load_data_region_point(url) {
	getJSON(
		url,
		function(data) {
			// Create a new vector layer.
			vec_region_point = create_vector_layer(data, 
											'EPSG:3857',
											region_point_style_function);
			b_region_point_loaded = true;
			process_loaded_data();
		}, 
		function(xhr) {
		}
	);
}

/**
 * Load area (polygon)
 */
function load_data_area_polygon(url) {
	getJSON(
		url,
		function(data) {
			// Create a new vector layer.
			vec_area_polygon = create_vector_layer(data, 
											'EPSG:3857',
											area_polygon_style_function);
			b_area_polygon_loaded = true;
			process_loaded_data();
		}, 
		function(xhr) {
		}
	);
}

/**
 * Load branch data (polygon centroid)
 */
function load_data_area_point(url) {
	getJSON(
		url,
		function(data) {
			// Create a new vector layer.
			vec_area_point = create_vector_layer(data, 
											'EPSG:3857',
											area_point_style_function);
			b_area_point_loaded = true;
			process_loaded_data();
		}, 
		function(xhr) {
		}
	);
}

/**
 * Load branch data (polygon centroid)
 */
function load_data_branch_point(url) {
	getJSON(
		url,
		function(data) {
			// Create a new vector layer.
			vec_branch_point = create_vector_layer(data, 
											'EPSG:3857',
											branch_point_style_function);
			b_branch_point_loaded = true;
			process_loaded_data();
		}, 
		function(xhr) {
		}
	);
}

/**
 * Load branch data (polygon centroid)
 */
function load_data_factory_point(url) {
	getJSON(
		url,
		function(data) {
			// Create a new vector layer.
			vec_factory_point = create_vector_layer(data, 
											'EPSG:3857',
											factory_point_style_function);
			b_factory_point_loaded = true;
			process_loaded_data();
		}, 
		function(xhr) {
		}
	);
}

/**
 * Load case data (attribute-only data)
 */
function load_data_region(url) {
	getJSON(
		url,
		function(data) {
			map_data = data;
			b_map_data_loaded = true;
			process_loaded_data();
		}, 
		function(xhr) {
		}
	);
}

/**
 * Load monthly case data (attribute-only data)
 */
function load_data_region_monthly(url) {
	getJSON(
		url,
		function(data) {
			map_data_monthly = data;
			b_map_data_monthly_loaded = true;
			process_loaded_data();
		}, 
		function(xhr) {
		}
	);
}

/**
 * Load area data
 */
function load_data_area(url) {
	getJSON(
		url,
		function(data) {
			map_data_area = data;
			b_map_data_area_loaded = true;
			process_loaded_data();
		}, 
		function(xhr) {
		}
	);
}

/**
 * Calculate extends
 *
 * @param r    Regions
 * @param f    Map feature
 * @param p    Padding size
 *
 */
function cal_region_extends(r, f, p) {
	var i;
	var j;
	var fi;
	var r_code;
	var ext;
	
	// Prepare container
	r.length = 0;
	for( i = 0; i < 10; i++ ) {
		r.push([ Number.MAX_SAFE_INTEGER, // min x
				 Number.MAX_SAFE_INTEGER, // min y
				-Number.MAX_SAFE_INTEGER, // max x
				-Number.MAX_SAFE_INTEGER]); // max y
	}
	
	for( i = 0; i < f.length; i++ ) {
		fi = f[i];
		r_code = parseInt(fi.get('REG_CODE')); // regoin code
		ext = fi.getGeometry().getExtent(); // feature extent
		
		// Vertify region code : must be between 1-10
		if((r_code) < 1 || (r_code > 10)) { continue; }
		
		// Correct Javascript array index.
		r_code -= 1;
		
		// Find min and max values.
		if( ext[0] < r[r_code][0] ) { r[r_code][0] = ext[0]; } // min-x
		if( ext[1] < r[r_code][1] ) { r[r_code][1] = ext[1]; } // min-y
		if( ext[2] > r[r_code][2] ) { r[r_code][2] = ext[2]; } // max-x
		if( ext[3] > r[r_code][3] ) { r[r_code][3] = ext[3]; } // max-y
	}
	
	// manual adjust
	for( i = 0; i < r.length; i++ ) {
		r[i][0] -= p;
		r[i][1] -= p;
		r[i][2] += p;
		r[i][3] += p;
	}
}

/**
 * Region selector listener.
 */
function on_ele_sel_region_change() {
	var i;
	var n;
	
	var reg_code = parseInt(ele_sel_region.value);
	
	if( (!reg_code) ) {
		// Switch layers visibility
		toggle_map_layer_visibility(vec_region_polygon, true);
		toggle_map_layer_visibility(vec_area_polygon, false);
		//toggle_map_layer_visibility(vec_area_point, false);
		//toggle_map_layer_visibility(vec_branch_point, false);
		
		return;
	}
	
	var idx = reg_code - 1;
	
	if(idx < 0) { return; }
	
	// Switch layers visibility
	toggle_map_layer_visibility(vec_region_polygon, false);
	toggle_map_layer_visibility(vec_area_polygon, true);
	//toggle_map_layer_visibility(vec_area_point, true);
	//toggle_map_layer_visibility(vec_branch_point, true);
	
	var reg = regions_info.regions[idx];
	
	console.log('REGION:',reg_code, reg);
	console.log('regions_info', regions_info);
	
	//--EDIT BY ITSARA
	/*ele_sel_area.options.length = 0;
	ele_sel_area.options[ele_sel_area.options.length] = new Option('เลือกพื้นที่', '-999');
	
	// Search areas that mathes selected region code
	for(i = 0; i < reg.length; i++) {
		ele_sel_area.options[ele_sel_area.options.length] = 
			new Option(
					reg[i].areas.AREA_TNAME, 
					reg[i].areas.AREA_CODE
		);
	}*/
	
	// Auto zoom
	if( (b_auto_zoom == true) && (region_ext.length > 0) ) {
		//console.log('x', region_ext[idx]);
		zoom_to_extent(map, region_ext[idx], 100);
	}
}

/**
 * Area selector listener.
 */
function on_ele_sel_area_change() {
	var i;
	var r_code = ele_sel_region.value;
	var a_code = ele_sel_area.value;
	console.log("R_CODE:" + r_code + ", A_CODE:" + a_code);
	
	//var f = vec_branch_point.getSource().getFeatures();
	var fa = vec_area_polygon.getSource().getFeatures();
	var fi;
	//var n_areas = f.length;
	
	//ele_sel_branch.options.length = 0;
	if(a_code !== '-999') {
		// Zoom to selected area
		for( i = 0; i < fa.length; i++ ) {
			fi = fa[i];
			
			if(fi.get('AREA_CODE') == a_code) {
				// Zoom to feature
				zoom_to_extent(map, fi.getGeometry().getExtent(), 2000);
				break;
			}
		}
	
		// REMOVED : 2017-06-03
		/*
		// Clear branches
		ele_sel_branch.options.length = 0;
		ele_sel_branch.options[ele_sel_branch.options.length] = new Option('เลือกสาขา', '-999');
		
		// Check all areas
		for( i = 0; i < n_areas; i++ ) {
			fi = f[i];
			if( (fi.get('REG_CODE') == r_code) && (fi.get('AREA_CODE') == a_code ) ) {
				//console.log(r_code, fi.get('REG_CODE'), fi.get('BRAN_CODE'), fi.get('BRAN_TNAME'));
				
				ele_sel_branch.options[ele_sel_branch.options.length] = new Option(
					fi.get('BRAN_TNAME'),
					fi.get('BRAN_CODE')
				);
			}
		}
		*/
	} else {
	}
}

/**
 * Branch selector listener.
 */
function on_ele_sel_branch_change() {
}

/**
 * Create a line chart element for Chart.js
 */
function create_linechart_element(label, n, lc, fc, la, fa) {
	return {
			data: [0,0,0,0,0,0,0,0,0,0,0,0],
			label: label,
			fill: false,
			lineTension: 0,
			backgroundColor: "rgba("+fc[0]+","+fc[1]+","+fc[2]+","+fa+")",
			borderColor: "rgba("+lc[0]+","+lc[1]+","+lc[2]+","+la+")",
			borderCapStyle: 'butt',
			borderDash: [],
			borderDashOffset: 0.0,
			borderJoinStyle: 'miter',
			pointBorderColor: "rgba("+lc[0]+","+lc[1]+","+lc[2]+","+la+"0)",
			pointBackgroundColor: "rgba("+fc[0]+","+fc[1]+","+fc[2]+","+fa+")",
			pointBorderWidth: 1,
			pointHoverRadius: 5,
			pointHoverBorderColor: "rgba("+lc[0]+","+lc[1]+","+lc[2]+","+la+")",
			pointHoverBorderWidth: 2,
			pointRadius: 3,
			pointHitRadius: 10,
	};
}

/**
 * Prepare chart data.
 */
function prepare_chart_overall() {
	console.log('Preparing chart...');
	// ["ภาษี", "ปราบปราม", "ใบอนุญาต", "แสตมป์", "โรงงาน"]
	var tax_line_color = "rgba(255,80,80,0.4)";
	var tax_fill_color = "rgba(255,80,80,1.0)";
	var point_size = 3;
	
	chart_context = document.getElementById("my_chart");
	
	var la = 0.3;
	var fa = 0.4;
	
	// Chart elements
	// fiscal year format
	var dummy_chart_prop_1 = create_linechart_element('ภาษี', 12, categories[1], categories[1], la, fa);
	var dummy_chart_prop_2 = create_linechart_element('การปราบปราม', 12, categories[2], categories[2], la, fa);
	var dummy_chart_prop_3 = create_linechart_element('ใบอนุญาต', 12, categories[3], categories[3], la, fa);
	var dummy_chart_prop_4 = create_linechart_element('สแตมป์', 12, categories[4], categories[4], la, fa);
	var dummy_chart_prop_5 = create_linechart_element('โรงงาน', 12, categories[5], categories[5], la, fa);
	var dummy_chart_prop_6 = create_linechart_element('รวม', 12, [80,200,255], [80,200,255],1.0, 0.95);
	// Attach data
	var chart_data = {
		labels: ['ต.ค.', 'พ.ย.', 'ธ.ค.', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.'],
		datasets: [
			dummy_chart_prop_1,
			dummy_chart_prop_2,
			dummy_chart_prop_3,
			dummy_chart_prop_4,
			dummy_chart_prop_5,
			dummy_chart_prop_6,
		]
	};
	// generic year format
	/*
	var dummy_chart_prop_1 = create_linechart_element('ภาค 1', 12, categories[1], categories[1], la, fa);
	var dummy_chart_prop_2 = create_linechart_element('ภาค 2', 12, categories[2], categories[2], la, fa);
	var dummy_chart_prop_3 = create_linechart_element('ภาค 3', 12, categories[3], categories[3], la, fa);
	var dummy_chart_prop_4 = create_linechart_element('ภาค 4', 12, categories[4], categories[4], la, fa);
	var dummy_chart_prop_5 = create_linechart_element('ภาค 5', 12, categories[5], categories[5], la, fa);
	var dummy_chart_prop_6 = create_linechart_element('ภาค 6', 12, categories[6], categories[6], la, fa);
	var dummy_chart_prop_7 = create_linechart_element('ภาค 7', 12, categories[7], categories[7], la, fa);
	var dummy_chart_prop_8 = create_linechart_element('ภาค 8', 12, categories[8], categories[8], la, fa);
	var dummy_chart_prop_9 = create_linechart_element('ภาค 9', 12, categories[9], categories[9], la, fa);
	var dummy_chart_prop_10 = create_linechart_element('ภาค 10', 12, categories[10], categories[10], la, fa);
	var dummy_chart_prop_11 = create_linechart_element('ทั้งประเทศ', 12, [80,200,255], [80,200,255],1.0, 0.95);
	
	// Attach data
	var chart_data = {
		labels: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
		datasets: [
			dummy_chart_prop_1,
			dummy_chart_prop_2,
			dummy_chart_prop_3,
			dummy_chart_prop_4,
			dummy_chart_prop_5,
			dummy_chart_prop_6,
			dummy_chart_prop_7,
			dummy_chart_prop_8,
			dummy_chart_prop_9,
			dummy_chart_prop_10,
			dummy_chart_prop_11,
		]
	};
	*/	

	// Create chart
	chart_container = new Chart(chart_context, {
		type: 'line',
		data: chart_data,
		options: {
			animation: {animateScale: true},
			responsive: true,
			scales: {
				xAxes: [{
					scaleLabel: {
						display: true,
						labelString: 'เดือน'
					}
				}],
				yAxes: [{
					scaleLabel: {
						display: true,
						labelString: 'มูลค่า'
					}
				}]
			},
		}
	});
}

/**
 * Prepare chart data.
 */
function prepare_chart() {
	console.log('Preparing chart...');
	// ["ภาษี", "ปราบปราม", "ใบอนุญาต", "แสตมป์", "โรงงาน"]
	var tax_line_color = "rgba(255,80,80,0.4)";
	var tax_fill_color = "rgba(255,80,80,1.0)";
	var point_size = 3;
	
	chart_context = document.getElementById("my_chart");
	
	var la = 0.3;
	var fa = 0.4;
	
	// Chart elements
	// fiscal year format
	var dummy_chart_prop = create_linechart_element('รวม', 12, [80,200,255], [80,200,255],1.0, 0.95);
	var chart_data = {
		labels: ['ต.ค.', 'พ.ย.', 'ธ.ค.', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.'],
		datasets: [
			dummy_chart_prop,
		]
	};

	// Create chart
	chart_container = new Chart(chart_context, {
		type: 'line',
		data: chart_data,
		options: {
			animation: {animateScale: true},
			responsive: true,
			scales: {
				xAxes: [{
					scaleLabel: {
						display: true,
						labelString: 'เดือน'
					}
				}],
				yAxes: [{
					scaleLabel: {
						display: true,
						labelString: 'มูลค่า'
					}
				}]
			},
		}
	});
}

/**
 * Prepare chart data.
 */
function prepare_chart_() {
	console.log('Preparing chart...');
	// ["ภาษี", "ปราบปราม", "ใบอนุญาต", "แสตมป์", "โรงงาน"]
	var tax_line_color = "rgba(255,80,80,0.4)";
	var tax_fill_color = "rgba(255,80,80,1.0)";
	var point_size = 3;
	
	chart_context = document.getElementById("my_chart");
	
	var la = 0.3;
	var fa = 0.4;
	
	// Chart elements
	// fiscal year format
	var dummy_chart_prop_1 = create_linechart_element('ภาษี', 12, categories[1], categories[1], la, fa);
	var dummy_chart_prop_2 = create_linechart_element('การปราบปราม', 12, categories[2], categories[2], la, fa);
	var dummy_chart_prop_3 = create_linechart_element('ใบอนุญาต', 12, categories[3], categories[3], la, fa);
	var dummy_chart_prop_4 = create_linechart_element('สแตมป์', 12, categories[4], categories[4], la, fa);
	var dummy_chart_prop_5 = create_linechart_element('โรงงาน', 12, categories[5], categories[5], la, fa);
	var dummy_chart_prop_6 = create_linechart_element('รวม', 12, [80,200,255], [80,200,255],1.0, 0.95);
	// Attach data
	var chart_data = {
		labels: ['ต.ค.', 'พ.ย.', 'ธ.ค.', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.'],
		datasets: [
			dummy_chart_prop_1,
			dummy_chart_prop_2,
			dummy_chart_prop_3,
			dummy_chart_prop_4,
			dummy_chart_prop_5,
			dummy_chart_prop_6,
		]
	};
	// generic year format
	/*
	var dummy_chart_prop_1 = create_linechart_element('ภาค 1', 12, categories[1], categories[1], la, fa);
	var dummy_chart_prop_2 = create_linechart_element('ภาค 2', 12, categories[2], categories[2], la, fa);
	var dummy_chart_prop_3 = create_linechart_element('ภาค 3', 12, categories[3], categories[3], la, fa);
	var dummy_chart_prop_4 = create_linechart_element('ภาค 4', 12, categories[4], categories[4], la, fa);
	var dummy_chart_prop_5 = create_linechart_element('ภาค 5', 12, categories[5], categories[5], la, fa);
	var dummy_chart_prop_6 = create_linechart_element('ภาค 6', 12, categories[6], categories[6], la, fa);
	var dummy_chart_prop_7 = create_linechart_element('ภาค 7', 12, categories[7], categories[7], la, fa);
	var dummy_chart_prop_8 = create_linechart_element('ภาค 8', 12, categories[8], categories[8], la, fa);
	var dummy_chart_prop_9 = create_linechart_element('ภาค 9', 12, categories[9], categories[9], la, fa);
	var dummy_chart_prop_10 = create_linechart_element('ภาค 10', 12, categories[10], categories[10], la, fa);
	var dummy_chart_prop_11 = create_linechart_element('ทั้งประเทศ', 12, [80,200,255], [80,200,255],1.0, 0.95);
	
	// Attach data
	var chart_data = {
		labels: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
		datasets: [
			dummy_chart_prop_1,
			dummy_chart_prop_2,
			dummy_chart_prop_3,
			dummy_chart_prop_4,
			dummy_chart_prop_5,
			dummy_chart_prop_6,
			dummy_chart_prop_7,
			dummy_chart_prop_8,
			dummy_chart_prop_9,
			dummy_chart_prop_10,
			dummy_chart_prop_11,
		]
	};
	*/	

	// Create chart
	chart_container = new Chart(chart_context, {
		type: 'line',
		data: chart_data,
		options: {
			animation: {animateScale: true},
			responsive: true,
			scales: {
				xAxes: [{
					scaleLabel: {
						display: true,
						labelString: 'เดือน'
					}
				}],
				yAxes: [{
					scaleLabel: {
						display: true,
						labelString: 'มูลค่า'
					}
				}]
			},
		}
	});
}

/**
 * Update chart data.
 */
function update_chart_data_overall(ctx, ctn, data, field) {
	var i;
	var k;
	var j;
	var m;
	var val;
	var monthly_data = [0,0,0,0,0,0,0,0,0,0,0,0];
	var monthly_sum_data = [0,0,0,0,0,0,0,0,0,0,0,0];
	var fm = data.features;
	var rk;
	var ri;
	var fi;
	var color = [0,0,0];
	
	console.log('update_chart_data_overall');
	
	// Clear previous dataset
	ctn.data.datasets.length = 0;
	
	// Insert new datasets.
	var labels = ['ภาษี', 'ปราบปราม', 'ใบอนุญาต', 'สแตมป์', 'โรงงาน'];
	for (i = 0; i < labels.length; i++) {
		ri = i + 1;
		color[0] = Math.round(Math.random()*255);
		color[1] = Math.round(Math.random()*255);
		color[2] = Math.round(Math.random()*255);
		var dummy = create_linechart_element(
									labels[i], 
									12, 
									color, 
									color,
									1.0,
									0.95);
		ctn.data.datasets.push(dummy);
	}
	
	// Accumulate values
	for( i = 0; i < fm.length; i++ ) {
		fi = fm[i];
		
		// get region code and month
		ri = parseInt(fi.properties.REG_CODE) - 1;
		m  = parseInt(fi.properties.MONTH) - 1
		f_val_tax = data.features[i].properties.VAL_TAX;
		f_val_case = data.features[i].properties.VAL_CASE;
		f_val_lic = data.features[i].properties.VAL_LIC;
		f_val_stamp = data.features[i].properties.VAL_STAMP;
		f_val_fac = data.features[i].properties.VAL_FAC;
		f_val_total = data.features[i].properties.VAL_TOTAL;

		// Accumulate values
		ctn.data.datasets[0].data[m] += f_val_tax;
		ctn.data.datasets[1].data[m] += f_val_case;
		ctn.data.datasets[2].data[m] += f_val_lic;
		ctn.data.datasets[3].data[m] += f_val_stamp;
		ctn.data.datasets[4].data[m] += f_val_fac;
		
		console.log(ri, m, f_val_tax, f_val_fac);
	}
	
	// Refresh chart
	ctn.update();
}

/**
 * Update chart data.
 *
 * @param ctx		Drawing context
 * @param ctn		Chart container
 * @param data		Chart data
 * @param field		Field to be used
 */
function update_chart_data(ctx, ctn, data, field) {
	var i;
	var k;
	var j;
	var m;
	var val;
	var monthly_data = [0,0,0,0,0,0,0,0,0,0,0,0];
	var monthly_sum_data = [0,0,0,0,0,0,0,0,0,0,0,0];
	var fm = data.features;
	var rk;
	var ri;
	var fi;
	
	// Clear
	
	// For each feature,...
	for( i = 0; i < fm.length; i++ ) {
		fi = fm[i];
		
		// Select field
		if(field == 'SUM') {
			val = fi.properties.SUM;
		} else if(field == 'COUNT') {
			val = fi.properties.COUNT;
		} else if(field == 'VAL_TOTAL') {
			val = fi.properties.VAL_TOTAL;
		}
		console.log(fi.properties.REG_CODE, fi.properties.MONTH, val);
		
		// Accumulate the value
		monthly_data[fi.properties.MONTH-1] = val;
		monthly_sum_data[fi.properties.MONTH-1] += val;
	}
	
	// Summary data
	for( i = 0; i < monthly_sum_data.length; i++ ) {
		ctn.data.datasets[0].data[i] = monthly_sum_data[i];
	}
	
	//var dummy = create_linechart_element('รวมssdf', 12, [80,200,255], [80,200,255],1.0, 0.95);
	//console.log(ctn.data.datasets.push(dummy));
	
	// Refresh chart
	ctn.update();
}

/**
 * Update chart data.
 *
 * @param ctx		Drawing context
 * @param ctn		Chart container
 * @param data		Chart data
 * @param field		Field to be used
 */
function update_chart_data_region(ctx, ctn, data, field, reg_code = -999, area_code = '') {
	var i;
	var k;
	var j;
	var m;
	var val;
	var monthly_data = [0,0,0,0,0,0,0,0,0,0,0,0];
	var monthly_sum_data = [0,0,0,0,0,0,0,0,0,0,0,0];
	var fm = data.features;
	var rk;
	var ri;
	var fi;
	var n_regions = 10;
	var n_areas = 0;
	var areas = 0;
	var label;
	var color = [0,0,0];
	
	console.log('update chart:', reg_code);
	console.log('c0', categories[1]);
	
	// Clear previous dataset
	ctn.data.datasets.length = 0;
	
	// If no region code is given,
	// then show graph of all regions.
	// Otherwise, show graphs of ares inside the
	// given region.
	if((reg_code == -999) && (area_code == '')) {
		// Insert new datasets.
		for (i = 0; i < n_regions; i++) {
			ri = i + 1;
			label = 'ภาค ' + (i+1);
			color[0] = categories[ri][0];
			color[1] = categories[ri][1];
			color[2] = categories[ri][2];
			var dummy = create_linechart_element(
										label, 
										12, 
										color, 
										color,
										1.0,
										0.95);
			ctn.data.datasets.push(dummy);
		}
		
		// Acculate values
		for( i = 0; i < fm.length; i++ ) {
			fi = fm[i];
			
			// Select field
			if(field == 'SUM') {
				val = fi.properties.SUM;
			} else if(field == 'COUNT') {
				val = fi.properties.COUNT;
			} else if(field == 'VAL_TOTAL') {
				val = fi.properties.VAL_TOTAL;
			}
			
			// get region code and month
			ri = parseInt(fi.properties.REG_CODE) - 1;
			m  = parseInt(fi.properties.MONTH) - 1
			
			// Accumulate the value
			ctn.data.datasets[ri].data[m] += val;
		}
		
		// Update chart datasets.
		for (i = 0; i < n_regions; i++) {
			for( j = 0; j < monthly_sum_data.length; j++ ) {
				//ctn.data.datasets[i].data[j] = monthly_sum_data[j];
			}
		}
	} else {
		// Check number of areas
		areas = document.getElementById('area').options;
		n_areas = areas.length;
		console.log("number of ares:", n_areas);
		
		// No area code is given.
		if( area_code == '' ) {
			// Region code is given
			// show graphs of ares inside the given region.
			// Insert new datasets.
			for(i = 1; i < n_areas; i++) {
				label = areas[i].text.replace('สำนักงานสรรพสามิต', '');
				console.log("\t", label);
				
				//color[0] = Math.round(((i/n_areas)*255));
				//color[1] = 128;
				//color[2] = 255 - area_color[0];
				color[0] = Math.round(Math.random()*255);
				color[1] = Math.round(Math.random()*255);
				color[2] = Math.round(Math.random()*255);
				var dummy = create_linechart_element(
											label, 
											12, 
											color, 
											color,
											1.0,
											0.95);
				ctn.data.datasets.push(dummy);
			}
			
			// Acculate values
			for( i = 0; i < (n_areas-1); i++ ) {
				for( m = 0; m < 12; m++ ) {
					val = Math.random() * 1000;
					ctn.data.datasets[i].data[m] += val;
				}
			}
		} else {
			// User provide both region code and area code.
			// In this case, show only one line
			// Check area name from dropdown list.
			/*label = '';
			for(i = 1; i < n_areas; i++) {
				if(areas[i].value == area_code) {
					label = areas[i].text;
					break;
				}
			}*/
			label = get_dropdown_text(areas, area_code);
			
			// Is area code found?
			if( label.length > 0 ) {
				color[0] = 80;//Math.round(Math.random()*255);
				color[1] = 220;//Math.round(Math.random()*255);
				color[2] = 255;//Math.round(Math.random()*255);
				var dummy = create_linechart_element(
											label, 
											12, 
											color, 
											color,
											1.0,
											0.95);
				ctn.data.datasets.push(dummy);
				
				// Update data
				for( m = 0; m < 12; m++ ) {
					val = Math.random() * 1000;
					ctn.data.datasets[0].data[m] += val;
				}
			}
		}
	}

	// For each feature,...
	/*for( i = 0; i < fm.length; i++ ) {
		fi = fm[i];
		
		// Select field
		if(field == 'SUM') {
			val = fi.properties.SUM;
		} else if(field == 'COUNT') {
			val = fi.properties.COUNT;
		} else if(field == 'VAL_TOTAL') {
			val = fi.properties.VAL_TOTAL;
		}
		console.log(fi.properties.REG_CODE, fi.properties.MONTH, val);
		
		// Accumulate the value
		monthly_data[fi.properties.MONTH-1] = val;
		monthly_sum_data[fi.properties.MONTH-1] += val;
	}*/
	
	// Summary data
	//for( i = 0; i < monthly_sum_data.length; i++ ) {
	//	ctn.data.datasets[0].data[i] = monthly_sum_data[i];
	//}
	
	//var dummy = create_linechart_element('รวมssdf', 12, [80,200,255], [80,200,255],1.0, 0.95);
	//console.log(ctn.data.datasets.push(dummy));
	
	// Refresh chart
	ctn.update();
}

/**
 *
 */
function get_dropdown_text(d, key) {
	var i;
	var text = '';
	for(i = 1; i < d.length; i++) {
		if(d[i].value == key) {
			text = d[i].text;
			break;
		}
	}
	return text;
}

/**
 * @param vf 		vector features
 */
function show_thematic_map_region(vf, data) {
	var i;
	var j;
	var vi;
	var fj;
	var ri;
	var rj;
	
	// THEMATIC MAP
	var min =  Number.MAX_SAFE_INTEGER;
	var max = -Number.MAX_SAFE_INTEGER;
	var range = 0.0;
	var val;
	
	// DUMMY DATA
	var classes = [];
	classes.length = 0;
	for( i = 0; i < vf.length; i++ ) {
		// Get region code of i-th feature
		vi = vf[i];
		ri = vi.get('REG_CODE'); 
		val = data.features[ri-1].properties.COUNT;
		classes.push({
				VAL:val, 
				COLOR_INDEX:-1});
				
		// Find min and max
		if(val < min) { min = val; }
		if(val > max) { max = val; }
	}
	console.log("min-max:", min, max);
	
	// Calcualte thresholds
	range = (max - min)/n_classes;
	val = min;
	thresholds.length = 0;
	thresholds.push(val);
	for( i = 0; i < n_classes; i++ ) {
		val += range;
		thresholds.push(val);
	}
	
	// Create lookup table
	create_LUT(n_classes, classes, thresholds, min, max, range);
	update_legend_box(ele_legend_box, thresholds);
	
	// Apply styles
	set_feature_style(vf, classes, default_polygon_thematic_style);
}

/**
 * Show area map
 * @param vf 		vector features
 */
function show_thematic_map_area(vf, target_area, data) {
	var i;
	var j;
	var vi;
	var vj;
	var fj;
	var ri;
	var rj;
	
	// THEMATIC MAP
	var min =  Number.MAX_SAFE_INTEGER;
	var max = -Number.MAX_SAFE_INTEGER;
	var range = 0.0;
	var val;
	
	// Match area codes
	var classes = [];
	classes.length = 0;
	for( i = 0; i < vf.length; i++ ) {
		// Get region code of i-th feature
		vi = vf[i];
		ri = vi.get('AREA_CODE'); 
		
		for (j = 0; j < data.features.length; j++) {
			rj = data.features[j].properties.AREA_CODE;
			val = data.features[j].properties.VAL_SUM;
			
			if (ri == rj) {
				classes.push({VAL:val, COLOR_INDEX:-1});
				break;
			}
		}
		
		// Find min and max
		if(val < min) { min = val; }
		if(val > max) { max = val; }
	}
	//console.log("min-max:", min, max);
	//return;
	
	// Calcualte thresholds
	range = (max - min)/n_classes;
	val = min;
	thresholds.length = 0;
	thresholds.push(val);
	for( i = 0; i < n_classes; i++ ) {
		val += range;
		thresholds.push(val);
	}
	
	// Create lookup table
	create_LUT(n_classes, classes, thresholds, min, max, range);
	
	// Apply styles
	set_feature_style(vf, classes, default_polygon_thematic_style);
}

/**
 * Show legend.
 *
 * @param e
 * @param t
 */
function update_legend_box(e, t) {
	e.innerHTML = "<h3>สัญลักษณ์แผนที่</h3>";
	console.log('kjkjkj', t);
	var i;
	for( i = 0; i < (t.length-1); i++ ) {
		var container = document.createElement("div");
		container.className = 'map_legend_box';
		
		// i-th legend color
		var legend = document.createElement("div");
		legend.className = 'map_legend_legend_box';
		legend.style.backgroundColor = 'rgba(' + colors[i][0] + ', ' + colors[i][1] + ', ' + colors[i][2] + ', ' + colors[i][3] + ')';
		
		// i-th legend text
		var text = document.createElement("div");
		text.className = 'map_legend_legend_text';
		text.innerHTML = t[i].toFixed(0) + " - " + t[i+1].toFixed(0);
		
		// Add to container.
		container.appendChild(legend);
		container.appendChild(text);
		e.appendChild(container);
	}
}