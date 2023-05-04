
<html>
    <head>
    <link rel="stylesheet" href="../../ExternalStyle/FormStyle.css" />
    <link rel="stylesheet" href="../../ExternalStyle/GeneralStyle.css" />
    <title> UPDATE QUANTITY</title>
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
                <h3>Update Quantity</h3>
                <input type="text"  name="newquantity" required placeholder="choose the quantity"><br>
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

        $newquantity = $_POST['newquantity'];
        if($newquantity<=0){
            echo"<script>alert('type quantity >0');</script> ";
            return;
          }
        $sql = "UPDATE products SET quantity='$newquantity' WHERE ID='$Productid'";
        if ($conn->query($sql) === TRUE) {
            echo "<script>
            alert('the quantity updated');
            </script>";
            echo "<script>
            window.close();
            </script>";
        }
    }
?>
