<?php
ob_start();
include 'Navbar.php';
session_start();
?>
<html>

<head>
  <link rel="stylesheet" href="../style/ShiftStyle.css">
  <title>Abeds Garage-Shift</title>
</head>
<body>
  <br/><br/><br/><br/>
  <table>
    <tr>
      <?php
      $daysOfWeek = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
      foreach ($daysOfWeek as $day) {
        echo "<th>$day</th>";
      }
      ?>
    </tr>
    <?php
    include "../../db_connection.php";
    if (!isset($_SESSION['week_offset'])) {
      $_SESSION['week_offset'] = 0; 
    }
    if (isset($_POST['previous_week'])) {
      $_SESSION['week_offset']--; 
    } else if (isset($_POST['next_week'])) {
      $_SESSION['week_offset']++; 
    }
    $WID = $_SESSION['id'];
    $weekOffsetDays = $_SESSION['week_offset'] * 7;


    $currentWeekStart = date('Y-m-d', strtotime('sunday this week') + $weekOffsetDays * 86400);
    $currentWeekEnd = date('Y-m-d', strtotime('sunday this week') + ($weekOffsetDays + 6) * 86400);

    echo "<p class='current-week'> $currentWeekStart to $currentWeekEnd</p>";

    $sql = "SELECT * FROM shift WHERE WorkerID='$WID' AND DATE(Date) BETWEEN DATE_ADD(CURDATE(), INTERVAL $weekOffsetDays DAY) AND DATE_ADD(CURDATE(), INTERVAL $weekOffsetDays + 6 DAY)";
    $result = $conn->query($sql);
    $workerStartTimes = array_fill(0, 7, ''); 
    while ($row = $result->fetch_assoc()) {
      $date = $row['Date'];
      $shiftHours = $row['ShiftHours'];
      $dayOfWeek = date('w', strtotime($date)); 
   
      $startTime = date('H:i', strtotime('08:00') + $shiftHours * 3600); 
      $workerStartTimes[$dayOfWeek] = $startTime;
    }
    echo "<tr>";
    for ($dayIndex = 0; $dayIndex < 7; $dayIndex++) {
      echo "<td>";
      if ($workerStartTimes[$dayIndex] !== '') {
        $date = date('Y-m-d', strtotime('sunday this week') + ($weekOffsetDays + $dayIndex) * 86400);
        echo "$date | 8:00 - $workerStartTimes[$dayIndex]";
      } 
      echo "</td>";
    }
    echo "</tr>";
    ?>
  </table>
  <div class="Buttons">
    <form method="post">
    <input type="submit" name="previous_week" value="Past week">
      <input type="submit" name="next_week" value="Next week">
    </form>
  </div>
</body>

</html>
<?php
?>
