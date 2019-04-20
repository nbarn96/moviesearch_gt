<?php
  include "auth.php";

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
        echo "<a href='#'>Demote</a>";
      } else {
        echo "<a href='#'>Promote</a>";
      }
      echo "<a href='#'>Delete account</a>";
      echo "</td>";
      echo "</tr>";
    }
  }

  function deleteUser($userId) {
    $con = conn();


  }
?>
