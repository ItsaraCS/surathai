<?php require('header.php'); ?>
<?php require('nav.php'); ?>
<!--SECTION-->
<div class="section" style="margin-top: 10px;">
    <!--FORM DATA-->
    <div class="col-md-5">
        <form name="insertForm" novalidate>
            <div class="panel panel-default" style="border-radius: 0;">
                <div class="panel-body">
                    <table class="table table-striped" style="margin-top: 0; margin-bottom: 0;">
                        <tbody>
                            <tr>
                                <td class="col-md-5"><p class="data-important">โรงงาน</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="FactoryName" name="FactoryName"
                                        data-factory-id="0"
                                        placeholder="ค้นหาชื่อโรงงาน"
                                        required>
                                    <span class="error-content hide" data-label="โรงงาน"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">วันที่</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="Date" disabled>
                                    <span class="error-content hide" data-label="วันที่"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">เลขแสตมป์เริ่มต้น</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="StartStampNumber" 
                                        data-stamp-remain="0"
                                        maxlength="12"
                                        placeholder="ค้นหาเลขแสตมป์เริ่มต้น..."
                                        required>
                                    <span class="error-content hide" data-label="เลขแสตมป์เริ่ม"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">เลขแสตมป์สิ้นสุด</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="EndStampNumber" maxlength="12" required>
                                    <span class="error-content hide" data-label="เลขสิ้นสุด"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-5"><p class="data-important">จำนวนดวง</p></td>
                                <td class="col-md-7">
                                    <input type="text" class="form-control input-sm" id="CountStamp" disabled required numbered>
                                    <span class="error-content hide" data-label="จำนวนดวง"></span>
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
        <div class="panel panel-default" style="height: 74vh; border-radius: 0; padding: 0;">
            <div class="panel-heading text-center header-table">
                <h3>รายการข้อมูล</h3>
            </div>
            <div class="panel-body" style="padding: 0;">
                <div class="table-responsive" style="height: 66vh;">
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
        var stampType = $('.nav .nav-menu').find('a[href="e_stamp.php"]').data('stamp-type');

        //--Page load
        setInit();
        
        //--Function
        function setInit() {
            if(stampType == 1) {
                $('form[name="insertForm"] tbody tr:eq(2) td:eq(0) p').html('เลขแสตมป์');
                $('form[name="insertForm"] tbody tr:eq(2) td:eq(1) input').attr('placeholder', 'ค้นหาเลขแสตมป์...');
                $('form[name="insertForm"] tbody tr:eq(3)').addClass('hide');
                $('#CountStamp').prop('disabled', false);
            }

            $('input, select, textarea').val('');
            $('#Date').val(factory.dataService.getCurrentDateTH('short'));
            getTable();
        }

        function getTable() {
            $('.eform-table thead th, ' +
                '.eform-table tbody tr').remove();
                
            params = {
                fn: 'gettable',
                job: 3
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

        $(document).on('keyup', '#StartStampNumber', function(e) {
            $('#EndStampNumber, #CountStamp').val('');
        });

        $(document).on('keyup', '#EndStampNumber', function(e) {
            var startStampNumber = $('#StartStampNumber').val();
            var endStampNumber = $(this).val();
            $('#CountStamp').val(0);

            if(endStampNumber.length == 12) {
                params = {
                    fn: 'getdata',
                    data: 3,
                    id: (startStampNumber +'-'+ endStampNumber)
                };

                factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                    if(res != undefined){
                        var data = JSON.parse(res);

                        $('#CountStamp').val(data);
                    }
                });
            }
        });

        $(document).on('keyup blur focus', '#CountStamp', function(e) {
            var countStamp = Number($('#CountStamp').val());
            var stampRemain = Number($('#StartStampNumber').attr('data-stamp-remain'));
            
            if(countStamp > 99) {
                factory.initService.setError($(this), 'กรุณาเลือกซื้อแบบเต็มเล่ม');
                $(this).val('');
            }

            if(countStamp > stampRemain) {
                factory.initService.setError($(this), 'จำนวนแสตมป์ไม่เพียงพอ');
                $(this).val('');
            }

            if(countStamp == 0) 
                $('#insertBtn').prop('disabled', true);
            else
                $('#insertBtn').prop('disabled', false);
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
                var insertData = {};
                if(stampType == 0){
                    insertData = {
                        FactoryName: $('#FactoryName').data('factory-id') || 0,
                        StartStampNumber: $('#StartStampNumber').val() || 0,
                        EndStampNumber: $('#EndStampNumber').val() || 0,
                        CountStamp: $('#CountStamp').val() || 0
                    };
                } else {
                    insertData = {
                        FactoryName: $('#FactoryName').data('factory-id') || 0,
                        StartStampNumber: $('#StartStampNumber').val() || 0,
                        CountStamp: $('#CountStamp').val() || 0
                    };
                }

                params = {
                    fn: 'submit',
                    data: 3,
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
            $('#Date').val(factory.dataService.getCurrentDateTH('short'));
            $('#insertBtn').prop('disabled', false);
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

                $('#FactoryName').val(ui.item.value);
                $('#FactoryName').attr('data-factory-id', ui.item.id);
            }
        });

        $('#StartStampNumber').autocomplete({ 
            source: function(req, res) {
                params = {
                    fn: 'autocomplete', 
                    src: ((stampType == 0) ? 4 : 3), 
                    value: req.term || ''
                };
                
                $.post(ajaxUrl, params, res, 'json');
            },
            minLength: 11,
            select: function(e, ui) { 
                e.preventDefault();
                
                factory.initService.setError($('input[id="EndStampNumber"], input[id="CountStamp"]'), 'clear');
                
                if(stampType == 0) {
                    $('#StartStampNumber, #EndStampNumber').val(ui.item.id);
                    $('#EndStampNumber').focus();

                    params = {
                        fn: 'getdata',
                        data: 3,
                        id: (ui.item.id +'-'+ ui.item.id)
                    };

                    factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                        if(res != undefined){
                            var data = JSON.parse(res);
                            
                            $('#CountStamp').val(data);
                        }
                    });
                } else {
                    $('#StartStampNumber').val(ui.item.id);
                    
                    params = {
                        fn: 'autocomplete',
                        src: 3,
                        value: $(this).val()
                    };
                    factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                        if(res != undefined){
                            var data = JSON.parse(res);
                            
                            var stampRemain = ((data[0].label).split(' ')[1]).replace(/^\(/g, '');
                            stampRemain = Number(stampRemain.replace(/\)$/g, ''));

                            if(stampRemain > 99) 
                                stampRemain -= 1;
                            
                            $('#StartStampNumber').attr('data-stamp-remain', stampRemain);
                            $('#CountStamp').val(stampRemain).focus();
                        }
                    });
                }
            }
        });
    });
</script>
<?php require('popup.php'); ?>
<?php require('footer.php'); ?>          