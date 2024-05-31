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

    //Take all the data about the user
    $email = $_SESSION["email"];
    $query = "SELECT * FROM users WHERE Email='$email'";
    if($user = mysqli_query($conn, $query)){
        if(mysqli_num_rows($user) > 0){
            $user = mysqli_fetch_array($user);
            $firstName = $user["FirstName"];
            $lastName = $user["LastName"];
            $admin = $user["Admin"];
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
    
    <main class="sectionEdit">
        <div class="container text-center fs-1 text">
            <div class="row">
                <div class="col">
                    <label>First Name: <?php echo "$firstName"; ?></label>
                </div>
                <div class="col">
                    <label>Last Name: <?php echo "$lastName"; ?></label>
                </div>
            </div>
            <div class="mt-4 tableDisplay">
            <table class="table table-striped mt-3 col-sm-4">
                <thead>
                    <tr>
                        <th>Book</th>
                        <th>End of the reservation</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        //Show all the reservation the user have
                        $query = "SELECT * FROM reservation WHERE Email='$email'";
                        if($reservations = mysqli_query($conn, $query)){
                            if(mysqli_num_rows($reservations) > 0){
                                while ($book = mysqli_fetch_array($reservations)){
                                    $endReservation = $book["EndReservation"];
                                    $bookId =$book["Id_book"];

                                    $bookQuery = "SELECT * FROM book WHERE Id='$bookId'";
                                    if ($bookResult = mysqli_query($conn, $bookQuery)) {
                                        if (mysqli_num_rows($bookResult) > 0) {
                                            $book = mysqli_fetch_array($bookResult);
                                            $title = $book["Title"];
                                        }
                                    }
                                    echo "<tr>";
                                    echo "<td>$title</td>";
                                    echo "<td>$endReservation</td>";
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
    
    <footer class="bg-dark mt-auto">
        <p class="text-center text-secondary p-3 m-0">© 2024, Antoine ESNAULT</p>
    </footer>
</body>
</html>