<?php include 'Navbar.php';
ob_start();
session_start();
?>
<html>

<head>
  <link rel="stylesheet" href="../../ExternalStyle/FormStyle.css" />
  <link rel="stylesheet" href="../../ExternalStyle/GeneralStyle.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <title>insert products</title>
</head>

<body>
  <style>
        body{
      background-color:#E3E3E3;
    }
    .CardGrid{
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(290px,50px));
      row-gap:2px;
      margin-left:80px;
      margin-top: 50px;
      margin-bottom: 50px;
      
    }

    .Grid2 {
      display: grid;
      grid-template-columns: auto auto;
    }

    .card-body {
      display: grid;
      grid-template-columns: auto auto;
      padding: 30px 0px;


    }

    .card {
      text-align: center;
      border-radius: 10px;
      width: 18rem;
      box-shadow: 0px 14px 28px rgba(0, 0, 0, 0.25), 0px 10px 10px rgba(0, 0, 0, 0.22);
    }

    .Lform {
      padding-top: 50px;
      margin-top: 30px;
    }
  </style>
  <?php

  include "../../db_connection.php";
  $RequestID = $_SESSION['RID'];
  echo "<br><h1 style='color:;margin-left:5px;'>Request Number $RequestID </h1>";
  $sql = "SELECT * FROM requests WHERE ID='$RequestID'";
  $result = $conn->query($sql);
  $row = $result->fetch_array();
  if ($row['status'] == 'done') {
    echo "
        <style>
        .Lform{
        display:none;
        }
        </style>
        ";
    echo "<big style='color:;margin-left:5px;'>The Request Is Finished , You can see the request history </big><hr>";
  }
  ?>

  <div class="Grid2">
    <div class="Lform">
      <h2> insert Product to the client </h2>
      <span>the time of Processing the product <b id="counter">0</b> seconds</span>
      <br>
      <form method="post">
        <select name="AddP" required>
          <?php
          include "../../db_connection.php";
          $RequestID = $_SESSION['RID'];
          $workerCompetence = $_SESSION['competence'];
          $sql = "SELECT * FROM products";
          $result = $conn->query($sql);
          while ($row = $result->fetch_assoc()) {
            $PID = $row['ID'];
            $imgSrc = $row['img'];
            if ($row['quantity'] > 0) {
              $matchedTo = $row['matchedTo'];
              $res = strpos($matchedTo, $workerCompetence);
              if (is_numeric($res)) {
                echo "
                    <option value=$PID>
                      Products ID :", $row['ID'],
                  " name :", $row['name'],
                  " price: :", $row['price'],
                  " Matched to  :", $row['matchedTo'],
                  " quantity :", $row['quantity'],
                  "</option>";
              }
            }
          }
          ?>
        </select>
        <br>
        <input type="text" name="quantity" placeholder="set quantity">
        <input type="submit" value="add product" name="addProduct">
        <input type="submit" value="finish the turn" name="closeturn" style="background-color:red">
        <input type="hidden" id="counterValue" name="counterValue" value="0">
      </form>
    </div>

    <div class="Lform">
      <h2> remove the product you want </h2><br>
      <form method='post'>
        <select name="Removing" required>
          <?php
          $sql = "SELECT * FROM turnproducts WHERE RequestID='$RequestID'";
          $result = $conn->query($sql);
          while ($row = $result->fetch_assoc()) {
            $deleteProductID = $row['ProductID'];
            echo "
                <option value=$deleteProductID>
                  Product ID :", $row['ProductID'], "
                  product name :", $row['name'], "
                  quantity :", $row['quantity'], "
                </option>";
          }
          ?>
        </select>
        <br>
        <input type="text" name="deleteQuantity" placeholder="set quantity" required>
        <input type="submit" value="delete Product" name="deleteProduct" style="background-color:red">
      </form>
    </div>
  </div>
  <div class="CardGrid">
    <?php
    include "../../db_connection.php";
    $RequestID = $_SESSION['RID'];
    $sql = "SELECT * FROM turnproducts WHERE RequestID='$RequestID'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $productID = $row['ProductID'];
        $quantity = $row['quantity'];
        $sql = "SELECT * FROM products WHERE ID='$productID'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $imgSrc = $row['img'];
        echo "
        <div class='card' style='width: 18rem;'>
          <img src='$imgSrc' class='card-img-top' style='border-radius:10px;margin-top:20px'>
          <h4>";
        $productSearch = mysqli_query($conn, "SELECT* FROM products WHERE ID='$productID'");
        $row2 = mysqli_fetch_array($productSearch);
        echo $row2['name'],
          "</h4>
          <div class='card-body'>
            <b>productID : </b>", $productID, "
            
            <b>quantity : </b>", $quantity, "
          </div>
        </div>";
      }
    } else {
      echo "<center><h1>No Product Inserted</h1></center>";
    }
    ?>
  </div>
  <script>
    let counter = 0;
    setInterval(function () {
      counter++;
      document.getElementById("counter").innerHTML = counter;
      document.getElementById("counterValue").value = counter;
    }, 1000);
  </script>
</body>

</html>
<?php

$RequestID = $_SESSION['RID'];
include "../../db_connection.php";
if (isset($_POST['addProduct'])) {
  $ProductID = $_POST['AddP'];
  $quantity = $_POST['quantity'];
  $counterValue = $_POST['counterValue'];

  $sql = "UPDATE products SET avgCount = avgCount + 1 WHERE ID = '$ProductID'";
  $conn->query($sql);
  $Search = mysqli_query($conn, "SELECT * FROM products WHERE ID = '$ProductID'");
  $row = mysqli_fetch_array($Search);

  $newexpectedFixTime = (($counterValue + $row['expectedFixTime']) / $row['avgCount']) / $quantity;
  $sql = "update products set expectedFixTime='$newexpectedFixTime' WHERE ID = '$ProductID'";
  $conn->query($sql);

  if (!(is_numeric($quantity))) {
    echo "<script>alert('you should type quantity');</script>";
    return;
  }
  if ($quantity <= 0) {
    echo "<script>alert('quantity lo hokit');</script>";
  }
  $sql = "SELECT * FROM turnproducts WHERE RequestID='$RequestID' AND ProductID='$ProductID'";
  $result = $conn->query($sql);
  $numofcloumns = $result->num_rows;
  //explain:if this product exist in the table just update the quantity don't insert new product
  if ($numofcloumns > 0) {
    //explain:update quantity in products table
    $sql = "SELECT * FROM products WHERE ID='$ProductID'";
    $Search = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($Search)) {
      $oldquantity = $row['quantity'];
      $productName = $row['name'];
    }
    $newquantity = $oldquantity - $quantity;
    //explain : if the quantity you typed exist in the mlai
    if ($quantity <= $oldquantity and $quantity > 0) {
      $sql = "UPDATE products SET quantity='$newquantity' WHERE ID='$ProductID' ";
      $conn->query($sql);
      //explain:update quantity in turnproducts table
      $sql = "SELECT * FROM turnproducts WHERE RequestID='$RequestID' AND ProductID='$ProductID'";
      $Search = mysqli_query($conn, $sql);
      while ($row = mysqli_fetch_array($Search)) {
        $oldproductturnquantity = $row['quantity'];
      }
      $newturnproductquantity = $oldproductturnquantity + $quantity;
      $sql = "UPDATE turnproducts SET quantity='$newturnproductquantity' WHERE RequestID='$RequestID' AND ProductID='$ProductID'";
      if ($conn->query($sql) === TRUE) {
        echo "
        <script type='text/javascript'>alert('quantity updated');</script>
        ";
        header("refresh:0");
      }
    } else {
      echo "
      <script type='text/javascript'>alert('quantity not avaible ');</script>
      ";
    }
  } else {
    //explain:if the product not exist in his request :insert the product
    $sql = "SELECT * FROM products WHERE ID='$ProductID'";
    $Search = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($Search)) {
      $oldquantity = $row['quantity'];
      $productName = $row['name'];
    }
    $newquantity = ($oldquantity - $quantity);
    if ($quantity <= $oldquantity) {
      $sql = "SELECT * FROM products WHERE ID='$ProductID'";
      $Search = mysqli_query($conn, $sql);
      while ($row = mysqli_fetch_array($Search)) {
        $productName = $row['name'];
      }
      $sql = "INSERT INTO turnproducts (RequestID,ProductID,quantity)
      VALUES ('$RequestID','$ProductID','$quantity')";
      $conn->query($sql);

      $sql = "UPDATE products SET quantity='$newquantity' WHERE ID='$ProductID' ";
      $conn->query($sql);
      echo "
        <script type='text/javascript'>alert('product inserted to the turn');</script>
        ";
      header("refresh:0");
    } else {
      echo "
        <script type='text/javascript'>alert('this quantity Not avaible');</script>
        ";
    }
  }
}
if (isset($_POST['closeturn'])) {
  $sql = "UPDATE requests SET status='done' WHERE ID='$RequestID'";
  if ($conn->query($sql) === TRUE) {
    $WID = $_SESSION['id'];
    $ToltalAllPrices = 0;
    $Search = mysqli_query($conn, "SELECT* FROM turnproducts WHERE RequestID='$RequestID'");
    while ($row = mysqli_fetch_array($Search)) {
      $TurnQuantity = $row['quantity'];
      $PID = $row['ProductID'];
      $Search = mysqli_query($conn, "SELECT* FROM products WHERE ID='$PID'");
      $row = mysqli_fetch_array($Search);
      $productPrice = $row['price'];
      $TotalProductPrice = $productPrice * $TurnQuantity;
      $ToltalAllPrices += $TotalProductPrice;
    }
    if ($ToltalAllPrices > 0) {
      $sql = "INSERT INTO receipt (RequestID,WorkerID,TotalPrice,status)
        VALUES ('$RequestID','$WID','$ToltalAllPrices','Not Paid')";
      if ($conn->query($sql) === TRUE) {
        echo "
            <script type='text/javascript'>alert('the reciept created');</script>
            ";
      }
    } else {
      $sql = "INSERT INTO receipt (RequestID,WorkerID,TotalPrice,status)
        VALUES ('$RequestID','$WID','$ToltalAllPrices','Paid')";
      if ($conn->query($sql) === TRUE) {
        echo "
            <script type='text/javascript'>alert('the reciept created');</script>
            ";
      } else {
        echo "Error in sql query";
      }
    }
    echo "<script type='text/javascript'>alert('the turn closed');</script>";

    $sql = "SELECT ProblemID FROM requests WHERE ID='$RequestID'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $ProblemID = $row['ProblemID'];

    $sql2 = "SELECT * FROM turnproblems WHERE ProblemID='$ProblemID'";
    $result2 = $conn->query($sql2);
    // if the products for the problem not exist (new problem)
    if ($result2->num_rows <= 0) {
      $sql3 = "SELECT *  FROM turnproducts WHERE RequestID = '$RequestID'";
      $result3 = $conn->query($sql3);
      $row3 = $result3->fetch_assoc();
      $PID = $row3['ProductID'];
      $QUANTITY = $row3['quantity'];
      $sql4 = "INSERT INTO turnproblems (ProductID, ProblemID, quantity) 
            VALUES ('$PID','$ProblemID','$QUANTITY' )";
      if ($conn->query($sql4) === TRUE) {
        echo "<script>alert('New record created successfully');</script>";
      } else {
        echo "Error: " . $insertSql . "<br>" . $conn->error;
      }
    }

    header("Refresh:0");
  } else {
    echo '<center>', '<h6 style="color:red">', "Error: " . $sql . "<br>" . $conn->error;
  }

}

if (isset($_POST['deleteProduct'])) {
  $deleteQuantity = $_POST['deleteQuantity'];
  $deleteProductID = $_POST['Removing'];

  $sql = "SELECT * FROM turnproducts WHERE RequestID='$RequestID' AND ProductID='$deleteProductID'";
  $result = $conn->query($sql);
  $row = $result->fetch_array();
  $oldTurnProductQuantity = $row['quantity'];

  $sql = "SELECT * FROM products WHERE ID='$deleteProductID'";
  $result = $conn->query($sql);
  $row = $result->fetch_array();
  $oldProductQuantity = $row['quantity'];

  if ($deleteQuantity > $oldTurnProductQuantity or $deleteQuantity <= 0) {
    echo "<script>alert('this quantity is bigger than the inserted or not match');</script>";
  } else {
    if ($oldProductQuantity == 1) {
      $sql = "DELETE FROM turnproducts WHERE RequestID='$RequestID' AND ProductID='$deleteProductID'";
      if ($conn->query($sql) === TRUE) {
        //update product quantity=quantity+1;
        $newQuantity = $oldProductQuantity + 1;
        $sql = "UPDATE products SET quantity='$newQuantity' WHERE ID='$deleteProductID'";
        if ($conn->query($sql) === TRUE) {
          echo "<script>
            alert('the quantity updated');
            </script>";
        }
      }
      echo "<script>alert('the product deleted');</script>";
    } else {
      //ُexplain:update product quantity=quantity+$deleteQuantity
      $newProductQuantity = $oldProductQuantity + $deleteQuantity;
      $sql = "UPDATE products SET quantity='$newProductQuantity' WHERE ID='$deleteProductID'";
      if ($conn->query($sql) === TRUE) {
      } else {
        echo "error";
      }
      $newTurnProductQuantity = $oldTurnProductQuantity - $deleteQuantity;
      //explain:update turn product quantity=quantity=$deleteQuantity
      $sql = "UPDATE turnproducts SET quantity='$newTurnProductQuantity' WHERE RequestID='$RequestID' AND ProductID='$deleteProductID'";
      if ($conn->query($sql) === TRUE) {
        echo "
      <script type='text/javascript'>alert('quantity updated');</script>
      ";
        header("refresh:0");
      } else {
        echo "error";
      }
    }
  }
  $sql = "SELECT * FROM turnproducts WHERE RequestID='$RequestID' AND ProductID='$deleteProductID'";
  $result = $conn->query($sql);
  $row = $result->fetch_array();
  $oldTurnProductQuantity = $row['quantity'];
  if ($oldTurnProductQuantity == 0) {
    $sql = "DELETE FROM turnproducts WHERE RequestID='$RequestID' AND ProductID='$deleteProductID'";
    if ($conn->query($sql) === TRUE) {

    } else {
      echo "
        <script type='text/javascript'>alert('error in sql query');</script>
        ";
    }
  }
}
?>