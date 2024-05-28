<?php 
session_start();
  if (isset($_SESSION['email'])) {
    header("Location: ./PHP/home.php");
  }
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    require_once("./PHP/config.php");
  
    //Register/Sign up
    if(isset($_POST["sign_up"])){
      $email = $_POST['email'];
      $firstName = $_POST['firstName'];
      $lastName = $_POST['lastName'];
      $password = $_POST['password'];

      $email = stripcslashes($email);
      $password = stripcslashes($password);
      $firstName = stripcslashes($firstName);
      $lastName = stripcslashes($lastName);

      $email = mysqli_real_escape_string($conn, $email);
      $password = mysqli_real_escape_string($conn, $password);
      $firstName = mysqli_real_escape_string($conn, $firstName);
      $lastName = mysqli_real_escape_string($conn, $lastName);

      $password = password_hash(mysqli_real_escape_string($conn, $_POST['password']), PASSWORD_BCRYPT);

      $sql = "INSERT INTO users VALUES('{$email}','{$firstName}','{$lastName}','{$password}',0)";
      $addUser = mysqli_query($conn, $sql);

      if (!$addUser) {
        echo "Something went wrong" . mysqli_error($conn);
      } else {
        //popup
        header('location: index.php');
      }
    }

    //Login 
    if (isset($_POST['login'])) {
      $email = $_POST["email"];
      $password = $_POST["password"];

      $email = stripcslashes($email);
      $password = stripcslashes($password);
      $email = mysqli_real_escape_string($conn, $email);
      $password = mysqli_real_escape_string($conn, $password);
      
      $sql = "SELECT * from users WHERE Email = '$email' AND Password = '$password'";
      $user = mysqli_query($conn, $sql);

      if (!$user) {
        die('query Failed' . mysqli_error($conn));
      }
      $user_email = "";
      $user_password = "";
      if(mysqli_num_rows($user) > 0){
        while ($row = mysqli_fetch_array($user)) {

          $user_email = $row['Email'];
          $user_password = $row['Password'];
        }
      }
      if ($user_email == $email  &&  $user_password == $password) {

        $_SESSION['email'] = $user_email;       // Storing the value in session
        header('location: ./PHP/home.php');
        exit;
      } else {
        header('location: index.php');
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
    <link href="./CSS/style.css" rel="stylesheet">
    <!--Boostrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="./JS/login.js" defer></script>
</head>
<body class="bg-dark">
  <div class="container position-absolute top-50 start-50 translate-middle">

    <div class="row justify-content-md-center">
        <div class="col-md-6">
            <div class="card">
              <div class="card-header text-center">
                <span class="px-3" style="cursor:pointer;" onclick="loginFunction()">Login</span>
                -
                <span class="px-3" style="cursor:pointer;" onclick="signFunction()">Sign up</span>
              </div>
                <div class="card-body">
                  <!--Login-->

                  <div id="div_login">
                    <h3 class="text-center">Login</h3>
                    <form action="" method="POST" class="text-center">
                      <div class="row">
                        <label for="email">Email:</label>
                        <input type="email" name="email" class="col-sm-6 mx-auto" required>
                      </div>
                      <div class="row">
                        <label for="password">Password:</label>
                        <input type="password" name="password" class="col-sm-6 mx-auto"  autocomplete="off" required>
                      </div>
                      <input type="submit" value="Login" name="login" class="m-3 btn btn-outline-success">
                    </form>
                  </div>
                    

                  <!--Sign up-->
                  <div class="collapse" id="div_register">
                    <h3 class="text-center">Sign up</h3>
                    <form action="" class="text-center">
                    <div class="row">
                      <label for="email">Email:</label>
                      <input type="email" name="email" class="col-sm-6 mx-auto" required>
                    </div>
                    <div class="row">
                      <label for="firstName">First Name:</label>
                      <input type="text" name="firstName" class="col-sm-6 mx-auto" required>
                    </div>
                    <div class="row">
                      <label for="lastName">Last Name:</label>
                      <input type="text" name="lastName" class="col-sm-6 mx-auto" required>
                    </div>
                    <div class="row">
                      <label for="password">Password:</label>
                      <input type="password" name="password" class="col-sm-6 mx-auto" autocomplete="off" required>
                    </div>
                      <input type="submit" value="Sign up" name="sign_up" class="m-3 btn btn-outline-success">
                    </form>
                  </div>

                </div>
            </div>
        </div>
    </div>

  </div>
</body>
</html>