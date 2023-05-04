<?php
  include 'AdminNav.php';
  echo "";
?>
<html>
  <head>
    <link rel="stylesheet" href="../../ExternalStyle/GeneralStyle.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Clients</title>
  </head>
  <style>
  .Grid{
    display: grid;
    grid-template-columns:290px 290px 290px 290px; 
    column-gap: 30px;
    margin-left: 30px;
    }

  body{
        background-color: #e3e1ff;
      }
      .card{
        box-shadow: 10px 15px lightblue;
      }
    </style>
  <body>
    <br>
    <div class='Grid'>
      <?php
        include "../../db_connection.php";
        $sql = "SELECT * FROM customers";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            $id = $row['ID'];
            $carID = $row['carID'];
            $firstname = $row['firstname'];
            $lastname = $row['lastname'];
            $email = $row['email'];
            $carType = $row['carType'];
            echo "
            <div class='card' style='width: 18rem;'>
              <img class='card-img-top' src='../../Imgs/Defult.png' alt='Card image cap'>
                <div class='card-body'>
                <h5 class='card-title'>$firstname $lastname</h5>
                ID :",$row['ID'],"<br>
                phoneNumber:",$row['PhoneNumber'],"<br>
                <p>Car type: $carType</br>
                Car ID:: $carID</br>
                email: $email</p></br>
               
                </div>
                </div>";
            if (isset($_POST[$id])){
                //using sql code, remove the product from products table
            }
          }
        } 
        else {
          echo "0 results";
        }
      ?>
    </div>
    <br>
  </body>
</html>