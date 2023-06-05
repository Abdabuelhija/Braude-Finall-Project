<?php
include 'LoginNav.php';
session_start();
include 'db_connection.php';
if (isset($_COOKIE['Blocked'])) {
  echo '<center>', '<h6 style="color:red">', "You have been blocked Dont try to sign!";
} else {
  CheckAccount();
}
function CheckAccount()
{
  include 'db_connection.php';
  $Search = mysqli_query($conn, "SELECT * FROM customers");
  while ($row = mysqli_fetch_array($Search)) {
    if ($_POST['loginID'] == $row['ID']) {
      if ($_POST['loginPassword'] == $row['pass']) {
        $_SESSION["id"] = $row['ID'];
        $_SESSION["carID"] = $row['carID'];
        $_SESSION["pass"] = $row['pass'];
        $_SESSION["firstname"] = $row['firstname'];
        $_SESSION["lastname"] = $row['lastname'];
        $_SESSION["email"] = $row['email'];
        $_SESSION["carType"] = $row['carType'];
        $_SESSION["PhoneNumber"] = $row['PhoneNumber'];
        $_SESSION["img"] = $row['img'];
        $_SESSION['count'] = 0;
        if ($row['isBlocked'] === 'Yes') {
          header("location:ClientFolder/php/Repass.php");
        } else {
          header("location:ClientFolder/php/Home.php");
        }
      } else {
        if (!isset($_SESSION["count"])) {
          echo '<center>', '<h6 style="color:red">', "Error password";
          $_SESSION["count"] = 1;
          return;
        } else {
          $_SESSION["count"]++;
          if ($_SESSION['count'] >= 3) {
            $UserMail = $row['email'];
            setcookie("Blocked", " ", time() + 30);
            blockUser($UserMail);
            return;
          }
          echo '<center>', '<h6 style="color:red">', "Error password, you tried " . $_SESSION['count'], " times";
          return;
        }
      }
    }
  }
  $Search = mysqli_query($conn, "SELECT * FROM workers");
  while ($row = mysqli_fetch_array($Search)) {
    if ($_POST['loginID'] == $row['ID']) {
      if ($_POST['loginPassword'] == $row['password']) {
        $_SESSION["id"] = $row['ID'];
        $_SESSION["firstname"] = $row['firstname'];
        $_SESSION["lastname"] = $row['lastname'];
        $_SESSION["email"] = $row['email'];
        $_SESSION["status"] = $row['status'];
        $_SESSION["competence"] = $row['competence'];
        $_SESSION["password"] = $row['password'];
        $_SESSION["img"] = $row['img'];
        $_SESSION['count'] = 0;
        header("location:WorkerFolder/php/Requests.php");
      } else {
        echo '<center>', '<h6 style="color:red">', "Error password, you tried ";
        return;

      }
    }
  }
  $Search = mysqli_query($conn, "SELECT * FROM admins");
  while ($row = mysqli_fetch_array($Search)) {
    if ($_POST['loginID'] == $row['ID']) {
      if ($_POST['loginPassword'] == $row['password']) {
        $_SESSION['count'] = 0;
        header("location:AdminFolder/php/Home.php");
      } else {
        echo '<center>', '<h6 style="color:red">', "Error password";
        return;
      }
    }
  }
  echo '<center>', '<h6 style="color:red">', "account not exist";
}
function blockUser($UserMail)
{
  include 'db_connection.php';
  echo '<center>', '<h6 style="color:red">', "You have been blocked allot of time";
  $_SESSION['count'] = 0;
  $randPass = rand(100000000, 999999999);
  $subject = "Warning From ABDS Carrage";
  $body = "Hello Customer , The password was typed incorrectly more than 3 times. your new password is $randPass";
  $headers = "From:a.abd.ab@hotmail.com";
  $Search = mysqli_query($conn, "SELECT * FROM customers WHERE email='$UserMail'");
  $row = mysqli_fetch_array($Search);
  $CLientPass = $row['pass'];
  $CLientPass1 = $row['Pass1'];
  $CLientPass2 = $row['Pass2'];
  $CLientPass3 = $row['Pass3'];

  $sql = "UPDATE customers SET Pass3='$CLientPass2',Pass2='$CLientPass1',Pass1='$CLientPass',pass ='$randPass',isBlocked='Yes' WHERE email='$UserMail'";
  if ($conn->query($sql) === TRUE) {
    echo "<script>alert('pass rested');</script>";
  }
  if (mail($UserMail, $subject, $body, $headers)) {
    echo '<center>', '<h6 style="color:green">', "Email successfully sent to $UserMail";
  } else {
    echo '<center>', '<h6 style="color:red">', "Email sending failed!";
  }

}
?>