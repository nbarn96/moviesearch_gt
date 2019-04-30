<?php
  session_start();
  include "auth.php";

  if (isset($_GET['action'])) {
    if ($_GET['action'] == "demote") {
      demoteUser($_GET['id']);
    } elseif ($_GET['action'] == "promote") {
      promoteUser($_GET['id']);
    } elseif ($_GET['action'] == "delete") {
      deleteUser($_GET['id']);
    } else if ($_GET['action'] == "addtolist") {
      addToList($_GET['id']);
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

  function addToList($title_id) {
    $conn = conn();
    $usr = $_SESSION['movies'];
    $ent_id = $title_id;
    $title = mysqli_query($conn, "SELECT primaryTitle FROM TitleBasics WHERE tconst = '$ent_id'");
    $row = mysqli_fetch_assoc($title);
    $title_name = $row['primaryTitle'];
    mysqli_query($conn, "INSERT INTO Lists (userId, titles, name) VALUES ('$usr', '$ent_id', '$title_name')");
    header("Location: /my-list.php");
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
    mysqli_query($con, "DELETE FROM Lists WHERE userId = '$userId'");
    mysqli_query($con, "DELETE FROM Users WHERE userId = '$userId'");
    header("Location: /users.php");
  }

  function promoteUser($userId) {
    $con = conn();
    mysqli_query($con, "UPDATE Users SET type = 'admin' WHERE userId = '$userId'");
    header("Location: /users.php");
  }

  function demoteUser($userId) {
    $con = conn();
    mysqli_query($con, "UPDATE Users SET type = 'user' WHERE userId = '$userId'");
    header("Location: /users.php");
  }

  function getUserList($userId) {
    $con = conn();
    $query = mysqli_query($con, "SELECT userId, titles, name, TitleRatings.averageRating as averageRating, TitleBasics.runtimeMinutes AS runtimeMinutes, TitleBasics.genres AS genres, TitleBasics.titleType AS titleType, TitleBasics.startYear as startYear, TitleRatings.numVotes AS numVotes FROM Lists INNER JOIN TitleBasics INNER JOIN TitleRatings WHERE Lists.userId = '$userId' AND Lists.titles = TitleBasics.tconst AND TitleRatings.tconst = Lists.titles");

    if (mysqli_num_rows($query) == 0) {
      echo "You have no titles in your list!";
    } else {
      echo "<table class='user-listing' style='min-width: 100%; text-align: left;'>";
      echo "<thead>";
      echo "<tr>";
      echo "<td style='max-width: 600px;'>";
      echo "Title";
      echo "</td>";
      echo "<td style='max-width: 600px;'>";
      echo "Statistics";
      echo "</td>";
      echo "</tr>";
      echo "</thead>";
      echo "<tbody>";
      while ($row = mysqli_fetch_array($query)) {
        $title_id = $row['titles'];
        $title = $row['name'];
        $genres = preg_replace('/(?<!\d),|,(?!\d{3})/', ', ', $row['genres']);
        $eps = mysqli_query($con, "SELECT COUNT(episodeNumber) AS num_eps FROM TitleEpisodes WHERE parentTconst = '$title_id'");
        $row_eps = mysqli_fetch_assoc($eps);
        $rating_query = mysqli_query($con, "SELECT averageRating, numVotes FROM TitleRatings WHERE tconst = '$title_id'");
        $ratings = mysqli_fetch_assoc($rating_query);

        if ($row['titleType'] == "tvSeries") {
          $titleType = "TV show";
          $row_runtime = $row_eps['num_eps'] * $row['runtimeMinutes'];
        } else if ($row['titleType'] == "tvMovie") {
          $titleType = "Made-for-TV movie";
          $row_runtime = $row['runtimeMinutes'];
        } else if ($row['titleType'] == "tvSpecial") {
          $titleType = "TV special";
          $row_runtime = $row['runtimeMinutes'];
        } else if ($row['titleType'] == "tvMiniSeries") {
          $titleType = "TV miniseries";
          $row_runtime = $row['runtimeMinutes'];
        } else {
          $titleType = "Movie";
          $row_runtime = $row['runtimeMinutes'];
        }

        echo "<tr>";
        echo "<td>";
        echo $row['name']." <span class='subtitle'>(".$row['startYear'].")</span><br>";
        echo "<a class='action-link' href='assets/scripts/user.php?user=".$row['userId']."&name=$title&action=delete'>Delete from list</a><br>";
        echo "<span class='subtitle'><i>$titleType</i></span><br>";
        if ($genres != "\N") {
          echo "<span class='subtitle'><i>Genres: $genres</i></span>";
        }
        echo "</td>";
        echo "<td>";
        if ($row['runtimeMinutes'] != "\N") {
          echo "<b>Total runtime:</b> ".number_format($row_runtime)." minutes, or ".number_format($row_runtime / 60, 2)." hours";
        } else {
          echo "<b>Total runtime:</b> Not defined in IMDB data";
        }
        if ($titleType == "TV show" && $row_eps['num_eps'] > 0) {
          echo "<br><b><abbr title='The value may be off because two-part episodes running within a single half-hour block are considered one episode.'>Number of episodes</abbr>:</b> ".number_format($row_eps['num_eps']);
        }
        echo "<br><b>IMDB rating:</b> ".$ratings['averageRating']."/10 (".number_format($ratings['numVotes'])." votes cast)";
        echo "</td>";
        echo "</tr>";
      }
      echo "</tbody>";
      echo "</table>";
    }
  }
?>
