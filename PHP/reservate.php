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
</head>
<body class="d-flex flex-column min-vh-100">
<nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="./home.php"><i class="fas fa-book"></i></a>
        </div>
        <div class="container-fluid">
            <a class="navbar-brand collapse" href="#"><i class="fas fa-book">Add book</i></a>
        </div>
        <a class="navbar-brand text-end" href="./profile.php"><i class="fas fa-user"></i></a>
    </nav>
    <main>
        <section class="sectionStart">
            <?php
            require_once("config.php");
            if(isset($_GET["Id"])){
                $id = $_GET["Id"];

                $sql = "SELECT * FROM book WHERE Id=$id";

                if($books = mysqli_query($conn, $sql)){
                    if(mysqli_num_rows($books) > 0){
                        while ($book = mysqli_fetch_array($books)){
                            echo '<div class="col-md-5 align-middle p-3"><div class="card"><div style="text-align:center;"><img src="'. $book["Image"] .'" class="card-img-top imgCard mt-1" alt="..."></div><div class="card-body"><h5 class="card-title">'. $book["Title"] .'</h5><p class="card-text">'. $book["Description"] .'</p></div><ul class="list-group list-group-flush"><li class="list-group-item">Author: '. $book["Author"] .'</li><li class="list-group-item">Date of publication: '. $book["Publication_date"] .'</li><li class="list-group-item">Theme: '. $book["Theme"] .'</li></ul></div></div>';
                        }
                    }
                    
                }
            }else{
                header("Location: ./home.php");
            }
            ?>
            <form action="" id="reservationForm" method="POST">
                <fieldset class="m-2">
                    <legend>Reservation:</legend>
                    <div class="row m-2">
                        <label for="StartReservation">Start of reservation:</label>
                        <input type="Date" name="StartReservation" class="col-sm-4" required/>
                        
                        <label for="EndReservation">End of reservation:</label>
                        <input type="Date" name="EndReservation" class="col-sm-4" required/>
                        <input type="submit" class="btn btn-light mt-2">
                    </div>
                    
                    
                </fieldset>
            </form>
        </section>
        
    </main>
    
    <footer class="bg-dark mt-auto">
        <p class="text-center text-secondary p-3 m-0">© 2024 Company, Inc</p>
    </footer>
</body>
</html>