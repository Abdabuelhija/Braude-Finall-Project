<?php
  include 'clientNav.php';
  session_start();
?>
<html>
  <head>
    <link rel="stylesheet" href="../Style/ProfileStyle.css"/>
    <link rel="stylesheet" href="../../ExternalStyle/FormStyle.css"/> 
    <link rel="stylesheet" href="../../ExternalStyle/GeneralStyle.css"/>
    <!-- <script type="text/javascript" src="../javascript/profile_Js_Func.js"></script> -->
    <title>Profile</title>
  </head>

  <body>
    <div class="g">
      <div class="card">
        <img src="../../Imgs/Defult.png" class="DefultImg">
          <div class="container">
            <h2><b><?php echo $_SESSION['firstname'],$_SESSION['lastname']?></b></h4>
            <button class="button" onclick="showUpdates()">update information</button>
            <hr> 
            <div class="info">
              <b>ID:</b><?php echo $_SESSION['id']?>
              <b> Email: </b><?php echo $_SESSION['email']?>
              <b> car type:</b><?php echo $_SESSION['carType']?>
              <b> car ID:</b><?php echo $_SESSION['carID']?>
            </div>
        </div>
      </div>
    </div>
    <p id="Show1" style="margin-top:1px;"><p>
    <center><p id="Show2"><p></center>
  <script> 
    function showUpdates(){
        document.getElementById('Show1').innerHTML ="<div class='showupdatesdiv'>"+
        "<button class='buttonSH' onclick='showfnameform()'>"+"update first name"+"</button>"+
        "<button class='buttonSH' onclick='showlnameform()'>"+"update last name"+"</button>"+
        "<button class='buttonSH' onclick='showcarIDform()'>"+"update car ID"+"</button>"+
        "<button class='buttonSH' onclick='showpassform()'>"+"update password"+"</button>"+
        "<button class='buttonSH' onclick='showemailform()'>"+"update email"+"</button>"+
        "<button class='buttonSH' onclick='showcarTypeform()'>"+"update car type"+"</div>";
    }
    function showfnameform(){
        document.getElementById('Show2').innerHTML ="<div class='Lform'>"+
        "<form method='post' >"+
        "<input type='text' name='newfname' placeholder='change your firts name to :'>"+
        "<input type='submit' value='change' name='FNSUB'>"+
        "</form>"+"</div>";
    }
    function showlnameform(){
        document.getElementById('Show2').innerHTML ="<div class='Lform'>"+
        "<form method='post'>"+
        "<input type='text' name='newlname' placeholder='change your last name to :'>"+
        "<input type='submit' value='change' name='LNSUB'>"+
        "</form>";
    }
    function showcarIDform(){
        document.getElementById('Show2').innerHTML = "<div class='Lform'>"+ "<form method='post'>"+
        "<input type='text' name='newcarid' placeholder='change your car ID to :'>"+
        "<input type='submit' value='change' name='CarIDSUB'>"+
        "</form>"+"</div>";
    }
    function showpassform(){
        document.getElementById('Show2').innerHTML = "<div class='Lform'>"+ "<form method='post'>"+
        "<input type='text' name='presentpass' placeholder='Type your present password:'>"+
        "<input type='text' name='newpass' placeholder='change your password  to :'>"+
        "<input type='submit' value='change' name='PssSUB'>"+
        "</form>"+"</div>";
    }
    function showemailform(){
        document.getElementById('Show2').innerHTML = "<div class='Lform'>"+ "<form method='post'>"+
        "<input type='text' name='newmail' placeholder='change your mail to :'>"+
        "<input type='submit' value='change' name='MSUB'>"+
        "</form>"+"</div>";
    }
    function showcarTypeform(){
        document.getElementById('Show2').innerHTML = "<div class='Lform'>"+ "<form method='post'>"+
        "<label for='cars'>"+"change Car type To :"+"</label>"+
        "<select name='cars' id='cars'>"+
        "<option value='mercedes' name='mercedes'>"+"Mercedes"+"</option>"+
        "<option value='bmw' name='bmw'>"+"BMW"+"</option>"+
        "<option value='volkswagen' name='volkswagen'>"+"Volkswagen"+"</option>"+
        "<option value='audi' name='audi'>"+"Audi"+"</option>"+
        "<option value='skoda' name='skoda'>"+"Skoda"+"</option>"+
        "</select>"+
        "<input type='submit' value='change' name='CtypeSUB'>"+
        "</form>"+"</div>";
    }
  </script>
  </body>
</html>
<?php
  include '../../db_connection.php';
  $IID=$_SESSION["id"];
  $CID=$_SESSION["carID"];
  $PasS=$_SESSION["pass"];
  $Fname=$_SESSION["firstname"];
  $Lname=$_SESSION["lastname"];
  $EMail=$_SESSION["email"];
  $CarTpe=$_SESSION["carType"];
  if (isset($_POST['FNSUB'])){
    $newfname=$_POST['newfname'];
    $sql ="UPDATE customers SET firstname='$newfname' WHERE id='$IID' ";
    if ($conn->query($sql) === TRUE) {
      echo "
      <script type='text/javascript'>alert('the first name changed');</script>
      ";
    } 
    else {
    echo '<center>','<h6 style="color:red">',"Error: " . $sql . "<br>" . $conn->error;
    }
  }
  if (isset($_POST['LNSUB'])){
    $newlname=$_POST['newlname'];
    $sql ="UPDATE customers SET lastname='$newlname' WHERE id='$IID' ";
    if ($conn->query($sql) === TRUE) {
      echo "
      <script type='text/javascript'>alert('the last name changed');</script>
      ";
    } 
    else {
      echo '<center>','<h6 style="color:red">',"Error: " . $sql . "<br>" . $conn->error;
    }
  }
  if (isset($_POST['CarIDSUB'])){
    $newcarid=$_POST['newcarid'];
    $sql =" UPDATE customers SET carID='$newcarid' WHERE id='$IID' ";
    if ($conn->query($sql) === TRUE) {
      echo "
      <script type='text/javascript'>alert('the car ID changed');</script>
      ";
    } 
    else {
      echo '<center>','<h6 style="color:red">',"Error: " . $sql . "<br>" . $conn->error;
    }
  }
  if (isset($_POST['PssSUB'])){
    $newpass=$_POST['newpass'];
    if($_POST['presentpass']==$PasS){
      $sql =" UPDATE customers SET pass='$newpass' WHERE id='$IID'";
      if ($conn->query($sql) === TRUE) {
        echo "
        <script type='text/javascript'>alert('the password changed');</script>
        ";
      } 
    else {
      echo '<center>','<h6 style="color:red">',"Error: " . $sql . "<br>" . $conn->error;
    }
    }
    else {
    echo "<center> <h2 style='color'> You typed the Wrong password </center> </h2> ";
    }
  }
  if (isset($_POST['MSUB'])){
    $newemail=$_POST['newmail'];
    $sql ="UPDATE customers SET email='$newemail' WHERE id='$IID' ";
    if ($conn->query($sql) === TRUE) {
      echo "
      <script type='text/javascript'>alert('the mail changed');</script>
      ";
    } 
    else {
      echo '<center>','<h6 style="color:red">',"Error: " . $sql . "<br>" . $conn->error;
    }
  }
  if (isset($_POST['CtypeSUB'])){
    $newcartype=$_POST['cars'];
    $sql =" UPDATE customers SET carType='$newcartype' WHERE id='$IID'";
    if ($conn->query($sql) === TRUE) {
      echo "
      <script type='text/javascript'>alert('the car type changed');</script>
      ";
    } 
    else {
      echo '<center>','<h6 style="color:red">',"Error: " . $sql . "<br>" . $conn->error;
    }
  }
?>