<html>

<head>
    <link rel="stylesheet" href="../../ExternalStyle/NavStyle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="topnav3">
        <a href="Home.php"><i class="fa fa-home" aria-hidden="true"></i> Home</a>
        <a href="messages.php"><i class="fa fa-comments-o" aria-hidden="true"></i> Message's</a>
        <a href="Products.php"><i class="fa fa-car" aria-hidden="true"></i> Product's</a>
        <a href="Workers.php"><i class="fa fa-briefcase" aria-hidden="true"></i> Worker's</a>
        <a href="Clients.php"><i class="fa fa-users" aria-hidden="true"></i> Customer's</a>
        <a href="Shift.php"><i class="fa fa-users" aria-hidden="true"></i> Daily Shift</a>
        <a class="Logout" onclick="logout()" class="fa fa-users" ><i class="fa fa-sign-out" aria-hidden="true" style="color: white;cursor:pointer;"></i></a>
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