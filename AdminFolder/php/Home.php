<?php include 'Navbar.php'; ?>
<html>
<head>
  <link rel="stylesheet" href="../style/AdminStyle.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <title>Home</title>
</head>
<body>
  <div style="width:100%;">
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card-header p-3 pt-2">
            <h5 class="text-sm mb-0 text-capitalize">Today</h5>
            <h5 class="mb-0"></h5>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card-header p-3 pt-2">
            <h5 class="text-sm mb-0 text-capitalize">The Next Audi Request Go to </h5>
            <h5 class="mb-0">
              <?php
              include "../../db_connection.php";
              $sql = "SELECT * FROM workers";
              $result = $conn->query($sql);
              echo "<i class='fa fa-briefcase' aria-hidden='true'></i></i>", $result->num_rows;
              ?>
            </h5>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card-header p-3 pt-2">
            <h5 class="text-sm mb-0 text-capitalize">The Next Skoda Request Go to </h5>
            <h5 class="mb-0">
              <?php
              include "../../db_connection.php";
              $sql = "SELECT * FROM workers";
              $result = $conn->query($sql);
              echo "<i class='fa fa-briefcase' aria-hidden='true'></i></i>", $result->num_rows;
              ?>
            </h5>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card-header p-3 pt-2">
            <h5 class="text-sm mb-0 text-capitalize">The Next BMW Request Go to </h5>
            <h5 class="mb-0">
              <?php
              include "../../db_connection.php";
              $sql = "SELECT * FROM workers";
              $result = $conn->query($sql);
              echo "<i class='fa fa-briefcase' aria-hidden='true'></i></i>", $result->num_rows;
              ?>
            </h5>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card-header p-3 pt-2">
            <h5 class="text-sm mb-0 text-capitalize">The Next Mercedes Request Go to </h5>
            <h5 class="mb-0">
              <?php
              include "../../db_connection.php";
              $sql = "SELECT * FROM workers";
              $result = $conn->query($sql);
              echo "<i class='fa fa-briefcase' aria-hidden='true'></i></i>", $result->num_rows;
              ?>
            </h5>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card-header p-3 pt-2">
            <h5 class="text-sm mb-0 text-capitalize">The Next volkswagen Request Go to </h5>
            <h5 class="mb-0">
              <?php
              include "../../db_connection.php";
              $sql = "SELECT * FROM workers";
              $result = $conn->query($sql);
              echo "<i class='fa fa-briefcase' aria-hidden='true'></i></i>", $result->num_rows;
              ?>
            </h5>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card-header p-3 pt-2">
            <h5 class="text-sm mb-0 text-capitalize">Request in Processing </h5>
            <h5 class="mb-0">
              <?php
              include "../../db_connection.php";
              $sql = "SELECT * FROM workers";
              $result = $conn->query($sql);
              echo "<i class='fa fa-briefcase' aria-hidden='true'></i></i>", $result->num_rows;
              ?>
            </h5>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card-header p-3 pt-2">
            <h5 class="text-sm mb-0 text-capitalize">Workers in the shift</h5>
            <h5 class="mb-0">
              <?php
              include "../../db_connection.php";
              $sql = "SELECT * FROM workers";
              $result = $conn->query($sql);
              echo "<i class='fa fa-briefcase' aria-hidden='true'></i></i>", $result->num_rows;
              ?>
            </h5>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card-header p-3 pt-2">
            <h5 class="text-sm mb-0 text-capitalize">Total Carage balance</h5>
            <h5 class="mb-0">
              <?php
              include "../../db_connection.php";
              $Search = mysqli_query($conn, "SELECT * FROM revenues");
              while ($row = mysqli_fetch_array($Search)) {
                echo "<i class='fa fa-ils' aria-hidden='true'></i>", $row['allRevenue'];
              }
              ?>
            </h5>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card-header p-3 pt-2">
            <h5 class="text-sm mb-0 text-capitalize">Workers Quantity</h5>
            <h5 class="mb-0">
              <?php
              include "../../db_connection.php";
              $sql = "SELECT * FROM workers";
              $result = $conn->query($sql);
              echo "<i class='fa fa-briefcase' aria-hidden='true'></i></i>", $result->num_rows;
              ?>
            </h5>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card-header p-3 pt-2">
            <h5 class="text-sm mb-0 text-capitalize">Customers Quantity</h5>
            <h4 class="mb-0">
              <?php
              include "../../db_connection.php";
              $sql = "SELECT * FROM customers";
              $result = $conn->query($sql);
              echo "<i class='fa fa-user' aria-hidden='true'></i></i>", $result->num_rows;
              ?>
            </h4>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card-header p-3 pt-2">
            <h5 class="text-sm mb-0 text-capitalize">Requests Quantity</h5>
            <h4 class="mb-0">
              <?php
              include "../../db_connection.php";
              $sql = "SELECT * FROM requests";
              $result = $conn->query($sql);
              echo "<i class='fa fa-wrench' aria-hidden='true'></i></i>", $result->num_rows;
              ?>
            </h4>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card-header p-3 pt-2">
            <h6 class="text-sm mb-0 text-capitalize">Paid receipts Quantity</h6>
            <h4 class="mb-0">
              <?php
              include "../../db_connection.php";
              $sql = "SELECT * FROM receipt WHERE status='Paid'";
              $result = $conn->query($sql);
              echo "<i class='fa fa-shopping-cart' aria-hidden='true'></i></i>", $result->num_rows;
              ?>
            </h4>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card-header p-3 pt-2">
            <h6 class="text-sm mb-0 text-capitalize">Un Paid receipts Quantity</h6>
            <h4 class="mb-0">
              <?php
              include "../../db_connection.php";
              $sql = "SELECT * FROM receipt WHERE status='Not Paid'";
              $result = $conn->query($sql);
              echo "<i class='fa fa-gavel' aria-hidden='true'></i></i>", $result->num_rows;
              ?>
            </h4>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card-header p-3 pt-2">
            <h6 class="text-sm mb-0 text-capitalize">Messages Quantity</h6>
            <h4 class="mb-0">
              <?php
              include "../../db_connection.php";
              $sql = "SELECT * FROM messages";
              $result = $conn->query($sql);
              echo "<i class='fa fa-comments' aria-hidden='true'></i></i>", $result->num_rows;
              ?>
            </h4>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card-header p-3 pt-2">
            <h6 class="text-sm mb-0 text-capitalize">Products Quantity</h6>
            <h4 class="mb-0">
              <?php
              include "../../db_connection.php";
              $sql = "SELECT * FROM products";
              $result = $conn->query($sql);
              echo "<i class='fa fa-tag' aria-hidden='true'></i></i>", $result->num_rows;
              ?>
            </h4>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
