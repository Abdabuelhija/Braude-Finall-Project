<html>

<head>
    <link rel="stylesheet" href="../../ExternalStyle/FormStyle.css" />
    <title> UPDATE PRICE</title>
</head>
<style>
    body {
        font-family: "Century Gothic", CenturyGothic, AppleGothic, sans-serif;
        font-style: normal;
        font-variant: normal;
        font-weight: 700;
        line-height: 26.4px;
        background-color: #e3e1ff;
        margin: auto;
        background-color: #e3e1ff;
    }

    .Lform {
        margin-top: 150px;
    }
</style>

<body>
    <div class="Lform">
        <form method="post">
            <h3>Update price </h3>
            <input type="text" name="newprice" required placeholder="choose the price"><br>
            <input type="submit" value="Update" name="submit">
        </form>
    </div>
</body>

</html>
<?php
include "../../db_connection.php";
session_start();
$Productid = $_SESSION['ProducID'];
if (isset($_POST['submit'])) {
    $newprice = $_POST['newprice'];
    if ($newprice <= 0) {
        echo "<script>alert('type price >0');</script> ";
        return;
    }

    $sql = "UPDATE products SET price='$newprice' WHERE ID='$Productid'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>
            alert('the price updated');
            </script>";
        echo "<script>
            window.close();
            </script>";
    } else {
        echo "Error in sql Query";
    }
}
?>