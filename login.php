<?php

    session_start();

    if(empty(@$_POST["password"])){
        echo "Please enter a password.";
        exit;
    }

    if(password_verify($_POST["password"], '[[[mypass]]]')){
        $_SESSION['password'] = $_POST["password"];
        $_SESSION['valid'] = true;
        echo "true";
        exit;
    }
    
    echo "Invalid code!";
    exit;

 ?>
