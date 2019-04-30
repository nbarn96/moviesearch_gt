<?php
  session_start();
  if (isset($_SESSION['movies'])) {
    unset($_SESSION['movies']);
    $loginmsg = "You have been logged out. If this was a mistake, log in again.";
  }

  require_once "assets/scripts/auth.php";

  $conn = conn();

  if (!empty($_POST['submit'])) {
    if (empty($_POST['username']) || empty($_POST['password'])) {
      $loginmsg = "Please enter all required information.";
    } else {
      $user = mysqli_real_escape_string($conn, $_POST['username']);
      $pass = mysqli_real_escape_string($conn, $_POST['password']);

      $loginmsg = login_user($user, $pass);
    }
  }

?><!DOCTYPE html>
<html lang="en">
  <head>
    <title>Login :: Movie app</title>
    <link rel="stylesheet" href="assets/main.css" />
    <link href="https://fonts.googleapis.com/css?family=Arimo" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script type="text/javascript" src="assets/extra.js"></script>
  </head>
  <body>
    <div align = "center">
    <div style = "width:300px; border: solid 1px #333333; " align = "left">
        <div style = "background-color:#333333; color:#FFFFFF; padding:9px;"><b>Login</b></div>

        <div style = "margin:30px">

        <?php if (isset($loginmsg)) { ?>
          <div style = "font-size:11px; color:#cc0000; margin: 10px 0;"><?php echo $loginmsg; ?></div>
        <?php } ?>

            <form action="" id="login" method="post">
                <label>Username: </label><input type = "text" name = "username" id="username" class="box" /><br /><br />
                <label>Password: </label><input type = "password" name = "password" id="password" class="box" /><br/><br />
                <input type="submit" name="submit" value="Log in" />
            </form>

        </div>

    </div>
    </div>
  </body>
</html>
