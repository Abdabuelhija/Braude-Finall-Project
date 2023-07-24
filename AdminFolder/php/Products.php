<?php
ob_start();
include 'Navbar.php';
session_start();
?>
<html>

<head>
  <link rel="stylesheet" href="../style/Products.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
    crossorigin="anonymous"></script>

  <title>Products</title>
  <style>
    input[type=submit] {
      width: 50%;
      padding: 10px 20px;
      margin: 8px 0;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      margin-left: 25%;
      margin-right: 25%;
    }
  </style>
</head>

<body>
  <button type="button" class="Add-Worker" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"
      aria-hidden="true"></i> Add Product </button><br>
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="height: 600px;">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">
            <h2>Add product</h2>
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" onsubmit="return validateForm()">
            <input type="text" id="name" name="name" placeholder="Product name" class="form-control" required><br>
            <input type="text" id="price" name="price" placeholder=" Product price" class="form-control" required><br>
            <input type="text" id="quantity" name="quantity" placeholder=" Product quantity" class="form-control"
              required><br>
            <input type="text" name="img" placeholder="Img url" class="form-control" required><br>
            Used For:<br>
            <?php
            include "../../db_connection.php";
            $sql = "SELECT * FROM specialization";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                $specialization=$row["specialization"];
                echo '<div class="form-check">';
                echo "<input class='form-check-input' id='{$specialization}' type='checkbox' name='{$specialization}' value='{$specialization}'>";
                echo "<label class='form-check-label'>{$specialization}</label>";
                echo '</div>';
              }
            }
            ?>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" name="submit" style="background-color:#0B8793 ;">Add
                Worker</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="CardGrid">
    <?php
    include "../../db_connection.php";
    $buttonID = 0;
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $id = $row['ID'];
        $name = $row['name'];
        $price = $row['price'];
        $matchedTo = $row['matchedTo'];
        $expectedFixTime = $row['expectedFixTime'];
        $quantity = $row['quantity'];
        $imgSrc = $row['img'];
        echo "<div class='card' style='width: 18rem;
          ";
        if ($quantity <= 0) {
          echo "background-color:rgb(167, 5, 5) ;";
        }
        echo "'>
            <img class='card-img-top' src='$imgSrc' style='width:286px;height:286px;'>
              <div class='card-body'>
              <h5 class='card-title'>$name</h5>";
        echo "<p>price : $price <br/>
              expected Fix Time : $expectedFixTime minutes <br/>
              Matched to: $matchedTo <br/>
              quantity: $quantity</p>";
        echo
          "<form method='post'><input type='submit' name='$buttonID' value='update  price'style='width:150px;'></form>";
        if (isset($_POST[$buttonID])) {
          $_SESSION['ProducID'] = $row['ID'];
          echo "<script>
              var myWindow = window.open('updateproductprice.php','','width=700px,height=500px');
              </script>";
        }
        $buttonID++;
        echo
          "<form method='post'><input type='submit' name='$buttonID' value='update  quantity' style='width:150px;'></form>";
        if (isset($_POST[$buttonID])) {
          $_SESSION['ProducID'] = $row['ID'];
          echo "<script>
                var myWindow = window.open('updateproductquantity.php','','width=700px,height=500px');
                </script>";
        }
        $buttonID++;
        echo
          "<form method='post'><input type='submit' name='$buttonID' value='Remove' style='width:150px;' ></form>";
        if (isset($_POST[$buttonID])) {
          $sql = "UPDATE products SET quantity='0'  WHERE ID='$id'";
          if ($conn->query($sql) === TRUE) {
            echo "<script>alert('product unavailable in the mlai Now');</script>";
            header("refresh:0");
          } else {
            echo "<script>alert('error in the inserting');</script>";
          }
        }
        $buttonID++;
        echo
          "</div>
          </div>";
      }
    } else {
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
$matchedTo = "";
if (isset($_POST['submit'])) {
  if (isset($_POST['mercedes']) == true) {
    $matchedTo = $matchedTo . '' . 'mercedes ';
  }
  if (isset($_POST['bmw']) == true) {
    $matchedTo = $matchedTo . '' . 'bmw ';
  }
  if (isset($_POST['skoda']) == true) {
    $matchedTo = $matchedTo . '' . 'skoda ';
  }
  if (isset($_POST['volkswagen']) == true) {
    $matchedTo = $matchedTo . '' . 'volkswagen ';
  }
  if (isset($_POST['audi']) == true) {
    $matchedTo = $matchedTo . '' . 'audi';
  }
  $name = $_POST['name'];
  $price = $_POST['price'];
  $quantity = $_POST['quantity'];
  $imgSrc = $_POST['img'];
  if ($price <= 0) {
    echo "<script>alert('type price >0');</script> ";
    return;
  }
  if ($quantity <= 0) {
    echo "<script>alert('type quantity >0');</script> ";
    return;
  }
  $sql = "INSERT INTO products (name,price,quantity,matchedTo,img)
    VALUES ('$name','$price','$quantity','$matchedTo','$imgSrc')";
  if ($conn->query($sql) === TRUE) {
    echo "<script>alert('product inserted');</script>";
    header("Refresh:2;url=Products.php");
  } else {
    echo "<script>alert('error in inserted');</script> ";
  }
}
ob_end_flush();
?>