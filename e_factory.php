<?php require('header.php'); ?>
<?php require('nav.php'); ?>
<!--SECTION-->
<div class="section" style="margin-top: 10px;">
    <!--FORM DATA-->
    <div class="col-md-5">
        <form name="insertForm" enctype="multipart/form-data" novalidate>
            <div class="panel panel-default" style="height: 65vh; overflow-y: scroll; border-radius: 0;">
                <div class="panel-body">
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
                                    <input type="text" class="form-control input-sm" id="ID" disabled required>
                                    <span class="error-content hide" data-label="รหัสโรงงาน"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">ประเภทสหกรณ์</p></td>
                                <td class="col-md-7">
                                    <select class="form-control input-sm" id="SuraType" required>
                                        <option value="">--เลือกประเภทสหกรณ์--</option>
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
                            <!--<tr>
                                <td class="col-md-5"><p class="data-important">พิกัดที่ตั้ง</p></td>
                                <td class="col-md-7">
                                    <div class="col-md-6">
                                        <div class="row" style="padding-right: 3px;">
                                            ละติจูด : <input type="text" class="form-control input-sm" id="address">
                                            <span class="error-content hide" data-label="ละติจูด"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row" style="padding-left: 3px;">
                                            ลองติจูด : <input type="text" class="form-control input-sm" id="address">
                                            <span class="error-content hide" data-label="ลองติจูด"></span>
                                        </div>
                                    </div>
                                </td>
                            </tr>-->
                            <tr>
                                <td class="col-md-5"><p class="data-important">พิกัดที่ตั้ง (ละติจูด)</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="Lat" readonly required>
                                    <span class="error-content hide" data-label="ละติจูด"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">พิกัดที่ตั้ง (ลองติจูด)</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="Long" readonly required>
                                    <span class="error-content hide" data-label="ลองติจูด"></span>
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
    <div class="col-md-7">
        <div class="panel panel-default" style="height: 45vh; border-radius: 0; padding: 0;">
            <div class="panel-body" style="padding-top: 0; padding-bottom: 0;">
                <div class="row">
                    <div id="map" class="map" style="width: 100%; height: 45vh;"></div>
                        <div id="popup" class="ol-popup">
                            <a href="#" id="popup-closer" class="ol-popup-closer"></a>
                            <div id="popup-content"></div>
                        </div>
                    </div>
                    <div id="dvloading" class="loader"><div>
                </div>
            </div>
        </div>
        <div class="panel panel-default" style="height: 29vh; border-radius: 0; padding: 0;">
            <div class="panel-body" style="padding: 0;">
                <div class="table-responsive" style="height: 29vh;">
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

        //--Page load
        setInit();

        //--Function
        function setInit() {
            $('input, select, textarea').val('');
            $('#Lat').val('15.870032');
            $('#Long').val('100.992541');

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

            map = new ol.Map({
                layers : [ layers_deemap ],
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
                            tbodyContent = '<tr>';
                            tbodyContent += '<td class="text-center">'+ i +'</td>';

                            for(var j=1; j<=data.label.length; j++) {
                                tdAlign = ({
                                    '0': 'text-left',
                                    '1': 'text-right',
                                    '2': 'text-center'
                                })[data.data[index].align];
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
            $('#Lat').val('15.870032');
            $('#Long').val('100.992541');
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