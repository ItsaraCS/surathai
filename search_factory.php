<?php require('header.php'); ?>
<?php require('nav.php'); ?>
<!--SECTION-->
<div class="section" style="margin-top: 10px;">
    <div class="col-md-12">
        <div class="section-top row">
            <!--DATA-->
            <div class="col-md-4">
                <div class="panel panel-default" style="border-radius: 0; border: 0;">
                    <div class="panel-body" style="padding: 0;">
                        <table class="table table-striped search-detail-table bg-info" style="margin-top: 0; margin-bottom: 0;">
                            <thead><tr></tr></thead>
                            <tbody></tbody>
                        </table>
                        <table class="table" style="margin-top: 0; margin-bottom: 0;">
                            <tbody>
                                <tr>
                                    <td class="col-md-12" colspan="2" style="padding: 10px !important;">
                                        <input class="form-control input-sm" id="FilterKeySearch" placeholder="ค้นหาชื่อโรงงาน">
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
        <div class="section-bottom row">
            <div class="panel panel-default panel-data-table-res">
                <div class="panel-body" style="padding: 0;">
                    <div class="table-responsive table-data-res">
                        <table class="table table-striped table-bordered search-table bg-info" data-toggle-status="default" data-toggle-rpp="5" style="margin-top: 0;"> 
                            <thead><tr></tr></thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="col-md-12 pagination" style="padding: 0; margin-bottom: 0;"></div>
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
        top: 119px;
    }

    .defaultZoom {
        top: 130px;
        left: 0.5em;
    }
    .ol-touch .defaultZoom {
        top: 158px;
    }
</style>
<!--JS-->  
<script type="text/javascript">
    $(document).ready(function(e) {
        //--Variable
        var factory = new Factory();
        var ajaxUrl = 'API/searchAPI.php';
        var params = {};
        var year = $('.nav-menu #year').val() || '';
        var region = $('.nav-menu #region').val() || 0;
        var province = $('.nav-menu #province').val() || 0;
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
            params = {
                fn: 'filter',
                job: 5,
                src: 0
            };

            factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                if(res != undefined) {
                    var data = JSON.parse(res);

                    $.each(data.year, function(index, item) {
                        $('.nav-menu #year').append('<option value="'+ item.value +'">'+ item.label +'</option>');
                    });

                    $.each(data.region, function(index, item) {
                        $('.nav-menu #region').append('<option value="'+ item.id +'" data-lat="'+ item.lat +'" data-lon="'+ item.long +'">'+ item.label +'</option>');
                    });
                    
                    $.each(data.province, function(index, item) {
                        $('.nav-menu #province').append('<option value="'+ item.id +'" data-lat="'+ item.lat +'" data-lon="'+ item.long +'">'+ item.label +'</option>');
                    });

                    $('.nav-menu #year, ' +
                        '.nav-menu #region, ' +
                        '.nav-menu #province').find('option:eq(1)').prop('selected', true);

                    getMap();
                    getTableAll();
                }
            });
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
            
			search_load_point_layers();

            $('#dvloading').hide().fadeOut();

            map.getView().setCenter(ol.proj.transform([103.697123, 13.231792], 'EPSG:4326', 'EPSG:3857'));
            map.getView().setZoom(4.5);

            var target = map.getTarget();
            var jTarget = typeof target === 'string' ? $("#" + target) : $(target);

            var labelPopup = document.querySelector('#label-popup');
            var popup = new ol.Overlay({
                element: labelPopup,
                positioning: 'bottom-center',
                stopEvent: true
            });
            map.addOverlay(popup);

            $(map.getViewport()).on('mousemove', function(e) {
                var view = map.getView();
                var resolution = view.getResolution();

                if(resolution < 100) {
                    var pixel = map.getEventPixel(e.originalEvent);
                    var hit = map.forEachFeatureAtPixel(pixel, function(feature, layer) {
                        return feature;
                    });
                    
                    if(hit) {
                        if(hit.get('FACTORY_TNAME') != undefined) 
                            jTarget.css('cursor', 'pointer');
                    } else 
                        jTarget.css('cursor', '');
                }
            });
            
            $(map.getViewport()).on('click', function(e) {
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

                            var contentPopup = '<div class="text-center" style="width: 300px;">' +
                                    '<div class="text-right" style="width: 240px;"><a href="#" id="popup-closer"><i class="fa fa-close"></i></a></div>' +
                                    '<h4 style="width: 240px; margin-top: 0; color: #333333;">' + hit.get('FACTORY_TNAME') +'</h4>' +
                                    '<div class="table-responsive" style="width: 240px;">' +
                                        '<table class="table table-bordered" style="margin: 0;">' +
                                            '<thead>' +
                                                '<th class="text-nowrap text-center" style="font-size: 10px;">ชื่อสถานประกอบการ</th>' +
                                                '<th class="text-nowrap text-center" style="font-size: 10px;">รหัสทะเบียนโรงงาน</th>' +
                                                /*'<th class="text-nowrap text-center" style="font-size: 10px;">ชื่อผู้ขอก่อตั้งโรงงาน</th>' +
                                                '<th class="text-nowrap text-center" style="font-size: 10px;">เลขที่ใบอนุญาตก่อตั้งโรงงาน</th>' +*/
                                                '<th class="text-nowrap text-center" style="font-size: 10px;">ประเภท</th>' +
                                                /*'<th class="text-nowrap text-center" style="font-size: 10px;">วันที่อนุญาต</th>' +
                                                '<th class="text-nowrap text-center" style="font-size: 10px;">สถานที่ตั้งโรงงาน</th>' +
                                                '<th class="text-nowrap text-center" style="font-size: 10px;">ยี่ห้อ</th>' +
                                                '<th class="text-nowrap text-center" style="font-size: 10px;">รูปฉลาก</th>' +
                                                '<th class="text-nowrap text-center" style="font-size: 10px;">แผนผังโรงงานและอุปกรณ์</th>' +
                                                '<th class="text-nowrap text-center" style="font-size: 10px;">ผลตรวจโรงงาน</th>' +*/
                                            '</thead>' +
                                            '<tbody>' +
                                                '<th class="text-nowrap" style="font-size: 10px;">' + hit.get('FACTORY_TNAME') +'</th>' +
                                                '<th class="text-nowrap" style="font-size: 10px;">' + hit.get('REGISTER_CODE') +'</th>' +
                                                '<th class="text-nowrap" style="font-size: 10px;">' + hit.get('CLASS') +'</th>' +
                                            '</tbody>' +
                                        '</table>' +
                                    '</div>' +
                                '</div>';

                            $(labelPopup).popover({
                                placement: 'top',
                                html: true,
                                content: contentPopup
                            });
                            $(labelPopup).popover('show');

                            $('#popup-closer').click(function(e) {
                                jTarget.css('cursor', '');
                                $(labelPopup).popover('destroy');
                            });
                        }
                    } else {
                        jTarget.css('cursor', '');
                        $(labelPopup).popover('destroy');
                    }
                }
            });
        }

        function getTable(params) {
            $('.search-table thead th, ' +
                '.search-table tbody tr, ' +
                '.pagination div').remove();
            
            if(params == undefined) {
                params = {
                    fn: 'gettable',
                    job: 5,
                    year: $('.nav-menu #year option:eq(1)').attr('value'),
                    region: $('.nav-menu #region option:eq(1)').attr('value') || 0,
                    province: $('.nav-menu #province option:eq(1)').attr('value') || 0,
                    menu: 0,
                    page: 1,
                    keyword: $('#FilterKeySearch').val() || '',
                    rpp: $('.search-table').attr('data-toggle-rpp') || 5
                };
            }
            
            factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                if(res != undefined) {
                    var data = JSON.parse(res);

                    zoomMapByArea();

                    var theadContent = '';
                    $.each(data.label, function(index, item) {
                        theadContent += '<th class="text-center text-nowrap bg-primary">' +
                                '<div class="checkbox checkbox-success" style="margin: 0 auto;">' +
                                    '<input id="'+ item +'" type="checkbox" class="select-export" checked="checked"><label for="'+ item +'" style="font-weight: bold;">'+ item +'</label>' +
                                '</div>' +
                            '</th>';
                    });
                    $('.search-table thead tr').append(theadContent);
                    
                    if(data.data.length != 0) {
                        var tbodyContent = '';
                        var alignContent = 0;
                        var index = 0;
                        
                        $.each(data.latlong, function(latlongIndex, latlongItem) {
                            if(data.latlong.length != 0)
                                tbodyContent = '<tr data-id="'+ data.data[index].id +'" data-lat="'+ latlongItem.Lat +'" data-lon="'+ latlongItem.Long +'">';
                            else
                                tbodyContent = '<tr data-id="'+ data.data[index].id +'" data-lat="0" data-lon="0">';

                            for(var j=1; j<=data.label.length; j++) {
                                tdAlign = ({
                                    '0': 'text-left',
                                    '1': 'text-right',
                                    '2': 'text-center',
                                    '3': 'text-center',
                                    '4': 'text-center'
                                })[data.data[index].align];
                                
                                if(data.data[index].align == 3)
                                    tbodyContent += '<td class="'+ tdAlign +' text-nowrap"><a href="#" title="คลิกเพื่อดูรูป" class="show-image"><img src="'+ ((data.data[index].text != '') ? data.data[index].text : 'img/noimages.png') +'" style="width: 50px; height: 50px;"></a></td>';
                                else if(data.data[index].align == 4)
                                    tbodyContent += '<td class="'+ tdAlign +' text-nowrap"><a href="'+ data.data[index].text +'" class="show-link">ดูเพิ่มเติม</a></td>';
                                else
                                    tbodyContent += '<td class="'+ tdAlign +' text-nowrap">'+ data.data[index].text +'</td>';

                                index += 1;
                            }

                            tbodyContent += '</tr>';
                            $('.search-table tbody').append(tbodyContent);
                        });

                        getPagination({
                            page: data.cur_page || 1,
                            perPage: data.row_per_page || 5,
                            splitPage: 3,
                            total: data.sum_of_row|| 0
                        });
                    } else 
                        $('.search-table tbody').append('<tr class="disabled"><td colspan="'+ data.label.length +'" style="text-align: center;">ไม่พบข้อมูล</td></tr>');
                }
            });
        }

        function getTableAll(params) {
            $('.search-detail-table thead th, ' +
                '.search-detail-table tbody tr, ' +
                '.search-table thead th, ' +
                '.search-table tbody tr, ' +
                '.pagination div').remove();
            
            if(params == undefined) {
                params = {
                    fn: 'gettable',
                    job: 5,
                    year: $('.nav-menu #year option:eq(1)').attr('value'),
                    region: $('.nav-menu #region option:eq(1)').attr('value') || 0,
                    province: $('.nav-menu #province option:eq(1)').attr('value') || 0,
                    menu: 0,
                    page: 1,
                    keyword: $('#FilterKeySearch').val() || '',
                    rpp: $('.search-table').attr('data-toggle-rpp') || 5
                };
            }
            
            factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                if(res != undefined) {
                    var data = JSON.parse(res);

                    zoomMapByArea();
                    
                    var searchDetailTableContent = '';
                    $.each(data.menu, function(index, item) {
                        if(index == 0) {
                            searchDetailTableContent += '<th class="text-center text-nowrap bg-primary">'+ item.subject +'</th>';
                                $.each(item.value, function(indexValue, itemValue) {
                                    searchDetailTableContent += '<th class="text-center text-nowrap bg-primary">'+ itemValue +'</th>';
                                });
                            $('.search-detail-table thead tr').append(searchDetailTableContent);
                            searchDetailTableContent = '';
                        }
                        
                        if(index == (data.menu.length - 1)) {
                            searchDetailTableContent += '<tr class="search-detail-total">' +
                                    '<td class="text-center"><p>'+ item.subject +'</p></td>';
                                    $.each(item.value, function(indexValue, itemValue) {
                                        searchDetailTableContent += '<td class="text-center">'+ item.value[indexValue] +'</td>';
                                    });
                                searchDetailTableContent += '</tr>';
                        } 
                        
                        if((index != 0) && (index != (data.menu.length - 1))) {
                            searchDetailTableContent += '<tr>' +
                                    '<td style="padding: 0 10px !important;"><p>'+ item.subject +'</p></td>';
                                    $.each(item.value, function(indexValue, itemValue) {
                                        searchDetailTableContent += '<td class="text-center" style="padding: 0 10px !important;">'+ item.value[indexValue] +'</td>';
                                    });
                                searchDetailTableContent += '</tr>';
                        }
                    });
                    $('.search-detail-table tbody').append(searchDetailTableContent);

                    var theadContent = '';
                    $.each(data.label, function(index, item) {
                        theadContent += '<th class="text-center text-nowrap bg-primary">' +
                                '<div class="checkbox checkbox-success" style="margin: 0 auto;">' +
                                    '<input id="'+ item +'" type="checkbox" class="select-export" checked="checked"><label for="'+ item +'" style="font-weight: bold;">'+ item +'</label>' +
                                '</div>' +
                            '</th>';
                    });
                    $('.search-table thead tr').append(theadContent);
                    
                    if(data.data.length != 0) {
                        var tbodyContent = '';
                        var alignContent = 0;
                        var index = 0;
                        
                        $.each(data.latlong, function(latlongIndex, latlongItem) {
                            if(data.latlong.length != 0)
                                tbodyContent = '<tr data-id="'+ data.data[index].id +'" data-lat="'+ latlongItem.Lat +'" data-lon="'+ latlongItem.Long +'">';
                            else
                                tbodyContent = '<tr data-id="'+ data.data[index].id +'" data-lat="0" data-lon="0">';

                            for(var j=1; j<=data.label.length; j++) {
                                tdAlign = ({
                                    '0': 'text-left',
                                    '1': 'text-right',
                                    '2': 'text-center',
                                    '3': 'text-center',
                                    '4': 'text-center'
                                })[data.data[index].align];
                                
                                if(data.data[index].align == 3)
                                    tbodyContent += '<td class="'+ tdAlign +' text-nowrap"><a href="#" title="คลิกเพื่อดูรูป" class="show-image"><img src="'+ ((data.data[index].text != '') ? data.data[index].text : 'img/noimages.png') +'" style="width: 50px; height: 50px;"></a></td>';
                                else if(data.data[index].align == 4)
                                    tbodyContent += '<td class="'+ tdAlign +' text-nowrap"><a href="'+ data.data[index].text +'" class="show-link">ดูเพิ่มเติม</a></td>';
                                else
                                    tbodyContent += '<td class="'+ tdAlign +' text-nowrap">'+ data.data[index].text +'</td>';

                                index += 1;
                            }

                            tbodyContent += '</tr>';
                            $('.search-table tbody').append(tbodyContent);
                        });

                        getPagination({
                            page: data.cur_page || 1,
                            perPage: data.row_per_page || 5,
                            splitPage: 3,
                            total: data.sum_of_row|| 0
                        });
                    } else 
                        $('.search-table tbody').append('<tr class="disabled"><td colspan="'+ data.label.length +'" style="text-align: center;">ไม่พบข้อมูล</td></tr>');
                }
            });
        }

        function getPagination(params) {
            $('.pagination div').remove();

            if(params == undefined) {
                params = {
                    page: 1,
                    perPage: $('.search-table').attr('data-toggle-rpp') || 5,
                    splitPage: 3,
                    total: 0
                };
            }

            factory.connectDBService.sendJSONStr('API/paginatorAPI.php', params).done(function(res) {
                if(res != undefined) {
                    $('.pagination').append(res);
                }
            });
        }

        function zoomMapByArea() {
            region = $('.nav-menu #region').val() || $('.nav-menu #region option:eq(1)').attr('value');
            province = $('.nav-menu #province').val() || $('.nav-menu #province option:eq(1)').attr('value');
            var regionLat = parseFloat($('.nav-menu #region option:selected').attr('data-lat')) || 13.231792;
            var regionLon = parseFloat($('.nav-menu #region option:selected').attr('data-lon')) || 103.697123;
            var provinceLat = parseFloat($('.nav-menu #province option:selected').attr('data-lat')) || 13.231792;
            var provinceLon = parseFloat($('.nav-menu #province option:selected').attr('data-lon')) || 103.697123;

            if(region != 0 && province != 0) {
                map.getView().setCenter(ol.proj.transform([provinceLon, provinceLat], 'EPSG:4326', 'EPSG:3857'));
                map.getView().setZoom(10);
            } else if(region != 0) {
                map.getView().setCenter(ol.proj.transform([regionLon, regionLat], 'EPSG:4326', 'EPSG:3857'));
                map.getView().setZoom(7);
            } else {
                map.getView().setCenter(ol.proj.transform([103.697123, 13.231792], 'EPSG:4326', 'EPSG:3857'));
                map.getView().setZoom(4.5);
            }

            marker_style = new ol.style.Style();
            marker_feature.setStyle(marker_style);

            if(map.getLayers().getArray().length == 3)
                map.getLayers().setAt(3, layers_marker);
        }

        //--Event
        $(document).on('change', '.nav-menu #year', function(e) {
            e.preventDefault();
            
            $('.nav-menu #region').find('option:eq(0)').prop('selected', true);
            $('.nav-menu #province option[value!=""]').remove();
            $('.search-detail-table thead tr').attr('data-menu', 0);
            $('#FilterKeySearch').val('');
            
            year = $('.nav-menu #year').val() || 0;

            if(year != '') {
                $('.nav-menu #region').find('option:eq(1)').prop('selected', true);
                region = $('.nav-menu #region').val() || '';
                
                if(region != '') {
                    params = {
                        fn: 'filter',
                        job: 5,
                        src: 5,
                        value: region || 0
                    };

                    factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                        if(res != undefined) {
                            var data = JSON.parse(res);

                            $.each(data, function(index, item) {
                                $('.nav-menu #province').append('<option value="'+ item.id +'" data-lat="'+ item.lat +'" data-lon="'+ item.long +'">'+ item.label +'</option>');
                            });
                            $('.nav-menu #province').find('option:eq(1)').prop('selected', true);

                            getTableAll({
                                fn: 'gettable',
                                job: 5,
                                year: $('.nav-menu #year').val() || $('.nav-menu #year option:eq(1)').attr('value'),
                                region: $('.nav-menu #region').val() || $('.nav-menu #region option:eq(1)').attr('value'),
                                province: $('.nav-menu #province').val() || $('.nav-menu #province option:eq(1)').attr('value'),
                                menu: 0,
                                page: 1,
                                keyword: '',
                                rpp: $('.search-table').attr('data-toggle-rpp') || 5
                            });
                        }
                    });
                }
            } else {
                getTableAll({
                    fn: 'gettable',
                    job: 5,
                    year: $('.nav-menu #year').val() || $('.nav-menu #year option:eq(1)').attr('value'),
                    region: $('.nav-menu #region').val() || $('.nav-menu #region option:eq(1)').attr('value'),
                    province: $('.nav-menu #province').val() || $('.nav-menu #province option:eq(1)').attr('value'),
                    menu: 0,
                    page: 1,
                    keyword: '',
                    rpp: $('.search-table').attr('data-toggle-rpp') || 5
                });
            }
        });

        $(document).on('change', '.nav-menu #region', function(e) {
            e.preventDefault();
            
            $('.nav-menu #province').find('option[value!=""]').remove();
            $('.search-detail-table thead tr').attr('data-menu', 0);
            $('#FilterKeySearch').val('');

            region = $('.nav-menu #region').val() || 0;
            
            if(region != '') {
                params = {
                    fn: 'filter',
                    job: 5,
                    src: 5,
                    value: region || 0
                };
            
                factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                    if(res != undefined) {
                        var data = JSON.parse(res);

                        $.each(data, function(index, item) {
                            $('.nav-menu #province').append('<option value="'+ item.id +'" data-lat="'+ item.lat +'" data-lon="'+ item.long +'">'+ item.label +'</option>');
                        });
                        $('.nav-menu #province').find('option:eq(1)').prop('selected', true);

                        getTableAll({
                            fn: 'gettable',
                            job: 5,
                            year: $('.nav-menu #year').val() || $('.nav-menu #year option:eq(1)').attr('value'),
                            region: $('.nav-menu #region').val() || $('.nav-menu #region option:eq(1)').attr('value'),
                            province: $('.nav-menu #province').val() || $('.nav-menu #province option:eq(1)').attr('value'),
                            menu: 0,
                            page: 1,
                            keyword: '',
                            rpp: $('.search-table').attr('data-toggle-rpp') || 5
                        });
                    }
                });
            } else {
                getTableAll({
                    fn: 'gettable',
                    job: 5,
                    year: $('.nav-menu #year').val() || $('.nav-menu #year option:eq(1)').attr('value'),
                    region: $('.nav-menu #region').val() || $('.nav-menu #region option:eq(1)').attr('value'),
                    province: $('.nav-menu #province').val() || $('.nav-menu #province option:eq(1)').attr('value'),
                    menu: 0,
                    page: 1,
                    keyword: '',
                    rpp: $('.search-table').attr('data-toggle-rpp') || 5
                });
            }
        });

        $(document).on('change', '.nav-menu #province', function(e) {
            e.preventDefault();

            $('.search-detail-table thead tr').attr('data-menu', 0);
            $('#FilterKeySearch').val('');

            getTableAll({
                fn: 'gettable',
                job: 5,
                year: $('.nav-menu #year').val() || $('.nav-menu #year option:eq(1)').attr('value'),
                region: $('.nav-menu #region').val() || $('.nav-menu #region option:eq(1)').attr('value'),
                province: $('.nav-menu #province').val() || $('.nav-menu #province option:eq(1)').attr('value'),
                menu: 0,
                page: 1,
                keyword: '',
                rpp: $('.search-table').attr('data-toggle-rpp') || 5
            });
        });

        $(document).on('click touchstart', '.search-detail-table tbody tr:not(.search-detail-total)', function(e) {
            e.preventDefault();

            $(this).closest('tbody').find('tr').removeClass('active-row');
            $(this).addClass('active-row');

            $('.search-detail-table thead tr').attr('data-menu', $(this)[0].rowIndex);

            getTable({
                fn: 'gettable',
                job: 5,
                year: $('.nav-menu #year').val() || $('.nav-menu #year option:eq(1)').attr('value'),
                region: $('.nav-menu #region').val() || $('.nav-menu #region option:eq(1)').attr('value'),
                province: $('.nav-menu #province').val() || $('.nav-menu #province option:eq(1)').attr('value'),
                menu: $(this)[0].rowIndex || 0,
                page: 1,
                keyword: $('#FilterKeySearch').val() || '',
                rpp: $('.search-table').attr('data-toggle-rpp') || 5
            });

            $('#label-popup').popover('destroy');
        });

        $(document).on('click', '.search-table tbody tr', function(e) {
            e.preventDefault();

            $(this).closest('tbody').find('tr').removeClass('active-row');
            $(this).addClass('active-row');

            lat = parseFloat($(this).attr('data-lat')) || 0;
            lon = parseFloat($(this).attr('data-lon')) || 0;
            
            if((lat != 0) && (lon != 0)) {
                e_set_factory_location(ol, map, lat, lon, marker_geom, 16, true);

                marker_style = new ol.style.Style({
                    image: new ol.style.Icon(({
                        anchor: [0.5, 1.6],
                        opacity: 1,
                        scale: 0.5,
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

            $('#label-popup').popover('destroy');
        });
        
        $(document).on('keyup', '#FilterKeySearch', function(e) {
            e.preventDefault();

            if($(this).val() == '') {
                getTable({
                    fn: 'gettable',
                    job: 5,
                    year: $('.nav-menu #year').val() || $('.nav-menu #year option:eq(1)').attr('value'),
                    region: $('.nav-menu #region').val() || $('.nav-menu #region option:eq(1)').attr('value'),
                    province: $('.nav-menu #province').val() || $('.nav-menu #province option:eq(1)').attr('value'),
                    menu: $('.search-detail-table thead tr').attr('data-menu') || 0,
                    page: 1,
                    keyword: $('#FilterKeySearch').val() || '',
                    rpp: $('.search-table').attr('data-toggle-rpp') || 5
                });
            }
        });

        $(document).on('click', '.set-pagination', function(e) {
            e.preventDefault();

            getTable({
                fn: 'gettable',
                job: 5,
                year: $('.nav-menu #year').val() || $('.nav-menu #year option:eq(1)').attr('value'),
                region: $('.nav-menu #region').val() || $('.nav-menu #region option:eq(1)').attr('value'),
                province: $('.nav-menu #province').val() || $('.nav-menu #province option:eq(1)').attr('value'),
                menu: $('.search-detail-table thead tr').attr('data-menu') || 0,
                page: $(this).attr('data-page') || 1,
                keyword: $('#FilterKeySearch').val() || '',
                rpp: $('.search-table').attr('data-toggle-rpp') || 5
            });
        });

        $(document).on('keyup', '.page-go-to', function(e) {
            e.preventDefault();

            var regex = /[^\d\,]/;
            var numPage = Number($('ul.pagination').attr('data-num-page')) || 0;

            if((regex.test($(this).val())) || ($(this).val() > numPage))
                $(this).val('');
                
            if(($(this).val() != '') && (e.which == 13)) {
                getTable({
                    fn: 'gettable',
                    job: 5,
                    year: $('.nav-menu #year').val() || $('.nav-menu #year option:eq(1)').attr('value'),
                    region: $('.nav-menu #region').val() || $('.nav-menu #region option:eq(1)').attr('value'),
                    province: $('.nav-menu #province').val() || $('.nav-menu #province option:eq(1)').attr('value'),
                    menu: $('.search-detail-table thead tr').attr('data-menu') || 0,
                    page: ($(this).val()).replace(',', '') || 1,
                    keyword: $('#FilterKeySearch').val() || '',
                    rpp: $('.search-table').attr('data-toggle-rpp') || 5
                });
            }
        });

        $(document).on('click', '.show-image', function(e) {
            e.preventDefault();
            
            if($(this).find('img').attr('src') != '') {
                Factory.prototype.utilityService.getPopup({
                    infoMsg: '<div class="text-center"><img src="'+ $(this).find('img').attr('src') +'" style="height: 60vh;"></div>',
                    btnMsg: 'ปิด'
                });
            } else {
                Factory.prototype.utilityService.getPopup({
                    infoMsg: 'ไม่พบรูปภาพ',
                    btnMsg: 'ปิด'
                });
            }
        });

        $(document).on('click', '.show-link', function(e) {
            e.preventDefault();
            
            Factory.prototype.utilityService.getPopup({
                infoMsg: 'ไม่พบข้อมูล',
                btnMsg: 'ปิด'
            });
        });

        $(document).on('click', '.export-file', function(e) {
            e.preventDefault();

            var exportType = $(this).text() || '';
            var typeParam = '';

            if(exportType != '') {
                switch(exportType) {
                    case 'PDF':
                        typeParam = 'pdf';

                        break;
                    case 'Word':
                        typeParam = 'word';

                        break;
                }
            }

            if($('.search-table').attr('data-toggle-status') != 'default') {
                $('.search-table').attr('data-toggle-status', 'default');
                $('.search-table').attr('data-toggle-rpp', 5);

                $('.section-top').show();
                $('.get-map .panel').css({ 'height': '40vh' });
                $('.get-map .map').css({ 'height': '40vh' });
                $('.search-table tbody, .pagination').show();
                $('.section-bottom .table-responsive').css({ 'height': '67vh', 'overflow': 'auto'  });
                $('.section-bottom').parent().css({ 'margin-top': '10px' });
                $('.section-bottom .panel').css({ 'height': '36vh' });
                $('.section-bottom .table-responsive').css({ 'height': '26vh' });

                map.updateSize();
                
                $('.search-table thead th, ' +
                    '.search-table tbody tr, ' +
                    '.pagination div').remove();
                
                params = {
                    fn: 'gettable',
                    job: 5,
                    year: $('.nav-menu #year option:eq(1)').attr('value'),
                    region: $('.nav-menu #region option:eq(1)').attr('value') || 0,
                    province: $('.nav-menu #province option:eq(1)').attr('value') || 0,
                    menu: 0,
                    page: 1,
                    keyword: $('#FilterKeySearch').val() || '',
                    rpp: $('.search-table').attr('data-toggle-rpp') || 5
                };
                
                factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                    if(res != undefined) {
                        var data = JSON.parse(res);

                        zoomMapByArea();

                        var theadContent = '';
                        $.each(data.label, function(index, item) {
                            theadContent += '<th class="text-center text-nowrap bg-primary">' +
                                    '<div class="checkbox checkbox-success" style="margin: 0 auto;">' +
                                        '<input id="'+ item +'" type="checkbox" class="select-export" checked="checked"><label for="'+ item +'" style="font-weight: bold;">'+ item +'</label>' +
                                    '</div>' +
                                '</th>';
                        });
                        $('.search-table thead tr').append(theadContent);
                        
                        if(data.data.length != 0) {
                            var tbodyContent = '';
                            var alignContent = 0;
                            var index = 0;
                            
                            $.each(data.latlong, function(latlongIndex, latlongItem) {
                                if(data.latlong.length != 0)
                                    tbodyContent = '<tr data-id="'+ data.data[index].id +'" data-lat="'+ latlongItem.Lat +'" data-lon="'+ latlongItem.Long +'">';
                                else
                                    tbodyContent = '<tr data-id="'+ data.data[index].id +'" data-lat="0" data-lon="0">';

                                for(var j=1; j<=data.label.length; j++) {
                                    tdAlign = ({
                                        '0': 'text-left',
                                        '1': 'text-right',
                                        '2': 'text-center',
                                        '3': 'text-center',
                                        '4': 'text-center'
                                    })[data.data[index].align];
                                    
                                    if(data.data[index].align == 3)
                                        tbodyContent += '<td class="'+ tdAlign +' text-nowrap"><a href="#" title="คลิกเพื่อดูรูป" class="show-image"><img src="'+ ((data.data[index].text != '') ? data.data[index].text : 'img/noimages.png') +'" style="width: 50px; height: 50px;"></a></td>';
                                    else if(data.data[index].align == 4)
                                        tbodyContent += '<td class="'+ tdAlign +' text-nowrap"><a href="'+ data.data[index].text +'" class="show-link">ดูเพิ่มเติม</a></td>';
                                    else
                                        tbodyContent += '<td class="'+ tdAlign +' text-nowrap">'+ data.data[index].text +'</td>';

                                    index += 1;
                                }

                                tbodyContent += '</tr>';
                                $('.search-table tbody').append(tbodyContent);
                            });

                            getPagination({
                                page: data.cur_page || 1,
                                perPage: data.row_per_page || 5,
                                splitPage: 3,
                                total: data.sum_of_row|| 0
                            });
                        } else 
                            $('.search-table tbody').append('<tr class="disabled"><td colspan="'+ data.label.length +'" style="text-align: center;">ไม่พบข้อมูล</td></tr>');

                        if(typeParam != '') {
                            factory.dataService.exportFile('search', {
                                menu: 'ค้นหาข้อมูลโรงงาน',
                                type: typeParam
                            });
                        }
                    }
                });
            } else {
                if(typeParam != '') {
                    factory.dataService.exportFile('search', {
                        menu: 'ค้นหาข้อมูลโรงงาน',
                        type: typeParam
                    });
                }
            }
        });

        $('#FilterKeySearch').autocomplete({ 
            source: function(req, res) {
                params = {
                    fn: 'autocomplete', 
                    src: 5, 
                    year: $('.nav-menu #year').val() || $('.nav-menu #year option:eq(1)').attr('value'),
                    value: req.term || '',
                    menu: $('.search-detail-table thead tr').attr('data-menu') || 0
                };

                $.post(ajaxUrl, params, res, 'json');
            },
            minLength: 1,
            select: function(e, ui) { 
                e.preventDefault();

                $(this).val(ui.item.value);

                getTable({
                    fn: 'gettable',
                    job: 5,
                    year: $('.nav-menu #year').val() || $('.nav-menu #year option:eq(1)').attr('value'),
                    region: $('.nav-menu #region').val() || $('.nav-menu #region option:eq(1)').attr('value'),
                    province: $('.nav-menu #province').val() || $('.nav-menu #province option:eq(1)').attr('value'),
                    menu: $('.search-detail-table thead tr').attr('data-menu') || 0,
                    page: 1,
                    keyword: ui.item.value || ''
                });
            }
        });

        $(document).on('click', '.search-table thead', function(e) {
            e.preventDefault();

            switch($('.search-table').attr('data-toggle-status')) {
                case 'default':
                    $('.search-table').attr('data-toggle-status', 'bottom');
                    $('.search-table').attr('data-toggle-rpp', 5);

                    $('.get-map .panel').css({ 'height': '70vh' });
                    $('.get-map .map').css({ 'height': '70vh' });
                    $('.section-bottom .panel').css({ 'height': '0' });
                    $('.section-bottom .table-responsive').css({ 'height': '5vh',  'overflow': 'hidden' });
                    $('.search-table tbody, .pagination').hide();

                    map.updateSize();

                    break;
                case 'bottom':
                    $('.search-table').attr('data-toggle-status', 'top');
                    $('.search-table').attr('data-toggle-rpp', 15);
    
                    $('.section-top').hide();
                    $('.search-table tbody, .pagination').show();
                    $('.section-bottom').parent().css({ 'margin-top': '0' });
                    $('.section-bottom .panel').css({ 'height': '77vh' });
                    $('.section-bottom .table-responsive').css({ 'height': '67vh', 'overflow': 'auto'  });

                    getTable();

                    break;
                case 'top':
                    $('.search-table').attr('data-toggle-status', 'default');
                    $('.search-table').attr('data-toggle-rpp', 5);

                    $('.section-top').show();
                    $('.get-map .panel').css({ 'height': '40vh' });
                    $('.get-map .map').css({ 'height': '40vh' });
                    $('.section-bottom').parent().css({ 'margin-top': '10px' });
                    $('.section-bottom .panel').css({ 'height': '36vh' });
                    $('.section-bottom .table-responsive').css({ 'height': '26vh' });

                    getTable();
                    map.updateSize();

                    break;
            }
        });
    });
</script>
<?php require('popup.php'); ?>
<?php require('footer.php'); ?>