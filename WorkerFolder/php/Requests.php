<?php
ob_start();

include 'Navbar.php';

session_start();
?>
<html>

<head>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <title>Requests</title>
</head>

<body>
  <style>
    body{
      background-color:#E3E3E3;
    }
    .CardGrid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(290px, 50px));
      row-gap: 2px;
      margin-left: 80px;
      margin-top: 50px;
      margin-bottom: 50px;
      
    }
    .card {
      text-align: center;
      border-radius: 10px;
      width: 18rem;
      /* background-color: #d3d1d1; */
      box-shadow: 0px 14px 28px rgba(0, 0, 0, 0.25), 0px 10px 10px rgba(0, 0, 0, 0.22);
    }

    .card-body {
      height: 200px;
      display: grid;
      grid-template-columns: auto auto;
    }

    .Requestbutton {
      border: none;
      padding: 1px 1px;
      margin-top: 20px;
      border-radius: 10px;
    }
    .card:hover {
      background-color: #f3f3f3;
      
    }
  </style>
  <form method='post'>
    <div class="CardGrid">
      <?php
      $Test = " ";
      include "../../db_connection.php";
      $sql = "SELECT * FROM requests ORDER BY ID DESC";
      $result = $conn->query($sql);
      while ($row = $result->fetch_assoc()) {
        if ($row["workerID"] == $_SESSION['id']) {
          $id = $row['ID'];
          echo "
              <button name=$id class='Requestbutton'>
                <div class='card' style='width: 18rem;'>
                  <img class='card-img-top' src='../../Imgs/Logo.png'>
                    <div class='card-body'>
                        <b>request ID </b>", $row['ID'],
            "<b>ClientID </b>", $row['clientID'],
            "<b>start Time: </b>", $row['startTime'],
            "<b>status </b> ", $row['status'],
            "
                </div>
                </div>
              </button>";
          if (isset($_POST[$id])) {
            $_SESSION['RID'] = $id;
            header("location:insert.php");
          }
        }
      }
      ?>
    </div>
  </form>
</body>

</html>
<?php
ob_end_flush();

?>