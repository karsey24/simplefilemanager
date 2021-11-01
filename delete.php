<?php

    session_start();

    if($_SESSION['valid'] !== true){
        echo '<a href="[[[HOMELINK]]]">leave</a>';
        exit;
    }

    if(password_verify($_SESSION['password'], '[[[mypass]]]')){
        if(!empty($_POST["id"]) && !empty($_POST["action"])){
            if($_POST["action"] != "delete") {
                echo "ERROR!";
                exit;
            }
            $targ = pathinfo($_POST["id"])["basename"];

            $result = unlink("/var/www/site/$targ");

            var_dump($result);
            exit;
        }
    }

    echo "ERROR!";
    exit;

 ?>
