<?php
    session_start();
    require_once("config.php");
    //Check if an user is connected
    if (!isset($_SESSION['email'])) {         // condition Check: if session is not set. 
        header('location: ../index.php');   // if not set the user is sendback to login page.
        exit;
    }
    //To deconnect the user
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

    //Get all the information about the book
    if(isset($_GET["Id"])){
        $id = $_GET["Id"];

        $sql = "SELECT * FROM book WHERE Id=$id";

        if($books = mysqli_query($conn, $sql)){
            if(mysqli_num_rows($books) > 0){
                $book = mysqli_fetch_array($books);
                    $id =$book["Id"];
                    $image = $book["Image"];
                    $title = $book["Title"];
                    $description = $book["Description"];
                    $author = $book["Author"];
                    $pub_date = $book["Publication_date"];
                    $theme = $book["Theme"];
            }
            
        }
    }else{
        header("Location: ./home.php");
    }

    //Variable to know if the dates of reservation of the book are free (0=free, 1=Booked)
    $free = 0;

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["reservate"])){
            $StartReservation = $_POST["StartReservation"];
            $endReservation = $_POST["EndReservation"];
    
            //Protection against SQL Injections
            $StartReservation = stripcslashes($StartReservation);
            $endReservation = stripcslashes($endReservation);
    
            $StartReservation = mysqli_real_escape_string($conn, $StartReservation);
            $endReservation = mysqli_real_escape_string($conn, $endReservation);

            //Take all the reservation that exist for this book
            $sql = "SELECT * FROM reservation WHERE Id_book=$id";

            //Check if the reservation is available
            if($reservations = mysqli_query($conn, $sql)){
                if(mysqli_num_rows($reservations) > 0){
                    while($reservation = mysqli_fetch_array($reservations)){
                        $start = $reservation["StartReservation"];
                        $end = $reservation["EndReservation"];

                        if(($StartReservation > $start && $StartReservation < $end) || ($endReservation > $start && $endReservation < $end)){
                            $free = 1;
                        }
                    }
                }
            }

            //Add the reservation to the database if it is available ansd return to the home page
            if($free != 1){
                $sql = "INSERT INTO reservation (StartReservation, EndReservation, Email, Id_book) VALUES ('$StartReservation', '$endReservation', '$email',  '$id')";
                if(mysqli_query($conn,$sql)){
                    echo '<script type="text/javascript">
                            window.onload = function() {
                                var myModal = new bootstrap.Modal(document.getElementById("reservationModal"));
                                myModal.show();
                            }
                        </script>';
                    header('refresh:1; url=./home.php');
                }else{
                    echo "Something went wrong: $sql";
                }
            }
            
        }  
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservate book</title>

    <link rel="stylesheet" href="../CSS/style.css">
    <!--Boostrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <!--Font awesome-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">

    <script src="../JS/reservate.js" defer></script>

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
    <main>
        <section class="sectionStart">
            <div class="col-md-5 align-middle p-3">
                <div class="card">
                    <div style="text-align:center;">
                        <img src="<?php echo $image; ?>" class="card-img-top imgCard mt-1">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php  echo $title; ?></h5>
                        <p class="card-text"><?php echo $description; ?></p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Author: <?php echo $author; ?></li>
                        <li class="list-group-item">Date of publication: <?php echo $pub_date; ?></li>
                        <li class="list-group-item">Theme: <?php echo $theme; ?></li>
                    </ul>
                </div>
            </div>
            <form action="" id="reservationForm" method="POST">
                <fieldset class="m-2">
                    <legend>Reservation:</legend>
                    <div class="row m-2">
                        <span id="errorDate" class="mb-2 text-danger h6"></span>
                        <label for="StartReservation">Start of reservation:</label>
                        <input type="Date" name="StartReservation" min="<?php $t=time(); echo(date("Y-m-d",$t)); ?>" id="StartReservation" disabled-dates="2024-06-04" class="col-sm-4" required onchange="updateMin()" disabled-dates="2024-06-04, 2024-06-15"/>
                        
                        <label for="EndReservation">End of reservation:</label>
                        <input type="Date" name="EndReservation" id="EndReservation" class="col-sm-4" required/>
                        <input type="submit" class="btn btn-light mt-3" name="reservate">
                    </div>
                    
                    
                </fieldset>
            </form>
        </section>
        
    </main>
    
    <!-- Modal to say to the user that is reservation is done-->
    <div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="reservationmodalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                  <h1 class="modal-title fs-5" id="reservationmodalLabel">Reservation done</h1>
              </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark mt-auto">
        <p class="text-center text-secondary p-3 m-0">© 2024, Antoine ESNAULT</p>
    </footer>
</body>

    <?php
        //Verify if the variable free is at 1 (= There is already one reservation at this dates) and execute the script to show the date where the book is reservate to the user
        if($free == 1){
            $startDate = new DateTime($start);
            $endDate = new DateTime($end);

            // Format the dates
            $startFormatted = $startDate->format('l, F jS Y');
            $endFormatted = $endDate->format('l, F jS Y');
            echo '<script type="text/javascript">
                    document.getElementById("errorDate").innerHTML = "The book is already reserved during ';
                    echo $startFormatted;
                    echo ' to ';
                    echo $endFormatted;
                    echo '.";
                </script>';
        }
        
    ?>
</html>