<?php
ob_start();
  include 'AdminNav.php';
session_start();
?>
<html>
    <head>
        <style>
            ul {list-style-type: none;}
body {font-family: Verdana, sans-serif;}

/* Month header */
.month {
  padding:50px 0px;
  width: 100%;
  background: #1abc9c;
  text-align: center;
}

/* Month list */
.month ul {
  margin: 0;
  padding: 0;
}

.month ul li {
  color: white;
  font-size: 20px;
  text-transform: uppercase;
  letter-spacing: 3px;
}

/* Previous button inside month header */
.month .prev {
  float: left;
  padding-top: 10px;
}

/* Next button */
.month .next {
  float: right;
  padding-top: 10px;
}

/* Weekdays (Mon-Sun) */
.weekdays {
  margin: 0;
  padding: 10px 0;
  background-color:#ddd;
}

.weekdays li {
  display: inline-block;
  width: 13.6%;
  color: #666;
  text-align: center;
}

/* Days (1-31) */
.days {
  padding: 10px 0;
  background: #eee;
  margin: 0;
}

.days li {
  list-style-type: none;
  display: inline-block;
  width: 13.6%;
  text-align: center;
  margin-bottom: 5px;
  font-size:12px;
  color: #777;
}

/* Highlight the "current" day */
.days li .active {
  padding: 6px;
  background-color: red;
  color: white !important;
  border-radius: 250px;
}

.days li .active:hover{
  padding: 10px;
  /* background-color:#1abc9c; */
}

.dateClick:hover{
  padding: 5px;
  background: #1abc9c;
  border-radius: 250px;
  color: white !important;
}
.day{
  border: none;
  padding:10px 50px;
}


        </style>
    </head>
    <body>
   <p id='showHere' style='padding:250px 250px;'></p>
<?php
// Check if a month and year have been specified in the URL
if (isset($_GET['month']) && isset($_GET['year'])) {
    $month = $_GET['month'];
    $year = $_GET['year'];
} else {
    // If no month and year are specified, use the current month and year
    $month = date('n');
    $year = date('Y');
}

// Get the number of days in the specified month and year
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
    } else {
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
          
            // echo "<table><tr><th>ID</th><th>Date</th><th>WorkerID</th><th>ShiftHours</th><th>HisTurn</th></tr>";
            // while ($row = mysqli_fetch_assoc($result)) {
            //     echo "<tr><td>" . $row['ID'] . "</td><td>" . $row['Date'] . "</td><td>" . $row['WorkerID'] . "</td><td>" . $row['ShiftHours'] . "</td><td>" . $row['HisTurn'] . "</td></tr>";
            // }
            // echo "</table>";
            $tableHtml = "<table><tr><th>ID</th><th>Date</th><th>WorkerID</th><th>ShiftHours</th><th>HisTurn</th></tr>";
          while ($row = mysqli_fetch_assoc($result)) {
              $tableHtml .= "<tr><td>" . $row['ID'] . "</td><td>" . $row['Date'] . "</td><td>" . $row['WorkerID'] . "</td><td>" . $row['ShiftHours'] . "</td><td>" . $row['HisTurn'] . "</td></tr>";
          }
          $tableHtml .= "</table>";
          echo '<script>document.getElementById("showHere").innerHTML ="' . addslashes($tableHtml) . '";</script>';
        } 
        else {
            echo'<script>document.getElementById("showHere").innerHTML ="No shift schedule found for the specified date.";</script>';
        }
        mysqli_close($conn);
}



?>

    </body>
</html>