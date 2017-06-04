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
                            <thead><tr></tr></thead>
                            <tbody></tbody>
                        </table>
                        <table class="table" style="margin-top: 0; margin-bottom: 0;">
                            <tbody>
                                <tr>
                                    <td class="col-md-12" colspan="6" style="padding: 10px !important;">
                                        <input class="form-control input-sm" id="StampNumber" placeholder="ค้นหาเลขแสตมป์">
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
                    <div class="table-responsive" style="height: 18vh;">
                        <table class="table table-striped table-bordered search-table" style="margin-top: 0;"> 
                            <thead><tr></tr></thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="col-md-12 pagination" style="padding: 0;"></div>
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
        var ajaxUrl = 'http://210.4.143.51/Surathai01/API/searchAPI.php';
        var params = {};
        var year = $('.nav-menu #year').val() || '';
        var region = $('.nav-menu #region').val() || 0;
        var province = $('.nav-menu #province').val() || 0;
        var lat = $(this).attr('data-lat') || 0;
        var lon = $(this).attr('data-lon') || 0;

        //--Page load
        getInit();

        //--Function
        function getInit() {
            params = {
                fn: 'filter',
                job: 4,
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

        function getTable(params) {
            $('.search-table thead th, ' +
                '.search-table tbody tr, ' +
                '.pagination div').remove();
            
            if(params == undefined) {
                year = $('.nav-menu #year option:eq(1)').attr('value');
                keyword = $('#StampNumber').val() || '';

                params = {
                    fn: 'gettable',
                    job: 4,
                    year: year,
                    region: 0,
                    province: 0,
                    menu: 0,
                    page: 1,
                    keyword: keyword
                };
            }
            
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
                            tbodyContent = '<tr data-id="'+ data.data[index].id +'">';

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
                            $('.search-table tbody').append(tbodyContent);
                        }

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
                year = $('.nav-menu #year option:eq(1)').attr('value');
                keyword = $('#StampNumber').val() || '';

                params = {
                    fn: 'gettable',
                    job: 4,
                    year: year,
                    region: 0,
                    province: 0,
                    menu: 0,
                    page: 1,
                    keyword: keyword
                };
            }
            
            factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                if(res != undefined) {
                    var data = JSON.parse(res);

                    var searchDetailTableContent = '';
                    $.each(data.menu, function(index, item) {
                        if(index == 0) {
                            searchDetailTableContent += '<th class="text-center text-nowrap">'+ item.subject +'</th>';
                                $.each(item.value, function(indexValue, itemValue) {
                                    searchDetailTableContent += '<th class="text-center text-nowrap">'+ itemValue +'</th>';
                                });
                            $('.search-detail-table thead tr').append(searchDetailTableContent);
                            searchDetailTableContent = '';
                        }
                        
                        if(index == (data.menu.length - 1)) {
                            searchDetailTableContent += '<tr class="search-detail-total">' +
                                    '<td class="text-center"><p>'+ item.subject +'</p></td>';
                                    $.each(item.value, function(indexValue, itemValue) {
                                        searchDetailTableContent += '<td class="text-center">'+ itemValue +'</td>';
                                    });
                                searchDetailTableContent += '</tr>';
                        } 
                        
                        if((index != 0) && (index != (data.menu.length - 1))) {
                            searchDetailTableContent += '<tr>' +
                                    '<td><p>'+ item.subject +'</p></td>';
                                    $.each(item.value, function(indexValue, itemValue) {
                                        searchDetailTableContent += '<td class="text-center">'+ itemValue +'</td>';
                                    });
                                searchDetailTableContent += '</tr>';
                        }
                    });
                    $('.search-detail-table tbody').append(searchDetailTableContent);

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
                            tbodyContent = '<tr data-id="'+ data.data[index].id +'">';

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
                            $('.search-table tbody').append(tbodyContent);
                        }

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
                    perPage: 5,
                    splitPage: 3,
                    total: 0
                };
            }

            factory.connectDBService.sendJSONStr('API/paginator.php', params).done(function(res) {
                if(res != undefined){
                    $('.pagination').append(res);
                }
            });
        }

        //--Event
        $(document).on('change', '.nav-menu #year', function(e) {
            e.preventDefault();
            
            $('.nav-menu #region').find('option:eq(0)').prop('selected', true);
            $('.nav-menu #province option[value!=""]').remove();
            
            year = $('.nav-menu #year').val() || 0;

            if(year != '') {
                $('.nav-menu #region').find('option:eq(1)').prop('selected', true);
                region = $('.nav-menu #region').val() || '';
                
                if(region != '') {
                    params = {
                        fn: 'filter',
                        job: 4,
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

            $('.search-detail-table thead tr').attr('data-menu', 0);
            $('#StampNumber').val('');

            year = $('.nav-menu #year').val() || $('.nav-menu #year option:eq(1)').attr('value');
            region = $('.nav-menu #region').val() || 0;
            province = $('.nav-menu #province').val() || 0;
            menu = 0;
            page = 1;
            keyword = '';

            getTableAll({
                fn: 'gettable',
                job: 4,
                year: year,
                region: region,
                province: province,
                menu: menu,
                page: page,
                keyword: keyword
            });
        });

        $(document).on('change', '.nav-menu #region', function(e) {
            e.preventDefault();
            
            $('.nav-menu #province').find('option[value!=""]').remove();

            region = $('.nav-menu #region').val() || 0;
            
            if(region != '') {
                params = {
                    fn: 'filter',
                    job: 4,
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

            $('.search-detail-table thead tr').attr('data-menu', 0);
            $('#StampNumber').val('');

            year = $('.nav-menu #year').val() || $('.nav-menu #year option:eq(1)').attr('value');
            region = $('.nav-menu #region').val() || 0;
            province = $('.nav-menu #province').val() || 0;
            menu = 0;
            page = 1;
            keyword = '';

            getTableAll({
                fn: 'gettable',
                job: 4,
                year: year,
                region: region,
                province: province,
                menu: menu,
                page: page,
                keyword: keyword
            });
        });

        $(document).on('change', '.nav-menu #province', function(e) {
            e.preventDefault();

            $('.search-detail-table thead tr').attr('data-menu', 0);
            $('#StampNumber').val('');

            year = $('.nav-menu #year').val() || $('.nav-menu #year option:eq(1)').attr('value');
            region = $('.nav-menu #region').val() || 0;
            province = $('.nav-menu #province').val() || 0;
            menu = 0;
            page = 1;
            keyword = '';

            getTableAll({
                fn: 'gettable',
                job: 4,
                year: year,
                region: region,
                province: province,
                menu: menu,
                page: page,
                keyword: keyword
            });
        });

        $(document).on('click', '.search-detail-table tbody tr:not(.search-detail-total)', function(e) {
            e.preventDefault();

            $(this).closest('tbody').find('tr').removeClass('active-row');
            $(this).addClass('active-row');

            $('.search-detail-table thead tr').attr('data-menu', $(this)[0].rowIndex);

            year = $('.nav-menu #year').val() || $('.nav-menu #year option:eq(1)').attr('value');
            region = $('.nav-menu #region').val() || 0;
            province = $('.nav-menu #province').val() || 0;
            menu = $(this)[0].rowIndex || 0;
            page = 1;
            keyword = $('#StampNumber').val() || '';

            getTable({
                fn: 'gettable',
                job: 4,
                year: year,
                region: region,
                province: province,
                menu: menu,
                page: page,
                keyword: keyword
            });
        });

        $(document).on('click', '.search-table tbody tr', function(e) {
            e.preventDefault();

            $(this).closest('tbody').find('tr').removeClass('active-row');
            $(this).addClass('active-row');

            params = {
                fn: 'getgeo',
                job: 4,
                id: $(this).attr('data-id') || 0
            };

            factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                if(res != undefined){
                    var data = JSON.parse(res);
                    console.log(data);
                }
            });

            Factory.prototype.utilityService.getPopup({
                infoMsg: 'รอแผนที่ทำเสร็จก่อน, ข้อมูลที่คุณเลือกคือ : '+ $(this).find('td:eq(0)').html(),
                btnMsg: 'ปิด'
            });
        });
        
        $(document).on('keyup', '#StampNumber', function(e) {
            e.preventDefault();

            if($(this).val() == '') {
                year = $('.nav-menu #year').val() || $('.nav-menu #year option:eq(1)').attr('value');
                region = $('.nav-menu #region').val() || 0;
                province = $('.nav-menu #province').val() || 0;
                menu = $('.search-detail-table thead tr').attr('data-menu') || 0;
                page = 1;
                keyword = $('#StampNumber').val() || '';

                getTable({
                    fn: 'gettable',
                    job: 4,
                    year: year,
                    region: region,
                    province: province,
                    menu: menu,
                    page: page,
                    keyword: keyword
                });
            }
        });

        $(document).on('click', '.set-pagination', function(e) {
            e.preventDefault();

            year = $('.nav-menu #year').val() || $('.nav-menu #year option:eq(1)').attr('value');
            region = $('.nav-menu #region').val() || 0;
            province = $('.nav-menu #province').val() || 0;
            menu = $('.search-detail-table thead tr').attr('data-menu') || 0;
            page = $(this).attr('data-page') || 1;
            keyword = $('#StampNumber').val() || '';

            getTable({
                fn: 'gettable',
                job: 4,
                year: year,
                region: region,
                province: province,
                menu: menu,
                page: page,
                keyword: keyword
            });
        });

        $(document).on('keyup', '.page-go-to', function(e) {
            e.preventDefault();

            var regex = /[^\d\,]/;

            if(regex.test($(this).val()))
                $(this).val('');
                
            if(($(this).val() != '') && (e.which == 13)) {
                year = $('.nav-menu #year').val() || $('.nav-menu #year option:eq(1)').attr('value');
                region = $('.nav-menu #region').val() || 0;
                province = $('.nav-menu #province').val() || 0;
                menu = $('.search-detail-table thead tr').attr('data-menu') || 0;
                page = ($(this).val()).replace(',', '') || 1;
                keyword = $('#StampNumber').val() || '';

                getTable({
                    fn: 'gettable',
                    job: 4,
                    year: year,
                    region: region,
                    province: province,
                    menu: menu,
                    page: page,
                    keyword: keyword
                });
            }
        });

        $(document).on('click', '.show-image', function(e) {
            e.preventDefault();
            
            Factory.prototype.utilityService.getPopup({
                infoMsg: '<img src="'+ $(this).find('img').attr('src') +'" style="width: 100%;">',
                btnMsg: 'ปิด'
            });
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

            $('.search-table').tableExport({
                type: 'pdf',
                escape: true,
                htmlContent: true
            });
        });

        $('#StampNumber').autocomplete({ 
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

                year = $('.nav-menu #year').val() || $('.nav-menu #year option:eq(1)').attr('value');
                region = $('.nav-menu #region').val() || 0;
                province = $('.nav-menu #province').val() || 0;
                menu = $('.search-detail-table thead tr').attr('data-menu') || 0;
                page = 1;
                keyword = ui.item.value || '';

                getTable({
                    fn: 'gettable',
                    job: 4,
                    year: year,
                    region: region,
                    province: province,
                    menu: menu,
                    page: page,
                    keyword: keyword
                });
            }
        });
    });
</script>
<?php require('popup.php'); ?>
<?php require('footer.php'); ?>