<html>

<head>
    <link rel="stylesheet" href="../../ExternalStyle/NavStyle.css" />
    <link rel="stylesheet" href="../../ExternalStyle/GeneralStyle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<style>

</style>

<body>
    <div class="topnav">
        <a href="Home.php"><i class="fa fa-home" aria-hidden="true"></i> Home</a>
        <a href="Profile.php"> <i class="fa fa-user" aria-hidden="true"></i> Profile</a>
        <a href="contact.php"><i class="fa fa-compress" aria-hidden="true"></i> Contact us</a>
        <a href="receipt.php"><i class="fa fa-history" aria-hidden="true"></i> Receipts</a>
        <a class="Logout" onclick="logout()"><i class="fa fa-sign-out" aria-hidden="true" style="color: white;cursor:pointer;"></i></a>
    </div>
</body>

<script>
    function logout() {
        if (confirm("Are you sure you want to logout?")) {
            window.location.assign('../../index.php');
        }
    }
</script>

</html>