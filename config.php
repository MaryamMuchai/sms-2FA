<?php
//connecting to the database

    $conn = mysqli_connect("localhost", "root", "password", "sms2fa");

    //check connection
    if(!$conn){
        echo 'Connection error: ' . mysqli_connect_error();
    }
    ?>