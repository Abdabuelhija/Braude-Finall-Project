<?php
  include 'LoginNav.php';
  session_start();
?>
<html>
  <head>
    <link rel="stylesheet" href="ExternalStyle/FormStyle.css"/>  
    <link rel="stylesheet" href="ExternalStyle/GeneralStyle.css"/>  
    <link rel="stylesheet" href="ExternalStyle/LoginStyle.css"/>  
    <title>Login</title>
  </head>
  <body class="LoginBody">
  <img src="./Imgs/Logo.png" alt="Logo" id="logo"> 
    <div class="Lform">
      <form action="checkPass.php" method="post" > 
        <h1>Login</h1>
        <input type="text"  name="loginID" placeholder="Type Your ID">
        <input type="password"  name="loginPassword" placeholder="Type your Password">
        <input type="submit" value="Submit" name="loginSubmet">
      </form>
      <center><small>If you not a member :<a href="Register.php" class="SU" >sign up</a><small></center>
    </div>
  </body>
</html>
<?php
?>