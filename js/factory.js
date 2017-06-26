function Factory() {
    var self = this;
}
Factory.prototype.initService = {
    setError: function(self, errorType) {
        var regex;

        $(self).closest('div, td').removeClass('has-error');
        $(self).closest('td').find('.error-content').removeClass('show').addClass('hide');

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
            case 'email-only':
                regex = /^\s*[\w\-\+_]+(\.[\w\-\+_]+)*\@[\w\-\+_]+\.[\w\-\+_]+(\.[\w\-\+_]+)*\s*$/;

                if((!(regex.test($(self).val()))) && ($(self).val() != '')) {
                    $(self).closest('div, td').addClass('has-error');
                    $(self).closest('td').find('.error-content').removeClass('hide').addClass('show').html('('+ $(self).closest('td').find('.error-content').data('label') +') ไม่ถูกต้อง');
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
        var path = (window.location.pathname).split('/');
        var headerMenuTitle = path[path.length - 1];
        
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
            case 'user.php':
                $('.header .header-menu-title span').html('ตั้งค่าบัญชี');
                $('.header .header-menu ul li').find('a[data-header-menu="ผู้ใช้งานระบบ"] span').css({ 'color': '#f17022' });
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
    sendJSONObj: function(ajaxUrl, params, loadingRequired) {
        loadingRequired = ((loadingRequired != undefined) && (loadingRequired != true)) ? false : true;
        
        var options = {
            type: 'post',
            url: ajaxUrl,
            cache: false,
            beforeSend: function() {
                if(loadingRequired)
                    $('body').append('<img src="img/loading.gif" class="loading">');
            },
            success: function() {
                if(loadingRequired)
                    $('.loading').remove();
            }
        };

        if(params != "" && params != undefined)
            options.data = params;
            
        return $.ajax(options);
    },
    sendJSONStr: function(ajaxUrl, params, loadingRequired) {
        loadingRequired = ((loadingRequired != undefined) && (loadingRequired != true)) ? false : true;
        
        var options = {
            type: 'post',
            url: ajaxUrl,
            cache: false,
            beforeSend: function() {
                if(loadingRequired)
                    $('body').append('<img src="img/loading.gif" class="loading">');
            },
            success: function() {
                if(loadingRequired)
                    $('.loading').remove();
            }
        };

        if(params != "" && params != undefined)
            options.data = JSON.stringify(params);
            
        return $.ajax(options);
    },
    sendJSONObjForUpload: function(ajaxUrl, params, loadingRequired) {
        loadingRequired = ((loadingRequired != undefined) && (loadingRequired != true)) ? false : true;

        var options = {
            type: 'post',
            url: ajaxUrl,
            processData: false,
            contentType: false,
            data: params,
            beforeSend: function() {
                if(loadingRequired)
                    $('body').append('<img src="img/loading.gif" class="loading">');
            },
            success: function() {
                if(loadingRequired)
                    $('.loading').remove();
            }
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
    },
    exportFile: function(page, params) {
        switch(page) {
            case 'map': 
                if($('#my_chart').closest('#collapse1').attr('aria-expanded') != 'true') 
                    $('#chart_title').trigger('click');

                setTimeout(function() {
                    if($('#my_chart').closest('#collapse1').attr('aria-expanded') == 'true') {
                        var map = html2canvas($('#map'));
                        var mapCanvas = map.render(map.parse());
                        var mapImage = mapCanvas.toDataURL('image/png');
                        
                        var chart = html2canvas($('#my_chart'));
                        var chartCanvas = chart.render(chart.parse());
                        var chartImage = chartCanvas.toDataURL('image/png');

                        var exportData = {
                            title: params.title || 'ระบบฐานข้อมูลผู้ประกอบการสุราชุมชน',
                            menu: params.menu || 'แผนที่',
                            year: ($('.nav-menu #year').val() != '') ? (Number($('.nav-menu #year').val()) + 543) : (Number($('.nav-menu #year option:eq(1)').attr('value')) + 543),
                            region: ($('.nav-menu #region').val() != '') ? $('.nav-menu #region option[value="'+ $('.nav-menu #region').val() +'"]').text() : $('.nav-menu #region option:eq(1)').text(),
                            province: ($('.nav-menu #area').val() != '') ? $('.nav-menu #area option[value="'+ $('.nav-menu #area').val() +'"]').text() : $('.nav-menu #area option:eq(1)').text(),
                            mapImage: {
                                map: mapImage,
                                legend: [],
                                layer: []
                            },
                            chartImage: chartImage
                        };

                        if($('#map_legend .map_legend_box').length != 0) {
                            var legend = [];
                            var backgroundColor;

                            $.each($('#map_legend .map_legend_box'), function(index, item) {
                                backgroundColor = ($(item).find('.map_legend_legend_box').css('background-color')).split(', ');
                                
                                legend[index] = {
                                    colorR: Number(backgroundColor[0].replace('rgba(', '')),
                                    colorG: Number(backgroundColor[1]),
                                    colorB: Number(backgroundColor[2].replace(')', '')),
                                    value: $(item).find('.map_legend_legend_text').html()
                                };
                            });

                            exportData.mapImage.legend = legend;
                        }

                        if($('#map_layer_toggler_container .layer_block').length != 0) {
                            var layer = [];

                            $.each($('#map_layer_toggler_container .layer_block'), function(index, item) {
                                layer[index] = {
                                    status: $(item).find('input[type="checkbox"]').is(':checked') || false,
                                    text: $(item).text()
                                }
                            });

                            exportData.mapImage.layer = layer;
                        }

                        params = {
                            funcName: 'exportMapForPDF',
                            params: exportData
                        };

                        factory.connectDBService.sendJSONStr('API/exportAPI.php', params).done(function(res) {
                            if(res != undefined) {
                                var data = JSON.parse(res);
                                var winCur = window.open(data['pdf'], '_blank');

                                if(winCur) {
                                    $('#chart_title').trigger('click');

                                    params = {
                                        funcName: 'removeFilePath',
                                        params: [
                                            '../'+ data['pdf'], 
                                            data['map'],
                                            data['chart']
                                        ]
                                    };

                                    setTimeout(function() {
                                        factory.connectDBService.sendJSONStr('API/exportAPI.php', params, false).done(function(res) {
                                            if(res != undefined) {
                                                if(res)
                                                    console.log('Open and remove file success');
                                                else
                                                    console.log(res);
                                            }
                                        });
                                    }, 1000);
                                }
                            }
                        });
                    }  
                }, 1000);

                break;
            case 'search':
                html2canvas($('.get-map'), {
                    onrendered: function(canvas) {
                        var exportData = {
                            title: params.title || 'ระบบฐานข้อมูลผู้ประกอบการสุราชุมชน',
                            menu: params.menu || 'ค้นหา',
                            year: ($('.nav-menu #year').val() != '') ? (Number($('.nav-menu #year').val()) + 543) : (Number($('.nav-menu #year option:eq(1)').attr('value')) + 543),
                            region: ($('.nav-menu #region').val() != '') ? $('.nav-menu #region option[value="'+ $('.nav-menu #region').val() +'"]').text() : $('.nav-menu #region option:eq(1)').text(),
                            province: ($('.nav-menu #province').val() != '') ? $('.nav-menu #province option[value="'+ $('.nav-menu #province').val() +'"]').text() : $('.nav-menu #province option:eq(1)').text(),
                            summaryTableData: {
                                header: [],
                                body: [],
                                footer: [],
                                sizeWidth: []
                            },
                            detailTableData: {},
                            mapImage: canvas.toDataURL('image/png')
                        };

                        //--summaryTableData
                        $.each($('.search-detail-table thead tr th'), function(index, item) {
                            exportData.summaryTableData.header.push($(item).html());
                            exportData.summaryTableData.sizeWidth.push((170 / $('.search-detail-table thead tr th').length));
                        });
                        $.each($('.search-detail-table tbody tr'), function(index, item) {
                            if(index != ($('.search-detail-table tbody tr').length - 1)) {
                                var summaryTableDataBody = [];

                                $.each($(item).find('td'), function(tdIndex, tdItem) {
                                    if($(tdItem).find('p, span').length > 0)
                                        summaryTableDataBody.push($(tdItem).find('p, span').html());
                                    else
                                        summaryTableDataBody.push($(tdItem).html());
                                });

                                exportData.summaryTableData.body[index] = summaryTableDataBody;
                            } else {
                                $.each($(item).find('td'), function(tdIndex, tdItem) {
                                    if($(tdItem).find('p, span').length > 0)
                                        exportData.summaryTableData.footer.push($(tdItem).find('p, span').html());
                                    else
                                        exportData.summaryTableData.footer.push($(tdItem).html());
                                });
                            }
                        });

                        //--detailTableData
                        if($('.search-table tbody tr').length > 1) {
                            var screen = 0;
                            var width = 0;
                            var total = 0;
                            var page = 1;

                            var detailTableDataPerPage = [];
                            var perPage = 0;
                            $.each($('.search-table thead tr th'), function(index, item) {
                                if($(item).find('.select-export').is(':checked')) {
                                    screen += $(item).innerWidth();
                                    width += $(item).innerWidth();
                                    total += 1;
                                    perPage += 1;

                                    if(width > 1100) {
                                        detailTableDataPerPage.push((perPage - 1));
                                        width = $(item).innerWidth();
                                        page += 1;
                                        perPage = 1;
                                    }
                                }
                            });

                            if(perPage > 0) 
                                detailTableDataPerPage.push(perPage);
                                
                            var detailTableDataHeader = [];
                            var detailTableDataSizeWidth = [];
                            $.each($('.search-table thead tr th'), function(index, item) {
                                if($(item).find('.select-export').is(':checked')) {
                                    detailTableDataHeader.push($(item).find('label').html());
                                    detailTableDataSizeWidth.push(($(item).innerWidth() / 4));
                                }
                            });

                            //--Get index with select export
                            var selectItem = [];
                            var selectIndex = 0;
                            $.each($('.search-table thead tr th'), function(theadIndex, theadItem) {
                                if($(theadItem).find('.select-export').is(':checked')) { 
                                    selectItem[selectIndex] = theadIndex;
                                    selectIndex++;
                                }
                            });

                            var detailTableDataBody = [];
                            var detailTableDataAlign = [];
                            for(var i=0; i<page; i++) { 
                                var body = [];
                                var align = [];

                                $.each($('.search-table tbody tr'), function(tbodyIndex, tbodyItem) { 
                                    var bodyData = [];
                                    var alignData = [];

                                    for(var j=0; j<detailTableDataPerPage[i]; j++) { 
                                        if(selectItem[j] != undefined) {
                                            if($(tbodyItem).find('td:eq('+ selectItem[j] +')').find('a').length > 0) {
                                                if($(tbodyItem).find('td:eq('+ selectItem[j] +')').find('a img').length > 0)
                                                    bodyData[j] = $(tbodyItem).find('td:eq('+ selectItem[j] +') a img').attr('src');
                                                else
                                                    bodyData[j] = $(tbodyItem).find('td:eq('+ selectItem[j] +') a').text();
                                            } else
                                                bodyData[j] = $(tbodyItem).find('td:eq('+ selectItem[j] +')').html();

                                            alignData[j] = ({
                                                'text-left': 'L',
                                                'text-right': 'R',
                                                'text-center': 'C'
                                            })[($(tbodyItem).find('td:eq('+ selectItem[j] +')').attr('class')).replace(' text-nowrap', '')];
                                        }
                                    }

                                    body[tbodyIndex] = bodyData;
                                    align[tbodyIndex] = alignData;
                                });

                                for(var j=0; j<detailTableDataPerPage[i]; j++) {
                                    selectItem.shift();
                                }

                                detailTableDataBody[i] = body;
                                detailTableDataAlign[i] = align;
                            }

                            var body = [];
                            var align = [];
                            var row = 0;
                            for(var i=0; i<page; i++) {
                                var header = [];
                                var sizeWidth = [];
                                body = [];
                                align = [];

                                for(var j=0; j<detailTableDataPerPage[i]; j++) {
                                    if(detailTableDataHeader[row] != undefined && detailTableDataSizeWidth[row] != undefined) {
                                        header.push(detailTableDataHeader[row]);
                                        sizeWidth.push(detailTableDataSizeWidth[row]);
                                    }

                                    row++;
                                }

                                exportData.detailTableData[i] = {
                                    header: header,
                                    body: detailTableDataBody[i],
                                    align: detailTableDataAlign[i],
                                    sizeWidth: sizeWidth
                                }
                            }
                        }

                        params = {
                            funcName: 'exportSearchForPDF',
                            params: exportData
                        };

                        factory.connectDBService.sendJSONStr('API/exportAPI.php', params).done(function(res) {
                            if(res != undefined) {
                                var data = JSON.parse(res);
                                var winCur = window.open(data['pdf'], '_blank');

                                if(winCur) {
                                    params = {
                                        funcName: 'removeFilePath',
                                        params: [
                                            '../'+ data['pdf'], 
                                            data['map']
                                        ]
                                    };

                                    setTimeout(function() {
                                        factory.connectDBService.sendJSONStr('API/exportAPI.php', params, false).done(function(res) {
                                            if(res != undefined) {
                                                if(res)
                                                    console.log('Open and remove file success');
                                                else
                                                    console.log(res);
                                            }
                                        });
                                    }, 1000);
                                }
                            }
                        });
                    }
                });

                break;
        }
    }
}