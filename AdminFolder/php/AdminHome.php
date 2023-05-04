<?php
  include 'AdminNav.php';
?>
<html>
  <head>
  <link rel="stylesheet" href="../../ExternalStyle/HomeStyle.css"/> 
    <link rel="stylesheet" href="../../ExternalStyle/GeneralStyle.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Home</title>
  </head>
<style>
  .card-header.p-3.pt-2{
background-color: rgb(247, 247, 247);
border-radius:10px;
box-shadow: 0 8px 6px -6px black;
margin-top: 60px;
box-shadow: 10px 15px lightblue;
}
.mb-0, .my-0 {
    padding: 10 0;
}
body{
        background-color: #e3e1ff;
      }
</style>
  <body>
  
    <div style="  background-color:#D7F9FB; width:100%;height:100%">
      <div class="container-fluid py-4" >
        <div class="row" style="margin-bottom:50px;">

  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4" >
    <div class="card-header p-3 pt-2" style="background-color:#ffbc42">
      <h5 class="text-sm mb-0 text-capitalize">Total Carage balance</h5>
      <h5 class="mb-0">
        <?php
            include "../../db_connection.php";
            $Search = mysqli_query($conn,"SELECT * FROM revenues");
            while($row = mysqli_fetch_array($Search)){
              echo "<i class='fa fa-ils' aria-hidden='true'></i>",$row['allRevenue'];
            }  
        ?>
      </h5>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card-header p-3 pt-2" >
      <h5 class="text-sm mb-0 text-capitalize">Workers Quantity</h5>
      <h5 class="mb-0">
        <?php
            include "../../db_connection.php";
            $sql = "SELECT * FROM workers";
            $result = $conn->query($sql);
        echo "<i class='fa fa-briefcase' aria-hidden='true'></i></i>
        ",$result->num_rows;
        ?>
      </h5>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card-header p-3 pt-2" >
      <h5 class="text-sm mb-0 text-capitalize">Customers  Quantity</h5>
      <h4 class="mb-0">
        <?php
            include "../../db_connection.php";
            $sql = "SELECT * FROM customers";
            $result = $conn->query($sql);
        echo "<i class='fa fa-user' aria-hidden='true'></i></i>
        ",$result->num_rows;
        ?>
      </h4>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
  <div class="card-header p-3 pt-2" >
          <h5 class="text-sm mb-0 text-capitalize">Requests  Quantity</h5>
      <h4 class="mb-0">
        <?php
            include "../../db_connection.php";
            $sql = "SELECT * FROM requests";
            $result = $conn->query($sql);
        echo "<i class='fa fa-wrench' aria-hidden='true'></i></i>
        ",$result->num_rows;
        ?>
      </h4>
    </div>
  </div>

</div>

<div class="row" >
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card-header p-3 pt-2" >
        <h6 class="text-sm mb-0 text-capitalize">Paid receipts  Quantity</h6>
      <h4 class="mb-0">
        <?php
            include "../../db_connection.php";
            $sql = "SELECT * FROM receipt WHERE status='Paid'";
            $result = $conn->query($sql);
        echo "<i class='fa fa-shopping-cart' aria-hidden='true'></i></i>
        ",$result->num_rows;
        ?>
      </h4>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card-header p-3 pt-2" >
      <h6 class="text-sm mb-0 text-capitalize">Un Paid receipts  Quantity</h6>
      <h4 class="mb-0">
        <?php
            include "../../db_connection.php";
            $sql = "SELECT * FROM receipt WHERE status='Not Paid'";
            $result = $conn->query($sql);
        echo "<i class='fa fa-gavel' aria-hidden='true'></i></i>
        ",$result->num_rows;
        ?>
      </h4>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
  <div class="card-header p-3 pt-2" >
      <h6 class="text-sm mb-0 text-capitalize">Messages  Quantity</h6>
      <h4 class="mb-0">
        <?php
            include "../../db_connection.php";
            $sql = "SELECT * FROM messages";
            $result = $conn->query($sql);
        echo "<i class='fa fa-comments' aria-hidden='true'></i></i>
        ",$result->num_rows;
        ?>
      </h4>
    </div>
  </div>  

  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card-header p-3 pt-2" >
      <h6 class="text-sm mb-0 text-capitalize">Products  Quantity</h6>
      <h4 class="mb-0">
        <?php
            include "../../db_connection.php";
            $sql = "SELECT * FROM products";
            $result = $conn->query($sql);
        echo "<i class='fa fa-tag' aria-hidden='true'></i></i>
        ",$result->num_rows;
        ?>
      </h4>
    </div>
  </div>

</div>

</div>
</div>

  </body>
</html>