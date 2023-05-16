<?php
ob_start();
  include 'Navbar.php';
session_start();
?>
<html>
  <head>
      <link rel="stylesheet" href="../../ExternalStyle/GeneralStyle.css"/>
    <link rel="stylesheet" href="../../ExternalStyle/FormStyle.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Products</title>
  </head>
  <style>
      .Lform{
        display:none;
      }
      .Grid{
    display: grid;
    grid-template-columns:290px 290px 290px 290px; 
    column-gap: 40px;
    row-gap: 20px;
    margin-left: 30px;
    }
    body{
        background-color: #e3e1ff;
      }
      .card{
        box-shadow: 10px 10px lightblue;
      }
</style>
  </style>
  <body>  
      <center><form method='post'><input type='submit' name='addProduct' value='Add Product' style='background-color:rgb(65, 158, 250);width:270px;'></form></center>
      <div class="Lform">
        <form method="post" onsubmit="return validateForm()">
            <h3>Add a product:</h3>
            Product name: <input type="text" id="name" name="name" required><br>
            Product price: <input type="text" id="price" name="price" required><br>
            Product quantity: <input type="text" id="quantity" name="quantity" required><br>
            expected fix time:<input type="text" name="expectedFixTime" required><br>
            full image src:<input type="text" name="img" required><br>
            Used For:<br>
            <input type="checkbox" id="mercedes" name="mercedes" value="mercedes">
            <label for="mercedes"> mercedes</label>
            <input type="checkbox" id="bmw" name="bmw" value="bmw">
            <label for="bmw">  bmw</label>
            <input type="checkbox" id="skoda" name="skoda" value="skoda">
            <label for="skoda"> skoda</label>
            <input type="checkbox" id="volkswagen" name="volkswagen" value="volkswagen">
            <label for="volkswagen"> volkswagen</label>
            <input type="checkbox" id="audi" name="audi" value="audi">
            <label for="audi"> audi</label>
            <br><br>
            <input type="submit" value="Add" name="submit">
        </form>
      </div>
    <br>
        <div class="Grid">
      <?php
        include "../../db_connection.php";
          $buttonID=0;
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $id = $row['ID'];
          $name = $row['name'];
          $price = $row['price'];
          $matchedTo = $row['matchedTo'];
          $expectedFixTime=$row['expectedFixTime'];
          $quantity = $row['quantity'];
          $imgSrc = $row['img'];
          echo "<div class='card' style='width: 18rem;
          ";if($quantity<=0){echo"background-color:rgb(167, 5, 5) ;";}
          
                echo"'>
              <img class='card-img-top' src='$imgSrc' style='width:286px;height:286px;'>
              <div class='card-body'>
              <h5 class='card-title'>$name</h5>
              <p> price :$price<br> 
              <p> expected Fix Time in minutes:$expectedFixTime<br> 
              Matched to: $matchedTo<br>
              quantity: $quantity</p></br>";
              echo
              "<form method='post'><input type='submit' name='$buttonID' value='update  price'style='width:150px;'></form>";
              if (isset($_POST[$buttonID])) {
                $_SESSION['ProducID']= $row['ID'];
              echo "<script>
              var myWindow = window.open('updateproductprice.php','','width=700px,height=500px');
              </script>";
              }
              $buttonID++;
              echo
              "<form method='post'><input type='submit' name='$buttonID' value='update  quantity' style='width:150px;'></form>";
              if (isset($_POST[$buttonID])) {
                $_SESSION['ProducID']=$row['ID'];
                echo "<script>
                var myWindow = window.open('updateproductquantity.php','','width=700px,height=500px');
                </script>";
              }
              $buttonID++;
              echo
              "<form method='post'><input type='submit' name='$buttonID' value='update expected fix time:' style='width:150px;'></form>";
              if (isset($_POST[$buttonID])) {
                $_SESSION['ProducID']=$row['ID'];
                echo "<script>
                var myWindow = window.open('expectedFixTime.php','','width=700px,height=500px');
                </script>";
              }
              $buttonID++;
              echo
              "<form method='post'><input type='submit' name='$buttonID' value='Remove' style='background-color:red;width:150px;' ></form>";
              if (isset($_POST[$buttonID])){
                $sql = "UPDATE products SET quantity='0'  WHERE ID='$id'";
                if ($conn->query($sql) === TRUE) {
                  echo "<script>alert('product unavailable in the mlai Now');</script>";
                  header("refresh:0");
                }
                else{
                  echo "<script>alert('error in the inserting');</script>";
                }
              }
              $buttonID++;
              echo
              "</div>
              </div>";
        }
          }
        else {
          echo "0 results";
        }
      ?>
      </div>
      <script>
  function validateForm() {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    var checkedOne = Array.prototype.slice.call(checkboxes).some(x => x.checked);
    if (!checkedOne) {
        alert("Please select at least one option");
        return false;
    }
  }
</script>
  </body>
</html>
  <?php
    if (isset($_POST['addProduct'])){
          showAddProductDiv();
        } 
        function showAddProductDiv(){
          echo"
          <style>
            .Lform{
              display:block;
            }
          </style>
          ";
        } 
        

  $matchedTo = "";
  if (isset($_POST['submit'])){
    if (isset($_POST['mercedes']) == true){
        $matchedTo = $matchedTo .''. 'mercedes ';
    }if (isset($_POST['bmw']) == true){
        $matchedTo = $matchedTo .''. 'bmw ';
    }if (isset($_POST['skoda']) == true){
        $matchedTo = $matchedTo .''. 'skoda ';
    }if (isset($_POST['volkswagen']) == true){
        $matchedTo = $matchedTo .''. 'volkswagen ';
    } if (isset($_POST['audi'])==true){
      $matchedTo = $matchedTo .''. 'audi';
    }  
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $expectedFixTime=$_POST['expectedFixTime'];
    $imgSrc=$_POST['img'];
    if($price<=0){
      echo"<script>alert('type price >0');</script> ";
      return;
    }
    if($quantity<=0){
      echo"<script>alert('type quantity >0');</script> ";
      return;
    }
    $sql = "INSERT INTO products (name,price,quantity,matchedTo,img,expectedFixTime)
    VALUES ('$name','$price','$quantity','$matchedTo','$imgSrc','$expectedFixTime')";
    if ($conn->query($sql) === TRUE) {
        echo"<script>alert('product inserted');</script>";
        header("Refresh:2;url=adminProducts.php");
    }
    else {
      echo"<script>alert('error in inserted');</script> ";
    }
  }
  ob_end_flush();
?>