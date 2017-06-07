<?php require('header.php'); ?>

<!--SECTION-->
<div class="section" style="margin-top: 10px;">

   
    <div class="col-md-6" >
        <div class="panel panel-info">
            <div class="panel-heading">
                <p style="font-weight: bold; font-size: 25px;" class="panel-title"><i class="fa fa-user" aria-hidden="true"></i> ระบบจัดการข้อมูลสมาชิก</p> 

            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3" align="center"> <img alt="User Pic" src="img/user.png" class="img-circle img-responsive"> <p style="font-size: 21px;" class="text-nowrap">นายทดสอบ ครั้งแรก</p></div>
                <div class=" col-md-9"> 
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td >ชื่อผู้ใช้งาน</td>
                                <td><span id="userName">ทดสอบ ครั้งแรก</span></td>
                            </tr>
                            <tr>
                                <td >เพศ</td>
                                <td><span id="userSex">ชาย</span></td>
                            </tr>
                            <tr>
                                <td >ระดับ</td>
                                <td><span id="userLv">1</span></td>
                            </tr>
                            <tr>
                                <td >ตำแหน่ง</td>
                                <td><span id="user">สำนักงานสรรพสามิตพื้นที่เชียงใหม่</span></td>
                            </tr>
                            <tr>
                                <td >สังกัด</td>
                                <td><span id="userBranchTXT">สรรพสามิตพื้นที่เชียงใหม่</span></td>
                            </tr>
                            <tr>
                                <td >อีเมลล์</td>
                                <td><a href="mailto:info@support.com"><span id="userEmail">info@support.com</span></a></td>
                            </tr>
                            <tr>
                                <td ><span>เบอร์โทรศัพท์</span></td>
                                <td><span id="userTel">02-7058899</span>(ที่ทำงาน)<br><br><span id="userMobile">091-914999</span>(มือถือ)</td>
                            </tr>
                            </tr>
                                <td >รหัสผ่านปัจจุบัน</td>
                                <td><input type="password" name="" id="userPassword" class="form-control" required="required" title="" value="555555"></td>
                            </tr>
                        </tbody>
                  </table>  
                </div>
              </div>
            </div>
        <div class="panel-footer">
            <div style="text-align: right;">
                <a href="#" data-original-title="Edit this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-warning">แก้ไข</a>
                <a data-original-title="Remove this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-danger">ยกเลิก</a>
            </div>
        </div>
            
          </div>
        </div>
      </div>
    <div class="col-md-6">
       <div class="panel panel-info" style="margin-top: 30px;">
           <div class="panel-heading">
               <p style="font-weight: bold; font-size: 25px;" class="panel-title"><i class="fa fa-search" aria-hidden="true"></i> ระบบค้นหาข้อมูลพนักงานในสังกัด</p>
           </div>
           <div class="panel-body">
                <form action="" method="POST" role="form">
                
                    <div class="form-group">
                        <input type="text" class="form-control" id="searchUser" placeholder="ระบุชื่อพนักงานที่ต้องการค้นหา">

                    </div>
                </form>
                <ul class="list-group">
                    <li class="list-group-item"> <img alt="User Pic" style="height: 35px;width: 35px; float: left;" src="img/user.png" class="img-circle img-responsive"><p>Item 1</p></li>
                    <li class="list-group-item"><img alt="User Pic" style="height: 35px;width: 35px; float: left;" src="img/user.png" class="img-circle img-responsive"><p>Item 2</p></li>
                    <li class="list-group-item"><img alt="User Pic" style="height: 35px;width: 35px; float: left;" src="img/user.png" class="img-circle img-responsive"><p>Item 3</p></li>
                    <li class="list-group-item"><img alt="User Pic" style="height: 35px;width: 35px; float: left;" src="img/user.png" class="img-circle img-responsive"><p>Item 4</p></li>
                    <li class="list-group-item"><img alt="User Pic" style="height: 35px;width: 35px; float: left;" src="img/user.png" class="img-circle img-responsive"><p>Item 5</p></li>
                </ul>
               <p style="text-align: right;">พบข้อมูลทั้งสิ้นจำนวน 5 รายการ</p>
           </div>
       </div>
    </div>
    </div>
</div>
</br>
<!--JS-->
<script type="text/javascript">
    
</script>

<?php require('popup.php'); ?>
<?php require('footer.php'); ?>     