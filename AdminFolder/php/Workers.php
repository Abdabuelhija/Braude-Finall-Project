<?php
include 'Navbar.php';
echo "";
?>

<html> 
    <head>
        <link rel="stylesheet" href="../../ExternalStyle/GeneralStyle.css"/>
        <link rel="stylesheet" href="../../ExternalStyle/FormStyle.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <style>
        .Lform{
        display:none;
        }
        .Grid{
    display: grid;
    grid-template-columns:290px 290px 290px 290px; 
    column-gap: 45px;
    margin-left: 30px;
    }
    body{
        background-color: #e3e1ff;
    }
    .card{
        margin-bottom: 50px;
    }
    </style>
    <body>
    <?php
    function showAddWorkerDiv(){
        echo"
        <style>
        .Lform{
            display:block;
        }
        </style>
        ";
    }
    ?>  
    <center><form method='post'><input type='submit' name='addWorker' value='Add Worker' style='background-color:rgb(65, 158, 250);width:270px;'></form></center>
    <div class="Lform">
        <form method="post" >
            <h3>Add a worker:</h3>
            Worker id: <input type="text" id="ID" name="ID" minlength="9" maxlength="9" required><br>
            Worker first name: <input type="text" id="firstname" name="firstname" required><br>
            Worker last name: <input type="text" id="lastname" name="lastname" required><br>
            Worker phone number: <input type="text" id="phonenumber" name="phonenumber" required><br>
            Worker email: <input type="text" id="email" name="email" required><br>
            Worker password: <input type="password" id="password" name="password" minlength="4" required><br>
            Competence:
            <select name="competence" id="competence" required>
                <option value="mercedes" name="mercedes">Mercedes</option>
                <option value="bmw" name="bmw">BMW</option>
                <option value="volkswagen" name="volkswagen">Volkswagen</option>
                <option value="audi" name="audi">Audi</option>
                <option value="skoda" name="skoda">Skoda</option>
            </select>
            <br><br>
            <input type="submit" value="Add" name="submit">
        </form>
    </div>
        <?php
            include "../../db_connection.php";
            echo "<br>";
            $matchedTo = "";
            if (isset($_POST['submit'])){
                $id = $_POST['ID'];
                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];
                $email = $_POST['email'];
                $competence = $_POST['competence'];
                $password = $_POST['password'];
                $phonenumber = $_POST['phonenumber'];
                    if(!(is_numeric($id))){
                        echo "<script>alert('the ID not a number');</script>";
                        return;
                    }
                if (isWorkerFound() == false){
                    $sql = "INSERT INTO workers (ID,firstname,lastname,PhoneNumber,email,competence,password)
                    VALUES ('$id','$firstname','$lastname','$phonenumber',' $email','$competence',' $password')";
                    if ($conn->query($sql) === TRUE) {
                    echo "<script>alert('Worker inserted');</script>";
                    header("refresh:0");
                    } 
                    else {
                        echo '<center>','<h6 style="color:red">',"Error: " . $sql . "<br>" . $conn->error;
                    }
                }   
                else{
                    echo "Worker already exist";
                }
            }
            function isWorkerFound(){
                include "../../db_connection.php";
                $sql = "SELECT * FROM workers";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        if ($row['ID'] == $_POST['ID']){
                            return true;
                        }
                    }
                }
                return false;
            }
        ?>
        <br>
        <div class="Grid">
            <?php
            include "../../db_connection.php";
            $sql = "SELECT * FROM workers";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $id = $row['ID'];
                    $firstname = $row['firstname'];
                    $lastname = $row['lastname'];
                    $competence = $row['competence'];
                    echo
                    "<div class='card' style='width: 18rem;'>
                    <img class='card-img-top' src='../../Imgs/Defult.png' alt='Card image cap'>
                    <div class='card-body'>
                    <h5 class='card-title'>$firstname $lastname</h5>
                    ID:",$row['ID'],"<br>
                    competence: $competence<br>
                    phoneNumber:",$row['PhoneNumber'],"<br>
                    email: ",$row['email'],"<br>
                    </div>
                    </div>";
                }
            } 
            else {
                echo "0 results";
            }
            function showInfo(){
            header("location:WorkerInfo.php");
            }
            ?>
        </div>
    </body>
</html>
<?php
    if (isset($_POST['addWorker'])){
        showAddWorkerDiv();
        } 
?>
