// NC
function tableToExcel(){
    // Get Title
    var exportTitle;
    $(".nav .nav-menu .col-sm-5 a").each(function( index, el ) {
        if($(el).find('.active')) { 
            exportTitle = $(".active").text();
        }
    });
    

    // Get Year
    var yearInt = parseInt($( ".nav .nav-menu #year").val())+543;
    var yearStr = "ปีงบประมาณ "+yearInt.toString();
    // Get Region
    var regionStr = $(".nav .nav-menu #region").val();
    if(regionStr == "0"){
        regionStr = "ทุกภาค";
    }else{
        regionStr = "ภาค "+regionStr;
    }
    // Get Province
    var provinceName;
    var provinceNumb = $(".nav .nav-menu #province").val();
    if( provinceNumb == "0"){
        provinceName = "ทุกจังหวัด";
    }else{
        provinceName = "จังหวัด"+$(".nav .nav-menu #province option[value="+provinceNumb+"]").text()
    }

    // Get graph img
    
    var canvas  = document.getElementById("myChart");
    var dataUrl = canvas.toDataURL();
    // var srcEnc = dataUrl.replace(/^data:image\/(png|jpg);base64,/, "");
    
    var returnImgName;
    $.ajax({
        url: "API/exportToExcelAPI.php",
        type: "POST",
        data: {image: dataUrl},
        dataType: "json",
        success: function(res){
            if(res != undefined) {
                returnImgName = res.ImageName;
                
                var graphJpg ='<img src="http://localhost/Surathai01/export/report/'+returnImgName+'.png">';
                // alert(graphJpg);
                //Get Title
                var ttitle = '<caption style="font-size:14px;"><b>'+exportTitle+'ประจำ'+yearStr +" "+ regionStr +" "+provinceName+' </b></caption>';

                //Get All thead 
                var allColumnName_arr = [];
                $("table thead th div").each(function( index, el ) {
                if($(el).find('input')) { 
                    var allColumnName = document.getElementsByTagName("input")[index].getAttribute("id");
                    allColumnName_arr.push(allColumnName);
                }
                });

                //Get Thead checked
                var columnName_arr = [];
                $("table thead th div").each(function( index, el ) {
                if($(el).find('input').is(':checked')) { 
                    var columnName = document.getElementsByTagName("input")[index].getAttribute("id");
                    columnName_arr.push(columnName);
                }
                });

                //Compare Array
                var hideColumn_arr = [];
                for(i = 0; i < allColumnName_arr.length; i++){
                    for(j = 0; j < columnName_arr.length; j++ ){
                        if(allColumnName_arr[i] == columnName_arr[j]){
                            hideColumn_arr.push(i)
                        }
                    }
                }

                // Start Tbody wrtie
                var margTbody = '<tbody>';

                for(m = 1; m < 12; m++){
                    var tbody_arr = []; 
                    $('table tbody .tr'+m+'').each(function(index, tr) {
                        var lines = $('td', tr).map(function(index, td) {
                            tbody_arr.push($(td).text());
                        });
                    });

                    // Select Column
                    var tbodySelected_arr = []; 
                    for(i = 0; i < tbody_arr.length; i++){
                        for(j = 0; j < hideColumn_arr.length; j++ ){
                            if(i == hideColumn_arr[j]){
                                tbodySelected_arr.push(tbody_arr[i])
                            }
                        }
                    }
                    
                    margTbody +='<tr>';
                    for (i = 0; i < tbodySelected_arr.length; i++) { 
                        if(m == 1 || m == 3 || m == 5 || m == 7 || m == 9 || m == 11 ){
                            margTbody += '<td class="text-right" style="background-color: #f9f9f9;">'+tbodySelected_arr[i]+'</td>';
                        }else{
                            margTbody += '<td class="text-right">'+tbodySelected_arr[i]+'</td>';
                        } 
                    }
                    margTbody +='</tr>';
                
                }
                margTbody += '<tr><td>'+graphJpg+'</td></tr>';
                margTbody +='</tbody>';
                // End Tbody wrtie

                // Write Thead
                var theadSet = '<thead id="getthead"><tr>';
                for (i = 0; i < columnName_arr.length; i++) { 
                    theadSet += '<th style="background-color: #65737E;color: #FFFFFF;" class="text-center text-nowrap"><div class="checkbox checkbox-primary" style="margin: 0 auto;"><label >'+columnName_arr[i]+'</label></div></th>';
                }
                theadSet +='</tr></thead>';
                
                

                //Combine
                var combine = '<table id="headerTable">'+ttitle+theadSet+margTbody+'</table>';

                // Call Function and sent param
                downloadExcell(combine);
            }
        }
    });
}

function downloadExcell(combine) {
    
    var output = [combine];
    var OpenWindow = window.open("excell_download.php", "excelltab", '');
    OpenWindow.dataFromParent = output; // dataFromParent is a variable in child.html
   
}

// Export Excel

var exportToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body style="background-color:#ffffff;"><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
    
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    window.location.href = uri + base64(format(template, ctx))
  }
})()
