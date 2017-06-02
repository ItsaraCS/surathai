<?php require('header.php'); ?>
<?php require('nav.php'); ?>
<!--OPEN LAYER-->
<link href="css/popup.css" rel="stylesheet" type="text/css">
<script src="js/getJson.js" type="text/javascript"></script>
<script src="js/olmaplib.js" type="text/javascript"></script>
<script src="js/mouselib.js" type="text/javascript"></script>
<script src="js/mappopup.js" type="text/javascript"></script>
<!--SECTION-->
<div class="section">
	<!--MAP-->
	<div class="container-fluid" style="margin-top: 15px;">
		<div id="map" class="map" style="width:100%; height: 100%; position:fixed"></div>
		<!--POPUP-->
		<div id="popup" class="ol-popup">
			<a href="#" id="popup-closer" class="ol-popup-closer"></a>
			<div id="popup-content"></div>
		</div>
	</div>

	<!--LOADING MAP-->
	<div id="dvloading" class="loader"></div>

	<!--GRAPH-->
	<div id="chart_container" class="panel">
		<div id="chart_group">
			<div id="chart_title" class="panel text-center" data-toggle="collapse" href="#collapse1">กราฟข้อมูลภาษี</div>
			<div id="collapse1" class="panel-collapse collapse">
				<div id="chart_box">
					<canvas id="my_chart" height="45px"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>
<!--JS-->
<script type="text/javascript">
    $(document).ready(function(e) {
        //--Variable
        var factory = new Factory();
        var ajaxUrl = 'http://210.4.143.51/Surathai01/API/taxmapAPI.php';
        var params = {};
		var year = $('.nav-menu #year').val() || '';
        var region = $('.nav-menu #region').val() || 0;
        var province = $('.nav-menu #province').val() || 0;

        //--Page load
		setInit();

        //--Function
		function setInit() {
            params = {
                fn: 'filter',
                job: 1,
                src: 0
            };

            factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                if(res != undefined){
                    var data = JSON.parse(res);
					console.log(data);

                    $.each(data.year, function(index, item) {
                        $('.nav-menu #year').append('<option value="'+ item.value +'">'+ item.label +'</option>');
                    });

                    $.each(data.region, function(index, item) {
                        $('.nav-menu #region').append('<option value="'+ item.id +'">'+ item.label +'</option>');
                    });
                    
                    $.each(data.province, function(index, item) {
                        $('.nav-menu #province').append('<option value="'+ item.id +'">'+ item.label +'</option>');
                    });
                }
            });
        }
        
		//--Event
		$(document).on('change', '.nav-menu #year', function(e) {
            e.preventDefault();
            
            $('.nav-menu #region').find('option:eq(0)').prop('selected', true);
            $('.nav-menu #province option[value!=""]').remove();
            
            year = $('.nav-menu #year').val() || '';

            if(year != '') {
                $('.nav-menu #region').find('option:eq(1)').prop('selected', true);
                region = $('.nav-menu #region').val() || 0;

                if(region != '') {
                    params = {
                        fn: 'filter',
                        job: 1,
                        src: 1,
                        value: region || 0
                    };

                    factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                        if(res != undefined){
                            var data = JSON.parse(res);

                            $.each(data, function(index, item) {
                                $('.nav-menu #province').append('<option value="'+ item.id +'">'+ item.label +'</option>');
                            });

                            $('.nav-menu #province').find('option:eq(1)').prop('selected', true);
                        }
                    });
                }
            }
        });

        $(document).on('change', '.nav-menu #region', function(e) {
            e.preventDefault();
            
            $('.nav-menu #province').find('option[value!=""]').remove();

            region = $('.nav-menu #region').val() || 0;
            
            if(region != '') {
                params = {
                    fn: 'filter',
                    job: 1,
                    src: 1,
                    value: region || 0
                };
            
                factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                    if(res != undefined){
                        var data = JSON.parse(res);

                        $.each(data, function(index, item) {
                            $('.nav-menu #province').append('<option value="'+ item.id +'">'+ item.label +'</option>');
                        });

                        $('.nav-menu #province').find('option:eq(1)').prop('selected', true);
                    }
                });
            }
        });

        $(document).on('change', '.nav-menu #province', function(e) {
            e.preventDefault();
        });

		//--Map API
		var filer_region = null;
		var styleCache = {};
		
		// UI elements
		var container = null;
		var ele_btn_view = null;
		var ele_popup = null;
		var ele_sel_region = null;
		var ele_sel_area = null;
		var ele_sel_branch = null;
		var ele_chart = null;
		var chart_context = null;
		var chart_container = null;
		
		// Tax data
		var tax_dat = null;
		
		// Misc
		var b_auto_zoom = true;
		var MY_DEFAULT_LINE_COLOR = [ 0, 0, 0, 0.5 ];
		
		// Data loading misc
		var b_region_polygon_loaded = false;
		var b_region_point_loaded = false;
		var b_province_polygon_loaded = false;
		var b_area_point_loaded = false;
		var b_branch_point_loaded = false;
		var b_tax_data_loaded = false;
		var b_data_ready = false;
		
		// Layers
		var ras_background = null;
		var vec_region_polygon = null;
		var vec_region_point = null;
		var vec_province_polygon = null;
		var vec_area_point = null;
		var vec_branch_point = null;
		var vec_province = null;
		
		// Polygon colors
		var n_classes = 10;
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
		var odata = [
			{REG:'01', VAL:0.00, COLOR_INDEX:-1},
			{REG:'02', VAL:0.00, COLOR_INDEX:-1},
			{REG:'03', VAL:0.00, COLOR_INDEX:-1},
			{REG:'04', VAL:0.00, COLOR_INDEX:-1},
			{REG:'05', VAL:0.00, COLOR_INDEX:-1},
			{REG:'06', VAL:0.00, COLOR_INDEX:-1},
			{REG:'07', VAL:0.00, COLOR_INDEX:-1},
			{REG:'08', VAL:0.00, COLOR_INDEX:-1},
			{REG:'09', VAL:0.00, COLOR_INDEX:-1},
			{REG:'10', VAL:0.00, COLOR_INDEX:-1},
		];
		
		// STYLE LABEL POINT.
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
				size:           '14px',
				offsetX:        '0',
				offsetY:        '-10',
				color:          'rgba(0, 0, 0, 1.0)',
				outline:        'rgba(255, 255, 255, 0.0)',
				outlineWidth:   '2',
				maxreso:        '6000'
			},
			area_points: {
				text:           'normal',
				align:          'center',
				baseline:       'bottom',
				rotation:       '0',
				font:           'MS Sans Serif',
				weight:         'normal',
				size:           '12px',
				offsetX:        '0',
				offsetY:        '-8',
				color:          'rgba(0, 0, 0, 0.5)',
				outline:        'rgba(255, 255, 255, 0.0)',
				outlineWidth:   '2',
				maxreso:        '800'
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
			}
		};

		getMap();

		function getMap() {
			// Show progress dialog
			console.log('Loading...');
			$('#dvloading').show();
			
			// Get UI elements
			container = document.getElementById('popup');
			ele_sel_region = document.getElementById('filter-region'); // region names list 
			ele_sel_area = document.getElementById('filter-area'); // area names list
			ele_sel_branch = document.getElementById('filter-branch'); // branch
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
			create_color_ramp(n_classes, colors, cs = 'Y_R', false);
			
			//default_region_polygon_styles.push(create_style(MY_DEFAULT_LINE_COLOR, 1, [255, 255, 255, 1.0]));
			
			polygon_styles.push(create_style(MY_DEFAULT_LINE_COLOR, 1, [255, 255, 255, 1.0]));
			for( i = 0; i < n_classes; i++ ) {
				/*polygon_styles.push(create_style(
										MY_DEFAULT_LINE_COLOR, 
										1, 
										[colors[i][0], 
											colors[i][1],
											colors[i][2],
											colors[i][3]]));*/
				//console.log(categories[i]);
				default_region_polygon_styles.push(create_style(
										MY_DEFAULT_LINE_COLOR, 
										1, categories[i]));										
			}
			
			for( i = 0; i < n_classes; i++ ) {
				default_polygon_thematic_style.push(
					create_style(
						MY_DEFAULT_LINE_COLOR, 
						1, 
						[colors[i][0], 
							colors[i][1],
							colors[i][2],
							colors[i][3]]));										
			}
			
			// Prepare data
			prepare_region_and_area();
			
			// Load map data
			load_data_region_polygon('data/geojson/excise_region.geojson');
			load_data_region_point('data/geojson/point_region.geojson');
			load_data_area_point('data/geojson/excise_area_centroid_compact.geojson');
			load_data_branch_point('data/geojson/excise_branch_centroid.geojson');
			load_data_province_polygon('data/geojson/province.geojson');
			load_data_tax('data/geojson/tax_data_10_years.geojson');
			
			console.log('Done.');
			$('#dvloading').hide();
		}
		
		/**
		 * Prepare regions, areas, and branches data.
		 */
		function prepare_region_and_area() {
			getJSON(
				'data/geojson/excise_area_centroid_compact.geojson',
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
		 * Load tax data (attribute-only data)
		 */
		function load_data_tax(url) {
			getJSON(
				url,
				function(data) {
					tax_data = data;
					b_tax_data_loaded = true;
					process_loaded_data();
				}, 
				function(xhr) {
				}
			);
		}
		
		/**
		 * Load province (polygon)
		 */
		function load_data_province_polygon(url) {
			getJSON(
				url,
				function(data) {
					// Create a new vector layer.
					vec_province_polygon = create_vector_layer(data, 
													'EPSG:3857',
													province_polygon_style_function);
					b_province_polygon_loaded = true;
					process_loaded_data();
				}, 
				function(xhr) {
				}
			);
		}
		
		/**
		 *
		 */
		function process_loaded_data() {
			b_data_ready = b_region_polygon_loaded
						&& b_region_point_loaded
						&& b_area_point_loaded
						&& b_branch_point_loaded
						&& b_province_polygon_loaded
						&& b_tax_data_loaded;
			
			if(b_data_ready == false) { 
				console.log('...still loading...');
				return; 
			}
			
			//
			// Initialize other components
			//
			// Set default style.
			set_default_region_polygon_style(vec_region_polygon.getSource().getFeatures());

			var projection = ol.proj.get('EPSG:3857');

			// Map instance.
			map = new ol.Map({
				layers : [vec_region_polygon,
							vec_province_polygon],
				overlays: [overlay],//for popup
				target : 'map',
				view: new ol.View({
				center: [100, 13],
				projection: projection,
				zoom: 3
				})
			});
			$('#dvloading').hide().fadeOut();
			
			map.getView().setCenter(ol.proj.transform([103.0, 8.5], 'EPSG:4326', 'EPSG:3857'));
			map.getView().setZoom(5.5);
			
			// Add mouse event listeners
			//map.on('pointerdown', on_map_mouse_down);
			//map.on('pointerup', on_map_mouse_up);
			//map.on('pointermove', on_map_mouse_move);
			//map.on('click', on_map_mouse_up);
			// DUMMY
			map.on('singleclick', function(evt) {
				var coordinate = evt.coordinate;
				var hdms = ol.coordinate.toStringHDMS(
								ol.proj.transform(
									coordinate, 
									'EPSG:3857', 
									'EPSG:4326'));

				var t1 = (Math.random() * 10000);
				var t2 = (Math.random() * 100);
				var t3 = (Math.random() * 1000);
				var t4 = (Math.random() * 400);
				var t5 = (Math.random() * 200);
				var str = "";
				str += "<b>มูลค่าภาษี (รวม)</b><br />";
				str += "<b>ภาษี:</b> " + t1.toFixed(2) + "<br />";
				str += "<b>ปราบปราม:</b> " + t2.toFixed(2) + "<br />";
				str += "<b>ใบอนุญาต:</b> " + t3.toFixed(2) + "<br />";
				str += "<b>แสตมป์:</b> " + t4.toFixed(2) + "<br />";
				str += "<b>โรงงาน:</b> " + t5.toFixed(2) + "<br />";
				popup_content.innerHTML = str;
				overlay.setPosition(coordinate);
			});

			// Attach event listeners
			ele_sel_region.onchange = on_ele_sel_region_change;
			ele_sel_area.onchange = on_ele_sel_area_change;
			ele_sel_branch.onchange = on_ele_sel_branch_change;
			ele_btn_view.onclick = show_map;
			
			// Region extends
			cal_region_extends(region_ext, vec_region_polygon.getSource().getFeatures());
		}
		
		/**
		 *
		 */
		function cal_region_extends(r, f) {
			var i;
			var j;
			var fi;
			var r_code;
			var ext;
			
			// Prepare container
			r.length = 0;
			for( i = 0; i < 10; i++ ) {
				r.push([ Number.MAX_SAFE_INTEGER, 
							Number.MAX_SAFE_INTEGER,
						-Number.MAX_SAFE_INTEGER,
						-Number.MAX_SAFE_INTEGER]);
			}
			
			for( i = 0; i < f.length; i++ ) {
				fi = f[i];
				r_code = parseInt(fi.get('REG_CODE'));
				ext = fi.getGeometry().getExtent();
				
				// Find min and max values.
				if( ext[0] < r[r_code-1][0] ) { r[r_code-1][0] = ext[0]; }
				if( ext[1] < r[r_code-1][1] ) { r[r_code-1][1] = ext[1]; }
				if( ext[2] > r[r_code-1][2] ) { r[r_code-1][2] = ext[2]; }
				if( ext[3] > r[r_code-1][3] ) { r[r_code-1][3] = ext[3]; }
			}
			//console.log(r);
		}
		
		
		
		// ----------------------------------------------------------------
		// OpenLayer's map style functions.
		// ----------------------------------------------------------------
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

			return text;
		}
		
		// ----------------------------------------------------------------
		// STYLES
		// ----------------------------------------------------------------
		/**
		 * Crate styles
		 */
		function region_polygon_style_function(feature, resolution) {
			return new ol.style.Style({
						image: new ol.style.Circle({
							radius: 4,
							fill: new ol.style.Fill({color: 'rgba(0, 0, 0, 0.8)'}),
							stroke: new ol.style.Stroke({color: 'red', width: 0.1})
						})});
		}
		
		/**
		 * Crate style
		 */
		function region_point_style_function(feature, resolution) {
			return new ol.style.Style({
						image: new ol.style.Circle({
							radius: 3,
							fill: new ol.style.Fill({color: 'rgba(0, 0, 0, 0.5)'}),
							stroke: new ol.style.Stroke({color: 'red', width: 0.1})
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
		function area_point_style_function(feature, resolution) {
			return new ol.style.Style({
						image: new ol.style.Circle({
							radius: 2,
							fill: new ol.style.Fill({color: 'rgba(0, 0, 0, 0.3)'}),
							stroke: new ol.style.Stroke({color: 'red', width: 0.1})
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
							radius: 2,
							fill: new ol.style.Fill({color: 'rgba(0, 0, 0, 0.15)'}),
							stroke: new ol.style.Stroke({color: 'red', width: 0.1})
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
		function province_polygon_style_function(feature, resolution) {
			return new ol.style.Style({
							fill : new ol.style.Fill({
									color : 'rgba(255, 255, 255, 0.5)',
							}),
							stroke : new ol.style.Stroke({
									color : 'rgba(0, 0, 0, 0.3)',
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
				//console.log(region_code);
				vi.setStyle(default_region_polygon_styles[region_code]);
			}
		}
		
		// ----------------------------------------------------------------
		// VIEW BUTTON
		// ----------------------------------------------------------------
		/**
		 *
		 */
		function show_map() {
			var i;
			var filter_year = $('#filter-year').val();
			var filter_region = $('#filter-region option:selected').val();
			var filter_area = $('#filter-area option:selected').val();
			var filter_branch = $('#filter-branch option:selected').val();
			var none = " / ";
			var fi;
			var ri;
			var vi;
			var sum;
			
			if(filter_region == '00') { return; }
			
			var target_reg_code = parseInt(ele_sel_region.value); // region code
			
			console.log(filter_year,filter_region,filter_area,filter_branch);
			//console.log('region', tax_data.features.length);
			console.log(filter_region, target_reg_code);
			
			sum = 0;
			for (i = 0; i < tax_data.features.length; i++ ) {
				fi = tax_data.features[i].properties;
				
				ri = fi.field_18; // region code
				val = fi.field_13; // tax value
				
				if(ri && val && !isNaN(ri) && !isNaN(val)) {
					if( ri == target_reg_code ) {
						//console.log('  ', target_reg_code, ri, val);
					}
				}
			}
			
			// Show thematic map
			show_thematic_map(vec_province_polygon.getSource().getFeatures(), odata);
			
			// Zoom to region
			//zoom_to_region(target_reg_code, vec_region_polygon.getSource().getFeatures());
			//zoom_to_extent(map, region_ext[target_reg_code-1], 100);
			
			// Update chart data
			update_chart_data(chart_context, chart_container, []);
		}
		
		// ----------------------------------------------------------------
		// SELECT
		// ----------------------------------------------------------------
		/**
		 * On region selector changed.
		 */
		function on_ele_sel_region_change() {
			var i;
			var n;
			
			var reg_code = parseInt(ele_sel_region.value);
			var idx = reg_code - 1;
			
			if(idx < 0) { return; }
			
			var reg = regions_info.regions[idx];
			
			console.log('REGION:',reg_code,reg);
			
			ele_sel_area.options.length = 0;
			ele_sel_area.options[ele_sel_area.options.length] = new Option('เลือกพื้นที่', '-999');
			
			// Search areas that mathes selected region code
			for(i = 0; i < reg.length; i++) {
				ele_sel_area.options[ele_sel_area.options.length] = 
					new Option(
							reg[i].areas.AREA_TNAME, 
							reg[i].areas.AREA_CODE
				);
			}
			
			// Auto zoom
			if( (b_auto_zoom == true) && (region_ext.length > 0) ) {
				console.log('x');
				zoom_to_extent(map, region_ext[idx], 100);
			}
		}
		
		/**
		 * On area selector changed.
		 */
		function on_ele_sel_area_change() {
			var i;
			var r_code = ele_sel_region.value;
			var a_code = ele_sel_area.value;
			console.log("R_CODE:" + r_code + ", A_CODE:" + a_code);
			
			var f = vec_branch_point.getSource().getFeatures();
			var fi;
			var n_areas = f.length;
			
			//ele_sel_branch.options.length = 0;
			if(a_code !== '-999') {
				//var option = '<select class="form-control" id="filter-branch"><option>เลือกสาขา</option></select>';  
				//$('#filter-branch-div').hide().html(option).fadeIn();
				
				ele_sel_branch.options.length = 0;
				ele_sel_branch.options[ele_sel_branch.options.length] = new Option('เลือกสาขา', '-999');
				
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
			} else {
			}
		}
		
		/**
		 *
		 */
		function on_ele_sel_branch_change() {
		}
		
		// ----------------------------------------------------------------
		// OpenLayer's mouse functions.
		// ----------------------------------------------------------------
		/**
		 * Post-process data after user release mouse button.
		 */
		function process_mouse_events(b_show_popup) {
			var i;
			var f_info = get_feature_info(map, mouse_new_px, ['REG_CODE']);
			var n_feats = f_info.length;
			
			if( b_mouse_drag == true) { return; }
			if( b_show_popup == true) { return; }
							
			if( n_feats > 0 ) {
				if( selected_feature != null ) {
					selected_feature.setStyle(feature_style_old);
				}
				
				feature_style_old = f_info[0].getStyle();
				selected_feature = f_info[0];
				selected_feature.setStyle(feature_style_selected);
				
				//if( b_popup_shown ) {
					console.log(b_popup_shown, b_show_popup, b_mouse_down, n_feats);
					// Top-most layer only.
					var r_code = parseInt(selected_feature.get('REG_CODE'));
					var r_name = $('#filter-region option').eq(r_code).text();
					var r_year = $('#filter-year option:selected').text();
					
					// Show popup dialog
					var html_str = '<div class="header">รายละเอียด</div>';
					html_str += '<div class="content"><b>ภาค</b>: ' + r_name + '</div>';
					//html_str += '<div class="content"><b>ภาษี</b>: ' + odata[r_code-1].VAL + '</div>';
	
					//console.log(mouse_new_geo);
					//overlay.setPosition(mouse_new_geo);
					//show_popup_window(map, overlay, mouse_new_geo, html_str);
				//}
			} else {
				if( selected_feature != null ) {
					selected_feature.setStyle(feature_style_old);
				}
				selected_feature = null;
			}
		}
		
		// ----------------------------------------------------------------
		// OpenLayer's map popup functions.
		// ----------------------------------------------------------------
		/**
		 *
		 */
		function _show_popup_window() {
			/*ele_popup = document.getElementById("popup-content");

			var tab = '<ul class="tab">';
			tab += '<li><a href="#" class="tablinks">ข้อมูล1</a></li>';
			tab += '</ul>';

			var tabCont = '<div id="tabCont1" class="tabcontent">';
			tabCont += '<p>' + '<strong>ภาค</strong>: - ' + '</p>';
			tabCont += '<p>' + '<strong>ปี</strong>: - ' + '</p>';
			tabCont += '</div>';

			dvPopup.innerHTML = tab + tabCont;
			document.getElementsByClassName("tablinks")[0].className += " active";
			document.getElementById('tabCont1').style.display = "block";*/
		}
		
		// ----------------------------------------------------------------
		// JSChart functions.
		// ----------------------------------------------------------------
		/**
		 *
		 */
		function prepare_chart() {
			console.log('Preparing chart...');
			// ["ภาษี", "ปราบปราม", "ใบอนุญาต", "แสตมป์", "โรงงาน"]
			var tax_line_color = "rgba(255,80,80,0.4)";
			var tax_fill_color = "rgba(255,80,80,1.0)";
			var cs_line_color = "rgba(80,180,80,0.4)";
			var cs_fill_color = "rgba(80,180,80,1.0)";
			var li_line_color = "rgba(80,80,175,0.4)";
			var li_fill_color = "rgba(80,80,175,1.0)";
			var st_line_color = "rgba(255,180,75,0.4)";
			var st_fill_color = "rgba(255,180,75,1.0)";
			var fa_line_color = "rgba(255,75,200,0.4)";
			var fa_fill_color = "rgba(255,75,200,1.0)";
			var point_size = 3;
			
			chart_context = document.getElementById("my_chart");
			
			var chart_data = {
				labels: [1,2,3,4,5,6,7,8,9,10,11,12],
				datasets: [
					{
						data: [0,0,0,0,0,0,0,0,0,0,0,0],
						label: "ภาษี",
						fill: false,
						lineTension: 0,
						backgroundColor: tax_fill_color,
						borderColor: tax_line_color,
						borderCapStyle: 'butt',
						borderDash: [],
						borderDashOffset: 0.0,
						borderJoinStyle: 'miter',
						pointBorderColor: tax_line_color,
						pointBackgroundColor: "#fff",
						pointBorderWidth: 1,
						pointHoverRadius: 5,
						pointHoverBackgroundColor: tax_line_color,
						pointHoverBorderColor: tax_fill_color,
						pointHoverBorderWidth: 2,
						pointRadius: point_size,
						pointHitRadius: 10,
					},
					{
						data: [0,0,0,0,0,0,0,0,0,0,0,0],
						label: "ปราบปราม",
						fill: false,
						lineTension: 0,
						backgroundColor: cs_fill_color,
						borderColor: cs_line_color,
						borderCapStyle: 'butt',
						borderDash: [],
						borderDashOffset: 0.0,
						borderJoinStyle: 'miter',
						pointBorderColor: cs_line_color,
						pointBackgroundColor: "#fff",
						pointBorderWidth: 1,
						pointHoverRadius: 5,
						pointHoverBackgroundColor: cs_line_color,
						pointHoverBorderColor: cs_fill_color,
						pointHoverBorderWidth: 2,
						pointRadius: point_size,
						pointHitRadius: 10,
					},
					{
						data: [0,0,0,0,0,0,0,0,0,0,0,0],
						label: "ใบอนุญาต",
						fill: false,
						lineTension: 0,
						backgroundColor: li_fill_color,
						borderColor: li_line_color,
						borderCapStyle: 'butt',
						borderDash: [],
						borderDashOffset: 0.0,
						borderJoinStyle: 'miter',
						pointBorderColor: li_line_color,
						pointBackgroundColor: "#fff",
						pointBorderWidth: 1,
						pointHoverRadius: 5,
						pointHoverBackgroundColor: li_line_color,
						pointHoverBorderColor: li_fill_color,
						pointHoverBorderWidth: 2,
						pointRadius: point_size,
						pointHitRadius: 10,
					},
					{
						data: [0,0,0,0,0,0,0,0,0,0,0,0],
						label: "แสตมป์",
						fill: false,
						lineTension: 0,
						backgroundColor: st_fill_color,
						borderColor: st_line_color,
						borderCapStyle: 'butt',
						borderDash: [],
						borderDashOffset: 0.0,
						borderJoinStyle: 'miter',
						pointBorderColor: st_line_color,
						pointBackgroundColor: "#fff",
						pointBorderWidth: 1,
						pointHoverRadius: 5,
						pointHoverBackgroundColor: st_line_color,
						pointHoverBorderColor: st_fill_color,
						pointHoverBorderWidth: 2,
						pointRadius: point_size,
						pointHitRadius: 10,
					},
					{
						data: [0,0,0,0,0,0,0,0,0,0,0,0],
						label: "โรงงาน",
						fill: false,
						lineTension: 0,
						backgroundColor: fa_fill_color,
						borderColor: fa_line_color,
						borderCapStyle: 'butt',
						borderDash: [],
						borderDashOffset: 0.0,
						borderJoinStyle: 'miter',
						pointBorderColor: fa_line_color,
						pointBackgroundColor: "#fff",
						pointBorderWidth: 1,
						pointHoverRadius: 5,
						pointHoverBackgroundColor: fa_line_color,
						pointHoverBorderColor: fa_fill_color,
						pointHoverBorderWidth: 2,
						pointRadius: point_size,
						pointHitRadius: 10,
					}
				]
			};

			chart_container = new Chart(chart_context, {
				type: 'line',
				data: chart_data,
				options: {
					animation: {animateScale: true},
					responsive: true,
					
				}
			});
		}
		/**
		 * Update chart data.
		 */
		function update_chart_data(ctx, ctn, data) {
			var i;
			var val;
			
			// Set and update chart data
			/*for(i = 0; i < data.length; i++) {
				ctn.data.datasets[0].data[i] = data[i].VAL;
			}*/
			
			for( k = 0; k < 5; k++ ) {
				val =  Math.floor(Math.random() * (20 - 0)) + 0;
				for(i = 0; i < 12; i++) {
					ctn.data.datasets[k].data[i] = val * Math.floor(Math.random() * (100 - 0)) + 0;
				}
			}
			ctn.update();
		}
		
		// ----------------------------------------------------------------
		// THEMATIC MAP SECTION
		// ----------------------------------------------------------------
		/**
		 * @param vf 		Vector features
		 * @param classes	Class information (i.e., region code, color index)
		 */
		function show_thematic_map(vf, classes) {
			// THEMATIC MAP
			var min =  Number.MAX_SAFE_INTEGER;
			var max = -Number.MAX_SAFE_INTEGER;
			var val;
			
			// DUMMY DATA
			classes.length = 0;
			for( i = 0; i < vf.length; i++ ) {
				classes.push({
						REG:i, 
						VAL:Math.random()*100, 
						COLOR_INDEX:-1});
				//{REG:'01', VAL:0.00, COLOR_INDEX:-1}
			}

			// Find min and max
			for( i = 0; i < classes.length; i++ ) {
				val = classes[i].VAL;
				if(val < min) { min = val; }
				if(val > max) { max = val; }
			}
			//console.log(min, max);
			
			// Calcualte thresholds
			var step = (max - min)/n_classes;
			val = min;
			thresholds.length = 0;
			thresholds.push(val);
			for( i = 0; i < n_classes; i++ ) {
				val += step;
				thresholds.push(val);
			}
			console.log(thresholds);
			console.log(classes[0]);
			//return;
			
			// Create lookup table
			var values = [];
			var idx_list = [];
			var t1;
			var t2;
			var c;
			for( i = 0; i < classes.length; i++ ) {
				vi = classes[i].VAL;
				ri = parseInt(classes[i].REG) - 1;
				
				c = -1;
				for( j = 0; j < (thresholds.length-1); j++ ) {
					t1 = thresholds[j];
					t2 = thresholds[j+1];
					
					// Normal value
					if((vi >= t1) && (vi <= t2)) {
						c = j + 1;
						break;
					}
					
					// Bigest value
					if( vi >= thresholds[thresholds.length-1]) {
						c = thresholds.length - 1;
						break;
					}
					
					// No data value
					if( vi < 0.0) {
						c = 0;
						break;
					}
				}
				classes[i].COLOR_INDEX = c;
				//console.log(i, classes[i].REG, classes[i].VAL, c, classes[i].COLOR_INDEX);
			}
			
			// Set default style.
			/*for( i = 0; i < vf.length; i++ ) {
				vf[i].setStyle(polygon_styles[0]);
			}*/
			//showDefaultStyleMap(vf);
			
			// Apply styles
			for( i = 0; i < vf.length; i++ ) {
				vi = vf[i];
				vi.setStyle(default_polygon_thematic_style[classes[i].COLOR_INDEX]);
			}
			/*for( i = 0; i < vf.length; i++ ) {
				// Get feature and its region code
				vi = vf[i];
				if(!vi.get('REG_CODE')) { continue; }
				
				idx = parseInt(vi.get('REG_CODE'));
				vi.setStyle(polygon_styles[classes[idx-1].COLOR_INDEX]);
			}*/
		}
    });
</script>
<?php require('footer.php'); ?>    