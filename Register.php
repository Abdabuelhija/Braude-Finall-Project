<?php
include 'LoginNav.php';
?>
<html>
<link rel="stylesheet" href="ExternalStyle/FormStyle.css" />
<link rel="stylesheet" href="ExternalStyle/GeneralStyle.css" />
<link rel="stylesheet" href="ExternalStyle/LoginStyle.css" />
<title>Login</title>

<body class="LoginBody">
<img src="./Imgs/Logo.png" alt="Logo" id="logo"> 
  <div class="Lform">
    <form method="post">
      <h1>Sign up</h1>
      <input type="text" name="registerID" placeholder="Type Your ID" minlength="9" maxlength="9" required>
      <input type="text" name="registerFirstName" placeholder="Type your first name" required>
      <input type="text" name="registerLastname" placeholder="Type your last name" required>
      <input type="text" name="registerEmail" placeholder="Type your email" required>
      <input type="password" name="registerPassword" placeholder="Type your Password" minlength="4" required>
      <input type="password" name="registerRePassword" placeholder="Type your Password" minlength="4" required>
      <input type="text" name="phonenumber" placeholder="Type your phone number" required>
      <input type="text" name="registercarID" placeholder="Type your car ID" required>
      <label for="cars">car type</label>
      <select name="cars" id="cars" required>
        <option value="mercedes" name="mercedes">Mercedes</option>
        <option value="bmw" name="bmw">BMW</option>
        <option value="volkswagen" name="volkswagen">Volkswagen</option>
        <option value="audi" name="audi">Audi</option>
        <option value="skoda" name="skoda">Skoda</option>
      </select>
      <input type="submit" value="Submit" name="submitRegister">
    </form>
  </div>
  <br><br><br><br><br><br>
</body>

</html>

<?php
include 'db_connection.php';
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

if (isset($_POST['submitRegister'])) {
  $registerID = $_POST['registerID'];
  $registercarID = $_POST['registercarID'];
  $registerFirstName = $_POST['registerFirstName'];
  $registerLastname = $_POST['registerLastname'];
  $registerEmail = $_POST['registerEmail'];
  $registerPassword = $_POST['registerPassword'];
  $registerRePassword = $_POST['registerRePassword'];
  $phonenumber = $_POST['phonenumber'];
  $cars = $_POST['cars'];

  $sql = "INSERT INTO customers (id,carID,pass,firstname,lastname,PhoneNumber,email,carType)
VALUES ('$registerID','$registercarID','$registerPassword','$registerFirstName',' $registerLastname','$phonenumber','$registerEmail','$cars')";

  if (ifAccountExistRegister() == false) {
    if ($registerPassword == $registerRePassword) {
      if ($conn->query($sql) === TRUE) {
        echo "
    <script type='text/javascript'>alert('New account created successfully'); 
    window.location.assign('Home.php');</script>
    ";
      } else {
        echo '<center>', '<h6 style="color:red">', "Error: " . $sql . "<br>" . $conn->error;
      }
    } else {
      echo '<center>', '<h6 style="color:red">', " the two pass dosnt match try again </center></h6>";
    }
  } else if (ifAccountExistRegister() == "CarId Exist..") {
    echo '<center>', '<h6 style="color:red">', "CarId Exist..";
  } else {
    echo '<center>', '<h6 style="color:red">', "The account already exist.";
  }
}
function ifAccountExistRegister()
{
  include 'db_connection.php';
  $Search = mysqli_query($conn, "SELECT * FROM customers");
  while ($row = mysqli_fetch_array($Search)) {

    if ($_POST['registerID'] == $row['id']) {
      return true;
    }
    if ($_POST['registercarID'] == $row['carID']) {
      return "CarId Exist..";
    }
  }

  $Search = mysqli_query($conn, "SELECT * FROM workers");
  while ($row = mysqli_fetch_array($Search)) {
    if ($_POST['registerID'] == $row['workerID']) {
      return true;
    }
  }

  $Search = mysqli_query($conn, "SELECT * FROM admins");
  while ($row = mysqli_fetch_array($Search)) {
    if ($_POST['registerID'] == $row['id']) {
      return true;
    }


  }

  return false;
}

function getInputsError()
{

}
?>