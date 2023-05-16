<?php
  ob_start();
  include 'Navbar.php';
  session_start();
?>
<html>
  <head>
    <link rel="stylesheet" href="../style/ShiftStyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <title>Abeds Garage-Shift</title>
  </head>
  <body>
  <button type="button" class="Add-Worker" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus" aria-hidden="true"></i> Add Worker </button>
    <p id='showHere'><b>Click on the selected date to view the shift</b></p>
    <?php
      if (isset($_GET['month']) && isset($_GET['year'])) {
        $month = $_GET['month'];
        $year = $_GET['year'];
      } 
      else {
        // If no month and year are specified, use the current month and year
        $month = date('n');
        $year = date('Y');
      }
      $num_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
      // Get the day of the week for the first day of the specified month and year
      $first_day = date("w", mktime(0, 0, 0, $month, 1, $year));
      // Generate the HTML code for the calendar
      echo '<div class="calendar">';
      echo '<div class="month">';
      echo '<ul>';
      // Add links to browse to the previous and next months
      $prev_month = $month - 1;
      $prev_year = $year;
      if ($prev_month < 1) {
        $prev_month = 12;
        $prev_year--;
      }
      echo '<li class="prev"><a href="?month=' . $prev_month . '&year=' . $prev_year . '" style="text-decoration: none;color:white;">&#10094;</a></li>';
      $next_month = $month + 1;
      $next_year = $year;
      if ($next_month > 12) {
        $next_month = 1;
        $next_year++;
      }
      echo '<li class="next"><a href="?month=' . $next_month . '&year=' . $next_year . '" style="text-decoration: none;color:white;">&#10095;</a></li>';
      echo '<li>' . date('F', mktime(0, 0, 0, $month, 1, $year)) . '<br><span style="font-size:18px">' . $year . '</span></li>';
      echo '</ul>';
      echo '</div>';
      echo '<ul class="weekdays">';
      echo '<li>Sunday</li>';
      echo '<li>Monday</li>';
      echo '<li>Tuesday</li>';
      echo '<li>Wednesday</li>';
      echo '<li>Thursday</li>';
      echo '<li>Friday</li>';
      echo '<li>Saturday</li>';
      echo '</ul>';
      echo '<form method="post">';
      echo '<ul class="days">';
      // Add blank cells for the days before the first day of the month
      for ($i = 0; $i < $first_day; $i++) {
        echo '<li></li>';
      }
      // Add cells for each day of the month
      for ($day = 1; $day <= $num_days; $day++) {
      // Highlight today's date
      $date = $year . "-" . $month . "-" . $day;
      if ($day == date('j') && $month == date('n') && $year == date('Y')) {
        echo '<li><button class="day" name='.$date.'><span class="active" >' . $day . '</span></button></li>';
      }
       else {
        echo '<li><button class="day" name='.$date.'><span class="dateClick">'. $day . '</span></button></li>';
      }
      if(isset($_POST[$date])){
        show($date);
      }
      }
      echo '</form>';
      // Add blank cells for the days after the last day of the month
      $num_blanks = 42 - ($first_day + $num_days);
      for ($i = 0; $i < $num_blanks; $i++) {
        // Add blank cells for the days after the last day of the month
        $num_blanks = 42 - ($first_day + $num_days);
        for ($i = 0; $i < $num_blanks; $i++) {
          echo '<li></li>';
        }
        echo '</ul>';
        echo '</div>';
      }

      function show($date){
      include '../../db_connection.php';
      $sql = "SELECT * FROM shift WHERE Date = '$date'";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) > 0) {
      $tableHtml = "<table style='width:100%;border-collapse:collapse;margin-top:80px;'><tr><th style='border:1px solid #000;padding:5px;'>ID</th><th style='border:1px solid #000;padding:5px;'>Date</th><th style='border:1px solid #000;padding:5px;'>WorkerID</th><th style='border:1px solid #000;padding:5px;'>ShiftHours</th><th style='border:1px solid #000;padding:5px;'>HisTurn</th></tr>";
      while ($row = mysqli_fetch_assoc($result)) {
      $tableHtml .= "<tr><td style='border:1px solid #000;padding:5px;'>" . $row['ID'] . "</td><td style='border:1px solid #000;padding:5px;'>" . $row['Date'] . "</td><td style='border:1px solid #000;padding:5px;'>" . $row['WorkerID'] . "</td><td style='border:1px solid #000;padding:5px;'>" . $row['ShiftHours'] . "</td><td style='border:1px solid #000;padding:5px;'>" . $row['HisTurn'] . "</td></tr>";
      }
      $tableHtml .= "</table>";
      echo '<script>document.getElementById("showHere").innerHTML ="' . addslashes($tableHtml) . '";</script>';
      } 
      else {
      echo '<script>document.getElementById("showHere").innerHTML = "<b style=\'color:red\'>No shift schedule found for the specified date.</b>";</script>';
      }
      mysqli_close($conn);
      }
    ?>
    <!-- modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <div class="modal-content">

  <div class="modal-header">
  <h5 class="modal-title" id="exampleModalLabel"><h2>Add worker to the shift</h2></h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
  </div>

  <div class="modal-body">
    <form method='post'>
    <label for="inputWorkerID">Worker</label>
    <select name="Workers" class="form-control form-control-lg" required>
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
    <input type="date" class="form-control" name="Date" required>
    </div>
    <div class="form-group">
    <label for="inputAddress2">Shift Hours</label>
    <input type="text" class="form-control"name="Hours" placeholder="type the shift Hours" required>
    </div>
    
    <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" name="submit">Add Worker</button>
    </div>

    </form>
  </div>
  </div>
  </div>
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
      // if the worker doesn't exist in the shift in this date and if the date not saturday and if the hours shift <10 so do :{  
      if ($conn->query($sql) === TRUE) {
      echo "<script>alert('Worker inserted');</script>";
      header("refresh:0");
      } 
      else {
      echo '<center>','<h6 style="color:red">',"Error: " . $sql . "<br>" . $conn->error;
      }
    // }
    } 
    function showAddProductDiv(){
        echo"
        <style>
        .Lform{
        display:block;
        }
        <style/>
        ";
      } 
?>