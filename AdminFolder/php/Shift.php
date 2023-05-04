<?php
ob_start();
  include 'AdminNav.php';
session_start();
?>
<html>
  <head>
    <link rel="stylesheet" href="../../ExternalStyle/GeneralStyle.css"/>
    <link rel="stylesheet" href="../../ExternalStyle/FormStyle.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Shift</title>
    <style>

      .Lform{
        display:none;
      }
      body{
        background-color: #e3e1ff;
      }
      </style>

  </head>
<body>
<center><form method='post'><input type='submit' name='addWorkerShift' value='add Worker to the shift' style='background-color:rgb(65, 158, 250);width:270px;'></form></center>

  <div class="Lform">
    <form method='post'>
    <label for="inputWorkerID">Worker ID</label>
    <select name="Workers" required>
          <?php
          include "../../db_connection.php";
            $sql = "SELECT * FROM workers";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
            $firstname = $row['firstname'];
            $lastname = $row['lastname'];
            $WorkerID = $row['ID'];
                echo"
                <option value=$WorkerID>",
                  $row['firstname'] ,$row['lastname'],"
                </option>";
            }
          ?>
        </select>

  <div class="form-group">
    <label for="inputAddress2">Date</label>
    <input type="date" class="form-control" name="Date" placeholder="">
  </div>

  <div class="form-group">
    <label for="inputAddress2">Shift Hours</label>
    <input type="text" class="form-control"name="Hours" placeholder="type the shift Hours">
  </div>
  <button type="submit" class="btn btn-primary" name="submit">add</button>
</form>
</div>
</body>
</html>
<?php
    if (isset($_POST['addWorkerShift'])){
          showAddProductDiv();
      } 
      if (isset($_POST['submit'])){
        $Worker=$_POST['Workers'];
        $Date=$_POST['Date'];
        $Hours=$_POST['Hours'];
        $sql = "INSERT INTO shift (Date,WorkerID,ShiftHours,HisTurn)
        VALUES ('$Date','$Worker','$Hours','0')";
      if ($conn->query($sql) === TRUE) {
      echo "<script>alert('Worker inserted');</script>";
      header("refresh:0");
      } 
      else {
          echo '<center>','<h6 style="color:red">',"Error: " . $sql . "<br>" . $conn->error;
      }
    } 
        function showAddProductDiv(){
          echo"
          <style>
            .Lform{
              display:block;
            }
          </>
          ";
        } 
?>

