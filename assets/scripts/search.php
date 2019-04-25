<?php
  include "auth.php";

  if (isset($_POST['action'])) {
    $con = conn();

    $search_query = $_POST['action'];

    if (stripos($search_query, "start:")) {
      $start_year = substr($search_query, stripos($search_query, "start:") + 1, 4);
      $query = mysqli_query($con, "SELECT TitleBasics.tconst AS tconst, primaryTitle, genres, titleType, startYear, runtimeMinutes, TitleRatings.averageRating AS averageRating, TitleRatings.numVotes AS numVotes FROM TitleBasics INNER JOIN TitleRatings WHERE primaryTitle LIKE '%$search_query%' AND titleType IN ('movie', 'tvMiniSeries', 'tvSeries', 'tvMovie', 'tvSpecial') AND startYear = '$start_year' AND TitleBasics.tconst = TitleRatings.tconst LIMIT 50");
    } else if (stripos($search_query, "rating:")) {
      $min_rating = substr($search_query, stripos($search_query, "rating:") + 1, 1);
      $query = mysqli_query($con, "SELECT TitleBasics.tconst AS tconst, primaryTitle, genres, titleType, startYear, runtimeMinutes, TitleRatings.averageRating AS averageRating, TitleRatings.numVotes AS numVotes FROM TitleBasics INNER JOIN TitleRatings WHERE primaryTitle LIKE '%$search_query%' AND titleType IN ('movie', 'tvMiniSeries', 'tvSeries', 'tvMovie', 'tvSpecial') AND averageRating >= '$min_rating' AND TitleBasics.tconst = TitleRatings.tconst LIMIT 50");
    } else {
      $query = mysqli_query($con, "SELECT DISTINCT TitleBasics.tconst AS tconst, primaryTitle, genres, titleType, startYear, runtimeMinutes, TitleRatings.averageRating AS averageRating, TitleRatings.numVotes AS numVotes FROM TitleBasics INNER JOIN TitleRatings WHERE primaryTitle LIKE '%$search_query%' AND titleType IN ('movie', 'tvMiniSeries', 'tvSeries', 'tvMovie', 'tvSpecial') AND TitleBasics.tconst = TitleRatings.tconst LIMIT 50");
    }

    if (mysqli_num_rows($query) == 0) {
      echo "<div style='text-align: center;'>Your search returned no results.</a>";
    } else {
      echo "<table class='user-listing' style='min-width: 100%; text-align: left;'>";
      echo "<thead>";
      echo "<tr>";
      echo "<td style='max-width: 700px;'>";
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
        $title_id = $row['tconst'];
        $title = $row['primaryTitle'];
        $genres = preg_replace('/(?<!\d),|,(?!\d{3})/', ', ', $row['genres']);
        $eps = mysqli_query($con, "SELECT COUNT(episodeNumber) AS num_eps FROM TitleEpisodes WHERE parentTconst = '$title_id'");
        $row_eps = mysqli_fetch_assoc($eps);

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
        echo $row['primaryTitle']." <span class='subtitle'>(".$row['startYear'].")</span><br>";
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
        echo "<br><b>IMDB rating:</b> ".$row['averageRating']."/10 (".number_format($row['numVotes'])." votes cast)";
        echo "</td>";
        echo "<td>";
        echo "<a href='assets/scripts/user.php?name=$title_id&action=addtolist'>Add to my list</a>";
        echo "</td>";
        echo "</tr>";
      }
      echo "</tbody>";
      echo "</table>";
    }
  }
?>
