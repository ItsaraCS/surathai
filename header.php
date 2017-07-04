<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surathai - ระบบฐานข้อมูลผู้ประกอบการสุราชุมชน</title>
    <!--jQuery-->
    <script src="lib/jquery/jquery-11.0.min.js" type="text/javascript"></script>
    <script src="lib/jquery/jquery-ui-1.12.1.custom/jquery-ui.js" type="text/javascript"></script>
    <link href="lib/jquery/jquery-ui-1.12.1.custom/jquery-ui.min.css" rel="stylesheet" type="text/css">
    <script src="lib/jquery/jquery-ui-1.12.1.custom/jquery-ui-timepicker-addon.js" type="text/javascript"></script>
    <link href="lib/jquery/jquery-ui-1.12.1.custom/jquery-ui-timepicker-addon.css" rel="stylesheet" type="text/css">
    <!--Bootstarp-->
    <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <script src="lib/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!--Openlayer-->
    <link href="lib/openlayer/ol.css" rel="stylesheet" type="text/css">
    <script src="lib/openlayer/ol.js" type="text/javascript"></script>
    <!--ChartJS-->
    <script src="lib/chartjs/Chart.min.js" type="text/javascript"></script>
    <!--Font Awesome-->
    <link href="lib/fontawesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!--CSS-->
    <link href="img/logoheader.png" rel="shortcut icon" type="image/x-icon">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <!--JS-->
    <script src="js/chart.js" type="text/javascript"></script>
    <script src="js/factory.js" type="text/javascript"></script>
    <script src="js/table_to_excel.js" type="text/javascript"></script>
    <!--MAP-->
    <link href="css/layer_toggler.css" rel="stylesheet" type="text/css">
	<link href="css/search_layer_toggler.css" rel="stylesheet" type="text/css">
    <script src="js/categories.js" type="text/javascript"></script>
    <script src="js/getJson.js" type="text/javascript"></script>
    <script src="js/olmaplib.js" type="text/javascript"></script>
    <script src="js/local_shared.js" type="text/javascript"></script>
    <script src="js/search_map_lib.js" type="text/javascript"></script>
    <script src="js/e_map_lib.js" type="text/javascript"></script>
    <!--HTML2Canvas-->
    <script src="lib/html2canvas/html2canvas.js" type="text/javascript"></script>
    <!--CHECK USER-->
    <script type="text/javascript">
        var factory = new Factory();
        var ajaxUrl = 'API/userAPI.php';
        var userData;
        var path = (window.location.pathname).split('/');
        var pathFile = path[path.length - 1];
        var userID = JSON.parse(sessionStorage.getItem('userID'));
        
        if(userID == null)
            window.open('login.php', '_self');
        else {
            if(pathFile != 'login.php') {
                factory.connectDBService.sendJSONObj(ajaxUrl, {}, false).done(function(res) {
                    if(res != undefined){
                        var data = JSON.parse(res);
                        userData = data;

                        if(userData.id == 0)
                            window.open('login.php', '_self');
                        else {
                            $(document).ready(function(e) {
                                $('.user-menu img, .user-menu-detail-avatar img').attr('src', ((userData.Gender == 0) ? 'img/user-female.png' : 'img/user-male.png'));
                                $('.user-menu-detail-label p').html(userData.Fullname);
                                $('#ProvinceTXT').html(userData.ProvinceTXT);
                            });
                        }
                    }
                });
            }
        }
    </script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!--HEADER-->
            <div class="header">
                <nav class="navbar navbar-default header-menu" role="navigation">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                            <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                                <a class="navbar-brand header-logo" href="#" style="padding-top: 5px; margin-top: 0px;"><img height="60" src="img/logoheader.png" ></a>
                        </div>

                        <ul class="nav navbar-nav navbar-left">
                            <li>
                                <h2 style="margin-top: 5px; font-size: 20px; margin-left: 10px;">ระบบฐานข้อมูลผู้ประกอบการสุราชุมชน</h2>
                                <h3 class="header-menu-title" style="margin-top: 0px; margin-left: 10px;"><i class="fa fa-caret-right text-right-indent"></i> <span style="font-size: 18px;"></span></h3>
                            </li>
                        </ul>
                        <div class="collapse navbar-collapse navbar-ex1-collapse">
                            <ul class="nav navbar-nav navbar-right">
                                <li class="navbar-icon"><a href="map.php" data-header-menu="แผนที่"><span style="font-size: 16px;"><i class="glyphicon glyphicon-map-marker"></i> แผนที่</span></a></li>
                                <li class="navbar-icon"><a href="search_tax.php" data-header-menu="ค้นหา"><span style="font-size: 16px;"><i class="glyphicon glyphicon-search"></i> ค้นหา</span></a></li>
                                <li class="navbar-icon"><a href="reporttax.php" data-header-menu="รายงาน"><span style="font-size: 16px;"><i class="glyphicon glyphicon-stats"></i> รายงาน</span></a></li>
                                <li class="navbar-icon"><a href="e_factory.php" data-header-menu="e-Form"><span style="font-size: 16px;"><i class="glyphicon glyphicon-list-alt"></i> e-Form</span></a></li>
                                <li class="navbar-icon">
                                    <a href="#" data-header-menu="ผู้ใช้งานระบบ">
                                        <div class="user-menu">
                                            <img class="img-responsive" style="display: inline-block;"><i class="fa fa-caret-down hidden-mobile"></i>
                                            <ul>
                                                <a class="btn btn-default" href="user.php" style="color: black;">ตั้งค่าบัญชี</a>
                                                <a class="btn btn-default" id="logoutBtn" href="#" style="color: black;">ออกจากระบบ</a>
                                            </ul>
                                            
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <div class="user-menu-detail hide">
                    <div class="user-menu-detail-avatar text-center">
                        <img>
                    </div>
                    <div class="user-menu-detail-label text-center" style="margin: 10px auto;">
                        <p></p>
                    </div>
                    <div class="col-md-12 user-menu-detail-btn text-center">              
                        <div class="col-md-6 text-left">
                            <div class="row">
                                <a href="user.php" class="btn btn-default">ตั้งค่าบัญชี</a>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="row">
                                <a class="btn btn-default" id="logoutBtn">ออกจากระบบ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>