<?php
  function conn() {
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'yudong');
    define('DB_PASSWORD', 'pEf=f1ti+uxo');
    define('DB_DATABASE', 'movies');
    $db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    return $db;
  }

  function login_user($user, $pass) {
    $conn = conn();

    $query = mysqli_query($conn, "SELECT userId, password FROM Users WHERE userId = '$user' AND password = '$pass'");

    if (mysqli_num_rows($query) == 0) {
      return "Your credentials are incorrect.";
    } else if (mysqli_num_rows($query) == 1) {
      session_start();
      $_SESSION['movies'] = $user;
      header("Location: index.php");
    }
  }
?>
