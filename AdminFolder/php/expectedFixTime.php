
<html>
    <head>
    <link rel="stylesheet" href="../../ExternalStyle/FormStyle.css" />
    <link rel="stylesheet" href="../../ExternalStyle/GeneralStyle.css" />
    <title> UPDATE expected fix time</title>
    </head>
    <style>
        body {
    font-family: "Century Gothic", CenturyGothic, AppleGothic, sans-serif;
    font-style: normal;
    font-variant: normal; 
    font-weight: 700; 
    line-height: 26.4px;
    background-color:#e3e1ff ;
    margin: auto;
    background-color:#e3e1ff;
  }
  .Lform{
        margin-top: 150px;
    }
    </style>
    <body>
        <div class="Lform">
            <form method="post" >
                <h3>Update expected Fix Time</h3>
                <input type="text"  name="newtime" required placeholder="choose the time number"><br>
                <input type="submit" value="Update" name="submit">
            </form>
        </div>
    </body>
</html>
<?php
    include "../../db_connection.php";
    session_start();
    $Productid=$_SESSION['ProducID'];
    if(isset($_POST['submit'])){
        $newtime = $_POST['newtime'];
        if($newtime<=0){
            echo"<script>alert('type Time >0');</script> ";
            return;
        }
        $sql = "UPDATE products SET expectedFixTime='$newtime' WHERE ID='$Productid'";
        if ($conn->query($sql) === TRUE) {
            echo "<script>
            alert('the expectedFixTime updated');
            </script>";
            echo "<script>
            window.close();
            </script>";
        }
    }
?>
