<?php
 $dbhost = "localhost";
 $dbuser = "root";
 $dbpass = "Abd2812";
 $db = "newdatabase";
 $conn = mysqli_connect($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
?>