<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>เข้าสู่ระบบ</title>
    <!--jQuery-->
    <script src="lib/jquery/jquery-11.0.min.js" type="text/javascript"></script>
    <script src="lib/jquery/jquery-ui-1.12.1.custom/jquery-ui.js" type="text/javascript"></script>
    <link href="lib/jquery/jquery-ui-1.12.1.custom/jquery-ui.min.css" rel="stylesheet" type="text/css">
    <!--Bootstarp-->
    <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <script src="lib/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!--CSS-->
    <link href="css/style_login.css" rel="stylesheet" type="text/css">
    <!--JS-->
    <script src="js/factory.js" type="text/javascript"></script>
</head>
<body>
    <div class="container">
        <div class="col-sm-6 text-center"><img src="img/logo.png" width="90%"/></div>
        <div class="col-sm-6">
            <div class="title"><img src="img/logoheader.png" width="12%"><h2 class="title-mobile text-nowrap text-white">ระบบฐานข้อมูลผู้ประกอบการสุราชุมชน</h2></div><br>        
            <form name="loginForm" novalidate>
                <div class="form-group row">
                    <div class="col-sm-10 position-center">
                        <div class="input-group text-center">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input type="text" class="form-control input-lg" id="username" name="username" autofocus placeholder="ชื่อเข้าใช้งาน">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-10 position-center">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input type="password" class="form-control input-lg" id="password" name="password" placeholder="รหัสผ่าน">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-offset-8 col-sm-3">
                        <input type="submit" class="form-control btn-info input-lg" id="loginBtn" value="เข้าสู่ระบบ">
                    </div>
                </div>
            </form>
<!--JS-->
<script type="text/javascript">
    $(document).ready(function(e) {
        //--Page load
        getInit();

        //--Function
        function getInit() {
            $('#username, #password').val('');
        }
    });
</script>
<?php require('footer.php'); ?>