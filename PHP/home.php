<?php
    require_once("config.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Favicon
    <link rel="shortcut icon" href="http://www.w3.org/2000/svg">-->
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
            <a class="navbar-brand" href="#"><i class="fas fa-book"></i></a>
        </div>
        <div class="container-fluid">
            <form class="d-flex">
                <input class="form-control me-2" type="text" placeholder="Search">
                <a href="#" class="navbar-brand"><i class="fas fa-search"></i></a>
            </form>
        </div>
        <a class="navbar-brand text-end" href=""><i class="fas fa-user"></i></a>
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
                                echo '<div class="col p-3"><div class="card" style="width: 18rem;"><img src="'. $book["Image"] .'" class="card-img-top" alt="..."><div class="card-body"><h5 class="card-title">'. $book["Title"] .'</h5><p class="card-text">'. $book["Description"] .'</p></div><ul class="list-group list-group-flush"><li class="list-group-item">Author: '. $book["Author"] .'</li><li class="list-group-item">Date of publication: '. $book["Publication_date"] .'</li><li class="list-group-item">Theme: '. $book["Theme"] .'</li></ul><div class="card-body"><a href="./HTML/details.html" class="card-link btn btn-secondary">Reservate</a><a href="./HTML/details.html" class="card-link btn btn-secondary">Details</a> </div></div></div>';
                            }
                        }
                        
                    }
                ?>
            
        </div>
    </section>
        
    
    


    
    <footer class="bg-dark mt-auto">
        <p class="text-center text-secondary p-3 m-0">© 2024 Company, Inc</p>
    </footer>
</body>
</html>