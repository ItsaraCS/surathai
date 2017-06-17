<!DOCTYPE html>
<html>
<head>
	<title></title>
    <script src="js/table_to_excel.js" type="text/javascript"></script>
</head>
<body OnLoad="JavaScript:init();">
<script type="text/javascript">
    var dataFromParent;    
    function init() {
        document.write(dataFromParent);
        exportToExcel('headerTable');
        setTimeout(closetab, 300);
  		// window.open('', '_self', ''); 
    }
    var closetab = function() {
        window.close();
    }
</script>
</body>
</html>
