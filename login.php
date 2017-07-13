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
    <!--Bootstarp-->
    <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <script src="lib/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!--CSS-->
    <link href="img/logoheader.png" rel="shortcut icon" type="image/x-icon">
    <link href="css/style_login.css" rel="stylesheet" type="text/css">
    <!--JS-->
    <script src="js/factory.js" type="text/javascript"></script>
</head>
<body>
    <div class="container">
            <div class="container">
                <div class="col-md-6"><img src="img/logo.png" class="img-responsive center-block" width="80%"/></div>
                <div class="col-md-6">   
                <div class="title" style="margin-top: 1.5vh;">
                <img src="img/logoheader.png" class="img-responsive center-block img-logo-title-mobile"   />
                </div>
                    <h6 style="color: white; font-size: 3.7vh;">ระบบฐานข้อมูลผู้ประกอบการสุราชุมชน
                    </h6>
                    <form name="loginForm" novalidate>
                        <div class="form-group row ">
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                    <input type="text" class="form-control input-lg" id="username" name="username" autofocus placeholder="ชื่อเข้าใช้งาน">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-9 ">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <input type="password" class="form-control input-lg" id="password" name="password" placeholder="รหัสผ่าน">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-offset-6 col-md-3">
                                <input type="submit" class="form-control btn-info input-lg" id="loginBtn" value="เข้าสู่ระบบ">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
<!--JS-->
<script type="text/javascript">
    $(document).ready(function(e) {
        //--Page load
        getInit();

        //--Function
        function getInit() {
            $('#username, #password').val('');
            $('#username, #password').attr('autocomplete', 'off');
        }
    });
</script>
<?php require('footer.php'); ?>