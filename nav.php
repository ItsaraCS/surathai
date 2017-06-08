<!--NAV-->
<?php 
    //$page = explode('/Surathai01/', $_SERVER['SCRIPT_NAME'])[1];
	// ADDED BY KUMPEE
	$p = explode('/', $_SERVER['SCRIPT_NAME']);
	$page = $p[count($p)-1];
	
    switch($page) {
        case 'map.php':
        case 'tax.php':
        case 'case.php':
        case 'license.php':
        case 'stamp.php':
        case 'factory.php':
?>

<div class="nav">
    <div class="container-fluid fixed nav-menu" style="margin-top: -5px; padding: 0;">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <a href="tax.php" class="btn btn-info btn-sm">งานภาษี</a>
                    <a href="case.php" class="btn btn-info btn-sm">งานปราบปราม</a>
                    <a href="license.php" class="btn btn-info btn-sm">ใบอนุญาต</a>
                    <a href="stamp.php" class="btn btn-info btn-sm">ข้อมูลแสตมป์</a>
                    <a href="factory.php" class="btn btn-info btn-sm">ข้อมูลโรงงาน</a>
                    <div class="btn-group" style="float: right;">
                        <a href="e_stamp.php" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown"> Export <span class="caret"></span></a>
                        <ul class="dropdown-menu" style="min-width: 0;">
                            <li><a href="#" class="export-file">PDF</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12" style="margin-top: 10px;">
            <div class="row">
                <div class="col-md-2">
                    <select class="form-control input-sm" id="year">
                        <option value="" selected>เลือกปีงบประมาณ</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control input-sm" id="region">
                        <option value="" selected>เลือกภาค</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control input-sm" id="area">
                        <option value="-999" selected>เลือกพื้นที่</option>
                    </select>
                </div>
                <div class="col-sm-4" id="btn-view"><a href="#" class="btn btn-danger btn-md">แสดงข้อมูล</a></div>
            </div>
        </div>
    </div>
</div>

<?php
            break;
        case 'search_tax.php':
        case 'search_case.php':
        case 'search_license.php':
        case 'search_stamp.php':
        case 'search_factory.php':
?>

<div class="nav">
    <div class="container-fluid fixed nav-menu" style="margin-top: -5px; padding: 0;">
        <div class="col-sm-2"><select class="form-control input-sm" id="year"><option value="" selected="true">เลือกปีงบประมาณ</option></select></div>
        <div class="col-sm-6">
            <a href="search_tax.php" class="btn btn-info btn-sm">งานภาษี</a>
            <a href="search_case.php" class="btn btn-info btn-sm">งานปราบปราม</a>
            <a href="search_license.php" class="btn btn-info btn-sm">ใบอนุญาต</a>
            <a href="search_stamp.php" class="btn btn-info btn-sm">ข้อมูลแสตมป์</a>
            <a href="search_factory.php" class="btn btn-info btn-sm">ข้อมูลโรงงาน</a>
            <a href="search_label.php" class="btn btn-info btn-sm">ฉลาก</a>
        </div>
        <div class="col-sm-1"><div class="row"><select class="form-control input-sm" id="region"><option value="" selected="true">เลือกภาค</option></select></div></div>
        <div class="col-sm-2"><select class="form-control input-sm" id="province"><option value="" selected="true">เลือกจังหวัด</option></select></div>    
        <div class="col-sm-1 btn-group text-center">
            <div class="row">
                <a href="#" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown"> Export <span class="caret"></span></a>&nbsp;
                <ul class="dropdown-menu" style="min-width: 100px;">
                    <li><a href="#" class="export-file">PDF</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
            break;
        case 'search_label.php':
?>

<div class="nav">
    <div class="container-fluid fixed nav-menu" style="margin-top: -5px; padding: 0;">
        <div class="col-sm-12">
            <a href="search_tax.php" class="btn btn-info btn-sm">งานภาษี</a>
            <a href="search_case.php" class="btn btn-info btn-sm">งานปราบปราม</a>
            <a href="search_license.php" class="btn btn-info btn-sm">ใบอนุญาต</a>
            <a href="search_stamp.php" class="btn btn-info btn-sm">ข้อมูลแสตมป์</a>
            <a href="search_factory.php" class="btn btn-info btn-sm">ข้อมูลโรงงาน</a>
            <a href="search_label.php" class="btn btn-info btn-sm">ฉลาก</a>
        </div>
    </div>
</div>

<?php
            break;
        case 'reporttax.php':
        case 'reportcase.php':
        case 'reportlicense.php':
        case 'reportstamp.php':
        case 'reportfactory.php':
?>

<div class="nav">
    <div class="container-fluid fixed nav-menu" style="margin-top: -5px; padding: 0;">
        <div class="col-sm-2"><select class="form-control input-sm" id="year"><option value="" selected="true">เลือกปีงบประมาณ</option></select></div>
        <div class="col-sm-5">
            <a href="reporttax.php" class="btn btn-info btn-sm">งานภาษี</a>
            <a href="reportcase.php" class="btn btn-info btn-sm">งานปราบปราม</a>
            <a href="reportlicense.php" class="btn btn-info btn-sm">ใบอนุญาต</a>
            <a href="reportstamp.php" class="btn btn-info btn-sm">ข้อมูลแสตมป์</a>
            <a href="reportfactory.php" class="btn btn-info btn-sm">ข้อมูลโรงงาน</a>
        </div>
        <div class="col-sm-1"><div class="row"><select class="form-control input-sm" id="region"><option value="" selected="true">เลือกภาค</option></select></div></div>
        <div class="col-sm-2"><select class="form-control input-sm" id="province"><option value="" selected="true">เลือกจังหวัด</option></select></div>    
        <div class="col-sm-1 btn-group text-center">
            <div class="row">
                <a href="#" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown"> Export <span class="caret"></span></a>&nbsp;
                <ul class="dropdown-menu" style="min-width: 100px;">
                    <li><a href="#" class="export-file">Excel</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
            break;
        case 'e_factory.php':
        case 'e_illegal.php':
        case 'e_stamp.php':
?>

<div class="nav">
    <div class="container-fluid fixed nav-menu" style="margin-top: -5px; padding: 0;">
        <div class="col-md-6 text-left">
            <a href="e_factory.php" class="btn btn-info btn-sm">โรงงาน</a>
            <a href="e_illegal.php" class="btn btn-info btn-sm">คดี</a>
            <div class="btn-group">
                <a href="e_stamp.php" class="btn btn-info btn-sm dropdown-toggle" data-stamp-type="<?php echo (isset($_GET['stamp-type'])) ? $_GET['stamp-type'] : 0; ?>" data-toggle="dropdown"> แสตมป์ <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="e_stamp.php?stamp-type=0" class="export-file">เต็มเล่ม</a></li>
                    <li><a href="e_stamp.php?stamp-type=1" class="export-file">แบ่งขาย</a></li>
                </ul>
            </div>
        </div>
        <div class="col-md-6 text-right" style="margin-top: 5px;">
            <span class="label label-success" id="Province" data-provice="0" style="padding: 0 15px; font-size: 18px; border-radius: 0;">อยู่ที่จังหวัด : <span id="ProvinceTXT"></span></span>
        </div>
    </div>
</div>

<?php 
            break;
    } 
?>