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


    if(isset($_GET["Id"])){
        $id = $_GET["Id"];

        $sql = "SELECT * FROM book WHERE Id=$id";

        if($books = mysqli_query($conn, $sql)){
            if(mysqli_num_rows($books) > 0){
                while ($book = mysqli_fetch_array($books)){
                    $image = $book["Image"];
                    $title = $book["Title"];
                    $description = $book["Description"];
                    $author = $book["Author"];
                    $pub_date = $book["Publication_date"];
                    $theme = $book["Theme"];
                }
            }
            
        }
    }
    elseif($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["edit"])){
            $bookId = $_POST["id"];
            $image = $_POST["imageURL"];
            $title = $_POST["title"];
            $description = $_POST["description"];
            $author = $_POST["author"];
            $pub_date = $_POST["pub_date"];
            $theme = $_POST["theme"];

            //Protection against SQL Injections
            $bookId = stripcslashes($bookId);
            $image = stripcslashes($image);
            $title = stripcslashes($title);
            $description = stripcslashes($description);
            $author = stripcslashes($author);
            $pub_date = stripcslashes($pub_date);
            $theme = stripcslashes($theme);

            $bookId = mysqli_real_escape_string($conn, $bookId);
            $image = mysqli_real_escape_string($conn, $image);
            $title = mysqli_real_escape_string($conn, $title);
            $description = mysqli_real_escape_string($conn, $description);
            $author = mysqli_real_escape_string($conn, $author);
            $pub_date = mysqli_real_escape_string($conn, $pub_date);
            $theme = mysqli_real_escape_string($conn, $theme);

            $sql = "UPDATE book SET `Title` = '$title', `Image` = '$image', `Author`='$author', `Theme`='$theme', `Description`='$description', `Publication_date`='$pub_date' WHERE `Id` = $bookId";


            if(mysqli_query($conn,$sql)){
                header("Location: ./home.php");
            }else{
                echo "Something went wrong: $sql";
            }
        }
    }
    else{
        header("Location: ./home.php");
    }

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit book</title>

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
        <form action="edit.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>" />
            <div class="form-group mb-3">
                <img src="<?php echo $image; ?>" class="form-control imgCard"/>
                <input type="url" name="imageURL" class="form-control" value="<?php echo $image ?>" required/>
            </div>
            <div class="form-group mb-3">
                <label>Title:</label>
                <input type="text" name="title" value="<?php echo $title; ?>" class="form-control" required/>
            </div>
            <div class="form-group mb-3">
                <label>Date of publication:</label>
                <input type="number" name="pub_date" value="<?php echo $pub_date; ?>" class="form-control" required/>
            </div>
            <div class="form-group mb-3">
                <label>Author:</label>
                <input type="text" name="author" value="<?php echo $author; ?>" class="form-control" required/>
            </div>
            <div class="form-group mb-3">
                <label>Theme:</label>
                <input type="text" name="theme" value="<?php echo $theme; ?>" class="form-control" required/>
            </div>
            <div class="form-group mb-3">
                <label>Description:</label>
                <textarea name="description" class="form-control" style="height:200px;" required><?php echo $description; ?></textarea>
            </div>

            <div class="form-group mb-3 text-center">
                <input type="submit" class="btn btn-success" name="edit" value="Submit">
                <a href="./home.php" class="btn btn-primary">Back</a>
            </div>
        </form>
    </main>
    
    <footer class="bg-dark mt-auto">
        <p class="text-center text-secondary p-3 m-0">© 2024, Antoine ESNAULT</p>
    </footer>
</body>
</html>