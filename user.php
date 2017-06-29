<?php require('header.php'); ?>
<!--SECTION-->
<div class="section" style="margin-top: 10px;">
    <div class="col-md-6 user-form">
        <div class="panel panel-info">
            <div class="panel-heading">
                <p style="display: inline-block; font-weight: bold; font-size: 25px;" class="panel-title"><i class="fa fa-user text-right-indent"></i> จัดการข้อมูลผู้ใช้</p> 
                <a class="btn btn-sm btn-success" id="addUserBtn" style="float: right; font-size: 19px;">เพิ่มผู้ใช้</a>
            </div>
            <div class="panel-body">
                <div class="row">
                    <form name="userForm" novalidate>
                        <div class="col-md-3 text-center"> 
                            <img class="img-circle img-responsive" id="ProfileImage"> 
                            <p style="margin-top: 15px; font-size: 20px;"><span id="FullnameTXT"></sapn></p>
                        </div>
                        <div class="col-md-9 table-responsive panel-body-user"> 
                        <h3 class="report-mobile" style="background-color: #31708f; border-radius: 10px; color: white;" >ฟอร์มข้อมูลผู้ใช้</h3>
                            <table class="table table-striped" style="overflow-y: scroll;">
                                <tbody>
                                    <tr>
                                        <td><p class="data-important">ชื่อผู้ใช้งาน</p></td>
                                        <td>
                                            <input type="text" class="form-control" id="Fullname" disabled required>
                                            <span class="error-content hide" data-label="ชื่อผู้ใช้งาน"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><p class="data-important">เพศ</p></td>
                                        <td>
                                            <select class="form-control" id="Gender" disabled required>
                                                <option value="" selected disabled>เลือกเพศ</option>
                                                <option value="0">หญิง</option>
                                                <option value="1">ชาย</option>
                                            </select>
                                            <span class="error-content hide" data-label="เพศ"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><p class="data-important">ตำแหน่ง</p></td>
                                        <td>
                                            <select class="form-control" id="Level" disabled required>
                                                <option value="" selected disabled>เลือกตำแหน่ง</option>
                                                <option value="1">ผู้จัดการ</option>
                                                <option value="2">เจ้าหน้าที่</option>
                                            </select>
                                            <span class="error-content hide" data-label="ตำแหน่ง"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><p>สังกัด</p></td>
                                        <td>
                                            <input type="text" class="form-control" id="UnderTXT" disabled>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><p>อีเมล์</p></td>
                                        <td>
                                            <input type="text" class="form-control" id="Email" disabled email-only>
                                            <span class="error-content hide" data-label="อีเมล์"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><p>เบอร์โทรศัพท์</p></td>
                                        <td>
                                            <input type="text" class="form-control" id="Tel" disabled>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><p>เบอร์มือถือ</p></td>
                                        <td>
                                            <input type="text" class="form-control" id="Mobile" disabled>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><p>รหัสผ่านปัจจุบัน</p></td>
                                        <td>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="Password" disabled>
                                                <span class="input-group-addon" style="padding: 0;">
                                                    <button type="button" class="btn btn-info" id="changePasswordBtn" title="คลิกเพื่อแก้ไข Password">แก้ไข</button>
                                                </span>
                                            </div>
                                            <span class="error-content hide" data-label="ชื่อผู้ใช้งาน"></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>  
                        </div>
                    </form>
                </div>
            </div>
            <div class="panel-footer">
                <div style="text-align: right;">
                    <a class="btn btn-sm btn-primary" id="insertBtn">บันทึก</a>
                    <a class="btn btn-sm btn-warning" id="updateBtn">แก้ไข</a>
                    <a class="btn btn-sm btn-danger" id="resetBtn">ยกเลิก</a>
                </div>
            </div>
          </div>
        </div>
    </div>
    <div class="col-md-6 search-user" style="height: 70vh;">
       <div class="panel panel-info">
           <div class="panel-heading">
               <p style="font-weight: bold; font-size: 25px;" class="panel-title"><i class="fa fa-search text-right-indent"></i> ค้นหาข้อมูลพนักงาน</p>
           </div>
           <div class="panel-body">
                <div class="form-group">
                    <input type="text" class="form-control" id="searchUser" placeholder="ค้นหาชื่อพนักงาน">
                </div>
                <div class="table-responsive" style="height: 50vh;">
                    <table class="table table-striped table-bordered search-table" data-id="0" style="margin-top: 0; overflow-y: scroll;"> 
                        <tbody></tbody>
                    </table>
                </div>
                <div class="col-md-12 pagination" style="height: 6vh; margin: 10px 0 0 0; padding: 0;"></div>
           </div>
       </div>
    </div>
</div>
<!--JS-->
<script type="text/javascript">
    $(document).ready(function(e) {
        //--Variable
        var factory = new Factory();
        var ajaxUrl = 'API/userAPI.php';
        var params = {};
        var userDataOrigin;

        //--Page load
        getInit();

        //--Function
        function getInit() {
            $('#addUserBtn, #insertBtn').hide();

            factory.connectDBService.sendJSONObj(ajaxUrl, {}).done(function(res) {
                if(res != undefined){
                    var data = JSON.parse(res);
                    userData = data;
                    userDataOrigin = data;

                    switch(userData.Level) {
                        case 0: //--ผู้ดูแลระบบ
                            $('#addUserBtn').show();
                            $('#Fullname, ' +
                                '#Gender, ' +
                                '#Email, ' +
                                '#Tel, ' +
                                '#Mobile').prop('disabled', false);
                            $('#Level, #Password').prop('disabled', true);
                            $('#Level option[value="3"]').remove();
                            $('#Level').append('<option value="0">ผู้ดูแลระบบ</option>').prop('selected', true);
                            break;
                        case 1: //--ผู้จัดการ
                            $('#addUserBtn').show();
                            $('#Fullname, ' +
                            '#Gender, ' +
                            '#Level, ' +
                            '#Email, ' +
                            '#Tel, ' +
                            '#Mobile').prop('disabled', false);
                            $('#Password').prop('disabled', true);
                            $('#Level').find('option[value="0"], option[value="3"]').remove();
                            break;
                        case 2: //--เจ้าหน้าที่
                            $('#addUserBtn').hide();
                            $('#Fullname, ' +
                            '#Gender, ' +
                            '#Level, ' +
                            '#Email, ' +
                            '#Tel, ' +
                            '#Mobile, ' +
                            '#Password').prop('disabled', true);
                            $('#Level').find('option[value="0"], option[value="3"]').remove();
                            break;
                        case 3: //--ผู้บริหาร
                            $('#addUserBtn').hide();
                            $('#Fullname, ' +
                            '#Gender, ' +
                            '#Level, ' +
                            '#Email, ' +
                            '#Tel, ' +
                            '#Mobile, ' +
                            '#Password').prop('disabled', true);
                            $('#Level option[value="0"]').remove();
                            $('#Level').append('<option value="3">ผู้บริหาร</option>').prop('selected', true);
                            break;
                    }

                    $('#ProfileImage').attr('src', ((userData.Gender == 0) ? 'img/user-female.png' : 'img/user-male.png'));
                    $('#FullnameTXT').html(userData.Fullname);
                    $('#Fullname').val(userData.Fullname);
                    $('#Gender').val(userData.Gender);
                    $('#Level').val(userData.Level);
                    switch(userData.Under) {
                        case 0: $('#UnderTXT').val('สรรพสามิต'); break;
                        case 1: $('#UnderTXT').val(userData.RegionTXT); break;
                        case 2: $('#UnderTXT').val(userData.AreaTXT); break;
                        case 3: $('#UnderTXT').val(userData.BranchTXT); break;
                    }
                    $('#Email').val(userData.Email);
                    $('#Tel').val(userData.Tel);
                    $('#Mobile').val(userData.Mobile);
                    $('#searchUser').val('');
                }
            });
        }

        function getTable(params) {
            $('.search-table thead th, ' +
                '.search-table tbody tr, ' +
                '.pagination div').remove();
            
            params = {
                fn: 'gettable',
                page: 1,
                keyword: $('#searchUser').val() || ''
            };
            
            factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                if(res != undefined) {
                    var data = JSON.parse(res);

                    if(data.data.length != 0) {
                        var tbodyContent = '';

                        for(var i=0; i<data.data.length; i++) {
                            tbodyContent += '<tr data-id="'+ data.data[i].id +'">';
                            tbodyContent += '<td class="text-left">'+ data.data[i].text +'</td>';
                            tbodyContent += '</tr>';
                        }
                        $('.search-table tbody').append(tbodyContent);

                        getPagination({
                            page: data.cur_page || 1,
                            perPage: data.row_per_page || 10,
                            splitPage: 3,
                            total: data.sum_of_row || 0
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
                    perPage: 10,
                    splitPage: 3,
                    total: 0
                };
            }

            factory.connectDBService.sendJSONStr('API/paginatorAPI.php', params).done(function(res) {
                if(res != undefined){
                    $('.pagination').append(res);
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

        $(document).on('keyup', 'input[email-only]', function(e) {
            factory.initService.setError($(this), 'email-only'); 
        });

        $(document).on('click', '#changePasswordBtn', function(e) {
            e.preventDefault();

            $('#Password').prop('disabled', false);
        });

        $(document).on('keyup', '#Fullname', function(e) {
            e.preventDefault();

            $('#FullnameTXT').html($(this).val());
        });

        $(document).on('change', '#Gender', function(e) {
            e.preventDefault();

            if($(this).val() == '1')
                $('#ProfileImage').attr('src', 'img/user-male.png');
            else
                $('#ProfileImage').attr('src', 'img/user-female.png');
        });

        $(document).on('click', '#addUserBtn', function(e) {
            e.preventDefault();

            $('#insertBtn').show();
            $('#updateBtn').hide();
            $('input').val('');
            $('select option[value=""]').prop('selected', true);
            $('#changePasswordBtn').prop('disabled', true);
            $('#Password').prop('disabled', false);
            $('#Password').prop('required', true);
            $('#Password').closest('tr').find('td:eq(0) p').append('<span style="font-size: 20px; color: #fe3d3d;"> *</span>');
            $('#ProfileImage').attr('src', 'img/noimages.png');
            $('#FullnameTXT').html('');
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
                params = {
                    fn: 'save',
                    id: 0,
                    Fullname: $('#Fullname').val(),
                    Gender: $('#Gender').val(),
                    Level: $('#Level').val(),
                    Email: $('#Email').val(),
                    Tel: $('#Tel').val(),
                    Mobile: $('#Mobile').val(),
                    Password: $('#Password').val()
                };
            
                factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                    if(res != undefined){
                        var data = JSON.parse(res);
                        
                        Factory.prototype.utilityService.getPopup({
                            infoMsg: data.Message,
                            btnMsg: 'ปิด'
                        });
                    }
                });
            }
        });

        $(document).on('click', '#updateBtn', function(e) {
            e.preventDefault();

            $('#Password').prop('required', false);

            var numError = 0;

            $.each($('form').find('input[required], select[required], textarea[required]'), function(index, item) {
                factory.initService.setError($(this), 'required');

                if($(this).val() == '')
                    numError += 1;
            });

            if(numError == 0) {
                var userDataUpdate = factory.dataService.getDataObjChange(userDataOrigin, {
                    Fullname: $('#Fullname').val(),
                    Gender: $('#Gender').val(),
                    Level: $('#Level').val(),
                    Email: $('#Email').val(),
                    Tel: $('#Tel').val(),
                    Mobile: $('#Mobile').val(),
                    Password: $('#Password').val()
                });

                params.fn = 'save';
                params.id = $('.search-table').attr('data-id');
                $.each(userDataUpdate, function(key, val) {
                    params[key] = val
                });
            
                factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                    if(res != undefined){
                        var data = JSON.parse(res);
                        
                        Factory.prototype.utilityService.getPopup({
                            infoMsg: data.Message,
                            btnMsg: 'ปิด'
                        });
                    }
                });
            }
        });

        $(document).on('click', '#resetBtn', function(e) {
            e.preventDefault();

            factory.initService.setError($('input, select, textarea'), 'clear');

            $('#insertBtn').hide();
            $('#updateBtn').show();
            $('#changePasswordBtn').prop('disabled', false);
            $('#ProfileImage').attr('src', ((userDataOrigin.Gender == 0) ? 'img/user-female.png' : 'img/user-male.png'));
            $('#FullnameTXT').html(userDataOrigin.Fullname);
            $('#Fullname').val(userDataOrigin.Fullname);
            $('#Gender').val(userDataOrigin.Gender);
            $('#Level').val(userDataOrigin.Level);
            switch(userDataOrigin.Under) {
                case 0: $('#UnderTXT').val('สรรพสามิต'); break;
                case 1: $('#UnderTXT').val(userDataOrigin.RegionTXT); break;
                case 2: $('#UnderTXT').val(userDataOrigin.AreaTXT); break;
                case 3: $('#UnderTXT').val(userDataOrigin.BranchTXT); break;
            }
            $('#Email').val(userDataOrigin.Email);
            $('#Tel').val(userDataOrigin.Tel);
            $('#Mobile').val(userDataOrigin.Mobile);

            $('#Password').val('').prop('disabled', true);
            $('#Password').closest('tr').find('td:eq(0) p span').remove();
        });

        $(document).on('click', '.search-table tbody tr', function(e) {
            e.preventDefault();

            $(this).closest('tbody').find('tr').removeClass('active-row');
            $(this).addClass('active-row');
            $('.search-table').attr('data-id', $(this).attr('data-id'));

            params = {
                fn: 'getdata',
                id: $(this).attr('data-id')
            };
        
            factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                if(res != undefined){
                    var data = JSON.parse(res);
                    userDataOrigin = data;
                    userDataOrigin.Password = '';
                    
                    $('#ProfileImage').attr('src', ((data.Gender == 0) ? 'img/user-female.png' : 'img/user-male.png'));
                    $('#FullnameTXT').html(data.Fullname);
                    $('#Fullname').val(data.Fullname);
                    $('#Gender').val(data.Gender);
                    $('#Level').val(data.Level);
                    switch(data.Under) {
                        case 0: $('#UnderTXT').val('สรรพสามิต'); break;
                        case 1: $('#UnderTXT').val(data.RegionTXT); break;
                        case 2: $('#UnderTXT').val(data.AreaTXT); break;
                        case 3: $('#UnderTXT').val(data.BranchTXT); break;
                    }
                    $('#Email').val(data.Email);
                    $('#Tel').val(data.Tel);
                    $('#Mobile').val(data.Mobile);
                }
            });
        });

        $(document).on('keyup', '.page-go-to', function(e) {
            e.preventDefault();

            var regex = /[^\d\,]/;
            var numPage = $('ul.pagination').attr('data-num-page') || 0;

            if((regex.test($(this).val())) || ($(this).val() > numPage))
                $(this).val('');
                
            if(($(this).val() != '') && (e.which == 13)) {
                getTable();
            }
        });

        $('#searchUser').autocomplete({
            source: function(req, res) {
                params = {
                    fn: 'autocomplete',
                    keyword: req.term || ''
                };

                $.post(ajaxUrl, params, res, 'json');
            },
            minLength: 1,
            select: function(e, ui) { 
                e.preventDefault();

                $(this).val(ui.item.value);
                getTable();
            }
        });
    }); 
</script>
<?php require('popup.php'); ?>
<?php require('footer.php'); ?>     