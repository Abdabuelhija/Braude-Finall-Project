<html>

<head>
    <link rel="stylesheet" href="../../ExternalStyle/NavStyle.css" />
    <link rel="stylesheet" href="../../GeneralStyle.css" />
    <link rel="stylesheet" href="../Style/WorkerStyle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="topnav">
        <a href="Requests.php"><i class="fa fa-paper-plane" aria-hidden="true"></i> Orders</a>
        <a href="carSearch.php"><i class="fa fa-search" aria-hidden="true"></i> Car Info</a>
        <a href="Shifts.php"><i class="fa fa-search" aria-hidden="true"></i> Shifts</a>
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