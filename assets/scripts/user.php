<?php
  include "auth.php";

  if (isset($_GET['action'])) {
    if ($_GET['action'] == "demote") {
      demoteUser($_GET['id']);
    } elseif ($_GET['action'] == "promote") {
      promoteUser($_GET['id']);
    } elseif ($_GET['action'] == "delete") {
      deleteUser($_GET['id']);
    }
  }

  function userRole($userId) {
    $conn = conn();

    $query = mysqli_query($conn, "SELECT type FROM Users WHERE userId = '$userId'");
    $res = mysqli_fetch_assoc($query);

    return $res['type'];
  }

  function getUsers() {
    $conn = conn();

    $query = mysqli_query($conn, "SELECT * FROM Users") or die(mysqli_error($conn));

    while ($row = mysqli_fetch_array($query)) {
      echo "<tr>";
      echo "<td>".$row['userId']."</td>";
      echo "<td>".$row['type']."</td>";
      echo "<td>";
      if ($row['type'] == "admin") {
        echo "<a href='assets/scripts/user.php?id=".$row['userId']."&action=demote'>Demote</a>";
      } else {
        echo "<a href='assets/scripts/user.php?id=".$row['userId']."&action=promote'>Promote</a>";
      }
      echo "<a href='assets/scripts/user.php?id=".$row['userId']."&action=delete'>Delete account</a>";
      echo "</td>";
      echo "</tr>";
    }
  }

  function deleteUser($userId) {
    $con = conn();
    $query = mysqli_query($con, "DELETE FROM Users WHERE userId = '$userId'");
    header("Location: /users.php");
  }

  function promoteUser($userId) {
    $con = conn();
    $query = mysqli_query($con, "UPDATE Users SET type = 'admin' WHERE userId = '$userId'");
    header("Location: /users.php");
  }

  function demoteUser($userId) {
    $con = conn();
    $query = mysqli_query($con, "UPDATE Users SET type = 'user' WHERE userId = '$userId'");
    header("Location: /users.php");
  }
?>
