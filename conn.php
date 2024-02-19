<?php
    $conn = mysqli_connect("localhost", "root", "") or die("ไม่สามารถเชื่อมต่อฐานข้อมูลได้: " . mysqli_connect_error()); 
    mysqli_select_db($conn, "tourlism_db") or die("ไม่พบฐานข้อมูล: " . mysqli_error($conn)); 
    mysqli_query($conn, "SET NAMES UTF8");
?>
