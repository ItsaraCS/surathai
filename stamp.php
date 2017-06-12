<?php require('header.php'); ?>
<?php require('nav.php'); ?>
<!--SECTION-->
<div class="section">
	<!--MAP-->
    <div class="container-fluid">
        <div class="row">
            <div id="map" class="map" style="position: fixed; width: 100%; height: 100%;"></div>
            <!--POPUP-->
            <div id="popup" class="ol-popup">
                <a href="#" id="popup-closer" class="ol-popup-closer"></a>
                <div id="popup-content"></div>
            </div>
            
            <!--MAP LEGEND AND LAYERS TOGGLERS-->
            <div id="map_legend"><h3>สัญลักษณ์แผนที่</h3></div>
            <div id="map_layer_toggler_container">
				<div id="map_layer_title">ชั้นข้อมูล</div>
				<div id="map_layer_toggler"></div>
			</div>
        </div>
    </div>

    <!--LOADING-->
    <div id="dvloading" class="loader"><div></div></div>
    
    <!--CHART GRAPH-->
    <div id="chart_container" class="panel">
        <div id="chart_group">
            <div id="chart_title" class="panel text-center" data-toggle="collapse" href="#collapse1">กราฟ</div>
            <div id="collapse1" class="panel-collapse collapse">
                <div id="chart_box">
                    <canvas id="my_chart" height="45px"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<!--MAP LIBRARY-->
<link href="css/popup.css" rel="stylesheet" type="text/css">
<link href="css/map_legend.css" rel="stylesheet" type="text/css">
<link href="css/layer_toggler.css" rel="stylesheet" type="text/css">
<script src="js/mouselib.js" type="text/javascript"></script>
<script src="js/mappopup.js" type="text/javascript"></script>
<script src="js/local_stamp.js" type="text/javascript"></script>
<!--JS-->
<script type="text/javascript">
    $(document).ready(function(e) {
        //--Variable
        var factory = new Factory();
        var ajaxUrl = 'http://210.4.143.51/Surathai01/API/taxmapAPI.php';
        var params = {};
		var year = $('.nav-menu #year').val() || '';
        var region = $('.nav-menu #region').val() || 0;
        var area = $('.nav-menu #area').val() || 0;

        //--Page load
		setInit();

        //--Function
		function setInit() {
            params = {
                fn: 'filter',
                job: 4,
                src: 0
            };

            factory.connectDBService.sendJSONObj(ajaxUrl, params, false).done(function(res) {
                if(res != undefined){
                    var data = JSON.parse(res);

                    $.each(data.year, function(index, item) {
                        $('.nav-menu #year').append('<option value="'+ item.value +'">'+ item.label +'</option>');
                    });

                    $.each(data.region, function(index, item) {
                        $('.nav-menu #region').append('<option value="'+ item.value +'">'+ item.label +'</option>');
                    });
                    
                    $.each(data.province, function(index, item) {
                        $('.nav-menu #area').append('<option value="'+ item.value +'">'+ item.label +'</option>');
                    });

                    $('.nav-menu #year, ' +
                        '.nav-menu #region, ' +
                        '.nav-menu #area').find('option:eq(1)').prop('selected', true);
                }
            });

			on_page_loaded();
        }
        
		//--Event
		$(document).on('change', '.nav-menu #year', function(e) {
            e.preventDefault();
            
            $('.nav-menu #region').find('option:eq(0)').prop('selected', true);
            $('.nav-menu #area option[value!="-999"]').remove();
            
            year = $('.nav-menu #year').val() || '';

            if(year != '') {
                $('.nav-menu #region').find('option:eq(1)').prop('selected', true);
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
                                $('.nav-menu #area').append('<option value="'+ item.value +'">'+ item.label +'</option>');
                            });

                            $('.nav-menu #area').find('option:eq(1)').prop('selected', true);
                        }
                    });
                }
            }
        });
        
        $(document).on('change', '.nav-menu #region', function(e) {
            e.preventDefault();
            
            $('.nav-menu #area').find('option[value!="-999"]').remove();
            region = $('.nav-menu #region').val() || 0;
            
            if(region != '') {
                params = {
                    fn: 'filter',
                    job: 4,
                    src: 1,
                    value: region || 0
                };
                console.log(params);
            
                factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                    if(res != undefined){
                        var data = JSON.parse(res);

                        $.each(data, function(index, item) {
                            $('.nav-menu #area').append('<option value="'+ item.value +'">'+ item.label +'</option>');
                        });

                        $('.nav-menu #area').find('option:eq(1)').prop('selected', true);
                    }
                });
            }
        });

        $(document).on('click', '.export-file', function(e) {
            e.preventDefault();

            window.open('export/map/stamp.pdf', '_blank');
        });
    });
</script>
<?php require('footer.php'); ?>    