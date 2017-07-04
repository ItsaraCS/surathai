<?php require('header.php'); ?>
<?php require('nav.php'); ?>
<!--SECTION-->
<div class="section" style="margin-top: 10px;">
    <!--FORM DATA-->
    <div class="col-md-4">
        <h3 class="report-mobile">ฟอร์มบันทึกข้อมูล</h3>
        <form name="insertForm" enctype="multipart/form-data" novalidate>
            <div class="panel panel-default panel-panel-default-eForm">
                <div class="panel-body" style="padding: 0;">
                    <table class="table table-striped" style="margin-top: 0; margin-bottom: 0;">
                        <tbody>
                            <tr>
                                <td class="col-md-12" colspan="2" style="padding: 10px !important;">
                                    <input type="text" class="form-control input-sm" id="FactoryName" 
                                        data-factory-id="0"
                                        placeholder="ค้นหาชื่อโรงงาน">
                                    <span class="error-content hide" data-label="ชื่อโรงงาน"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">รหัสโรงงาน</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="ID" maxlength="6" required>
                                    <span class="error-content hide" data-label="รหัสโรงงาน"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">ประเภทสหกรณ์</p></td>
                                <td class="col-md-7">
                                    <select class="form-control input-sm" id="SuraType" required>
                                        <option value="">เลือกประเภทสหกรณ์</option>
                                        <option value="1">สุรากลั่น</option>
                                        <option value="2">สุราแช่</option>
                                        <option value="3">สุรากลั่น และสุราแช่</option>
                                    </select>
                                    <span class="error-content hide" data-label="ประเภทสหกรณ์"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">เลขทะเบียนสรรพสามิต</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="RegistNo" required>
                                    <span class="error-content hide" data-label="เลขทะเบียนสรรพสามิต"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">ชื่อผู้จัดการ</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="ContactName" required>
                                    <span class="error-content hide" data-label="ชื่อผู้จัดการ"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">ทุนการผลิต</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="PCapital" required numbered>
                                    <span class="error-content hide" data-label="ทุนการผลิต"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">แรงม้า</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="HPower" required numbered>
                                    <span class="error-content hide" data-label="แรงม้า"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">จำนวนคนงาน</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="Worker" required numbered>
                                    <span class="error-content hide" data-label="จำนวนคนงาน"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">สถานที่ตั้ง</p></td>
                                <td class="col-md-7">
                                    <textarea class="form-control input-sm" id="Address" required></textarea>
                                    <span class="error-content hide" data-label="สถานที่ตั้ง"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">พิกัดที่ตั้ง (ละติจูด)</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="Lat" required>
                                    <span class="error-content hide" data-label="ละติจูด"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">พิกัดที่ตั้ง (ลองจิจูด)</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="Lon" required>
                                    <span class="error-content hide" data-label="ลองจิจูด"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">แผนผัง</p></td>
                                <td class="col-md-7">
                                    <div class="col-md-12">
                                        <div class="row thumbnail thumbnail-upload" style="margin-bottom: 5px; height: 100px;"></div>
                                    </div>
                                    <div class="col-md-12 text-right">
                                        <div class="row">
                                            <span id="uploadBtn" class="btn btn-success">เลือกรูปภาพ
                                                <input type="file" id="Plan" name="plan" accept="image/jpeg, image/png" required>
                                            </span>
                                        </div>
                                    </div>
                                    <span class="error-content hide" data-label="แผนผัง"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">เลขที่ใบอนุญาต (ก่อสร้าง)</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="LicenseNo" required>
                                    <span class="error-content hide" data-label="เลขที่ใบอนุญาต (ก่อสร้าง)"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-default" style="border-radius: 0;">
                <div class="panel-body text-right">
                    <button type="submit" class="btn btn-submit btn-sm" id="insertBtn">บันทึก</button>
                    <button type="submit" class="btn btn-warning btn-sm hide" id="updateBtn">แก้ไข</button>
                    <button type="reset" class="btn btn-sm btn-danger" id="resetBtn">ยกเลิก</button>
                </div>
            </div>
        </form>
    </div>

    <!--MAP AND TABLE DATA-->
    <div class="col-md-8">
        <h3 class="report-mobile">แผนที่</h3>
        <div class="panel panel-default" style="height: 51vh; border-radius: 0; padding: 0;">
            <div class="panel-body" style="padding-top: 0; padding-bottom: 0;">
                <div class="row">
                    <div id="map" class="map map-eForm" style="width: 100%; height: 51vh;"></div>
                        <div id="popup" class="ol-popup">
                            <a href="#" id="popup-closer" class="ol-popup-closer"></a>
                            <div id="popup-content"></div>
                        </div>
                    </div>
                    <div id="dvloading" class="loader"><div>
                </div>
            </div>
        </div>
        <h3 class="report-mobile">ตารางแสดงข้อมูลบันทึกล่าสุด</h3>
        <div class="panel panel-default" style="height: 25vh; border-radius: 0; padding: 0;">
            <div class="panel-body" style="padding: 0;">
                <div class="table-responsive" style="height: 25vh; overflow-y: hidden;">
                    <table class="table table-striped table-bordered eform-table bg-info" style="margin-top: 0;"> 
                        <thead><tr></tr></thead>
                        <tbody></tbody>
                    </table>
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
        var ajaxUrl = 'API/eformAPI.php';
        var params = {};
        var lat = $('#Lat').val() || 0;
        var lon = $('#Lon').val() || 0;
        var marker_geom = null;
        var marker_feature = null;
        var marker_style = null;
        var marker_source = null;
        var layers_marker = null;

        //--Page load
        setInit();

        //--Function
        function setInit() {
            $('input, select, textarea').val('');

            getMap();
            getTable();
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
                    map.getView().setCenter(ol.proj.transform([99.697123, 17.231792], 'EPSG:4326', 'EPSG:3857'));
                    map.getView().setZoom(9);
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

            $('#dvloading').hide().fadeOut();

            map.getView().setCenter(ol.proj.transform([99.697123, 17.231792], 'EPSG:4326', 'EPSG:3857'));
            map.getView().setZoom(9);

            map.on('singleclick', function(event) {
				var lonlat = e_get_factory_location(ol, map, event, marker_geom, 9, false);
				//console.log('lat:', lonlat[1], 'lon:', lonlat[0]);
                
                $('#Lat').val(lonlat[1]);
                $('#Lon').val(lonlat[0]);

                marker_style = new ol.style.Style({
                    image: new ol.style.Icon(({
                        anchor: [0.5, 46],
                        anchorXUnits: 'fraction',
					    anchorYUnits: 'pixels',
                        opacity: 0.8,
                        scale: 1,
                        src: 'img/marker-eform.png'
                    }))
                });
                marker_feature.setStyle(marker_style);
			});

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

        function getTable() {
            $('.eform-table thead th, ' +
                '.eform-table tbody tr').remove();

            params = {
                fn: 'gettable',
                job: 1
            };

            factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                if(res != undefined){
                    var data = JSON.parse(res);

                    var theadContent = '<th class="text-center text-nowrap bg-primary" style="padding: 4px 10px;">#</th>';
                    $.each(data.label, function(index, item) {
                        theadContent += '<th class="text-center text-nowrap bg-primary" style="padding: 4px 10px;">'+ item +'</th>';
                    });
                    $('.eform-table thead tr').append(theadContent);

                    if(data.data.length != 0) {
                        var row = (data.data.length / data.label.length);
                        var tbodyContent = '';
                        var alignContent = 0;
                        var index = 0;

                        for(var i=1; i<=row; i++) {
                            if(data.latlong.length != 0)
                                tbodyContent = '<tr data-id="'+ data.data[index].id +'" data-lat="'+ data.latlong[i].Lat +'" data-lon="'+ data.latlong[i].Long +'">';
                            else
                                tbodyContent = '<tr data-id="'+ data.data[index].id +'" data-lat="0" data-lon="0">';

                            tbodyContent += '<td class="text-center">'+ i +'</td>';

                            for(var j=1; j<=data.label.length; j++) {
                                tdAlign = ({
                                    '0': 'text-left',
                                    '1': 'text-right',
                                    '2': 'text-center',
                                    '3': 'text-center',
                                    '4': 'text-center'
                                })[data.data[index].align];
                                
                                if(data.data[index].align == 3)
                                    tbodyContent += '<td class="'+ tdAlign +' text-nowrap"><a href="#" title="คลิกเพื่อดูรูป" class="show-image"><img src="'+ data.data[index].text +'" style="width: 50px; height: 50px;"></a></td>';
                                else if(data.data[index].align == 4)
                                    tbodyContent += '<td class="'+ tdAlign +' text-nowrap"><a href="'+ data.data[index].text +'" class="show-link">ดูเพิ่มเติม</a></td>';
                                else
                                    tbodyContent += '<td class="'+ tdAlign +' text-nowrap">'+ data.data[index].text +'</td>';

                                index += 1;
                            }

                            tbodyContent += '</tr>';
                            $('.eform-table tbody').append(tbodyContent);
                        }
                    } else 
                        $('.eform-table tbody').append('<tr><td colspan="'+ data.label.length +'" style="text-align: center;">ไม่พบข้อมูล</td></tr>');
                }
            });
        }

        //--Event
        $(document).on('keyup', 'input[required], textarea[required]', function(e) {
            factory.initService.setError($(this), 'required'); 
        });

        $(document).on('change', 'select[required]', function(e) {
            factory.initService.setError($(this), 'required'); 
        });

        $(document).on('keyup', 'input[numbered], textarea[numbered]', function(e) {
            factory.initService.setError($(this), 'numbered');
        });

        $(document).on('change', '#Plan', function(e) {
            if(typeof (FileReader) != 'undefined') {
	            var regexp = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.png)$/;
	            var imgContent = '';
                var fakePath = '';

                $('.thumbnail-upload img').remove();
                factory.initService.setError($('#Plan'), 'clear');
	            
	            $.each($(this)[0].files, function(index, item) {
	                var file = $(this);

	                if(regexp.test(file[0].name.toLowerCase())) {
	                	if(file[0].size <= 500000) {
		                    var reader = new FileReader();

		                    reader.onload = function(e) {
		                    	imgContent = '<img src="'+ e.target.result +'" style="height: 100%;">';

								$('.thumbnail-upload').append(imgContent);
		                    }

		                    reader.readAsDataURL(file[0]);
	                	} else{
                            factory.initService.setError($('#Plan'), 'image-size');
                            $('#Plan').val('');
                        }
	                } else {
                        factory.initService.setError($('#Plan'), 'image-type');
                        $('#Plan').val('');
                    }
	            });
	        }
        });

        $(document).on('keyup', '#Lat, #Lon', function(e) {
            e.preventDefault();

            if(($('#Lat').val() != '') && ($('#Lon').val() != '')) {
                lat = parseFloat($('#Lat').val()) || 0;
                lon = parseFloat($('#Lon').val()) || 0;

                e_set_factory_location(ol, map, lat, lon, marker_geom, 9, true);

                marker_style = new ol.style.Style({
                    image: new ol.style.Icon(({
                        opacity: 0.8,
                        scale: 1,
                        src: 'img/marker-eform.png'
                    }))
                });
                marker_feature.setStyle(marker_style);
            } else {
                map.getView().setCenter(ol.proj.transform([99.697123, 17.231792], 'EPSG:4326', 'EPSG:3857'));
                map.getView().setZoom(9);
                marker_feature.removeLayer(marker_style);
            }
        });

        $(document).on('click', '.eform-table tbody tr', function(e) {
            e.preventDefault();

            $(this).closest('tbody').find('tr').removeClass('active-row');
            $(this).addClass('active-row');

            lat = parseFloat($(this).attr('data-lat')) || 0;
            lon = parseFloat($(this).attr('data-lon')) || 0;

            if((lat != 0) && (lon != 0)) {
                e_set_factory_location(ol, map, lat, lon, marker_geom, 9, true);

                marker_style = new ol.style.Style({
                    image: new ol.style.Icon(({
                        opacity: 0.8,
                        scale: 0.07,
                        src: 'img/marker-factory.png'
                    }))
                });
                marker_feature.setStyle(marker_style);
            } else {
                Factory.prototype.utilityService.getPopup({
                    infoMsg: 'ไม่พบค่าพิกัดที่ตั้ง',
                    btnMsg: 'ปิด'
                });
            }
        });

        $(document).on('click', '#insertBtn', function(e) {
            e.preventDefault();

            var numError = 0;

            $.each($('form').find('input[required], select[required], textarea[required]'), function(index, item) {
                factory.initService.setError($(this), 'required');

                if($(this).val() == '')
                    numError += 1;
            });

            if(numError == 0) {
                var formData = new FormData();
                var content = {
                    ID: $('#ID').val() || 0,
                    SuraType: $('#SuraType').val() || 0,
                    RegistNo: $('#RegistNo').val() || '',
                    ContactName: $('#ContactName').val() || '',
                    PCapital: Number(($('#PCapital').val()).replace(/\,/g, '')) || 0,
                    HPower: Number(($('#HPower').val()).replace(/\,/g, '')) || 0,
                    Worker: Number(($('#Worker').val()).replace(/\,/g, '')) || 0,
                    Address: $('#Address').val() || '',
                    Lat: $('#Lat').val() || '',
                    Long: $('#Long').val() || '',
                    LicenseNo: $('#LicenseNo').val() || ''
                };
                
                formData.append('fn', 'submit'); 
                formData.append('data', 1);
                formData.append('content', content);
                formData.append('pic', $('input[name="plan"]')[0].files[0]);

                factory.connectDBService.sendJSONObjForUpload(ajaxUrl, formData).done(function(res) {
                    if(res != undefined) {
                        var data = JSON.parse(res);

                        Factory.prototype.utilityService.getPopup({
                            infoMsg: data.ResultMsg,
                            btnMsg: 'ปิด'
                        });

                        $(document).on('click', '.close-btn', function(e) {
                            $('#resetBtn').trigger('click');
                            getTable();
                        });
                    } 
                });
            }
        });

        $(document).on('click', '#resetBtn', function(e) {
            e.preventDefault();

            factory.initService.setError($('input, select, textarea'), 'clear');
            $('input, select, textarea').val('');
            $('.thumbnail-upload img').remove();
            $('.eform-table tbody tr').removeClass('active-row');

            map.getView().setCenter(ol.proj.transform([99.697123, 17.231792], 'EPSG:4326', 'EPSG:3857'));
            map.getView().setZoom(9);
            marker_feature.removeLayer(marker_style);
        });

        $('#FactoryName').autocomplete({ 
            source: function(req, res) {
                params = {
                    fn: 'autocomplete', 
                    src: 1, 
                    value: req.term || ''
                };

                $.post(ajaxUrl, params, res, 'json');
            },
            minLength: 1,
            select: function(e, ui) { 
                e.preventDefault();

                $(this).val(ui.item.value);

                params = {
                    fn: 'getdata',
                    data: 1,
                    id: ui.item.id
                };

                factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                    if(res != undefined){
                        var data = JSON.parse(res);

                        $('#ID').val(data.ID);
                        $('#SuraType').val(data.SuraType);
                        $('#RegistNo').val(data.RegistNo);
                        $('#ContactName').val(data.ContactName);
                        $('#PCapital').val(data.PCapital);
                        $('#HPower').val(data.HPower);
                        $('#Worker').val(data.Worker);
                        $('#Address').val(data.Address);
                        $('#Lat').val(data.Lat);
                        $('#Long').val(data.Long);
                        $('.thumbnail-upload').append('<img src="'+ ((data.Plan != '') ? data.Plan : 'img/noimages.png') +'" style="height: 100%;">');
                        $('#LicenseNo').val(data.LicenseNo);
                        $('.nav-menu #Province').attr('data-provice', data.Province);
                        $('#ProvinceTXT').html(data.ProvinceTXT);
                    }
                });
            }
        });
    });
</script>
<?php require('popup.php'); ?>
<?php require('footer.php'); ?>     