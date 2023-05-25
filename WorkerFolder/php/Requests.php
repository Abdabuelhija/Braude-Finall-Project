<?php
ob_start();

  include 'Navbar.php';

session_start();
?>
<html>
  <head>
  <!-- <link rel="stylesheet" href="../../ExternalStyle/GeneralStyle.css"/> -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Requests</title>
  </head>
  <body>
  <style>
      .Grid{
    display: grid;
    grid-template-columns:290px 290px 290px 290px; 
    column-gap: 20px;
    margin-left: 30px;
    }
    body{
        background-color: #e3e1ff;
    }
    .card {
        text-align: center;
        border-radius: 10px;
        width: 18rem;
        background-color:aliceblue;
      }
      .card-body{
        height: 200px;
        display: grid;
        grid-template-columns: auto auto  ;
      }
      .Requestbutton{
        border:none; 
        padding:1px 1px;
        margin-top:20px;
      }
      .card:hover{
        background-color:lightblue;
        
      }
  </style>
    <form method='post'>
    <div class="Grid">
        <?php
          $Test=" ";
          include "../../db_connection.php";
          $sql = "SELECT * FROM requests ORDER BY ID DESC";
          $result = $conn->query($sql);
          while($row = $result->fetch_assoc()) {
            if ($row["workerID"] == $_SESSION['id']){
              $id=$row['ID'];
              echo"
              <button name=$id class='Requestbutton'>
                <div class='card' style='width: 18rem;'>
                  <img class='card-img-top' src='../../Imgs/Logo.png'>
                    <div class='card-body'>
                        <b>request ID </b>",$row['ID'],
                        "<b>ClientID </b>",$row['clientID'],
                        "<b>Time: </b>",$row['Time'],
                        "<b>status </b> ",$row['status'],
                        "
                </div>
                </div>
              </button>";
              if (isset($_POST[$id])){
                $_SESSION['RID']=$id;
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