<?php require('header.php'); ?>
<?php require('nav.php'); ?>
<!--SECTION-->
<div class="section" style="margin-top: 10px;">
    <div class="col-md-12">
        <div class="row">
            <!--DATA-->
            <div class="col-md-4">
                <div class="panel panel-default" style="border-radius: 0; border: 0;">
                    <div class="panel-body" style="padding: 0;">
                        <table class="table table-striped search-detail-table" style="margin-top: 0; margin-bottom: 0;">
                            <thead>
                                <tr>
                                    <th class="text-center text-nowrap">รายการ</th>
                                    <th class="text-center text-nowrap">ภาษี</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="col-md-5" style="padding: 0 10px !important;"><p>ก่อสร้าง</p></td>
                                    <td class="col-md-7 text-center" style="padding: 0 10px !important;"><span id="Construction"></span></td>
                                </tr>
                                <tr>
                                    <td class="col-md-5" style="padding: 0 10px !important;"><p>ผลิต</p></td>
                                    <td class="col-md-7 text-center" style="padding: 0 10px !important;"><span id="Manufacture"></span></td>
                                </tr>
                                <tr>
                                    <td class="col-md-5" style="padding: 0 10px !important;"><p>ขาย</p></td>
                                    <td class="col-md-7 text-center" style="padding: 0 10px !important;"><span id="Sale"></span></td>
                                </tr>
                                <tr>
                                    <td class="col-md-5" style="padding: 0 10px !important;"><p>ขน</p></td>
                                    <td class="col-md-7 text-center" style="padding: 0 10px !important;"><span id="Transport"></span></td>
                                </tr>
                                <tr>
                                    <td class="col-md-5" style="padding: 0 10px !important;"><p>สแตมป์</p></td>
                                    <td class="col-md-7 text-center" style="padding: 0 10px !important;"><span id="Stamp"></span></td>
                                </tr>
                                <tr class="search-detail-total">
                                    <td class="col-md-5 text-center"><p>รวมทั้งสิ้น</p></td>
                                    <td class="col-md-7 text-center"><span id="Total"></span></td>
                                </tr>
                                <tr>
                                    <td class="col-md-12" colspan="2" style="padding: 10px !important;">
                                        <input class="form-control input-sm" id="FactoryName" placeholder="ค้นหาชื่อโรงงาน">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!--MAP-->
            <div class="col-md-8 get-map">
                <div class="panel panel-default" style="height: 40vh; border-radius: 0; padding: 0;">
                    <div class="panel-body" style="padding-top: 0; padding-bottom: 0;">
                        <div class="row">
                            <div id="map" class="map" style="width: 100%; height: 40vh;"></div>
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
            <div class="panel panel-default" style="height: 34vh;">
                <div class="panel-heading text-center header-table">
                    <h3>รายการข้อมูล</h3>
                </div>
                <div class="panel-body" style="padding: 0;">
                    <div class="table-responsive" style="height: 20vh;">
                        <table class="table table-striped table-bordered search-table" style="margin-top: 0;"> 
                            <thead><tr></tr></thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="col-md-12 pagination"></div>
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
        var ajaxUrl = 'http://210.4.143.51/Surathai01/API/reportAPI.php';
        //var ajaxUrl = 'http://210.4.143.51/Surathai01/API/eformAPI.php';
        //var ajaxUrl = 'http://210.4.143.51/Surathai01/API/searchAPI.php';
        var params = {};
        var year = $('.nav-menu #year').val() || '';
        var region = $('.nav-menu #region').val() || '';
        var province = $('.nav-menu #province').val() || '';

        //--Page load
        getInit();

        //--Function
        function getInit() {
            params = {
                fn: 'filter',
                job: 1,
                src: 0
            };

            factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                if(res != undefined){
                    var data = JSON.parse(res);

                    $.each(data.year, function(index, item) {
                        $('.nav-menu #year').append('<option value="'+ item.value +'">'+ item.label +'</option>');
                    });

                    $.each(data.region, function(index, item) {
                        $('.nav-menu #region').append('<option value="'+ item.id +'">'+ item.label +'</option>');
                    });
                    
                    $.each(data.province, function(index, item) {
                        $('.nav-menu #province').append('<option value="'+ item.id +'">'+ item.label +'</option>');
                    });

                    getTable();
                }
            });

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

            map = new ol.Map({
                layers : [ layers_deemap ],
                //overlays: [overlay],//for popup
                target : 'map',
                view: new ol.View({
                center: [13.0, 100.5],
                projection: projection,
                zoom: 6
                })
            });

            $('#dvloading').hide().fadeOut();

            /* Zoom Slider */ 
            zoomslider = new ol.control.ZoomSlider();
            map.addControl(zoomslider);

            map.getView().setCenter(ol.proj.transform([108.697123, 10.231792], 'EPSG:4326', 'EPSG:3857'));
            map.getView().setZoom(6.0);
        }

        function getTable() {
            $('.search-table thead th, ' +
                '.search-table tbody tr').remove();

            year = $('.nav-menu #year').val() || '';
            region = $('.nav-menu #region').val() || '';
            province = $('.nav-menu #province').val() || '';

            if(year != '') {
                params = {
                    fn: 'gettable',
                    job: 1,
                    year: year,
                    region: region || 0,
                    province: province || 0
                };
                
                factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                    if(res != undefined) {
                        var data = JSON.parse(res);

                        var theadContent = '';
                        $.each(data.label, function(index, item) {
                            theadContent += '<th class="text-center text-nowrap">' +
                                    '<div class="checkbox checkbox-primary" style="margin: 0 auto;">' +
                                        '<input id="'+ item +'" type="checkbox" checked="checked"><label for="'+ item +'" style="font-weight: bold;">'+ item +'</label>' +
                                    '</div>' +
                                '</th>';
                        });
                        $('.search-table thead tr').append(theadContent);
                        
                        if(data.data.length != 0) {
                            var row = (data.data.length / data.label.length);
                            var tbodyContent = '';
                            var alignContent = 0;
                            var index = 0;

                            for(var i=1; i<=row; i++) {
                                tbodyContent = '<tr>';

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
                                $('.search-table tbody').append(tbodyContent);
                            }
                        } else 
                            $('.search-table tbody').append('<tr><td colspan="'+ data.label.length +'" style="text-align: center;">ไม่พบข้อมูล</td></tr>');
                    }
                });
            }
        }

        function getPagination() {
            //--pagination
        }

        //--Event
        $(document).on('change', '.nav-menu #year', function(e) {
            e.preventDefault();
            
            $('.nav-menu #region').find('option:eq(0)').prop('selected', true);
            $('.nav-menu #province option[value!=""]').remove();
            
            year = $('.nav-menu #year').val() || '';
            region = $('.nav-menu #region').val() || '';

            if(year != '') {
                $('.nav-menu #region').find('option:eq(1)').prop('selected', true);

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

            getTable();
        });

        $(document).on('change', '.nav-menu #region', function(e) {
            e.preventDefault();
            
            $('.nav-menu #province').find('option[value!=""]').remove();

            region = $('.nav-menu #region').val() || '';
            
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

            getTable();
        });

        $(document).on('change', '.nav-menu #province', function(e) {
            e.preventDefault();

            getTable();
        });

        $(document).on('click', '.search-detail-table tbody tr:not(.search-detail-total):not(:nth-last-child(1))', function(e) {
            e.preventDefault();

            year = $('.nav-menu #year').val() || '';

            if(year != '') {
                $(this).closest('tbody').find('tr').removeClass('search-active-table');
                $(this).addClass('search-active-table');

                getTable();
            } else {
                Factory.prototype.utilityService.getPopup({
                    infoMsg: 'กรุณาเลือกปีงบประมาณก่อนดูข้อมูล',
                    btnMsg: 'ปิด'
                });
            } 
        });

        $(document).on('click', '.search-table tbody tr', function(e) {
            e.preventDefault();

            $(this).closest('tbody').find('tr').removeClass('search-active-table');
            $(this).addClass('search-active-table');

            Factory.prototype.utilityService.getPopup({
                infoMsg: 'รอแผนที่ทำเสร็จก่อน, ข้อมูลที่คุณเลือกคือ : '+ $(this).find('td:eq(0)').html(),
                btnMsg: 'ปิด'
            });
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
                        console.log(data);
                    }
                });
            }
        });
    });
</script>
<?php require('popup.php'); ?>
<?php require('footer.php'); ?>