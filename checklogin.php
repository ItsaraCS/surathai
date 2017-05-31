<?php
    $user = $_POST['username'];
    $pwd = $_POST['password'];
    
    if ($user != '' && $pwd != '') {
        header( "refresh: 0; url=/Surathai01/tax.php" ); exit(0);
        
    } else { header( "refresh: 0; url=/Surathai01/login.php" ); exit(0); }