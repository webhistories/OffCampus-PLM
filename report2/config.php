<?php
    session_start();

    $_SESSION['success'] = ""; 
    $connect = mysqli_connect('localhost:3309', 'root', '123456', 'gp_test_copy');
?>