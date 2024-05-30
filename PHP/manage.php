<?php
    session_start();
    require_once("config.php");
    if (!isset($_SESSION['email'])) {         // condition Check: if session is not set. 
        header('location: ../index.php');   // if not set the user is sendback to login page.
        exit;
    }
    if (isset($_POST['logout'])) {
        session_destroy();            //  destroys session 
        header('location: ../index.php');
        exit;
    }

    //take the valueof admin (0:no, 1:yes) for the user
    $email = $_SESSION["email"];
    $query = "SELECT Admin FROM users WHERE Email='$email'";
    if($user = mysqli_query($conn, $query)){
        if(mysqli_num_rows($user) > 0){
            $user = mysqli_fetch_array($user);
            $admin = $user["Admin"];
        }
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // Update admin status when the Modify button is pressed
        if (isset($_POST['ModifyAdmin'])) {
            $sql = "SELECT * FROM users";
            if ($users = mysqli_query($conn, $sql)) {
                if (mysqli_num_rows($users) > 0) {
                    while ($user = mysqli_fetch_array($users)) {
                        $userEmail = $user["Email"];
                        $firstName = $user["FirstName"];
                        $lastName = $user["LastName"];
                        $adminStatus = $user["Admin"];
                        $status = isset($_POST["admin_$firstName$lastName"]) ? 1 : 0;   //Put to 1 if the checkbox is check otherwise 0
                        if($status != $adminStatus && $userEmail != $email){
                            $updateQuery = "UPDATE users SET Admin='$status' WHERE Email='$userEmail'";
                            if(mysqli_query($conn, $updateQuery)){
                                echo $status;
                            } else {
                                echo "Erreur lors de la suppression du livre.";
                            }
                        }
                    }
                }
            }
        }
        //Delete Reservation
        if(isset($_POST["deleteReservation"])){
            $reservationId = $_POST["id"];
            $sql = "DELETE FROM reservation WHERE Id='$reservationId'";
            if(mysqli_query($conn, $sql)){

            } else {
                echo "Erreur lors de la suppression du livre.";
            }
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <link rel="stylesheet" href="../CSS/style.css">
    <!--Boostrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <!--Font awesome-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">

    <script src="../JS/manage.js" defer></script>
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="./home.php"><i class="fas fa-book"></i></a>
        </div>
        <div class="container-fluid">
            <?php
            if($admin == 1){
                echo '<a class="navbar-brand" href="./add.php">Add book</i></a>';
                echo '</div>';
                echo '<div class="container-fluid">';
                echo '<a class="navbar-brand" href="./manage.php">Management</i></a>';
            }
            
            ?>
        </div>
        <div class="dropdown dropstart">
            <a class="navbar-brand text-end" data-bs-toggle="dropdown" href="./profile.php"><i class="fas fa-user"></i></a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="./profile.php">Profile</a></li>
                <li><form action="" method="POST"><button class="dropdown-item" type="submit" name="logout">Log out</button></form></li>
            </ul>
        </div>
        
    </nav>
    
    <main class="sectionStart">
        <div class="container text-center fs-1 text">
            <button class="btn btn-outline-dark" id="btnAdmin" onclick="adminView()">Administrator</button>
            <button class="btn btn-outline-dark" id="btnReservation" onclick="reservationView()">Reservation</button>
            <div id="adminTable">
                <form method="POST">
                    <table class="table table-striped mt-3 col-sm-4">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Administrator</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                //Show all the users in a table
                                $query = "SELECT * FROM users";
                                if ($users = mysqli_query($conn, $query)) {
                                    if (mysqli_num_rows($users) > 0) {
                                        while ($user = mysqli_fetch_array($users)) {
                                            $firstName = $user["FirstName"];
                                            $lastName = $user["LastName"];
                                            $administrator = $user["Admin"];
                                            $userEmail = $user["Email"];
                                            echo "<tr>";
                                            echo "<td>$firstName</td>";
                                            echo "<td>$lastName</td>";
                                            echo "<td><input type='checkbox' name='admin_$firstName$lastName' ";
                                            if ($administrator == 1) {
                                                echo "checked";
                                            }
                                            if ($email == $userEmail) {
                                                echo " disabled";
                                            }
                                            echo "></td>";
                                            echo '</tr>';
                                        }
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                    <button class="btn btn-secondary" name="ModifyAdmin">Modify</button>
                </form>
            </div>
            <div id="reservationTable" class="collapse">
                <table class="table table-striped mt-3 col-sm-4">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Book</th>
                            <th>Start of the reservation</th>
                            <th>End of the reservation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            //Show all the reservation in a table with a bin to delete it if necessary
                            $query = "SELECT * FROM reservation ORDER BY Email";
                            if($reservations = mysqli_query($conn, $query)){
                                if(mysqli_num_rows($reservations) > 0){
                                    while ($reservation = mysqli_fetch_array($reservations)){
                                        $id = $reservation["Id"];
                                        $endReservation = $reservation["EndReservation"];
                                        $startReservation = $reservation["StartReservation"];
                                        $bookId =$reservation["Id_book"];
                                        $idEmail = $reservation["Email"];
    
                                        $bookQuery = "SELECT * FROM book WHERE Id='$bookId'";
                                        if ($bookResult = mysqli_query($conn, $bookQuery)) {
                                            if (mysqli_num_rows($bookResult) > 0) {
                                                $book = mysqli_fetch_array($bookResult);
                                                $title = $book["Title"];
                                            }
                                        }
                                        $userQuery = "SELECT * FROM users WHERE EMAIL='$idEmail'";
                                        if ($userResult = mysqli_query($conn, $userQuery)) {
                                            if (mysqli_num_rows($userResult) > 0) {
                                                $user = mysqli_fetch_array($userResult);
                                                $name = $user["FirstName"] . " ". $user["LastName"];
                                            }
                                        }
                                        echo "<tr>";
                                        echo "<td>$name</td>";
                                        echo "<td>$title</td>";
                                        echo "<td>$startReservation</td>";
                                        echo "<td>$endReservation</td>";
                                        echo '<td><a href="" class="card-link btn btn-secondary" data-bs-toggle="modal" data-bs-target="#deleteReservationModal"  data-id="'. $id .'"><i class="fas fa-trash-alt"></i></a></td>';
                                        echo "</tr>";
                                    }
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    
    <!-- Modal for deleting a reservation-->
    <div class="modal fade" id="deleteReservationModal" tabindex="-1" aria-labelledby="deleteReservationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="deleteReservationModalLabel">Delete Reservation</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure to delete this reservation?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form action="" method="POST">
                    <input type="hidden" name="id" id="id">
                    <button type="submit" class="btn btn-primary" name="deleteReservation">Delete</button>
                </form>
            </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark mt-auto">
        <p class="text-center text-secondary p-3 m-0">© 2024, Antoine ESNAULT</p>
    </footer>
</body>
</html>