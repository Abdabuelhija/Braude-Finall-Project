<?php
  include 'clientNav.php';
  session_start();
?>
<html>
  <head> 
    <link rel="stylesheet" href="../Style/CustomerStyle.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Receipt</title>
  </head>
  <style>
    body{
        background-color:#e3e1ff;
    }
    .card{
      background-color:#e3e1ff; */
    }
    .Grid{
    display: grid;
    grid-template-columns:290px 290px 290px 290px; 
    column-gap: 40px;
    margin-left: 30px;
    margin-top: 20px;
    }
  </style>
  <body>
  <center><button class='Printbutton'onclick='window.print()'><i class='fa fa-print ' aria-hidden='true' ></i></button></center>
    
      <form method='post'>
      <div class="Grid">
        <?php
          include "../../db_connection.php";
          $CLIENTID=$_SESSION['id'];
            $sql = "SELECT * FROM  requests
            JOIN  receipt ON receipt.RequestID = requests.ID 
            WHERE requests.clientID = '$CLIENTID' ";
            $result = $conn->query($sql);
            // print_r($result->fetch_assoc());
            while ($row  = $result->fetch_assoc()) {
              $status = $row ['status'];
              $REC_ID = $row ['ID'];
              // $id = $row ['ID'];
              $TotalPrice = $row ['TotalPrice'];
          echo "
                <div class='card'>
                  <img class='card-img-top' src='../../Imgs/Logo.png' alt='Card image cap'>
                    <div class='card-body'>
                      <div class='info'>
                        <b>receipt ID</b>", $row['ID'],
            "<b>RequestID</b>", $row['RequestID'],
            "<b>status: </b>", $row['status'];
                        
                        $WID=$row ['WorkerID'];

                        $sql = "SELECT * FROM  workers WHERE ID = '$WID' ";
                        $result = $conn->query($sql);
                        $row2 = $result->fetch_assoc();
                        echo 
                        "<b>Worker name</b>", $row2 ['firstname'],$row2 ['lastname'];
                        
              $RequestID = $row ['RequestID'];
              $Search = mysqli_query($conn, "SELECT * FROM requests WHERE ID='$RequestID'");
              $row = mysqli_fetch_array($Search);
              $time = $row['Time'];
              echo
                "<b>date</b>", $time;
              echo
                "<b>Products : </b><br>
                      </div>";

              $Search = mysqli_query($conn, "SELECT* FROM turnproducts WHERE RequestID='$RequestID'");
              while ($row = mysqli_fetch_array($Search)) {
                $TurnQuantity = $row['quantity'];
                $PID =$row['ProductID'];
                $productSearch = mysqli_query($conn, "SELECT* FROM products WHERE ID='$PID'");
                $row2 = mysqli_fetch_array($productSearch);
                
                echo "<b>",  $row2['name'], "</b>", "
                          <div class='Receiptinfo'>
                          <b>Quantity : </b> ", $row['quantity'];
                $PID = $row['ProductID'];
                $Search = mysqli_query($conn, "SELECT* FROM products WHERE ID='$PID'");
                $row = mysqli_fetch_array($Search);
                $productPrice = $row['price'];
                $TotalProductPrice = $productPrice * $TurnQuantity;
                echo
                  "<b> price</b>", $TotalProductPrice,
                  "</div>";
              }
              echo
                "<b>Total price</b><br>
                              <i class='fa fa-ils' aria-hidden='true'></i>$TotalPrice
                        </div> ";
              if ($status != "Paid") {
                echo "<button class='button'name=$REC_ID>pay</button>";
              }
              echo "
                    </div>";
              if (isset($_POST[$REC_ID])) {
                $_SESSION['price'] = $TotalPrice;
                $_SESSION['REC_ID'] = $REC_ID;
                header("location:PaymentForm.php");
              }
            }
        ?>
      </div>
    </form>
  </body>
</html>
