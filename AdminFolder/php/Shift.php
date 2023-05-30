<?php
ob_start();
include 'Navbar.php';
session_start();
?>

<html>

<head>
<link rel="stylesheet" href="../style/Products.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
    crossorigin="anonymous"></script>
  <title>Abeds Garage-Shift</title>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>

<body>
  <input type="date" id="date-input" class="Date">
  <button type="button" class="Add-Worker" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"
      aria-hidden="true"></i> Add Worker </button>

  <table class="table" id="results-table" style="text-align:center;">
    <thead>
      <tr>
        <th>Worker</th>
        <th>Shift Hours</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">
            <h2>Add worker to the shift</h2>
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post">
            <label for="inputWorkerID">Worker</label>
            <select name="Workers" class="form-control form-control-lg" required>
              <?php
              include "../../db_connection.php";
              $sql = "SELECT * FROM workers";
              $result = $conn->query($sql);
              while ($row = $result->fetch_assoc()) {
                $firstname = $row['firstname'];
                $lastname = $row['lastname'];
                $WorkerID = $row['ID'];
                echo "
          <option value=$WorkerID>",
                  $row['firstname'], $row['lastname'], "
          </option>";
              }
              ?>
            </select>
            <div class="form-group">
              <label for="inputAddress2">Date</label>
              <input type="date" class="form-control" name="Date" required id="dateInput">
            </div>
            <div class="form-group">
              <label for="inputAddress2">Shift Hours</label>
              <input type="text" class="form-control" name="Hours" placeholder="type the shift Hours" required>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="addSubmit" class="btn btn-primary" name="addSubmit">Add Worker</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function () {
      $(document).ready(function () {
        $('#date-input').change(function () {
          $.get('getShifts.php', { date: $(this).val() }, function (data) {
            $('#results-table tbody').empty();
            var shifts = JSON.parse(data);
            for (var i = 0; i < shifts.length; i++) {
              var workerId = shifts[i].WorkerID;
              var shiftHours = shifts[i].ShiftHours;
              var shiftDate = shifts[i].Date;
              var deleteButton = '<button class="btn btn-danger delete-worker" data-worker="' + workerId + '" data-date="' + shiftDate + '"><i class="fa fa-trash " aria-hidden="true"></i></button>';
              $('#results-table tbody').append('<tr><td>' + shifts[i].firstname + ' ' + shifts[i].lastname + '</td><td>' + shifts[i].ShiftHours + '</td><td>' + deleteButton + '</td></tr>');
            }
          });
        });
      });
      // Handle delete button click
      $('#results-table').on('click', '.delete-worker', function () {
        var workerId = $(this).data('worker');
        var shiftDate = $(this).data('date');
        var date = $('#date-input').val();
        // Calculate the minimum allowed date for deletion
        var today = new Date();
        var minDeleteDate = new Date();
        minDeleteDate.setDate(today.getDate() - 1);
        // Convert shift date to a Date object
        var shiftDateObj = new Date(shiftDate);
        // Check if the shift date is at least 7 days prior to today
        if (shiftDateObj >= minDeleteDate) {
          // Display confirmation dialog
          var confirmed = confirm('Are you sure you want to delete this worker?');
          if (confirmed) {
            // Send AJAX request to deleteWorker.php
            $.post('deleteWorker.php', { workerId: workerId, date: date }, function (response) {
              if (response === 'success') {
                // Refresh the table after successful deletion
                $('#date-input').trigger('change');
              } else {
                alert('Failed to delete the worker.');
              }
            });
          }
        }
        else {
          alert('Deletion is only allowed for shifts that are at least 7 days prior to the current date.');
        }
      });
    });

    // this for the add worker date input :
    // Get current date
    var currentDate = new Date();
    // Calculate minimum selectable date (current date + 7 days)
    var minDate = new Date();
    minDate.setDate(currentDate.getDate() + 7);
    // Format the minimum date as YYYY-MM-DD (required by the date input)
    var formattedMinDate = minDate.toISOString().split('T')[0];
    // Set the minimum selectable date for the date input
    document.getElementById('dateInput').setAttribute('min', formattedMinDate);
  </script>

</body>

</html>

<?php
if (isset($_POST['addSubmit'])) {
  $Worker = $_POST['Workers'];
  $Date = $_POST['Date'];
  $Hours = $_POST['Hours'];
  $dayOfWeek = date('l', strtotime($Date));
  $isExist = false;
  if ($dayOfWeek != 'Saturday' and $Hours < 10 and isExist($Worker, $Date) == false) {
    $sql = "INSERT INTO shift (Date,WorkerID,ShiftHours,HisTurn)
        VALUES ('$Date','$Worker','$Hours','0')";
    if ($conn->query($sql) === TRUE) {
      echo "<script>alert('Worker inserted');</script>";
      header("refresh:0");
    } else {
      echo '<center>', '<h6 style="color:red">', "Error: " . $sql . "<br>" . $conn->error;
    }
  } else {
    echo "<script>alert('not confirmed on saturday or shift hours >9 or the worker exist ');</script>";
  }
}
function isExist($WorkerID, $Date)
{
  include "../../db_connection.php";
  $sql = "SELECT * FROM shift WHERE WorkerID=$WorkerID and Date='$Date'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    return true;
  }
  return false;
}
?>