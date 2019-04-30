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
    <title>Movie app</title>
    <link rel="stylesheet" href="assets/main.css" />
    <link href="https://fonts.googleapis.com/css?family=Arimo" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script type="text/javascript" src="assets/extra.js"></script>
  </head>
  <body>
    <div class="user-masthead">
      <p>Welcome back <b><?php echo $usr; ?></b>! <a href='/my-list.php'>View your list</a><?php if ($role == "admin") { ?>, <a href='/users.php'>manage users</a><?php } ?> or <a href='/login.php'>logout</a>.</p>
    </div>
    <div class="head-content">
      <h1>Movie app</h1>
      <p>A simple web application to find information on movies, directors, episode titles, and more!</p>
    </div>
    <div class="form">
      <input type="text" id="query" placeholder="Search for stuff!" />
      <input type="hidden" id="role" value="<?php echo $role; ?>" />
    </div>
    <div id="loading">Loading results...</div>
    <div id="results"></div>
    <div class="help">
      <h3>Search tips</h3>
      <p>
        To search for a movie, simply enter the name in the field above.
      </p>
      <p>
        This tool also supports finding movies by director, episode titles, and actors. To find the name of
        a movie or show using these attributes, type "[attribute name]: [query]" in the search bar. For
        example, to search for a show by an episode title, type "episode: [name of episode]".
      </p>
    </div>
  </body>
</html>
