<?php
  session_start();
  if (!$_SESSION['movies']) {
    header("Location: login.php");
  }

  include "assets/scripts/auth.php";
  include "assets/scripts/user.php";

  $conn = conn();

  $usr = $_SESSION['movies'];
?><!DOCTYPE html>
<html lang="en">
  <head>
    <title>User management :: Movie app</title>
    <link rel="stylesheet" href="assets/main.css" />
    <link href="https://fonts.googleapis.com/css?family=Arimo" rel="stylesheet">
    <script type="text/javascript" src="assets/extra.js"></script>
    <script type="text/javascript" src="assets/jquery.js"></script>
  </head>
  <body>
    <div class="user-masthead">
      <p>Welcome back <b><? echo $usr; ?></b>! <a href='/my-list.php'>View your list</a><? if $role == "admin" { ?>, <a href='/users.php'>manage users</a><? } ?> or <a href='/login.php'>logout</a>.</p>
    </div>
    <div class="head-content">
      <h1>User management</h1>
    </div>
    <table class="user-listing">
      <thead>
        <tr>
          <td>Username</td>
          <td>Role</td>
        </tr>
      </thead>
    </table>
  </body>
</html>
