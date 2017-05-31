<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <!-- Jquery -->
        <script type="text/javascript" src="lib/jquery/jquery.min.js"></script>
        <!-- Bootstarp -->
        <link rel="stylesheet" href="lib/bootstrap/css/bootstrap.min.css">
        <script type="text/javascript" src="lib/bootstrap/js/bootstrap.min.js"></script>
        <!-- css -->
        <link rel="stylesheet" href="css/style_login.css">
    </head>
    <body>

        <div class="container">
            <!-- LOGO -->
            <div class="col-sm-6 text-center"><img src="img/logo.png" width="90%"/></div>
            <!-- FORM -->
            <div class="col-sm-6">
                <div class="title"><h2 class="title-mobile text-nowrap text-white">ระบบฐานข้อมูลผู้ประกอบการสุราชุมชน</h2></div><br>
                
                <form method="post" name="login-form" action="checklogin.php">
                    <!-- Email. -->
                    <div class="form-group row">
                        <div class="col-sm-10 position-center">
                            <div class="input-group text-center">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                <input type="text" class="form-control input-lg" id="txtname" name="username" autofocus placeholder="ชื่อเข้าใช้งาน"/>
                            </div>
                        </div>
                    </div>
                    <!-- Password. -->
                    <div class="form-group row">
                        <div class="col-sm-10 position-center">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <input type="password" class="form-control input-lg" id="txtPassword" name="password" placeholder="รหัสผ่าน"/>
                            </div>
                        </div>
                    </div>
                    <!-- Btn Sign in. -->
                    <div class="form-group row">
                        <div class="col-sm-offset-8 col-sm-3">
                            <input type="submit" class="form-control btn-info input-lg" id="btnSignin" value="เข้าสู่ระบบ" />
                        </div>
                    </div>

                </form>
            </div>
            
        </div>
    </body>
</html>
