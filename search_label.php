<?php require('header.php'); ?>
<?php require('nav.php'); ?>
<!--SECTION-->
<div class="section" style="margin-top: 10px;">
    <div class="col-md-12">
        <div class="row">
            <!--DATA-->
            <div class="col-md-4">
                <div class="panel panel-default" style="height: 55vh; border-radius: 0;">
                    <div class="panel-body" style="padding: 0;">
                        <table class="table table-striped search-label-detail-table" style="margin-top: 0; margin-bottom: 0;">
                            <tbody>
                                <tr>
                                    <td class="col-md-12" style="padding: 10px !important;">
                                        <input class="form-control input-sm" id="BrandName" placeholder="ค้นหาชื่อยี่ห้อ">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-md-12 text-center" style="height: 47vh; padding: 10px !important; border: 0;">
                                        <img src="" id="BrandImage" style="width: 80%; height: 40vh;">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!--MAP-->
            <div class="col-md-8 get-map">
                <div class="panel panel-default" style="height: 55vh; border-radius: 0; padding: 0;">
                    <div class="panel-body" style="padding-top: 0; padding-bottom: 0;">
                        <div class="row">
                            <div id="map" class="map" style="width: 100%; height: 55vh;"></div>
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
            <div class="panel panel-default" style="height: 18vh;">
                <div class="panel-body" style="padding: 0;">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered search-table" style="margin: 0 auto; overflow-y: hidden;"> 
                            <thead>
                                <tr>
                                    <th class="text-center text-nowrap">ชื่อสถานประกอบการโรงงาน</th>
                                    <th class="text-center text-nowrap">รหัสทะเบียนโรงงาน</th>
                                    <th class="text-center text-nowrap">ชื่อผู้ขออนุญาตผลิต</th>
                                    <th class="text-center text-nowrap">เลขที่ใบอนุญาตผลิต</th>
                                    <th class="text-center text-nowrap">ยี่ห้อที่ผลิต</th>
                                    <th class="text-center text-nowrap">ดีกรี</th>
                                    <th class="text-center text-nowrap">ประเภท</th>
                                    <th class="text-center text-nowrap">วันที่อนุญาต</th>
                                    <th class="text-center text-nowrap">วันที่ต่อใบอนุญาต</th>
                                    <th class="text-center text-nowrap">สถานที่ตั้ง</th>
                                    <th class="text-center text-nowrap">รูปผังโรงงาน</th>
                                    <th class="text-center text-nowrap">ผลตรวจ</th>
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
<!--JS-->  
<script type="text/javascript">
    $(document).ready(function(e) {
        //--Variable
        var factory = new Factory();
        var ajaxUrl = 'http://210.4.143.51/Surathai01/API/labelAPI.php';
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
                    serverType: 'geoserver', crossOrigin: 'anonymous', noWrap: true,  wrapX: false
                }),  
                extent: [ -20037508.34, -20037508.34, 20037508.34, 20037508.34 ]
            });

            var projection = ol.proj.get('EPSG:3857');

            marker_geom = new ol.geom.Point([0, 0]);
			marker_feature = new ol.Feature({geometry: marker_geom});
			marker_style = new ol.style.Style({
				image: new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
					anchor: [0.5, 16],
					anchorXUnits: 'fraction',
					anchorYUnits: 'pixels',
					opacity: 0.7,
					src: 'img/marker-search.png'
				}))
			});
			marker_feature.setStyle(marker_style);
			marker_source = new ol.source.Vector({
				features: [marker_feature]
			});
			layers_marker = new ol.layer.Vector({
				source: marker_source
			});

            map = new ol.Map({
                layers : [ layers_deemap, layers_marker ],
                //overlays: [overlay],//for popup
                target : 'map',
                view: new ol.View({
                center: [13.0, 100.5],
                projection: projection,
                zoom: 6
                })
            });
			
			// ==========================================================
			// ADDED BY KUMPEE - 2017-06-04
			// ==========================================================
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
			// ==========================================================

            $('#dvloading').hide().fadeOut();

            /* Zoom Slider */ 
            zoomslider = new ol.control.ZoomSlider();
            map.addControl(zoomslider);

            map.getView().setCenter(ol.proj.transform([103.697123, 13.231792], 'EPSG:4326', 'EPSG:3857'));
            map.getView().setZoom(4.5);
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
                        data = data[0];
                        console.log(data)

                        if(data.length != 0) {
                            $('.search-table tbody tr').remove();
                            $('#BrandImage').attr('src', ((data.picture != 'data/label/') ? data.picture : 'img/noimages.png'));
                            $('.search-table tbody').append('<tr data-lat="'+ data.lat +'" data-lon="'+ data.long +'">' + 
                                    '<td class="text-center text-nowrap">'+ data.factory_name +'</td>' +
                                    '<td class="text-center text-nowrap">'+ data.factory_code +'</td>' +
                                    '<td class="text-center text-nowrap">'+ data.contact +'</td>' +
                                    '<td class="text-center text-nowrap">'+ data.license +'</td>' +
                                    '<td class="text-center text-nowrap">'+ data.brand +'</td>' +
                                    '<td class="text-center text-nowrap">'+ data.degree +'</td>' +
                                    '<td class="text-center text-nowrap">'+ data.type +'</td>' +
                                    '<td class="text-center text-nowrap">'+ data.issue_date +'</td>' +
                                    '<td class="text-center text-nowrap">'+ data.extend_date +'</td>' +
                                    '<td class="text-center text-nowrap">'+ data.address +'</td>' +
                                    '<td class="text-center text-nowrap"><a href="#" title="คลิกเพื่อดูรูป" class="show-image"><img src="'+ ((data.plan != 'data/factoryplan/') ? data.plan : 'img/noimages.png') +'" style="width: 50px; height: 50px;"></a></td>' +
                                    '<td class="text-center text-nowrap"><a href="#" title="คลิกเพื่อดูรูป" class="show-image"><img src="'+ ((data.picture != 'data/label/') ? data.picture : 'img/noimages.png') +'" style="width: 50px; height: 50px;"></a></td>' +
                                '</tr>');
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

            $(this).closest('tbody').find('tr').removeClass('active-row');
            $(this).addClass('active-row');

            lat = parseFloat($(this).attr('data-lat')) || 0;
            lon = parseFloat($(this).attr('data-lon')) || 0;
            
            if((lat != 0) && (lon != 0))
                e_set_factory_location(ol, map, lat, lon, marker_geom, 18, true);
            else {
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