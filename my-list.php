<?php
session_start();
if (!isset($_SESSION['movies'])) {
  header("Location: login.php");
}

include_once "assets/scripts/user.php";

$conn = conn();

$usr = $_SESSION['movies'];

$role = userRole($usr);
?><!DOCTYPE html>
<html lang="en">
  <head>
    <title>My list :: Movie app</title>
    <link rel="stylesheet" href="assets/main.css" />
    <link href="https://fonts.googleapis.com/css?family=Arimo" rel="stylesheet">
    <script type="text/javascript" src="assets/extra.js"></script>
    <script type="text/javascript" src="assets/jquery.js"></script>
  </head>
  <body>
    <div class="user-masthead">
      <p>Welcome back <b><?php echo $usr; ?></b>! <a href='/my-list.php'>View your list</a><?php if ($role == "admin") { ?>, <a href='/users.php'>manage users</a><?php } ?> or <a href='/login.php'>logout</a>.</p>
    </div>
    <div class="head-content">
      <h1><?php echo $usr; ?>'s list (<a href="index.php">Return to home page</a>)</h1>
      <br>
      <?php getUserList($usr); ?>
    </div>
  </body>
</html>
