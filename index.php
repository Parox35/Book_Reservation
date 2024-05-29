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
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
      }
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

      $password = password_hash(mysqli_real_escape_string($conn, $password), PASSWORD_BCRYPT);

      $sql = "INSERT INTO users VALUES('{$email}','{$firstName}','{$lastName}','{$password}',0)";
      $addUser = mysqli_query($conn, $sql);

      if (!$addUser) {
        echo "Something went wrong" . mysqli_error($conn);
      } else {
        echo '<script type="text/javascript">
              window.onload = function() {
                  var myModal = new bootstrap.Modal(document.getElementById("createModal"));
                  myModal.show();}
            </script>';
      header('refresh:2; url=index.php');
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
      
      $sql = "SELECT * from users WHERE Email = '$email'";
      $user = mysqli_query($conn, $sql);

      if (!$user) {
        die('query Failed' . mysqli_error($conn));
      }

      if(mysqli_num_rows($user) > 0){
          $row = mysqli_fetch_array($user);
          $hashed_password = $row['Password'];

          if (password_verify($password, $hashed_password)) {
              $_SESSION['email'] = $row['Email'];  // Storing the value in session
              header('Location: ./PHP/home.php');
              exit;
          } else {
              echo "Invalid email or password.";
          }
      } else {
          echo "Invalid email or password.";
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
                    <form action="" class="text-center" method="POST">
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
                          <button type="submit" name="sign_up" class="m-3 btn btn-outline-success">Sign up</button>
                    </form>
                  </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createmodalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                  <h1 class="modal-title fs-5" id="createmodalLabel">Account created</h1>
              </div>
            </div>
        </div>
    </div>

  </div>
</body>
</html>