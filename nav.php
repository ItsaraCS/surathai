<!--NAV-->
<?php 
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
    <div class="container-fluid fixed nav-menu" style="margin-top: -10px; padding: 0;">
        <div class="col-md-5">
        <ul class="nav navbar-nav nav-sec">
                <li><a href="tax.php" class="btn-primary" style="margin: 2px;padding: 1.3vh;"><span style="font-size: 2.5vh;">งานภาษี</span></a></li> 
                <li><a href="case.php" class="btn-primary" style="margin: 2px;padding: 1.3vh;"><span style="font-size: 2.5vh;">งานปราบปราม</span></a></li> 
                <li><a href="license.php" class="btn-primary" style="margin: 2px;padding: 1.3vh;"><span style="font-size: 2.5vh;">ใบอนุญาต</span></a></li> 
                <li><a href="stamp.php"" class="btn-primary" style="margin: 2px;padding: 1.3vh;"><span style="font-size: 2.5vh;">ข้อมูลสแตมป์</span></a></li> 
                <li><a href="factory.php" class="btn-primary" style="margin: 2px;padding: 1.3vh;"><span style="font-size: 2.5vh;">ข้อมูลโรงงาน</span></a></li> 
                  
            </ul>  
        </div>
            <div class="col-md-2 " ><select class="form-control input-md" id="year"><option value="" selected>เลือกปีงบประมาณ</option></select></div>
            <div class="col-md-1 problem" ><select class="form-control input-md" id="region"><option value="" selected>เลือกภาค</option></select></div>
            <div class="col-md-2 "><select class="form-control input-md" id="area"><option value="-999" selected>เลือกพื้นที่</option></select>
            </div>
            <div class="col-md-1" id="btn-view"><a href="#" class="btn btn-danger btn-md btn-mobile-center">แสดงข้อมูล</a></div>

            <div class="col-md-1 btn-group text-center export-menu">
                <div class="row export-menu">
                    <a href="#" class="btn btn-primary btn-md dropdown-toggle" data-toggle="dropdown"> Export <span class="caret"></span></a>&nbsp;
                    <ul class="dropdown-menu" style="min-width: 100px;">
                        <li><a href="#" class="export-file">PDF</a></li>
                    </ul>
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
    <div class="container-fluid fixed nav-menu" style="margin-top: -10px; padding: 0;">
        <div class="col-md-6">
        <ul class="nav navbar-nav nav-sec">
                <li><a href="search_tax.php" class="btn-primary" style="margin: 2px;padding: 1.3vh;"><span style="font-size: 2.5vh;">งานภาษี</span></a></li> 
                <li><a href="search_case.php" class="btn-primary" style="margin: 2px;padding: 1.3vh;"><span style="font-size: 2.5vh;">งานปราบปราม</span></a></li> 
                <li><a href="search_license.php" class="btn-primary" style="margin: 2px;padding: 1.3vh;"><span style="font-size: 2.5vh;">ใบอนุญาต</span></a></li> 
                <li><a href="search_stamp.php" class="btn-primary" style="margin: 2px;padding: 1.3vh;"><span style="font-size: 2.5vh;">ข้อมูลแสตมป์</span></a></li> 
                <li><a href="search_factory.php" class="btn-primary" style="margin: 2px;padding: 1.3vh;"><span style="font-size: 2.5vh;">ข้อมูลโรงงาน</span></a></li> 
                <li><a href="search_label.php" class="btn-primary" style="margin: 2px;padding: 1.3vh;"><span style="font-size: 2.5vh;">ฉลาก</span></a></li>   
            </ul>  
        </div>
            <div class="col-md-2 nav-select-year" ><select class="form-control input-md" id="year"><option value="" selected="true">เลือกปีงบประมาณ</option></select></div>
            <div class="col-md-1 problem nav-select" ><select class="form-control input-md" id="region"><option value="" selected="true">เลือกภาค</option></select></div>
            <div class="col-md-2 nav-select"><select class="form-control input-md" id="province"><option value="" selected="true">เลือกจังหวัด</option></select>
            </div>
            <div class="col-md-1 btn-group text-center export-menu">
                <div class="row export-menu">
                    <a href="#" class="btn btn-primary btn-md dropdown-toggle" data-toggle="dropdown"> Export <span class="caret"></span></a>&nbsp;
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
        <div class="col-md-12">
            <ul class="nav navbar-nav nav-sec">
                <li><a href="search_tax.php" class="btn-primary" style="margin: 2px;padding: 1.3vh;"><span style="font-size: 2.5vh;">งานภาษี</span></a></li>
                <li><a href="search_case.php" class="btn-primary" style="margin: 2px;padding: 1.3vh;"><span style="font-size: 2.5vh;">งานปราบปราม</span></a></li>
                <li><a href="search_license.php" class="btn-primary" style="margin: 2px;padding: 1.3vh;"><span style="font-size: 2.5vh;">ใบอนุญาต</span></a></li>
                <li><a href="search_stamp.php" class="btn-primary" style="margin: 2px;padding: 1.3vh;"><span style="font-size: 2.5vh;">ข้อมูลแสตมป์</span></a></li> 
                <li><a href="search_factory.php" class="btn-primary" style="margin: 2px;padding: 1.3vh;"><span style="font-size: 2.5vh;">ข้อมูลโรงงาน</span></a></li>
                 <li><a href="search_label.php" class="btn-primary" style="margin: 2px;padding: 1.3vh;"><span style="font-size: 2.5vh;">ฉลาก</span></a></li>     
            </ul>  
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
    <div class="container-fluid fixed nav-menu" style="margin-top: -10px; padding: 0;">
        <div class="col-md-6">
        <ul class="nav navbar-nav nav-sec">
                <li><a href="reporttax.php" class="btn-primary" style="margin: 2px;padding: 1.3vh;"><span style="font-size: 2.5vh;">งานภาษี</span></a></li> 
                <li><a href="reportcase.php" class="btn-primary" style="margin: 2px;padding: 1.3vh;"><span style="font-size: 2.5vh;">งานปราบปราม</span></a></li> 
                <li><a href="reportlicense.php" class="btn-primary" style="margin: 2px;padding: 1.3vh;"><span style="font-size: 2.5vh;">ใบอนุญาต</span></a></li> 
                <li><a href="reportstamp.php" class="btn-primary" style="margin: 2px;padding: 1.3vh;"><span style="font-size: 2.5vh;">ข้อมูลแสตมป์</span></a></li> 
                <li><a href="reportfactory.php" class="btn-primary" style="margin: 2px;padding: 1.3vh;"><span style="font-size: 2.5vh;">ข้อมูลโรงงาน</span></a></li>   
            </ul>  
        </div>
            <div class="col-md-2 nav-select-year" ><select class="form-control input-md" id="year"><option value="" selected="true">เลือกปีงบประมาณ</option></select></div>
            <div class="col-md-1 problem nav-select" ><select class="form-control input-md" id="region"><option value="" selected="true">เลือกภาค</option></select></div>
            <div class="col-md-2 nav-select"><select class="form-control input-md" id="province"><option value="" selected="true">เลือกจังหวัด</option></select>
            </div>
            <div class="col-md-1 btn-group text-center export-menu">
                <div class="row export-menu">
                    <a href="#" class="btn btn-primary btn-md dropdown-toggle" data-toggle="dropdown"> Export <span class="caret"></span></a>&nbsp;
                    <ul class="dropdown-menu" style="min-width: 100px;">
                        <li><a href="#" class="export-file" onclick="tableToExcel('getexc')">Excel</a></li>
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
    <div class="container-fluid fixed nav-menu" style="margin-top: -10px; padding: 0;">
        <div class="col-md-6 text-left">
           <ul class="nav navbar-nav nav-sec">
                <li><a href="e_factory.php" class="btn-primary" style="margin: 2px;padding: 1.3vh;"><span style="font-size: 2.5vh;">โรงงาน</span></a></li> 
                <li><a href="e_illegal.php" class="btn-primary" style="margin: 2px;padding: 1.3vh;"><span style="font-size: 2.5vh;">คดี</span></a></li> 
            <div class="btn-group eForm-Zoning-Stamp">
                <a href="e_stamp.php" class="btn btn-primary btn-sm dropdown-toggle" data-stamp-type="<?php echo (isset($_GET['stamp-type'])) ? $_GET['stamp-type'] : 0; ?>" data-toggle="dropdown"> แสตมป์ <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="e_stamp.php?stamp-type=0" class="export-file">เต็มเล่ม</a></li>
                    <li><a href="e_stamp.php?stamp-type=1" class="export-file">แบ่งขาย</a></li>
                </ul>
            </div>
            </ul> 
        </div>
        <div class="col-md-6 text-right" style="margin-top: 5px;">
            <span class="label label-success" id="Province" data-provice="0" style="padding: 0 15px; font-size: 2.5vh; border-radius: 0;">อยู่ที่จังหวัด : <span id="ProvinceTXT"></span></span>
        </div>
    </div>
</div>

<?php 
            break;
    } 
?>