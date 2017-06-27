<?php require('header.php'); ?>
<?php require('nav.php'); ?>
<!--SECTION-->
<div class="section" style="margin-top: 10px;">
    <div class="col-md-12">
        <div class="row">
            <!--DATA-->
            <div class="col-md-4">
                <div class="panel panel-default" style="height: 54vh; border-radius: 0;">
                    <div class="panel-body" style="padding: 0;">
                        <table class="table table-striped search-label-detail-table" style="margin-top: 0; margin-bottom: 0;">
                            <tbody>
                                <tr>
                                    <td class="col-md-12" style="padding: 10px !important;">
                                        <input class="form-control input-sm" id="BrandName" placeholder="ค้นหาชื่อยี่ห้อ">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-md-12 text-center" style="height: 43vh; padding: 10px !important; border: 0;">
                                        <img src="" id="BrandImage" style="width: 100%; height: 43vh;">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!--MAP-->
            <div class="col-md-8 get-map">
                <div class="panel panel-default" style="height: 54vh; border-radius: 0; padding: 0;">
                    <div class="panel-body" style="padding-top: 0; padding-bottom: 0;">
                        <div class="row">
                            <div id="map" class="map" style="width: 100%; height: 54vh;"></div>
                                <div id="label-popup"></div>
                                <div id="popup" class="ol-popup">
                                    <a href="#" id="popup-closer" class="ol-popup-closer"></a>
                                    <div id="popup-content"></div>
                                </div>
                            </div>
                            <div id="dvloading" class="loader"><div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--TABLE DATA-->
    <div class="col-md-12" style="margin-top: 10px;">
        <div class="row">
            <div class="panel panel-default" style="height: 22vh;">
                <div class="panel-body" style="padding: 0;">
                    <div class="table-responsive" style="height: 22vh;">
                        <table class="table table-striped table-bordered search-table bg-info" style="margin: 0 auto; overflow-y: scroll;"> 
                            <thead>
                                <tr>
                                    <th class="text-center text-nowrap bg-primary">ชื่อสถานประกอบการโรงงาน</th>
                                    <th class="text-center text-nowrap bg-primary">รหัสทะเบียนโรงงาน</th>
                                    <th class="text-center text-nowrap bg-primary">ชื่อผู้ขออนุญาตผลิต</th>
                                    <th class="text-center text-nowrap bg-primary">เลขที่ใบอนุญาตผลิต</th>
                                    <th class="text-center text-nowrap bg-primary">ยี่ห้อที่ผลิต</th>
                                    <th class="text-center text-nowrap bg-primary">ดีกรี</th>
                                    <th class="text-center text-nowrap bg-primary">ประเภท</th>
                                    <th class="text-center text-nowrap bg-primary">วันที่อนุญาต</th>
                                    <th class="text-center text-nowrap bg-primary">วันที่ต่อใบอนุญาต</th>
                                    <th class="text-center text-nowrap bg-primary">สถานที่ตั้ง</th>
                                    <th class="text-center text-nowrap bg-primary">รูปผังโรงงาน</th>
                                    <th class="text-center text-nowrap bg-primary">ผลตรวจ</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--STYLE-->
<style>
    .pan {
        top: 70px;
        left: 0.5em;
    }
    .ol-touch .pan {
        top: 80px;
    }

    .zoom-box {
        top: 100px;
        left: 0.5em;
    }
    .ol-touch .zoom-box {
        top: 110px;
    }

    .defaultZoom {
        top: 130px;
        left: 0.5em;
    }
    .ol-touch .defaultZoom {
        top: 140px;
    }
</style>
<!--JS-->  
<script type="text/javascript">
    $(document).ready(function(e) {
        //--Variable
        var factory = new Factory();
        var ajaxUrl = 'API/labelAPI.php';
        var params = {};
        var lat = 0;
        var lon = 0;
        var marker_geom = null;
        var marker_feature = null;
        var marker_style = null;
        var marker_source = null;
        var layers_marker = null;

        //--Page load
        getInit();

        //--Function
        function getInit() {
            $('#BrandName').val('');
            $('#BrandImage').attr('src', 'img/noimages.png');

            getMap();
        }

        function getMap() {
            var layers_deemap =  new ol.layer.Tile({ 
                source: new ol.source.TileWMS( {
                    url: 'http://www.dee-map.com/geoserver/gwc/service/wms/dmwms',
                    params: { 'LAYERS': 'Dee-Map', 'VERSION': '1.1.1', 'FORMAT': 'image/png8' },
                    //serverType: 'geoserver', 
                    crossOrigin: 'anonymous', noWrap: true,  wrapX: false
                }),  
                extent: [ -20037508.34, -20037508.34, 20037508.34, 20037508.34 ]
            });

            var projection = ol.proj.get('EPSG:3857');
            marker_geom = new ol.geom.Point([0, 0]);
			marker_feature = new ol.Feature({geometry: marker_geom});
			marker_source = new ol.source.Vector({
				features: [marker_feature]
			});
			layers_marker = new ol.layer.Vector({
				source: marker_source
			});

            window.app = {};
            var app = window.app;
            var dragBox;

            app.pan = function(opt_options) {
                var options = opt_options || {};
                var button = document.createElement('button');
                button.innerHTML = '<i class="fa fa-hand-paper-o"></i>';

                var self = this;
                var handlePan = function(e) {
                    //--Active Btn
                    //--Remove BG-BTN-Color
                    $('.zoom-box button').attr("style","background-color: rgba(0,60,136,.5);");
                    //--Fill BG-BTN-Color
                    $('.pan button').attr("style","background-color: rgba(0,60,136,.9);");

                    map.removeInteraction(dragBox);
                };

                button.addEventListener('click', handlePan, false);
                button.addEventListener('touchstart', handlePan, false);

                var element = document.createElement('div');
                element.className = 'pan ol-unselectable ol-control';
                element.title = 'Pan';
                element.appendChild(button);

                ol.control.Control.call(this, {
                    element: element,
                    target: options.target
                });
            };
            app.zoomBox = function(opt_options) {
                var options = opt_options || {};
                var button = document.createElement('button');
                button.innerHTML = '<i class="fa fa-search-plus"></i>';

                var handleZoomBox = function(e) {
                    //--Active Btn
                    //--Remove BG-BTN-Color
                    $('.pan button').attr("style","background-color: rgba(0,60,136,.5);");
                    //--Fill BG-BTN-Color
                    $('.zoom-box button').attr("style","background-color: rgba(0,60,136,.9);");

                    var select = new ol.interaction.Select();
                    map.addInteraction(select);

                    var selectedFeatures = select.getFeatures();
                    dragBox = new ol.interaction.DragBox({
                        condition: ol.events.condition.mouseOnly
                    });
                    map.addInteraction(dragBox);

                    dragBox.on('boxend', function() {
                        var extent = dragBox.getGeometry().getExtent();
                        map.getView().fit(extent, map.getSize());
                    });
                    
                    dragBox.on('boxstart', function() {
                        selectedFeatures.clear();
                    });
                };

                button.addEventListener('click', handleZoomBox, false);
                button.addEventListener('touchstart', handleZoomBox, false);

                var element = document.createElement('div');
                element.className = 'zoom-box ol-unselectable ol-control';
                element.title = 'Zoom Box';
                element.appendChild(button);

                ol.control.Control.call(this, {
                    element: element,
                    target: options.target
                });
            };
            app.defaultZoom = function(opt_options) {
                var options = opt_options || {};
                var defaultZoomBtn = document.createElement('button');

                defaultZoomBtn.innerHTML = '<i class="fa fa-globe" aria-hidden="true"></i>';

                var handledefaultZoom = function(e) {
                    map.getView().setCenter(ol.proj.transform([103.697123, 13.231792], 'EPSG:4326', 'EPSG:3857'));
                    map.getView().setZoom(4.5);
                };
                
                defaultZoomBtn.addEventListener('click', handledefaultZoom, false);

                var element = document.createElement('div');
                element.className = 'defaultZoom ol-unselectable ol-control';
                element.title = 'Zoom Full';
                element.appendChild(defaultZoomBtn);

                ol.control.Control.call(this, {
                    element: element,
                    target: options.target
                });

            };
            ol.inherits(app.pan, ol.control.Control);
            ol.inherits(app.zoomBox, ol.control.Control);
            ol.inherits(app.defaultZoom, ol.control.Control);

            map = new ol.Map({
                controls: ol.control.defaults({
                    attributionOptions: ({
                        collapsible: false
                    })
                }).extend([
                    new app.pan(),
                    new app.zoomBox(),
                    new app.defaultZoom()
                ]),
                layers : [ layers_deemap, layers_marker ],
                //overlays: [overlay],//for popup
                target : 'map',
                view: new ol.View({
                    center: [13.0, 100.5],
                    projection: projection,
                    zoom: 6
                })
            });
            
			getJSON(
				'data/geojson/factory_2126_point.geojson',
				function(data) {
					var v = create_vector_layer(data, 
												'EPSG:3857', 
												search_point_style_function);
					map.addLayer(v);
				}, 
				function(xhr) {
				}
			);

            $('#dvloading').hide().fadeOut();

            map.getView().setCenter(ol.proj.transform([103.697123, 13.231792], 'EPSG:4326', 'EPSG:3857'));
            map.getView().setZoom(4.5);

            var target = map.getTarget();
            var jTarget = typeof target === 'string' ? $("#" + target) : $(target);

            var element = document.getElementById('label-popup');
            var popup = new ol.Overlay({
                element: element,
                positioning: 'bottom-center',
                stopEvent: false
            });
            map.addOverlay(popup);
            
            $(map.getViewport()).on('mousemove', function(e) {
                var view = map.getView();
                var resolution = view.getResolution();

                if(resolution < 100) {
                    var pixel = map.getEventPixel(e.originalEvent);
                    var hit = map.forEachFeatureAtPixel(pixel, function(feature, layer) {
                        if(feature) {
                            var geometry = feature.getGeometry();
                            var coord = geometry.getCoordinates();
                            popup.setPosition(coord);
                        }
                        
                        return feature;
                    });
                    
                    if(hit) {
                        if(hit.get('FACTORY_TNAME') != undefined) {
                            jTarget.css('cursor', 'pointer');

                            $(element).popover({
                                placement: 'top',
                                html: true,
                                content: '<h4 style="width: 200px; color: #333333; margin: 0; font-weight: normal; text-align: center;">' + hit.get('FACTORY_TNAME') +'</h4>'
                            });
                            $(element).popover('show');
                        }
                    } else {
                        jTarget.css('cursor', '');
                        $(element).popover('destroy');
                    }
                }
            });
        }

        //--Event
        $('#BrandName').autocomplete({ 
            source: function(req, res) {
                params = {
                    fn: 'autocomplete', 
                    label: req.term || ''
                };

                $.post(ajaxUrl, params, res, 'json');
            },
            minLength: 1,
            select: function(e, ui) { 
                e.preventDefault();
                
                $(this).val(ui.item.value);

                params = {
                    fn: 'getdata',
                    label: ui.item.value || ''
                };

                factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                    if(res != undefined){
                        var data = JSON.parse(res);

                        if(data.length != 0) {
                            $('.search-table tbody tr').remove();
                            $('#BrandImage').attr('src', ((data[0].picture != 'data/label/') ? data[0].picture : 'img/noimages.png'));

                            var tbodyContent = '';
                            $.each(data, function(index, item) {
                                tbodyContent += '<tr data-picture="'+ item.picture +'" data-lat="'+ item.lat +'" data-lon="'+ item.long +'">' + 
                                        '<td class="text-center text-nowrap">'+ item.factory_name +'</td>' +
                                        '<td class="text-center text-nowrap">'+ item.factory_code +'</td>' +
                                        '<td class="text-center text-nowrap">'+ item.contact +'</td>' +
                                        '<td class="text-center text-nowrap">'+ item.license +'</td>' +
                                        '<td class="text-center text-nowrap">'+ item.brand +'</td>' +
                                        '<td class="text-center text-nowrap">'+ item.degree +'</td>' +
                                        '<td class="text-center text-nowrap">'+ item.type +'</td>' +
                                        '<td class="text-center text-nowrap">'+ item.issue_date +'</td>' +
                                        '<td class="text-center text-nowrap">'+ item.extend_date +'</td>' +
                                        '<td class="text-center text-nowrap">'+ item.address +'</td>' +
                                        '<td class="text-center text-nowrap"><a href="#" title="คลิกเพื่อดูรูป" class="show-image"><img src="'+ ((item.plan != '') ? item.plan : 'img/noimages.png') +'" style="width: 50px; height: 50px;"></a></td>' +
                                        '<td class="text-center text-nowrap"><a href="#" title="คลิกเพื่อดูรูป" class="show-image"><img src="img/noimages.png" style="width: 50px; height: 50px;"></a></td>' +
                                    '</tr>';
                            });
                            $('.search-table tbody').append(tbodyContent);
                        }
                    }
                });
            }
        });

        $(document).on('keyup', '#BrandName', function(e) {
            e.preventDefault();
            
            if($(this).val() == '') {
                $('#BrandImage').attr('src', 'img/noimages.png');
                $('.search-table tbody tr').remove();
                
                map.getView().setCenter(ol.proj.transform([103.697123, 13.231792], 'EPSG:4326', 'EPSG:3857'));
                map.getView().setZoom(4.5);
                marker_source.removeFeature(marker_feature);
            }
        });

        $(document).on('click', '.show-image', function(e) {
            e.preventDefault();
            
            Factory.prototype.utilityService.getPopup({
                titleMsg: '<i class="fa fa-exclamation-circle text-right-indent"></i> รูปผังโรงงาน',
                infoMsg: '<img src="'+ $(this).find('img').attr('src') +'" style="width: 100%;">',
                btnMsg: 'ปิด'
            });
        });

        $(document).on('click', '.search-table tbody tr', function(e) {
            e.preventDefault();

            $('#BrandImage').attr('src', (($(this).attr('data-picture') != 'data/label/') ? $(this).attr('data-picture') : 'img/noimages.png'));

            $(this).closest('tbody').find('tr').removeClass('active-row');
            $(this).addClass('active-row');

            lat = parseFloat($(this).attr('data-lat')) || 0;
            lon = parseFloat($(this).attr('data-lon')) || 0;
            
            if((lat != 0) && (lon != 0)) {
                e_set_factory_location(ol, map, lat, lon, marker_geom, 15, true);

                marker_feature = new ol.Feature({geometry: marker_geom});
                marker_source = new ol.source.Vector({
                    features: [marker_feature]
                });
                layers_marker = new ol.layer.Vector({
                    source: marker_source
                });
                marker_style = new ol.style.Style({
                    image: new ol.style.Icon(({
                        opacity: 1,
                        scale: 1,
                        src: 'img/marker-search.png'
                    }))
                });
                marker_feature.setStyle(marker_style);
                map.getLayers().setAt(3, layers_marker);
            } else {
                Factory.prototype.utilityService.getPopup({
                    infoMsg: 'ไม่พบค่าพิกัดที่ตั้ง',
                    btnMsg: 'ปิด'
                });
            }
        });
    });
</script>
<?php require('popup.php'); ?>
<?php require('footer.php'); ?>