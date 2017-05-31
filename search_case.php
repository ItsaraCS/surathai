<?php require('header.php'); ?>
<?php require('nav.php'); ?>
<!--SECTION-->
<div class="section" style="margin-top: 10px;">
    <div class="col-md-12">
        <div class="row">
            <!--DATA-->
            <div class="col-md-4">
                <div class="panel panel-default" style="border-radius: 0; border: 0;">
                    <div class="panel-body" style="padding: 0;">
                        <table class="table table-striped search-detail-table" style="margin-top: 0; margin-bottom: 0;">
                            <thead>
                                <tr>
                                    <th class="text-center text-nowrap">ประเภทคดี</th>
                                    <th class="text-center text-nowrap">จำนวนคดี</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="col-md-5"><p>ไม่ทำบัญชี</p></td>
                                    <td class="col-md-7 text-center"><span id="NotAccount"></span></td>
                                </tr>
                                <tr>
                                    <td class="col-md-5"><p>ผลิต</p></td>
                                    <td class="col-md-7 text-center"><span id="Manufacture"></span></td>
                                </tr>
                                <tr>
                                    <td class="col-md-5"><p>ขาย</p></td>
                                    <td class="col-md-7 text-center"><span id="Sale"></span></td>
                                </tr>
                                <tr>
                                    <td class="col-md-5"><p>ขน</p></td>
                                    <td class="col-md-7 text-center"><span id="Transport"></span></td>
                                </tr>
                                <tr class="search-detail-total">
                                    <td class="col-md-5 text-center"><p>รวมทั้งสิ้น</p></td>
                                    <td class="col-md-7 text-center"><span id="Total"></span></td>
                                </tr>
                                <tr>
                                    <td class="col-md-12" colspan="2" style="padding: 10px !important;">
                                        <input class="form-control input-sm" id="accuseName" placeholder="ค้นหาชื่อผู้กระทำผิด">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!--MAP-->
            <div class="col-md-8 get-map">
                <div class="panel panel-default" style="height: 40vh; border-radius: 0; padding: 0;">
                    <div class="panel-body" style="padding-top: 0; padding-bottom: 0;">
                        <div class="row">
                            <div id="map" class="map" style="width: 100%; height: 40vh;"></div>
                                <div id="popup" class="ol-popup">
                                    <a href="#" id="popup-closer" class="ol-popup-closer"></a>
                                    <div id="popup-content"></div>
                                </div>
                            </div>
                            <div id="dvloading" class="loader"><div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--TABLE DATA-->
    <div class="col-md-12" style="margin-top: 10px;">
        <div class="row">
            <div class="panel panel-default" style="height: 34vh;">
                <div class="panel-heading text-center header-table">
                    <h3>รายการข้อมูล</h3>
                </div>
                <div class="panel-body" style="padding: 0;">
                    <div class="table-responsive" style="height: 20vh;">
                        <table class="table table-striped table-bordered search-table" style="margin-top: 0;"> 
                            <thead><tr></tr></thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="col-md-12 pagination"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require('popup.php'); ?>
<?php require('footer.php'); ?>