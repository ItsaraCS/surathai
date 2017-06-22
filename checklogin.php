<?php
    $user = $_POST['username'];
    $pwd = $_POST['password'];
    
    if ($user != '' && $pwd != '') {
        header( "refresh: 0; url=".$_SERVER['REQUEST_URI']."tax.php" ); exit(0);
        
    } else { header( "refresh: 0; url=".$_SERVER['REQUEST_URI']."login.php" ); exit(0); }