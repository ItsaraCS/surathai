function Factory() {
    var self = this;
}
Factory.prototype.initService = {
    setError: function(self, errorType) {
        var regex;

        $(self).closest('div, td').removeClass('has-error');
        $(self).next('.error-content').removeClass('show').addClass('hide');

        switch(errorType) {
            case 'required':
                if($(self).val() == '') {
                    $(self).closest('div, td').addClass('has-error');
                    $(self).closest('td').find('.error-content').removeClass('hide').addClass('show').html('('+ $(self).closest('td').find('.error-content').data('label') +') ห้ามว่าง');
                }

                break;
            case 'duplicate':
                $(self).closest('div, td').addClass('has-error');
                $(self).closest('td').find('.error-content').removeClass('hide').addClass('show').html('('+ $(self).closest('td').find('.error-content').data('label') +') ห้ามซ้ำ');
                break;
            case 'please-field':
                $(self).closest('div, td').addClass('has-error');
                $(self).closest('td').find('.error-content').removeClass('hide').addClass('show').html('กรุณากรอก ('+ $(self).closest('td').find('.error-content').data('label') +') ก่อนค้นหา');
                
                setTimeout(function() {
                    $(self).closest('div, td').removeClass('has-error');
                    $(self).closest('td').find('.error-content').removeClass('show').addClass('hide');
                }, 3000);
                break;
            case 'image-type':
                $(self).closest('div, td').addClass('has-error');
                $(self).closest('td').find('.error-content').removeClass('hide').addClass('show').html('ประเภทไฟล์ ('+ $(self).closest('td').find('.error-content').data('label') +') ไม่ถูกต้อง');
                
                setTimeout(function() {
                    $(self).closest('div, td').removeClass('has-error');
                    $(self).closest('td').find('.error-content').removeClass('show').addClass('hide');
                }, 3000);
                break;
            case 'image-size':
                $(self).closest('div, td').addClass('has-error');
                $(self).closest('td').find('.error-content').removeClass('hide').addClass('show').html('รูป ('+ $(self).closest('td').find('.error-content').data('label') +') ขนาดเกิน');
                
                setTimeout(function() {
                    $(self).closest('div, td').removeClass('has-error');
                    $(self).closest('td').find('.error-content').removeClass('show').addClass('hide');
                }, 3000);
                break;
            case 'numbered':
                regex = /^(\d+)?([.]?\d{0,2})?$/;
                var data = ($(self).val()).replace(/\,/g, '');
                
                if(!(regex.test(data))) {
                    data = addCommas(data.substr(0, (data.length - 1)));
                    $(self).val(data);
                
                    $(self).closest('div, td').addClass('has-error');
                    var numDigit = (data.split('.')[1] != undefined) ? (data.split('.')[1]).length : 0;
                    
                    if(numDigit == 2)
                        $(self).closest('td').find('.error-content').removeClass('hide').addClass('show').html('('+ $(self).closest('td').find('.error-content').data('label') +') ต้องไม่เกิน 2 ตำแหน่ง');
                    else
                        $(self).closest('td').find('.error-content').removeClass('hide').addClass('show').html('('+ $(self).closest('td').find('.error-content').data('label') +') ต้องเป็นตัวเลข');
                    
                    setTimeout(function() {
                        $(self).closest('div, td').removeClass('has-error');
                        $(self).closest('td').find('.error-content').removeClass('show').addClass('hide');

                        if($(self).prop('required') && $(self).val() == '') {
                            $(self).closest('div, td').addClass('has-error');
                            $(self).closest('td').find('.error-content').removeClass('hide').addClass('show').html('('+ $(self).closest('td').find('.error-content').data('label') +') ห้ามว่าง');
                        }
                    }, 3000);
                } else {
                    if(($(self).val() == '') && ($(self).prop('required'))) {
                        $(self).closest('div, td').addClass('has-error');
                        $(self).closest('td').find('.error-content').removeClass('hide').addClass('show').html('('+ $(self).closest('td').find('.error-content').data('label') +') ห้ามว่าง');
                    } else {
                        data = ((/[\.]/).test(data)) ? addCommas(data) : Number(data).toLocaleString('en');
                        $(self).val(data);
                    }
                }

                function addCommas(data) {
                    data += '';
                    x = data.split('.');
                    x1 = x[0];
                    x2 = x.length > 1 ? '.' + x[1] : '';

                    var rgx = /(\d+)(\d{3})/;

                    while (rgx.test(x1)) {
                        x1 = x1.replace(rgx, '$1' + ',' + '$2');
                    }
                    return x1 + x2;
                }

                break;
            case 'clear':
                $(self).closest('div, td').removeClass('has-error');
                $(self).closest('td').find('.error-content').removeClass('show').addClass('hide');
                break;
            default:
                $(self).closest('div, td').addClass('has-error');
                $(self).closest('td').find('.error-content').removeClass('hide').addClass('show').html(errorType);
                break;
        }
    },
    setMenu: function() {
        var headerMenuTitle = (window.location.pathname).split('/Surathai01/')[1];
        
        $('.header .header-menu ul li a span').css({ 'color': '#2A7CBF' });
        $('.nav .nav-menu').find('a').removeClass('active disabled');
        $('.nav .nav-menu').find('a[href="'+ headerMenuTitle +'"]').addClass('active disabled');
        
        switch(headerMenuTitle) {
            case 'map.php':
                $('.header .header-menu-title span').html('แผนที่');
                $('.header .header-menu ul li').find('a[data-header-menu="แผนที่"] span').css({ 'color': '#f17022' });
                break;
            case 'tax.php':
                $('.header .header-menu-title span').html('แผนที่งานภาษี');
                $('.header .header-menu ul li').find('a[data-header-menu="แผนที่"] span').css({ 'color': '#f17022' });
                break;
            case 'case.php':
                $('.header .header-menu-title span').html('แผนที่งานปราบปราม');
                $('.header .header-menu ul li').find('a[data-header-menu="แผนที่"] span').css({ 'color': '#f17022' });
                break;
            case 'license.php':
                $('.header .header-menu-title span').html('แผนที่ใบอนุญาต');
                $('.header .header-menu ul li').find('a[data-header-menu="แผนที่"] span').css({ 'color': '#f17022' });
                break;
            case 'stamp.php':
                $('.header .header-menu-title span').html('แผนที่ข้อมูลแสตมป์');
                $('.header .header-menu ul li').find('a[data-header-menu="แผนที่"] span').css({ 'color': '#f17022' });
                break;
            case 'factory.php':
                $('.header .header-menu-title span').html('แผนที่ข้อมูลโรงงาน');
                $('.header .header-menu ul li').find('a[data-header-menu="แผนที่"] span').css({ 'color': '#f17022' });
                break;
            case 'search_tax.php':
                $('.header .header-menu-title span').html('ค้นหางานภาษี');
                $('.header .header-menu ul li').find('a[data-header-menu="ค้นหา"] span').css({ 'color': '#f17022' });
                break;
            case 'search_case.php':
                $('.header .header-menu-title span').html('ค้นหางานปราบปราม');
                $('.header .header-menu ul li').find('a[data-header-menu="ค้นหา"] span').css({ 'color': '#f17022' });
                break;
            case 'search_license.php':
                $('.header .header-menu-title span').html('ค้นหาใบอนุญาต');
                $('.header .header-menu ul li').find('a[data-header-menu="ค้นหา"] span').css({ 'color': '#f17022' });
                break;
            case 'search_stamp.php':
                $('.header .header-menu-title span').html('ค้นหาข้อมูลแสตมป์');
                $('.header .header-menu ul li').find('a[data-header-menu="ค้นหา"] span').css({ 'color': '#f17022' });
                break;
            case 'search_factory.php':
                $('.header .header-menu-title span').html('ค้นหาข้อมูลโรงงาน');
                $('.header .header-menu ul li').find('a[data-header-menu="ค้นหา"] span').css({ 'color': '#f17022' });
                break;
            case 'search_label.php':
                $('.header .header-menu-title span').html('ค้นหาฉลาก');
                $('.header .header-menu ul li').find('a[data-header-menu="ค้นหา"] span').css({ 'color': '#f17022' });
                break;
            case 'reporttax.php':
                $('.header .header-menu-title span').html('รายงานงานภาษี');
                $('.header .header-menu ul li').find('a[data-header-menu="รายงาน"] span').css({ 'color': '#f17022' });
                break;
            case 'reportcase.php':
                $('.header .header-menu-title span').html('รายงานงานปราบปราม');
                $('.header .header-menu ul li').find('a[data-header-menu="รายงาน"] span').css({ 'color': '#f17022' });
                break;
            case 'reportlicense.php':
                $('.header .header-menu-title span').html('รายงานใบอนุญาต');
                $('.header .header-menu ul li').find('a[data-header-menu="รายงาน"] span').css({ 'color': '#f17022' });
                break;
            case 'reportstamp.php':
                $('.header .header-menu-title span').html('รายงานข้อมูลแสตมป์');
                $('.header .header-menu ul li').find('a[data-header-menu="รายงาน"] span').css({ 'color': '#f17022' });
                break;
            case 'reportfactory.php':
                $('.header .header-menu-title span').html('รายงานข้อมูลโรงงาน');
                $('.header .header-menu ul li').find('a[data-header-menu="รายงาน"] span').css({ 'color': '#f17022' });
                break;
            case 'e_factory.php':
                $('.header .header-menu-title span').html('ระบบบันทึกข้อมูลโรงงาน');
                $('.header .header-menu ul li').find('a[data-header-menu="e-Form"] span').css({ 'color': '#f17022' });
                break;
            case 'e_illegal.php':
                $('.header .header-menu-title span').html('ระบบบันทึกข้อมูลคดี');
                $('.header .header-menu ul li').find('a[data-header-menu="e-Form"] span').css({ 'color': '#f17022' });
                break;
            case 'e_stamp.php':
                $('.header .header-menu ul li').find('a[data-header-menu="e-Form"] span').css({ 'color': '#f17022' });
                $('.nav .nav-menu').find('a[href="'+ headerMenuTitle +'"]').removeClass('disabled').css({ 'pointer': 'cursor' });

                $('.nav .nav-menu').find('a[href="'+ headerMenuTitle +'"]').next().find('li').removeClass('active');
                var stampType = $('.nav .nav-menu').find('a[href="'+ headerMenuTitle +'"]').data('stamp-type');
                
                if(stampType == 0) {
                    $('.header .header-menu-title span').html('ระบบจ่ายแสตมป์สุรา (แบบเต็มเล่ม)');
                    $('.nav .nav-menu').find('a[href="'+ headerMenuTitle +'"]').next().find('li:eq(0)').addClass('active');
                } else {
                    $('.header .header-menu-title span').html('ระบบจ่ายแสตมป์สุรา (แบบแบ่งขาย)');
                    $('.nav .nav-menu').find('a[href="'+ headerMenuTitle +'"]').next().find('li:eq(1)').addClass('active');
                }

                break;
            default:
                $('.header .header-menu-title span').html('แผนที่');
                $('.header .header-menu ul li').find('a[data-header-menu="แผนที่"] span').css({ 'color': '#f17022' });
                break;
        }
    }
}
Factory.prototype.utilityService = {
    getDataImportant: function() {
        $('.data-important').append('<span style="font-size: 20px; color: #fe3d3d;"> *</span>');
    },
    getPopup: function(options) {
        options = (options != undefined) ? options : {};
        options.titleMsg = options.titleMsg  || '<i class="fa fa-exclamation-circle text-right-indent"></i> แจ้งเตือนจากระบบ';
        options.infoMsg = options.infoMsg  || '';
        options.btnMsg = options.btnMsg  || 'ตกลง';
        
        $('#popupModal .popup-title').html(options.titleMsg);
        $('#popupModal .popup-info').html(options.infoMsg);
        $('#popupModal .popup-btn').html(options.btnMsg);
        $('#popupModal').modal('show');
    }
}
Factory.prototype.connectDBService = {
    sendJSONObj: function(ajaxUrl, params) {
        var options = {
            type: 'post',
            url: ajaxUrl,
            cache: false
        };

        if(params != "" && params != undefined)
            options.data = params;
            
        return $.ajax(options);
    },
    sendJSONStr: function(ajaxUrl, params) {
        var options = {
            type: 'post',
            url: ajaxUrl,
            cache: false
        };

        if(params != "" && params != undefined)
            options.data = JSON.stringify(params);
            
        return $.ajax(options);
    },
    sendJSONObjForUpload: function(ajaxUrl, params) {
        var options = {
            type: 'post',
            url: ajaxUrl,
            processData: false,
            contentType: false,
            data: params
        };

        return $.ajax(options);
    }
}
Factory.prototype.dataService = {
    filterObjUsed: function(obj, filterKey) {
        var newObj = {};

        $.each(obj, function(key, value){
            if($.inArray(key, filterKey) != -1)
                newObj[key] = value;
        });

        return newObj;
    },
    filterObjNoUsed: function(obj, filterKey) {
        var newObj = {};

        $.each(obj, function(key, value){
            if($.inArray(key, filterKey) == -1)
                newObj[key] = value;
        });

        return newObj;
    },
    filterArrUsed: function(array, filterKey) {
        var newObj = {};
        var newArr = [];

        $.each(array, function(index, item){
            newObj = {};
            $.each(item, function(key, value){
                if($.inArray(key, filterKey) != -1)
                    newObj[key] = value;
            });
            newArr.push(newObj);
        });

        return newArr;
    },
    filterArrNoUsed: function(array, filterKey) {
        var newObj = {};
        var newArr = [];

        $.each(array, function(index, item){
            newObj = {};
            $.each(item, function(key, value){
                if($.inArray(key, filterKey) == -1)
                    newObj[key] = value;
            });
            newArr.push(newObj);
        });

        return newArr;
    },
    getDataObjChange: function(originData, updateData, primaryKey) {
        var dataObjChange = {};

        $.each(originData, function(keyOrigin, valOrigin){
            $.each(updateData, function(keyUpdate, valUpdate){
                if(keyOrigin == keyUpdate){
                    if(valOrigin != valUpdate){
                        if(primaryKey != '' && primaryKey != undefined)
                            dataObjChange['condition'] = primaryKey +" = '"+ updateData[primaryKey] +"'";

                        if((/^\d{2}\/\d{2}\/\d{4}$/).test(valUpdate))
                            valUpdate = dataService.getDateFormateForDB(valUpdate);

                        dataObjChange[keyUpdate] = valUpdate;
                    }
                }
            });
        });

        return dataObjChange;
    },
    getDataArrChange: function(originData, updateData, primaryKey) {
        var dataArrChange = [];
        var dataObjChange = {};

        $.each(originData, function(originIndex, originItem){
            dataObjChange = {};

            $.each(originItem, function(keyOrigin, valOrigin){
                $.each(updateData[originIndex], function(keyUpdate, valUpdate){
                    if(keyOrigin == keyUpdate){
                        if(valOrigin != valUpdate){
                            if(primaryKey != '' && primaryKey != undefined)
                                dataObjChange['condition'] = primaryKey +" = '"+ updateData[originIndex][primaryKey] +"'";
                            
                            if((/^\d{2}\/\d{2}\/\d{4}$/).test(valUpdate))
                                valUpdate = dataService.getDateFormateForDB(valUpdate);

                            dataObjChange[keyUpdate] = valUpdate;
                        }
                    }
                });
            });
            
            if(!$.isEmptyObject(dataObjChange))
                dataArrChange.push(dataObjChange);
        });

        return dataArrChange;
    },
    detectDataArrChange: function(originData, updateData, detectKey, primaryKey) {
        var dataArrChange = {
            'insertData': [],
            'deleteData': []
        };

        $.each(originData, function(originIndex, originItem){
            $.each(originItem, function(keyOrigin, valOrigin){
                if(keyOrigin == detectKey){
                    $.each(updateData[originIndex], function(keyUpdate, valUpdate){
                        if(keyUpdate == detectKey){
                            if(valOrigin != valUpdate){
                                if(valUpdate != 0)
                                    dataArrChange['insertData'].push(updateData[originIndex]);
                                else{
                                    updateData[originIndex]['condition'] = primaryKey +" = '"+ updateData[originIndex][primaryKey] +"'";
                                    dataArrChange['deleteData'].push(updateData[originIndex]);
                                }
                            }
                        }
                    });
                }
            });
        });

        if((dataArrChange['insertData'].length != 0) || (dataArrChange['deleteData'].length != 0))
            return dataArrChange;
        else
            return {}
    },
    getDateFormateForDB: function(dateOrigin) {
        if(dateOrigin != '' && dateOrigin != undefined){
            var dateRegExp = /^\d{4}\-\d{2}\-\d{2}$/;

            if(!dateRegExp.test(dateOrigin)){
                var dateFormateForDB = '';
            
                dateOrigin = dateOrigin.split('/');
                dateFormateForDB = (Number(dateOrigin[2]) - 543) +'-'+ dateOrigin[1] +'-'+ dateOrigin[0];

                return dateFormateForDB;
            }
        }

        var now = new Date();
        var today = now.getFullYear() + '-';
        today += (now.getMonth() + 1) + '-';
        today += (now.getDate().toString().length < 2 ? ('0' + now.getDate()) : now.getDate());

        return today;
    },
    getCurrentDate: function() {
        var now = new Date();
        var today = now.getFullYear() + '-';
        today += ((now.getMonth() + 1).toString().length < 2 ? ('0' + (now.getMonth() + 1)) : (now.getMonth() + 1))  + '-';
        today += (now.getDate().toString().length < 2 ? ('0' + now.getDate()) : now.getDate());

        return today;
    },
    getCurrentDateTH: function(type) {
        var now = new Date();
        var day = now.getDay();
        var date = now.getDate();
        var month = now.getMonth();
        var year = (now.getFullYear() + 543);
        var dayNames = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
        var monthNames = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];         
        var today = '';

        switch(type) {
            case 'short':
                today = ((date.toString().length < 2 ? ('0' + date) : date)) +'/';
                today += ((month + 1).toString().length < 2 ? ('0' + (month + 1)) : (month + 1))  + '/';
                today += (now.getFullYear() + 543);
                break;
            case 'full':
                today = 'วัน ' + dayNames[day] + ' ที่ ' + date + ' ' + monthNames[month] + ' ' + year;
                break;
        }

        return today;
    }
}