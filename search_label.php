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
                        <table class="table table-striped table-bordered search-label-table" style="margin: 0 auto; overflow-y: hidden;"> 
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

                        if(data.length != 0) {
                            $('.search-label-table tbody tr').remove();
                            $('#BrandImage').attr('src', data.picture);
                            $('.search-label-table tbody').append('<tr>' + 
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
                                    '<td class="text-center text-nowrap"><a href="#" title="คลิกเพื่อดูรูป" class="show-image"><img src="'+ data.plan +'" style="width: 50px; height: 50px;"></a></td>' +
                                    '<td class="text-center text-nowrap"><a href="#" title="คลิกเพื่อดูรูป" class="show-image"><img src="'+ data.plan +'" style="width: 50px; height: 50px;"></a></td>' +
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
                $('.search-label-table tbody tr').remove();
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
    });
</script>
<?php require('popup.php'); ?>
<?php require('footer.php'); ?>