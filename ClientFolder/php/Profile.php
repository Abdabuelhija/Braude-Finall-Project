<?php
include 'Navbar.php';
session_start();
?>
<html>

<head>
  <link rel="stylesheet" href="../Style/ProfileStyle.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
    crossorigin="anonymous"></script>
  <title>Profile</title>
</head>

<body>
  <?php
  include '../../db_connection.php';
  $ID = $_SESSION["id"];
  $CarID = $_SESSION["carID"];
  $Password = $_SESSION["pass"];
  $FirstName = $_SESSION["firstname"];
  $LastName = $_SESSION["lastname"];
  $Email = $_SESSION["email"];
  $CarType = $_SESSION["carType"];
  $PhoneNumber = $_SESSION["PhoneNumber"];
  $img = $_SESSION["img"];
  ?>
  <div class="g">
    <div class="card">
      <img src="<?php echo $img; ?>" class="DefultImg">
      <div class="container">
        <h2><b>
            <?php echo $_SESSION['firstname'] . " " . $_SESSION['lastname'] ?>
          </b></h4>
          <button class="button" type="button" data-toggle="modal" data-target="#exampleModal"
            onclick="showUpdates()">update information</button>
          <hr>
          <div class="info">
            <b>ID:</b>
            <?php echo $_SESSION['id'] ?>
            <b> Email: </b>
            <?php echo $_SESSION['email'] ?>
            <b> car type:</b>
            <?php echo $_SESSION['carType'] ?>
            <b> car ID:</b>
            <?php echo $_SESSION['carID'] ?>
          </div>
      </div>
    </div>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="height: 810px;">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">
            <h2>Update informations</h2>
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="row" method="post">
            <div class="form-group col-md-12">
              <label>Phone number</label>
              <input type="text" id="phonenumber" name="phonenumber" class="form-control"
                value="<?php echo $PhoneNumber; ?>" required><br>
            </div>
            <div class="form-group col-md-6">
              <label>First name</label>
              <input type="text" id="firstname" name="firstname" class="form-control" value="<?php echo $FirstName; ?>"
                required><br>
            </div>
            <div class="form-group col-md-6">
              <label>Last name</label>
              <input type="text" id="lastname" name="lastname" class="form-control" value="<?php echo $LastName; ?>"
                required><br>
            </div>
            <div class="form-group col-md-6">
              <label>Email</label>
              <input type="email" id="email" name="email" class="form-control" value="<?php echo $Email; ?>"
                required><br>
            </div>
            <div class="form-group col-md-6">
              <label>Password</label>
              <input type="password" id="password" name="password" minlength="4" class="form-control"
                value="<?php echo $Password; ?>" required><br>
            </div>
            <div class="form-group col-md-6">
              <label>Car ID</label>
              <input type="text" id="carID" name="carID" minlength="4" class="form-control"
                value="<?php echo $CarID; ?>" required><br>
            </div>
            <div class="form-group col-md-12">
              <label>Img url</label>
              <input type="text" id="imgurl" name="imgurl" class="form-control"
                value="<?php echo $img; ?>" required><br>
            </div>
            <div class="form-group col-md-6">
              <label>Car type</label>
              <select name="CarType" id="CarType" class="form-control form-control-lg" required>
                <option value="mercedes" name="mercedes">Mercedes</option>
                <option value="bmw" name="bmw">BMW</option>
                <option value="volkswagen" name="volkswagen">Volkswagen</option>
                <option value="audi" name="audi">Audi</option>
                <option value="skoda" name="skoda">Skoda</option>
              </select>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" name="update"
                style="background-color:#0B8793 ;">Update</button>
            </div>
          </form>
        </div>
      </div>
</body>

</html>
<?php
include '../../db_connection.php';
if (isset($_POST['update'])) {
  $newPhoneNumber = $_POST['phonenumber'];
  $newFirstName = $_POST['firstname'];
  $newLastName = $_POST['lastname'];
  $newEmail = $_POST['email'];
  $newPassword = $_POST['password'];
  $newCarID = $_POST['carID'];
  $newCarType = $_POST['CarType'];
  $newimgurl=$_POST['imgurl'];

  $sql = "UPDATE customers SET 
    PhoneNumber = '$newPhoneNumber', 
    firstname = '$newFirstName', 
    lastname = '$newLastName', 
    email = '$newEmail', 
    pass = '$newPassword', 
    carID = '$newCarID', 
    carType = '$newCarType',
    img='$newimgurl'
    WHERE ID = '$ID'";
  if ($conn->query($sql) === TRUE) {
      $_SESSION["carID"] =$newCarID;
      $_SESSION["pass"] =$newPassword;
      $_SESSION["firstname"] =$newFirstName;
      $_SESSION["lastname"] =$newLastName;
      $_SESSION["email"] =$newEmail;
      $_SESSION["carType"] =$newCarType;
      $_SESSION["PhoneNumber"] = $newPhoneNumber;
      $_SESSION["img"] = $newimgurl;

    echo "<script>alert('the changes were successfully saved ');</script>";
  }
}
?>