<?php require('header.php'); ?>
<?php require('nav.php'); ?>
<!--SECTION-->
<div class="section" style="margin-top: 10px;">
    <!--FORM DATA-->
    <div class="col-md-5">
        <form name="insertForm" novalidate>
            <div class="panel panel-default" style="height: 67vh; overflow-y: scroll; border-radius: 0;">
                <div class="panel-body">
                    <table class="table table-striped" style="margin-top: 0; margin-bottom: 0;">
                        <tbody>
                            <tr>
                                <td class="col-md-12" colspan="2" style="padding: 10px !important;">
                                    <input type="text" class="form-control input-sm" id="FactoryName" 
                                        data-factory-id="0"
                                        data-id=""
                                        placeholde0r="ค้นหาชื่อโรงงาน">
                                    <span class="error-content hide" data-label="ชื่อโรงงาน"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">วันเกิดเหตุ</p></td>
                                <td class="col-md-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control datepicker" id="AccidentDate" required>
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default datepicker-btn">
                                                <i class="fa fa-calendar" style="font-size: 14px;"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <span class="error-content hide" data-label="วันเกิดเหตุ"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">ประเภทพรบ.</p></td>
                                <td class="col-md-7">
                                    <select class="form-control input-sm" id="ActType" required>
                                        <option value="" selected>--เลือกประเภทพรบ.--</option>
                                    </select>
                                    <span class="error-content hide" data-label="ประเภทพรบ"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">ชื่อผู้ต้องหา</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="SuspectName" required>
                                    <span class="error-content hide" data-label="ชื่อผู้ถูกกล่าวหา"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">ชื่อผู้กล่าวหา</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="AccuserName" required>
                                    <span class="error-content hide" data-label="ชื่อผู้ถูกกล่าวหา"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">สถานที่เกิดเหตุ</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="AccidentLocation" required>
                                    <span class="error-content hide" data-label="หลักฐาน"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">ข้อกล่าวหา</p></td>
                                <td class="col-md-7">
                                    <textarea class="form-control input-sm" id="Allegation" required></textarea>
                                    <span class="error-content hide" data-label="ชื่อผู้จัดการ"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">ของกลางชนิด/จำนวน</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="Impound" required>
                                    <span class="error-content hide" data-label="เงินศาลปรับ"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">เงินเปรียบเทียบ</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="ComparisonMoney" required numbered>
                                    <span class="error-content hide" data-label="สถานที่ตั้ง"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">ค่าปรับ</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="Mulct" required numbered>
                                    <span class="error-content hide" data-label="เงินสินบน"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">พนักงานสอบสวน</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="InquiryOfficial" required>
                                    <span class="error-content hide" data-label="เงินรางวัล"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">เงินสินบน</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="Bribe" required numbered>
                                    <span class="error-content hide" data-label="เงินส่งคลัง"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">เงินรางวัล</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="Reward" required numbered>
                                    <span class="error-content hide" data-label="จังหวัด"></span>
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
                                <td class="col-md-5"><p class="data-important">รหัสพื้นที่</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="AreaCode" required>
                                    <span class="error-content hide" data-label="รหัสผู้ประกอบการ"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">พื้นที่</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="Area" required>
                                    <span class="error-content hide" data-label="รหัสผู้ประกอบการ"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">รหัสผู้ประกอบการ</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="OperatorCode" required>
                                    <span class="error-content hide" data-label="รหัสผู้ประกอบการ"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-default" style="border-radius: 0;">
                <div class="panel-body text-right">
                    <button type="submit" class="btn btn-submit btn-sm" id="insertBtn">บันทึก</button>
                    <button type="reset" class="btn btn-sm btn-danger" id="resetBtn">ยกเลิก</button>
                </div>
            </div>
        </form>
    </div>

    <!--MAP AND TABLE DATA-->
    <div class="col-md-7">
        <div class="panel panel-default" style="height: 51vh; border-radius: 0; padding: 0;">
            <div class="panel-body" style="padding-top: 0; padding-bottom: 0;">
                <div class="row">
                    <div id="map" class="map" style="width: 100%; height: 51vh;"></div>
                        <div id="popup" class="ol-popup">
                            <a href="#" id="popup-closer" class="ol-popup-closer"></a>
                            <div id="popup-content"></div>
                        </div>
                    </div>
                    <div id="dvloading" class="loader"><div>
                </div>
            </div>
        </div>
        <div class="panel panel-default" style="height: 25vh; border-radius: 0; padding: 0;">
            <div class="panel-body" style="padding: 0;">
                <div class="table-responsive" style="height: 25vh; overflow-y: hidden;">
                    <table class="table table-striped table-bordered eform-table" style="margin-top: 0;"> 
                        <thead><tr></tr></thead>
                        <tbody></tbody>
                    </table>
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
        var ajaxUrl = 'http://210.4.143.51/Surathai01/API/eformAPI.php';
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
            params = {
                fn: 'filter',
                src: 3
            };

            factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                if(res != undefined){
                    var data = JSON.parse(res);

                    $.each(data, function(index, item) {
                        $('#ActType').append('<option value="'+ item.id +'">'+ item.label +'</option>');
                    });
                }
            });

            $('input, select, textarea').val('');
            $('#AccidentDate').val(factory.dataService.getCurrentDateTH('short'));

            getMap();
            getTable();
        }
        
        function getMap() {
            var vectorLayer_region = null;
            var vectorLayer_province = null;
            var dataGJson_world = null;
            var dataGJson_point_region = null;
            var style_region = null;
            var zoomslider;
            var container = $('#popup');
            var content = $('#popup-content');
            var closer = $('#popup-closer');
            
            //--DEE-MAP
            var layers_deemap = new ol.layer.Tile({ 
                source: new ol.source.TileWMS({
                    url: 'http://www.dee-map.com/geoserver/gwc/service/wms/dmwms',
                    params: { 'LAYERS': 'Dee-Map', 'VERSION': '1.1.1', 'FORMAT': 'image/png8' },
                    serverType: 'geoserver', crossOrigin: 'anonymous', noWrap: true,  wrapX: false
                }),  
                extent: [-20037508.34, -20037508.34, 20037508.34, 20037508.34]
            });
    
            var projection = ol.proj.get('EPSG:3857');

			// ===========================================
			// ADDED BY KUMPEE
			marker_geom = new ol.geom.Point([0, 0]);
			marker_feature = new ol.Feature({geometry: marker_geom});
			marker_style = new ol.style.Style({
				image: new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
					anchor: [0.5, 46],
					anchorXUnits: 'fraction',
					anchorYUnits: 'pixels',
					opacity: 0.7,
					src: 'img/marker-eform.png'
				}))
			});
			marker_feature.setStyle(marker_style);
			marker_source = new ol.source.Vector({
				features: [marker_feature]
			});
			layers_marker = new ol.layer.Vector({
				source: marker_source
			});
			// ===========================================
			
            map = new ol.Map({
                layers : [ layers_deemap, layers_marker ],
                //overlays: [overlay], //--for popup
                target : 'map',
                view: new ol.View({
                    center: [99.697123, 17.231792],
                    projection: projection,
                    zoom: 16
                })
            });
            
            $('#dvloading').hide().fadeOut();
            
            //--Zoom Slider
            zoomslider = new ol.control.ZoomSlider();
            map.addControl(zoomslider);
            
            map.getView().setCenter(ol.proj.transform([99.697123, 17.231792], 'EPSG:4326', 'EPSG:3857'));
            map.getView().setZoom(9.0);
			
			// ===========================================
			// ADDED BY KUMPEE
			map.on('singleclick', function(event) {
				// lonlat[0] : Longitude
				// lonlat[1] : Latitude
				var lonlat = e_get_factory_location(ol, map, event, marker_geom, 9, false);
				console.log('lat:', lonlat[1], 'lon:', lonlat[0]);
                
                $('#Lat').val(lonlat[1]);
                $('#Lon').val(lonlat[0]);
			});
			// ===========================================
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
                    console.log(data);

                    var theadContent = '<th class="text-center text-nowrap" style="padding: 4px 10px;">#</th>';
                    $.each(data.label, function(index, item) {
                        theadContent += '<th class="text-center text-nowrap" style="padding: 4px 10px;">'+ item +'</th>';
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

        $(document).on('keyup', '#Lat, #Lon', function(e) {
            e.preventDefault();

            if(($('#Lat').val() != '') && ($('#Lon').val() != '')) {
                lat = parseFloat($('#Lat').val()) || 0;
                lon = parseFloat($('#Lon').val()) || 0;

                e_set_factory_location(ol, map, lat, lon, marker_geom, 9, true);
            }
        });

        $(document).on('click', '.eform-table tbody tr', function(e) {
            e.preventDefault();

            $(this).closest('tbody').find('tr').removeClass('active-row');
            $(this).addClass('active-row');

            lat = parseFloat($(this).attr('data-lat')) || 0;
            lon = parseFloat($(this).attr('data-lon')) || 0;

            if((lat != 0) && (lon != 0))
                e_set_factory_location(ol, map, lat, lon, marker_geom, 9, true);
            else {
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
                var insertData = {
                    ID: $('#FactoryName').data('id') || 0,
                    AccidentDate: $('#AccidentDate').val() || '',
                    ActType: $('#ActType').val() || 0,
                    SuspectName: $('#SuspectName').val() || '',
                    AccuserName: $('#AccuserName').val() || '',
                    AccidentLocation: $('#AccidentLocation').val() || '',
                    Allegation: $('#Allegation').val() || '',
                    Impound: $('#Impound').val() || '',
                    ComparisonMoney: Number(($('#ComparisonMoney').val()).replace(/\,/g, '')) || 0,
                    Mulct: Number(($('#Mulct').val()).replace(/\,/g, '')) || 0,
                    InquiryOfficial: Number(($('#InquiryOfficial').val()).replace(/\,/g, '')) || '',
                    Bribe: Number(($('#Bribe').val()).replace(/\,/g, '')) || 0,
                    Reward: Number(($('#Reward').val()).replace(/\,/g, '')) || 0,
                    Lat: $('#Lat').val() || '',
                    Long: $('#Long').val() || '',
                    AreaCode: $('#AreaCode').val() || '',
                    Area: $('#Area').val() || '',
                    OperatorCode: $('#OperatorCode').val() || ''
                };

                params = {
                    fn: 'submit',
                    data: 2,
                    content: $.param(insertData)
                };

                factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                    if(res != undefined){
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
            $('#AccidentDate').val(factory.dataService.getCurrentDateTH('short'));
        });

        $('#FactoryName').autocomplete({ 
            source: function(req, res) {
                params = {
                    fn: 'autocomplete', 
                    src: 2, 
                    value: req.term || ''
                };

                $.post(ajaxUrl, params, res, 'json');
            },
            minLength: 1,
            select: function(e, ui) { 
                e.preventDefault(); 
                
                params = {
                    fn: 'getdata',
                    data: 2,
                    id: ui.item.id
                };

                factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                    if(res != undefined){
                        var data = JSON.parse(res);
                        console.log(data);

                        /*$('#FactoryName').val(data.FactoryName);
                        $('#AccidentDate').val(data.AccidentDate);
                        $('#ActType').val(data.ActType);
                        $('#SuspectName').val(data.SuspectName);
                        $('#AccuserName').val(data.AccuserName);
                        $('#AccidentLocation').val(data.AccidentLocation);
                        $('#Allegation').val(data.Allegation);
                        $('#Impound').val(data.Impound);
                        $('#ComparisonMoney').val(data.ComparisonMoney);
                        $('#Mulct').val(data.Mulct);
                        $('#InquiryOfficial').val(data.InquiryOfficial);
                        $('#Bribe').val(data.Bribe);
                        $('#Reward').val(data.Reward);
                        $('#Lat').val(data.Lat);
                        $('#Long').val(data.Long);
                        $('#AreaCode').val(data.AreaCode);
                        $('#Area').val(data.Area);
                        $('#OperatorCode').val(data.OperatorCode);
                        $('.nav-menu #Province').attr('data-provice', data.Province);
                        $('#ProvinceTXT').html(data.ProvinceTXT);*/
                    }
                });
            }
        });
    });
</script>
<?php require('popup.php'); ?>
<?php require('footer.php'); ?>     