<?php require('header.php'); ?>
<?php require('nav.php'); ?>
<!--SECTION-->
<div class="section hide">
    <!--TABLE REPORT-->
    <div class="col-md-12 table-responsive table-report" style="height: 29vh; margin-bottom: 15px;">
        <table class="table table-striped table-bordered">
            <thead><tr></tr></thead>
            <tbody></tbody>
        </table>
    </div>

    <!--CHART-->
    <div class="col-md-12 my-chart" style="height: 35vh;">
        <div class="col-md-12 text-center">
            <h3 id="chartType" style="margin: 0;">กราฟรายเดือน</h3>
        </div>
        <div class="col-md-12 text-right" style="margin-bottom: 10px;">
            <button type="button" class="btn btn-success btn-sm" id="changeChartBtn" data-chart-type="0"><i class="fa fa-line-chart text-right-indent"></i> <span>เลือกกราฟรายปี</span></button>
        </div>
        <canvas id="myChart"></canvas>
    </div>
</div>
<!--JS-->
<script type="text/javascript">
    $(document).ready(function(e) {
        //--Variable
        var factory = new Factory();
        var ajaxUrl = 'http://210.4.143.51/Surathai01/API/reportAPI.php';
        var params = {};
        var year = $('.nav-menu #year').val() || '';
        var region = $('.nav-menu #region').val() || 0;
        var province = $('.nav-menu #province').val() || 0;
        localStorage.setItem('mode', 0);
        var mode = localStorage.getItem('mode');

        //--Page load
        setInit();

        //--Function
        function setInit() {
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
                    getChart();
                }
            });
        }

        function getTable() {
            $('.section').removeClass('show').addClass('hide');
            $('.table-report thead th, ' +
                '.table-report tbody tr').remove();

            year = $('.nav-menu #year').val() || '';
            region = $('.nav-menu #region').val() || 0;
            province = $('.nav-menu #province').val() || 0;
            mode = localStorage.getItem('mode');

            if(year != '') {
                $('.section').removeClass('hide').addClass('show');

                params = {
                    fn: 'gettable',
                    job: 1,
                    year: year,
                    region: region || 0,
                    province: province || 0,
                    mode: mode || 0
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
                        $('.table-report thead').append(theadContent);
                        
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
                                    tbodyContent += '<td class="'+ tdAlign +'">'+ data.data[index].text +'</td>';
                                    index += 1;
                                }

                                tbodyContent += '</tr>';
                                $('.table-report tbody').append(tbodyContent);
                            }
                        } else 
                            $('.table-report tbody').append('<tr><td colspan="'+ data.label.length +'" style="text-align: center;">ไม่พบข้อมูล</td></tr>');
                    }
                });
            }
        }
        
        function getChart() {
            var chartReportData = {
                monthData: [],
                chartData: [],
                xAxes: 'เดือน',
                yAxes: 'บาท'
            };
            year = $('.nav-menu #year').val() || '';
            region = $('.nav-menu #region').val() || 0;
            province = $('.nav-menu #province').val() || 0;
            mode = localStorage.getItem('mode');

            chartReport(chartReportData);

            if(year != '') {
                params = {
                    fn: 'getgraph',
                    job: 1,
                    year: year,
                    region: region || 0,
                    province: province || 0,
                    mode: mode || 0
                };

                factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                    if(res != undefined) {
                        var data = JSON.parse(res);

                        chartReportData.monthData = data.labels;
                        chartReportData.chartData = data.datasets;
                        chartReportData.xAxes = ((params.mode == 0) ? 'เดือน' : 'ปี');
                        
                        chartReport(chartReportData);
                    }
                });
            }  
        }

        //--Event
        $(document).on('change', '.nav-menu #year', function(e) {
            e.preventDefault();
            
            $('.nav-menu #region').find('option:eq(0)').prop('selected', true);
            $('.nav-menu #province option[value!=""]').remove();
            
            year = $('.nav-menu #year').val() || '';

            if(year != '') {
                $('.nav-menu #region').find('option:eq(1)').prop('selected', true);
                region = $('.nav-menu #region').val() || 0;

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

            localStorage.setItem('mode', 0);
            $('#changeChartBtn').find('span').html('เลือกกราฟรายปี');
            $('#chartType').html('กราฟรายเดือน');

            getTable();
            getChart();
        });

        $(document).on('change', '.nav-menu #region', function(e) {
            e.preventDefault();
            
            $('.nav-menu #province').find('option[value!=""]').remove();

            region = $('.nav-menu #region').val() || 0;
            
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

            localStorage.setItem('mode', 0);
            $('#changeChartBtn').find('span').html('เลือกกราฟรายปี');
            $('#chartType').html('กราฟรายเดือน');

            getTable();
            getChart();
        });

        $(document).on('change', '.nav-menu #province', function(e) {
            e.preventDefault();

            localStorage.setItem('mode', 0);
            $('#changeChartBtn').find('span').html('เลือกกราฟรายปี');
            $('#chartType').html('กราฟรายเดือน');

            getTable();
            getChart();
        });

        $(document).on('click', '.export-file', function(e) {
            e.preventDefault();

            window.open('export/report/reporttax.xlsx', '_blank');
        });

        $(document).on('click', '#changeChartBtn', function(e) {
            e.preventDefault();

            year = $('.nav-menu #year').val() || '';
            
            if(year != '') {
                if(localStorage.getItem('mode') == 0) {
                    localStorage.setItem('mode', 1);
                    $(this).find('span').html('เลือกกราฟรายเดือน');
                    $('#chartType').html('กราฟรายปี (เปรียบเทียบ 5 ปี)');
                } else {
                    localStorage.setItem('mode', 0);
                    $(this).find('span').html('เลือกกราฟรายปี');
                    $('#chartType').html('กราฟรายเดือน');
                }

                getTable();
                getChart();
            }
        });
    });
</script>
<?php require('footer.php'); ?>