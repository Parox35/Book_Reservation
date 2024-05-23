<?php


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=nav, initial-scale=1.0">
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
                    <form action="." method="POST" class="text-center">
                      <div class="row">
                        <label for="">Email:</label>
                        <input type="email" class="col-sm-6 mx-auto">
                      </div>
                      <div class="row">
                        <label for="">Password:</label>
                        <input type="password" class="col-sm-6 mx-auto">
                      </div>
                      <input type="submit" value="Login" class="m-3 btn btn-outline-success">
                    </form>
                  </div>
                    

                  <!--Sign up-->
                  <div class="collapse" id="div_register">
                    <h3 class="text-center">Sign up</h3>
                    <form action="" class="text-center">
                    <div class="row">
                      <label for="">Email:</label>
                      <input type="email" class="col-sm-6 mx-auto">
                    </div>
                    <div class="row">
                      <label for="">First Name:</label>
                      <input type="text" class="col-sm-6 mx-auto">
                    </div>
                    <div class="row">
                      <label for="">Last Name:</label>
                      <input type="text" class="col-sm-6 mx-auto">
                    </div>
                    <div class="row">
                      <label for="">Age:</label>
                      <input type="number" class="col-sm-6 mx-auto">
                    </div>
                    <div class="row">
                      <label for="">Password:</label>
                      <input type="password" class="col-sm-6 mx-auto">
                    </div>
                      <input type="submit" value="Sign up" class="m-3 btn btn-outline-success">
                    </form>
                  </div>

                </div>
            </div>
        </div>
    </div>

  </div>
</body>
</html>