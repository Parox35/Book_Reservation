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
        if(isset($_POST["delete"])){
            $bookId = $_POST["bookId"];
            $sql = "DELETE FROM book WHERE Id='$bookId'";
            if(mysqli_query($conn, $sql)){
                header('Location: ./home.php');
                exit;
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
    <title>Library</title>


    <link rel="stylesheet" href="../CSS/style.css">
    <!--Boostrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="../JS/login.js" defer></script>
    <!--Font awesome-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    
    <script src="../JS/home.js" defer></script>
    
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

    <section class="sectionSearch">
        <form class="d-flex ms-3 me-3 row" role="search">
            <input class="form-control me-2 searchbar col-sm-4" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success col-sm-1" type="submit"><i class="fas fa-search"></i></button>
            <div class="row">
                <p class="m-3" style="cursor:pointer;" onclick="showFilter()">Filter<i class="fas fa-caret-right ms-2" id="carretFilter"></i></p>
                <div class="row m-2 collapse" id="filterItems">
                    <div class="col">
                        Themes:
                        <select name="themes" id="themes">
                        <?php
                            $sql = "SELECT Theme FROM book";

                            if($themes = mysqli_query($conn, $sql)){
                                if(mysqli_num_rows($themes) > 0){
                                    echo '<option value="0">---</option>';
                                    while ($theme = mysqli_fetch_array($themes)){
                                        echo '<option value="'.$theme["Theme"].'">'. $theme["Theme"]  .'</option>';
                                    }
                                }
                            }
                        ?>
                        </select>
                    </div>
                    <div class="col">
                        Authors:
                        <select name="authors" id="authors">
                            <?php
                                $sql = "SELECT Author FROM book";

                                if($authors = mysqli_query($conn, $sql)){
                                    if(mysqli_num_rows($authors) > 0){
                                        echo '<option value="0">---</option>';
                                        while ($author = mysqli_fetch_array($authors)){
                                            echo '<option value="'.$author["Author"].'">'. $author["Author"]  .'</option>';
                                        }
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            
        </form>
    </section>
   

    <section>
        <div class="row m-3">
                <?php

                    if(isset($_GET["authors"]) && isset($_GET["themes"])){
                        $author = $_GET["authors"];
                        $theme = $_GET["themes"];

                        if($author == "0" && $theme == "0"){
                            $sql = "SELECT * FROM book";
                        }elseif ($author == "0" || empty($author)){
                            $sql = "SELECT * FROM book WHERE Theme='$theme'";
                        }elseif ($theme == "0" || empty($theme)){
                            $sql = "SELECT * FROM book WHERE Author='$author'";
                        }else{
                            $sql = "SELECT * FROM book WHERE Author='$author' and Theme='$theme'";
                        }
                    }else{
                        $sql = "SELECT * FROM book";
                    }
                    
                    

                    if($books = mysqli_query($conn, $sql)){
                        if(mysqli_num_rows($books) > 0){
                            while ($book = mysqli_fetch_array($books)){
                                echo '<div class="col p-3"><div class="card" style="width: 18rem;"><img src="'. $book["Image"] .'" class="card-img-top" alt="..."><div class="card-body"><h5 class="card-title">'. $book["Title"] .'</h5><p class="card-text">'. $book["Description"] .'</p></div><ul class="list-group list-group-flush"><li class="list-group-item">Author: '. $book["Author"] .'</li><li class="list-group-item">Date of publication: '. $book["Publication_date"] .'</li><li class="list-group-item">Theme: '. $book["Theme"] .'</li></ul><div class="card-body"><a href="./reservate.php?Id='. $book["Id"] .'" class="card-link btn btn-secondary">Reservate</a>';
                                
                                if($admin == 1){
                                    echo '<a href="./edit.php?Id='. $book["Id"] .'" class="card-link btn btn-secondary">Edit</a>';
                                    echo '<a href="" class="card-link btn btn-secondary" data-bs-toggle="modal" data-bs-target="#deleteModal"  data-id="'. $book["Id"] .'" data-title="'. htmlspecialchars($book["Title"], ENT_QUOTES, 'UTF-8') .'"><i class="fas fa-trash-alt"></i></a>';
                                }
                                echo '</div></div></div>';
                            }
                        }
                        
                    }
                ?>
            
        </div>
    </section>
        
    
    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Delete book</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure to delete <span id="bookTitle">this book</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form action="" method="POST" id="deleteForm">
                    <input type="hidden" name="bookId" id="bookId">
                    <button type="submit" class="btn btn-primary" name="delete">Delete</button>
                </form>
            </div>
            </div>
        </div>
    </div>


    
    <footer class="bg-dark mt-auto">
        <p class="text-center text-secondary p-3 m-0">© 2024 Company, Inc</p>
    </footer>
</body>
</html>