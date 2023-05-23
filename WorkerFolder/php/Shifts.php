<?php
ob_start();
include 'WorkerNav.php';
session_start();
?>
<html>
<head>
  <link rel="stylesheet" href="style/ShiftStyle.css">
  <style>
    .current-week {
      text-align: center;
      font-size: 24px;
      font-weight: bold;
    }
    @keyframes pulsate {
      0% {
        transform: scale(1);
      }
      50% {
        transform: scale(1.2);
      }
      100% {
        transform: scale(1);
      }
    }
  </style>
  <title>Abeds Garage-Shift</title>
</head>
<body>
  <div class="Buttons">
    <form method="post">
      <input type="submit" name="previous_week" value="Past Week">
      <input type="submit" name="next_week" value="Next Week">
    </form>
  </div>
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
      $_SESSION['week_offset'] = 0; // Initialize the week offset if it hasn't been set
    }
    if (isset($_POST['previous_week'])) {
      $_SESSION['week_offset']--; // Decrease the week offset if the "Past Week" button was pressed
    } else if (isset($_POST['next_week'])) {
      $_SESSION['week_offset']++; // Increase the week offset if the "Next Week" button was pressed
    }
    $WID = $_SESSION['id'];
    $weekOffsetDays = $_SESSION['week_offset'] * 7;

    // Calculate the current week's start and end dates
    $currentWeekStart = date('Y-m-d', strtotime('sunday this week') + $weekOffsetDays * 86400);
    $currentWeekEnd = date('Y-m-d', strtotime('sunday this week') + ($weekOffsetDays + 6) * 86400);

    echo "<p class='current-week'>Current Week: $currentWeekStart to $currentWeekEnd</p>";

    $sql = "SELECT * FROM shift WHERE WorkerID='$WID' AND DATE(Date) BETWEEN DATE_ADD(CURDATE(), INTERVAL $weekOffsetDays DAY) AND DATE_ADD(CURDATE(), INTERVAL $weekOffsetDays + 6 DAY)";
    $result = $conn->query($sql);
    $workerStartTimes = array_fill(0, 7, ''); // Initialize array with empty strings
    while ($row = $result->fetch_assoc()) {
      $date = $row['Date'];
      $shiftHours = $row['ShiftHours'];
      $dayOfWeek = date('w', strtotime($date)); // Get the day of the week (0 = Sunday, 1 = Monday, etc.)
      // Calculate the start time by adding the worker's hours to 8:00
      $startTime = date('H:i', strtotime('08:00') + $shiftHours * 3600); // 3600 seconds in an hour
      $workerStartTimes[$dayOfWeek] = $startTime;
    }
    echo "<tr>";
    for ($dayIndex = 0; $dayIndex < 7; $dayIndex++) {
      echo "<td>";
      if ($workerStartTimes[$dayIndex] !== '') {
        $date = date('Y-m-d', strtotime('sunday this week') + ($weekOffsetDays + $dayIndex) * 86400); // Calculate the date based on the day index
        echo "$date | 8:00 - $workerStartTimes[$dayIndex]";
      } else {
        echo "Free"; // Empty cell for days without a shift
      }
      echo "</td>";
    }
    echo "</tr>";
    ?>
  </table>
</body>
</html>
<?php    
?>
