<?php
include 'Navbar.php';
echo "";
?>

<html>

<head>
    <link rel="stylesheet" href="../style/Products.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</head>

<body>
    <button type="button" class="Add-Worker" data-toggle="modal" data-target="#AddWorkerModal"><i class="fa fa-plus"
            aria-hidden="true"></i> Add Worker </button>
    <button type="button" class="Add-Worker" data-toggle="modal" data-target="#AddSpecializationModal"><i
            class="fa fa-plus" aria-hidden="true"></i> Add Specialization </button><br>
    <!-- Add Worker Modal -->
    <div class="modal fade" id="AddWorkerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="height: 800px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <h2>Add worker</h2>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <input type="text" id="ID" name="ID" placeholder="ID" minlength="9" maxlength="9"
                            class="form-control" required><br>
                        <input type="text" placeholder="first name" id="firstname" name="firstname" class="form-control"
                            required><br>
                        <input type="text" placeholder="last name" id="lastname" name="lastname" class="form-control"
                            required><br>
                        <input type="text" placeholder="phone number" id="phonenumber" name="phonenumber"
                            class="form-control" required><br>
                        <input type="mail" placeholder="email" id="email" name="email" class="form-control"
                            required><br>
                        <input type="password" placeholder="password" id="password" name="password" minlength="4"
                            class="form-control" required><br>
                        <input type="text" placeholder="imgurl" id="imgurl" name="imgurl" class="form-control"
                            required><br>
                        Competence:
                        <?php
                        include "../../db_connection.php";
                        $sql = "SELECT * FROM specialization";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $specialization = $row["specialization"];
                                echo '<div class="form-check">';
                                echo "<input class='form-check-input' type='checkbox' name='{$specialization}' value='{$specialization}'>";
                                echo "<label class='form-check-label'>{$specialization}</label>";
                                echo '</div>';
                            }
                            
                        }
                        ?>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="submit"
                                style="background-color:#0B8793 ;">Add Worker</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Specialization Modal  -->
    <div class="modal fade" id="AddSpecializationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="height: 250px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <h2>Add Specialization</h2>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <input type="text" id="SpecializationInput" name="SpecializationInput" placeholder="Audi"
                            class="form-control" required><br>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="AddSpecializationSubmit"
                                style="background-color:#0B8793 ;">Add Specialization</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="CardGrid">
        <?php
        include "../../db_connection.php";
        $sql = "SELECT * FROM workers";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id = $row['ID'];
                $firstname = $row['firstname'];
                $lastname = $row['lastname'];
                $competence = $row['competence'];
                $img = $row['img'];
                echo
                    "<div class='card' style='width: 18rem;'>
                    <img class='card-img-top' src=$img>
                    <div class='card-body'>
                    <h5 class='card-title'>$firstname $lastname</h5>
                    ID:", $row['ID'], "<br>
                    competence: $competence<br>
                    phone number:", $row['PhoneNumber'], "<br>
                    email: ", $row['email'], "<br>
                    </div>
                    </div>";
            }
        } else {
            echo "0 results";
        }
        ?>
    </div>
    <script>
    function validateForm() {
      var checkboxes = document.querySelectorAll('input[type="checkbox"]');
      var checkedOne = Array.prototype.slice.call(checkboxes).some(x => x.checked);
      if (!checkedOne) {
        alert("Please select at least one option");
        return false;
      }
    }
  </script>
</body>

</html>
<?php
include "../../db_connection.php";
echo "<br>";
$matchedTo = "";
if (isset($_POST['submit'])) {
    $id = $_POST['ID'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $competence = $_POST['competence'];
    $password = $_POST['password'];
    $phonenumber = $_POST['phonenumber'];
    $imgurl = $_POST['imgurl'];

    if (!(is_numeric($id))) {
        echo "<script>alert('the ID not a number');</script>";
        return;
    }
    if (isWorkerFound() == false) {
        $sql = "INSERT INTO workers (ID, firstname, lastname, PhoneNumber, email, competence, password, img)
        VALUES ('$id', '$firstname', '$lastname', '$phonenumber', '$email', '$competence', '$password', '$imgurl')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Worker inserted');</script>";
        } else {
            echo '<center>', '<h6 style="color:red">', "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "<script>alert('The Worker already exist');</script>";
    }
}
if (isset($_POST['AddSpecializationSubmit'])) {
    $specializationInput = $_POST['SpecializationInput'];
    $sql = "INSERT INTO specialization (specialization) VALUES ('$specializationInput')";
    if ($conn->query($sql) === True) {
        echo "<script>alert('the specialization inserted ');</script>";
    } else {
        echo '<center>', '<h6 style="color:red">', "Error: " . $sql . "<br>" . $conn->error;
    }
}

function isWorkerFound()
{
    include "../../db_connection.php";
    $sql = "SELECT * FROM workers";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['ID'] == $_POST['ID']) {
                return true;
            }
        }
    }
    return false;
}
?>