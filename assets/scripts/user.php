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

  if (isset($_GET['user'])) {
    if ($_GET['action'] == "delete") {
      $usrname = $_GET['user'];
      $del_title = $_GET['name'];
      $conn = conn();
      mysqli_query($conn, "DELETE FROM Lists WHERE userId = '$usrname' AND name = '$del_title'");
      header("Location: /my-list.php");
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

  function getUserList($userId) {
    $con = conn();
    $query = mysqli_query($con, "SELECT userId, name, TitleBasics.genres AS genres, TitleBasics.titleType AS titleType FROM Lists, TitleBasics WHERE Lists.userId = '$userId' AND Lists.titles = TitleBasics.tconst");

    if (mysqli_num_rows($query) == 0) {
      echo "You have no titles in your list!";
    } else {
      echo "<table class='user-listing' style='min-width: 100%; text-align: left;'>";
      echo "<thead>";
      echo "<tr>";
      echo "<td>";
      echo "Title";
      echo "</td>";
      echo "<td>";
      echo "Title info";
      echo "</td>";
      echo "<td>";
      echo "Actions";
      echo "</td>";
      echo "</tr>";
      echo "</thead>";
      echo "<tbody>";
      while ($row = mysqli_fetch_array($query)) {
        $title = $row['name'];
        $genres = preg_replace('/(?<!\d),|,(?!\d{3})/', ', ', $row['genres']);
        $runtime = mysqli_query($con, "SELECT primaryTitle, SUM(runtimeMinutes) AS total_runtime FROM TitleBasics, TitleEpisodes WHERE TitleEpisodes.parentTconst = TitleBasics.tconst AND primaryTitle = '$title'");
        $eps = mysqli_query($con, "SELECT SUM(episodeNumber) AS num_eps FROM TitleEpisodes, TitleBasics WHERE parentTconst = TitleBasics.tconst AND TitleBasics.primaryTitle = '$title'");
        $row_runtime = mysqli_fetch_assoc($runtime);
        $row_eps = mysqli_fetch_assoc($eps);

        if ($row['titleType'] == "tvSeries") {
          $titleType = "TV show";
        } else {
          $titleType = "Movie";
        }

        echo "<tr>";
        echo "<td>";
        echo $row['name']."<br>";
        echo "<span class='subtitle'><i>$titleType</i></span><br>";
        echo "<span class='subtitle'><i>Genres: $genres</i></span>";
        echo "</td>";
        echo "<td>";
        echo "<b>Total runtime:</b> ".number_format($row_runtime['total_runtime'])." minutes, or ".number_format($row_runtime['total_runtime'] / 60, 2)." hours<br>";
        echo "<b>Number of episodes:</b> ".number_format($row_eps['num_eps'])."</br>";
        echo "</td>";
        echo "<td>";
        echo "<a href='assets/scripts/user.php?user=".$row['userId']."&name=$title&action=delete'>Delete</a>";
        echo "</td>";
        echo "</tr>";
      }
      echo "</tbody>";
      echo "</table>";
    }
  }
?>
