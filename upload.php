<?php
    session_start();

    if($_SESSION['valid'] !== true){
        echo '<a href="[[[HOMELINK]]]">leave</a>';
        exit;
    }

    if(password_verify($_SESSION['password'], '[[[mypass]]]') && array_key_exists("file", $_FILES)){
        $filename = basename($_FILES["file"]["name"]);

        $filesize = $_FILES['file']['size'];

        $location = "/var/www/site/".$filename;

        if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
            echo "Uploaded $filename succesfully, size $filesize bytes";
        } else {
            echo "Unspecified error";
        }
    }

?>
